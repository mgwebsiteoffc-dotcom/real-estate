<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Portal Configuration Table
        Schema::create('portal_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('portal_name'); // 99acres, magicbricks, housing, etc
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('webhook_url')->nullable();
            $table->json('settings')->nullable(); // portal-specific settings
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Portal Property Sync Table
        Schema::create('portal_property_syncs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('portal_config_id')->constrained()->onDelete('cascade');
            $table->string('portal_property_id')->nullable(); // ID from external portal
            $table->string('status')->default('pending'); // pending, synced, failed, removed
            $table->timestamp('last_synced_at')->nullable();
            $table->text('sync_response')->nullable();
            $table->text('error_message')->nullable();
            $table->json('field_mapping')->nullable();
            $table->timestamps();
        });

        // Portal Sync Logs
        Schema::create('portal_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portal_property_sync_id')->constrained()->onDelete('cascade');
            $table->string('action'); // create, update, delete
            $table->string('status'); // success, failed
            $table->text('request_data')->nullable();
            $table->text('response_data')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_sync_logs');
        Schema::dropIfExists('portal_property_syncs');
        Schema::dropIfExists('portal_configs');
    }
};
