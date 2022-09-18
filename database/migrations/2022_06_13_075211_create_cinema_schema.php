<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        // throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');

        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->int('duration');
            $table->string('language', 50);
            $table->string('country', 100);
            $table->string('genre', 50);
            $table->date('release_date');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cinemas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->int('total_cinema_halls');
            $table->foreignId('city_id')->on('cities')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cinema_halls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->int('total_seats');
            $table->foreignId('cinema_id')->on('cinemas')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->datetime('date');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->foreignId('movie_id')->on('movies')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('cinema_hall_id')->on('cinema_halls')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cinema_seats', function (Blueprint $table) {
            $table->id();
            $table->string('seat_number', 10);
            $table->enum('type', ['golden', 'silver', 'vip', 'other...']);
            $table->foreignId('cinema_hall_id')->on('cinema_halls')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('show_seats', function (Blueprint $table) {
            $table->id();
            $table->double('price', 10, 2);
            $table->enum('status', ['']);
            $table->foreignId('cinema_seat_id')->on('cinema_seats')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('show_id')->on('show_seats')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('booking_id')->on('bookings')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->int('number_of_seats', 10);
            $table->enum('status', ['']);
            $table->foreignId('user_id')->on('users')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('show_id')->on('show_seats')->references('id')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
        Schema::dropIfExists('cinemas');
        Schema::dropIfExists('cinema_halls');
        Schema::dropIfExists('shows');
        Schema::dropIfExists('cinema_seats');
        Schema::dropIfExists('show_seats');
        Schema::dropIfExists('bookings');
    }
}
