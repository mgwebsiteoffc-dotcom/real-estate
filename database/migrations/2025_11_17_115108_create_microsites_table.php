<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('microsites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('custom_domain')->nullable()->unique();
            $table->string('title');
            $table->text('description')->nullable();
            
            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
            
            // Design Settings
            $table->string('template')->default('default'); // default, modern, luxury, minimal
            $table->json('theme_colors')->nullable(); // primary, secondary, etc.
            $table->json('sections')->nullable(); // hero, features, gallery, contact, etc.
            
            // Analytics
            $table->integer('views')->default(0);
            $table->integer('leads_captured')->default(0);
            
            // Status
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();
        });

        // Microsite Leads (captured from microsite)
        Schema::create('microsite_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('microsite_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('message')->nullable();
            $table->string('source')->default('microsite');
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('microsite_leads');
        Schema::dropIfExists('microsites');
    }
};
