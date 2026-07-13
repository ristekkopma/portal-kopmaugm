<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Jobs\SendEventNotificationEmail;
use App\Mail\EventNotificationMail;
use App\Models\Event;
use App\Models\EventNotificationBatch;
use App\Models\EventNotificationRecipient;
use App\Models\User;
use App\Services\EventRecipientVerifier;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Tests\TestCase;

class EventNotificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('role')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('can_manage_event_followers')->default(false);
            $table->boolean('can_send_event_notifications')->default(false);
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('event_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('organizer_name')->nullable();
            $table->string('registration_url')->nullable();
            $table->string('status')->default('published');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('event_notification_batches', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->text('spreadsheet_url');
            $table->text('message')->nullable();
            $table->string('status');
            $table->unsignedInteger('total_recipients');
            $table->unsignedInteger('valid_recipients');
            $table->unsignedInteger('failed_recipients');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();
        });

        Schema::create('event_notification_recipients', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('email');
            $table->string('status');
            $table->text('failure_reason')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('event_notification_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function test_spreadsheet_valid_matches_users_case_insensitively(): void
    {
        $first = $this->user('AURELIA FITRI SHOLEHAH', 'naswalia67@gmail.com');
        $second = $this->user('Bayu Pratama', 'bayu@example.com');
        $csv = "email,id,name\n NASWALIA67@GMAIL.COM ,{$first->id}, Aurelia   Fitri Sholehah\nbayu@example.com,{$second->id},bayu pratama";

        $result = app(EventRecipientVerifier::class)->verifyCsv($csv);

        $this->assertSame(2, $result['valid']);
        $this->assertSame(0, $result['failed']);
        $this->assertSame(0, $result['duplicate']);
    }

    public function test_create_event_form_displays_email_notification_section(): void
    {
        $admin = $this->user('Super Admin', 'admin@example.com');
        $admin->update(['role' => UserRole::SuperAdmin]);

        $this->actingAs($admin)
            ->get('/admin/events/create')
            ->assertOk()
            ->assertSee('Notifikasi Email Event');
    }

    public function test_invalid_and_duplicate_rows_block_the_whole_batch(): void
    {
        $first = $this->user('Aurelia', 'aurelia@example.com');
        $second = $this->user('Bayu', 'bayu@example.com');
        $csv = "id,name,email\n"
            ."{$first->id},Nama Salah,aurelia@example.com\n"
            ."{$first->id},Aurelia,bayu@example.com\n"
            ."999,Tidak Ada,unknown@example.com\n"
            ."{$second->id},Bayu,email-salah\n"
            ."{$second->id},Bayu,bayu@example.com";

        $result = app(EventRecipientVerifier::class)->verifyCsv($csv);

        $this->assertSame(0, $result['valid']);
        $this->assertGreaterThan(0, $result['failed']);
        $this->assertGreaterThan(0, $result['duplicate']);
    }

    public function test_wrong_header_and_empty_spreadsheet_are_rejected(): void
    {
        $verifier = app(EventRecipientVerifier::class);

        try {
            $verifier->verifyCsv("id,name,phone\n1,Aurelia,0812");
            $this->fail('Header salah harus ditolak.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('data.spreadsheet_url', $exception->errors());
        }

        $this->expectException(ValidationException::class);
        $verifier->verifyCsv("id,name,email\n");
    }

    public function test_public_csv_url_can_be_verified_and_inaccessible_url_is_rejected(): void
    {
        $user = $this->user('Aurelia', 'aurelia@example.com');
        Http::fake([
            'https://example.com/valid.csv' => Http::response("id,name,email\n{$user->id},Aurelia,aurelia@example.com"),
            'https://example.com/private.csv' => Http::response('Forbidden', 403),
        ]);

        $result = app(EventRecipientVerifier::class)->verifyUrl('https://example.com/valid.csv');
        $this->assertSame(1, $result['valid']);

        $this->expectException(ValidationException::class);
        app(EventRecipientVerifier::class)->verifyUrl('https://example.com/private.csv');
    }

    public function test_verified_recipient_is_sent_once_and_batch_is_completed(): void
    {
        Mail::fake();
        $user = $this->user('Aurelia', 'aurelia@example.com');
        $event = Event::create(['title' => 'Seminar Digital', 'status' => 'published']);
        $batch = EventNotificationBatch::create([
            'event_id' => $event->id,
            'spreadsheet_url' => 'https://example.com/data.csv',
            'status' => 'queued',
            'total_recipients' => 1,
            'valid_recipients' => 1,
            'failed_recipients' => 0,
        ]);
        $recipient = EventNotificationRecipient::create([
            'batch_id' => $batch->id,
            'event_id' => $event->id,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => 'queued',
        ]);

        (new SendEventNotificationEmail($recipient->id))->handle();
        (new SendEventNotificationEmail($recipient->id))->handle();

        Mail::assertSent(EventNotificationMail::class, 1);
        $this->assertSame('sent', $recipient->fresh()->status);
        $this->assertSame('sent', $batch->fresh()->status);
    }

    public function test_batch_with_failed_verification_cannot_send_any_email(): void
    {
        Mail::fake();
        $user = $this->user('Aurelia', 'aurelia@example.com');
        $event = Event::create(['title' => 'Seminar Digital', 'status' => 'published']);
        $batch = EventNotificationBatch::create([
            'event_id' => $event->id,
            'spreadsheet_url' => 'https://example.com/data.csv',
            'status' => 'queued',
            'total_recipients' => 2,
            'valid_recipients' => 1,
            'failed_recipients' => 1,
        ]);
        $recipient = EventNotificationRecipient::create([
            'batch_id' => $batch->id,
            'event_id' => $event->id,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => 'queued',
        ]);

        $this->expectException(RuntimeException::class);
        try {
            (new SendEventNotificationEmail($recipient->id))->handle();
        } finally {
            Mail::assertNothingSent();
        }
    }

    private function user(string $name, string $email): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => 'rahasia',
        ]);
    }
}
