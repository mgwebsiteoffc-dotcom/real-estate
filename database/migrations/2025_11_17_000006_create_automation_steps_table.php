<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automation_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('automation_workflows')->onDelete('cascade');
            $table->integer('step_order')->default(1);
            $table->string('action_type'); // send_whatsapp, delay, send_email, etc.
            $table->json('action_config');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_steps');
    }
};
