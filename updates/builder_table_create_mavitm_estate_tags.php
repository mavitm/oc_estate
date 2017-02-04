<?php namespace Mavitm\Estate\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMavitmEstateTags extends Migration
{
    public function up()
    {
        Schema::create('mavitm_estate_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mavitm_estate_tags');
    }
}
