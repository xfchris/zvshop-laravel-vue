<?php

use App\Constants\AppConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->enum('status', AppConstants::STATUS)->default(AppConstants::CREATED);
            $table->bigInteger('requestId')->unique();
            $table->string('processUrl');
            $table->json('products')->nullable();
            $table->decimal('totalAmount', 10, 2);
            $table->string('reference_id', 30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}
