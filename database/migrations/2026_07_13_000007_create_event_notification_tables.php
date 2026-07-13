<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_notification_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('spreadsheet_url');
            $table->text('message')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('valid_recipients')->default(0);
            $table->unsignedInteger('failed_recipients')->default(0);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();
        });

        Schema::create('event_notification_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('event_notification_batches')->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('status')->default('verified');
            $table->text('failure_reason')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['batch_id', 'user_id']);
            $table->unique(['batch_id', 'email']);
        });

        Schema::create('event_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->nullable()->constrained('event_notification_batches')->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('can_send_event_notifications')->default(false)->after('can_manage_event_followers');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('can_send_event_notifications');
        });

        Schema::dropIfExists('event_notification_logs');
        Schema::dropIfExists('event_notification_recipients');
        Schema::dropIfExists('event_notification_batches');
    }
};
