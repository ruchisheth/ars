<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetmaxsitecodeProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE PROCEDURE `setMaxSiteCode`()
            BEGIN
            DECLARE n int(11) DEFAULT 0;
            DECLARE i int(11) DEFAULT 0;
            DECLARE chain_code int(11) DEFAULT 0;
            DECLARE max_code int(11) DEFAULT 0;
            SET n = (SELECT COUNT(*) FROM sites where site_code ="");
            WHILE i<n DO 
            set chain_code = (SELECT chain_id FROM sites where site_code = "" OR site_code is NULL limit 1);
            set max_code = (SELECT max(cast(site_code as signed)) + 1 FROM sites where chain_id = chain_code);
            update sites set site_code = max_code where chain_id = chain_code and site_code = "" LIMIT 1;
                SET i = i + 1;
                END WHILE;
                End');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         DB::unprepared('DROP PROCEDURE IF EXISTS setMaxSiteCode');
    }
}
