<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the table if it exists
        Schema::dropIfExists('activity_attachments');
        Schema::dropIfExists('activities');

        // Recreate activities table with correct structure
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // call, email, note, meeting, whatsapp
            $table->text('description');
            $table->string('status')->nullable(); // completed, scheduled, cancelled
            $table->datetime('activity_date');
            $table->integer('duration_minutes')->nullable();
            $table->timestamps();
        });

        // Recreate activity_attachments table
        Schema::create('activity_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_attachments');
        Schema::dropIfExists('activities');
    }
};
