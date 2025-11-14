<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('company_name');
            $table->string('company_email')->unique();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active');
        });
    }

    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'company_email', 'package_id', 'status']);
        });
    }
};
