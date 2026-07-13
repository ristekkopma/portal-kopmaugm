<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->string('thumbnail')->nullable()->after('image');
            $table->string('banner')->nullable()->after('thumbnail');
            $table->string('organizer_name')->nullable()->after('banner');
            $table->string('organizer_logo')->nullable()->after('organizer_name');
            $table->date('event_date')->nullable()->after('category');
            $table->time('start_time')->nullable()->after('event_date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->string('location')->nullable()->after('end_time');
            $table->enum('event_type', ['offline', 'online', 'hybrid'])->default('offline')->after('location');
            $table->string('registration_url')->nullable()->after('event_type');
            $table->dateTime('registration_deadline')->nullable()->after('registration_url');
            $table->string('contact_person')->nullable()->after('registration_deadline');
            $table->longText('rundown')->nullable()->after('contact_person');
            $table->longText('terms')->nullable()->after('rundown');
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])
                ->default('published')
                ->after('terms');
            $table->dateTime('published_at')->nullable()->after('status');
        });

        DB::table('events')->orderBy('id')->each(function (object $event): void {
            $baseSlug = Str::slug($event->title) ?: 'event';

            DB::table('events')->where('id', $event->id)->update([
                'slug' => $baseSlug . '-' . $event->id,
                'banner' => $event->image,
                'registration_url' => $event->url,
                'organizer_name' => 'Kopma UGM',
                'event_date' => $event->opened_at,
                'published_at' => $event->created_at,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn([
                'slug',
                'thumbnail',
                'banner',
                'organizer_name',
                'organizer_logo',
                'event_date',
                'start_time',
                'end_time',
                'location',
                'event_type',
                'registration_url',
                'registration_deadline',
                'contact_person',
                'rundown',
                'terms',
                'status',
                'published_at',
            ]);
        });
    }
};
