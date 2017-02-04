<?php namespace Mavitm\Estate\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMavitmEstateRealty extends Migration
{
    public function up()
    {
        Schema::create('mavitm_estate_realty', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->smallInteger('status')->nullable();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->string('excerpt', 255)->nullable();
            $table->text('description')->nullable();
            $table->double('price', 10, 2);
            $table->smallInteger('published');
            $table->integer('sort_order')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mavitm_estate_realty');
    }
}
