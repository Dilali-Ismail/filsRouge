<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraiteurAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traiteur_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('traiteur_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->boolean('is_available');
            $table->timestamps();
            $table->unique(['traiteur_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('traiteur_availabilities');
    }
}
