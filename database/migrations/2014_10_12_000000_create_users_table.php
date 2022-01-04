<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 120)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('banned_until')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->String('id_type', 20)->nullable();
            $table->bigInteger('id_number')->nullable();

            $table->string('address', 180)->nullable();
            $table->bigInteger('phone')->nullable();

            $table->unique(['id_type', 'id_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
