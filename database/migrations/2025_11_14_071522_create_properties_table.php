<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Basic Information
            $table->string('title');
            $table->string('property_code')->unique();
            $table->enum('type', ['apartment', 'villa', 'plot', 'commercial', 'office', 'warehouse', 'shop'])->default('apartment');
            $table->enum('listing_type', ['sale', 'rent', 'lease'])->default('sale');
            $table->enum('status', ['available', 'sold', 'rented', 'reserved', 'under_construction'])->default('available');
            
            // Pricing
            $table->decimal('price', 15, 2);
            $table->decimal('price_per_sqft', 10, 2)->nullable();
            $table->boolean('price_negotiable')->default(true);
            
            // Location
            $table->text('address');
            $table->string('locality')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Property Details
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('balconies')->nullable();
            $table->decimal('area_sqft', 10, 2)->nullable();
            $table->decimal('carpet_area', 10, 2)->nullable();
            $table->integer('total_floors')->nullable();
            $table->integer('floor_number')->nullable();
            $table->enum('furnishing', ['unfurnished', 'semi_furnished', 'fully_furnished'])->nullable();
            $table->enum('facing', ['north', 'south', 'east', 'west', 'north_east', 'north_west', 'south_east', 'south_west'])->nullable();
            $table->integer('parking_spaces')->default(0);
            $table->integer('age_of_property')->nullable(); // in years
            
            // Amenities (stored as JSON)
            $table->json('amenities')->nullable();
            
            // Description
            $table->text('description')->nullable();
            $table->text('highlights')->nullable();
            
            // Images
            $table->string('featured_image')->nullable();
            
            // Additional Info
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->integer('views_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['company_id', 'status']);
            $table->index(['type', 'listing_type']);
            $table->index('city');
        });

        // Property Images Table
        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('properties');
    }
};
