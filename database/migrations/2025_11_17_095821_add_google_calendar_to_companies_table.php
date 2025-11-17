<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('google_calendar_enabled')->default(false);
            $table->text('google_calendar_credentials')->nullable();
            $table->string('google_calendar_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['google_calendar_enabled', 'google_calendar_credentials', 'google_calendar_id']);
        });
    }
};
