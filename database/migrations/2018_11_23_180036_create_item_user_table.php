<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemUserTable extends Migration
{

    public function up()
    {
        Schema::create('item_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->string('type')->index();
            
            $table->timestamps();
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('user_id')->references('id')->on('users');
        });    
        
    }

    public function down()
    {
        Schema::dropIfExists('item_user');
    }
}
