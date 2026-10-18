<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('site_visits')) {
            return;
        }

        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_hash', 64)->index();
            $table->string('session_id', 100)->nullable();
            $table->string('path', 255)->index();
            $table->string('source_channel', 40)->default('direct')->index();
            $table->string('source_label', 100)->nullable();
            $table->string('referrer_url', 500)->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->string('country_code', 8)->nullable()->index();
            $table->string('country_name', 100)->nullable();
            $table->string('region_name', 100)->nullable();
            $table->string('city_name', 100)->nullable()->index();
            $table->string('device_type', 20)->default('desktop');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
