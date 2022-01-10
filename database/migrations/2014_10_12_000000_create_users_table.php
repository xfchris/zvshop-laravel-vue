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
            $table->string('surname', 120)->nullable();
            $table->string('email', 120)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('banned_until')->nullable();

            $table->String('document_type', 20)->nullable();
            $table->string('document', 30)->nullable();
            $table->string('address', 300)->nullable();
            $table->string('phone', 30)->nullable();

            $table->rememberToken();
            $table->timestamps();

            $table->unique(['document_type', 'document']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
