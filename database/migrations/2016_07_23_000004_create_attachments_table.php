<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('attachments')) {
            return;
        }

        Schema::create('attachments', function (Blueprint $table) {
            $table->integer('id');
            $table->primary('id');
            $table->string('filename', 200)->default('')->comment('保存文件名');
            $table->string('original_name', '200')->default('')->comment('原始文件名');
            $table->string('real_path', 200)->default('')->comment('保存路径');
            $table->string('mime', '100')->default('')->nullable();
            $table->integer('size')->default(0);
            $table->string('md5')->default('')->nullable();
            $table->string('sha1')->default('')->nullable();
            $table->boolean('is_image')->default(0);
            $table->text('url')->nullable();
            $table->integer('platform_attachment_id');
            $table->integer('corp_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
