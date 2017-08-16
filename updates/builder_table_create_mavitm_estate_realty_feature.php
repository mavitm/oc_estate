<?php namespace Mavitm\Estate\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMavitmEstateRealtyFeature extends Migration
{
    public function up()
    {
        Schema::create('mavitm_estate_realty_feature', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('realty_id');
            $table->integer('feature_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mavitm_estate_realty_feature');
    }
}
