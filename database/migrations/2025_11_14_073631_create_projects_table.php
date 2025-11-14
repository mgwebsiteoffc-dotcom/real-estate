<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Basic Information
            $table->string('name');
            $table->string('project_code')->unique();
            $table->string('builder_name')->nullable();
            $table->enum('type', ['residential', 'commercial', 'mixed'])->default('residential');
            $table->enum('status', ['upcoming', 'under_construction', 'ready_to_move', 'completed'])->default('upcoming');
            
            // Location
            $table->text('address');
            $table->string('locality')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Project Details
            $table->integer('total_units')->nullable();
            $table->integer('available_units')->nullable();
            $table->integer('total_towers')->nullable();
            $table->integer('total_floors')->nullable();
            $table->decimal('total_area_acres', 10, 2)->nullable();
            
            // Pricing
            $table->decimal('price_min', 15, 2)->nullable();
            $table->decimal('price_max', 15, 2)->nullable();
            
            // Launch & Possession
            $table->date('launch_date')->nullable();
            $table->date('possession_date')->nullable();
            
            // Amenities & Features
            $table->json('amenities')->nullable();
            $table->json('specifications')->nullable();
            
            // Description
            $table->text('description')->nullable();
            $table->text('highlights')->nullable();
            
            // Media
            $table->string('featured_image')->nullable();
            $table->string('brochure_pdf')->nullable();
            
            // RERA & Approvals
            $table->string('rera_number')->nullable();
            $table->json('approvals')->nullable();
            
            // Additional
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['company_id', 'status']);
            $table->index('city');
        });

        // Project Images Table
        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('image_type')->default('gallery'); // gallery, floor_plan, master_plan
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Link projects to properties
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('company_id')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
        
        Schema::dropIfExists('project_images');
        Schema::dropIfExists('projects');
    }
};
