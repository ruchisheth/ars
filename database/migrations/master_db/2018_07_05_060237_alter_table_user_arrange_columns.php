<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUserArrangeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users'))
        {
            DB::statement("ALTER TABLE users MODIFY COLUMN role int(11) AFTER user_type");
            DB::statement("ALTER TABLE users MODIFY COLUMN client_code varchar(10) AFTER role");
            DB::statement("ALTER TABLE users MODIFY COLUMN db_version int(11) AFTER client_code");
            DB::statement("ALTER TABLE users MODIFY COLUMN is_first tinyint(1) AFTER db_version");
            DB::statement("ALTER TABLE users MODIFY COLUMN status tinyint(1) AFTER is_first");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
