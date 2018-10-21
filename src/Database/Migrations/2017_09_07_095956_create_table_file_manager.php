<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFileManager extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        // file_manager table
        Schema::create('file_manager' , function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->string('alt' , 255)->nullable();
            $table->string('name' , 255);
            $table->string('original_name' , 255)->nullable();
            $table->string('mime' , 255);
            $table->string('type' , 10);
            $table->string('ext' , 5)->nullable();
            $table->integer('size')->default(0);
            $table->integer('user_id')->default(0);
            $table->timestamps();
        });

        // relation table
        Schema::create('file_managerables' , function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->integer('file_managerable_id');
            $table->string('file_managerable_type' , 255);
            $table->integer('file_manager_id')->unsigned()->index();
            $table->foreign('file_manager_id')->references('id')->on('file_manager')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('file_manager');
        Schema::dropIfExists('file_managerables');
        Schema::enableForeignKeyConstraints();
    }

}
