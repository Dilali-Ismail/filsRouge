<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMakeupPortfolioItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('makeup_portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('makeup_id')->constrained('makeups')->onDelete('cascade');
            $table->enum('type', ['image', 'video']);
            $table->string('file_path');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('makeup_portfolio_items');
    }
}
