<?php namespace Mavitm\Estate\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMavitmEstateRealty extends Migration
{
    public function up()
    {
        Schema::table('mavitm_estate_realty', function($table) {
            $table->integer('views')->after('address')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('mavitm_estate_realty', function($table) {
            $table->dropColumn('views');
        });
    }
}
