<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('companies', function (Blueprint $table) {
        $table->string('whatsapp_api_token')->nullable();
        $table->boolean('whatsapp_enabled')->default(false);
    });
}

public function down()
{
    Schema::table('companies', function (Blueprint $table) {
        $table->dropColumn(['whatsapp_api_token', 'whatsapp_enabled']);
    });
}

};
