<?php namespace Mavitm\Estate\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMavitmEstateCategories extends Migration
{
    public function up()
    {
        Schema::table('mavitm_estate_realty', function($table) {
            $table->string('address', 255)->after('description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('mavitm_estate_realty', function($table) {
            $table->dropColumn('address');
        });
    }
}
