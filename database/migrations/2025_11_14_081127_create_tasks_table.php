<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            
            // Polymorphic relation (task can be for lead, property, project, etc)
            $table->morphs('taskable');
            
            // Task Details
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['call', 'meeting', 'email', 'site_visit', 'follow_up', 'other'])->default('follow_up');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            
            // Dates
            $table->dateTime('due_date');
            $table->dateTime('completed_at')->nullable();
            $table->integer('reminder_minutes')->nullable(); // minutes before due_date
            
            // Notes
            $table->text('completion_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['company_id', 'status']);
            $table->index(['assigned_to', 'due_date']);
            // $table->morphs('taskable'); <-- REMOVE THIS LINE (duplicate)
        });

        // Activities Log
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Polymorphic relation
            $table->morphs('activityable');
            
            // Activity Details
            $table->string('type'); // call, email, meeting, note, status_change, etc
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // additional data
            
            $table->timestamps();
            
            // Indexes
            $table->index(['company_id', 'created_at']);
            // $table->morphs('activityable'); <-- REMOVE THIS LINE (duplicate)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
        Schema::dropIfExists('tasks');
    }
};
