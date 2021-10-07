<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSetmaxsitecodeProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS setMaxSiteCode');
        DB::unprepared('CREATE PROCEDURE `setMaxSiteCode`()
            BEGIN   
            DECLARE chain_done, code_done BOOLEAN DEFAULT FALSE;    
            declare site_id int(11) default 0;
            declare  projectId int(11) default 0;

            declare chain_code int(11) default 0;
            declare max_code int(11) default 0;

            DECLARE curChains CURSOR FOR SELECT chains.id, max(cast(sites.site_code as signed)) as site_code FROM `chains` LEFT JOIN sites on chains.id = sites.chain_id GROUP BY chains.id;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET chain_done = TRUE;

            OPEN curChains;
            cur_chain_loop: LOOP
            FETCH FROM curChains INTO chain_code, max_code;

            IF chain_done THEN
            CLOSE curChains;
            LEAVE cur_chain_loop;
            END IF;
            set code_done = FALSE;
            BLOCK2: BEGIN
            DECLARE curSites CURSOR FOR SELECT id from sites where (site_code = "" OR site_code is NULL) AND chain_id = chain_code;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET code_done = TRUE;
            OPEN curSites;
            cur_sites_loop: LOOP
            FETCH FROM curSites INTO site_id;   
            IF code_done THEN            
            CLOSE curSites;
            LEAVE cur_sites_loop;
            END IF;
            SET max_code = max_code + 1;
            update sites set site_code = max_code where id = site_id;

                END LOOP cur_sites_loop;
                END BLOCK2;
                END LOOP cur_chain_loop;
                END');
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
