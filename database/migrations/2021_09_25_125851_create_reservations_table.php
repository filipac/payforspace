<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('unique_identifier')->nullable();
            $table->string('status')->default(\App\Enums\ReservationStatus::pending()->value);
            $table->dateTime('reservation_started_at')->nullable();
            $table->dateTime('active_from')->nullable();
            $table->dateTime('active_until')->nullable();
            $table->integer('number_of_days')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->string('box_identifier');
            $table->json('transaction')->nullable();
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
        Schema::dropIfExists('reservations');
    }
}
