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
    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->integer('user_id')->nullable();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->text('payload');
        $table->integer('last_activity');
        $table->timestamps(0);  // Laravel tự động thêm cột created_at và updated_at
    });
}

public function down()
{
    Schema::dropIfExists('sessions');
}
};
