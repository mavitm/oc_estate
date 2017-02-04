<?php namespace Mavitm\Estate\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMavitmEstateRealtyProperties extends Migration
{
    public function up()
    {
        Schema::create('mavitm_estate_realty_properties', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('realty_id')->unsigned()->nullable()->index();
            $table->string('name', 255);
            $table->string('value', 255);
            $table->string('iconcss', 255)->nullable();
            $table->integer('sort_order')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mavitm_estate_realty_properties');
    }
}
