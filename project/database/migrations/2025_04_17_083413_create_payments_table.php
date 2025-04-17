<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->string('payment_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->default('stripe');
            $table->json('payment_details')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['reservation_id', 'status']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
