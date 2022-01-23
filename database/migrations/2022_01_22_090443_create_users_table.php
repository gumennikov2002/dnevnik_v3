<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('profile_pic')->nullable(true)->default('images/default.png');
            $table->string('full_name')->nullable(false);
            $table->bigInteger('phone')->nullable(false)->unique();
            $table->string('email')->nullable(true)->unique();
            $table->string('password')->nullable(false)->default('$2y$10$NRK3jAm2oepfKvgCjPjSkeegzlKOegtC46oMVXQ8FLNzvcbrVFFle');
            $table->string('date_of_birth');
            $table->text('additional_info')->nullable(true);
            $table->string('role');
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
        Schema::dropIfExists('users');
    }
}
