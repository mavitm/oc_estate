<?php namespace Mavitm\Estate\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMavitmEstateRealtyTag extends Migration
{
    public function up()
    {
        Schema::create('mavitm_estate_realty_tag', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('realty_id');
            $table->integer('tag_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mavitm_estate_realty_tag');
    }
}
