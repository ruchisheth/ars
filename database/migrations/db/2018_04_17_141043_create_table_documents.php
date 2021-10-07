<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('documents'))
            {
                Schema::create('documents', function (Blueprint $table) {
                    $table->increments('id_document');

                    $table->integer('id_client')->unsigned()->index();
                    $table->string('document_name');
                    $table->string('file_name')->nullable();
                    $table->integer('lft');
                    $table->integer('rgt');
                    $table->integer('id_parent')->nullable()->index();
                    $table->enum('document_type', array('F', 'FO'))->comment('F = File, FO = Folder.');
                    $table->integer('depth');

                    $table->timestamps();

                    //Add foreign key constrain in id_client column
                    $table->foreign('id_client')
                    ->references('id')->on('clients')
                    ->onDelete('cascade');
                });
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
