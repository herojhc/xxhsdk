<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('users')) {
            //
            Schema::table('users', function (Blueprint $table) {
                $this->createFields($table);
            });
        } else {
            Schema::create('users', function (Blueprint $table) {
                $this->createFields($table);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'job',
                'avatar',
                'gender',
                'birthday',
                'country',
                'province',
                'city',
                'timezone',
                'locale',
                'mobile',
                'mobile_validated',
                'email_validated',
                'email_validated_at',
                'name_validated',
                'status',
                'api_token',
                'source'
            ]);
        });
    }


    protected function createFields(Blueprint $table)
    {

        if (Schema::hasColumn('users', 'id')) {
            $table->renameColumn('id', 'user_id');
        } else {
            if (!Schema::hasColumn('users', 'user_id')) {
                $table->increments('user_id');
            }
        }

        if (Schema::hasColumn('users', 'password')) {
            $table->string('password')->nullable()->default('')->change();
        }

        $email = $table->string('email')->nullable()->default('');
        if (Schema::hasColumn('users', 'email')) {
            $email->change();
        }

        $table->string('username', 50)->default('')->nullable();
        $table->string('job', 20)->default('')->nullable();
        $table->string('avatar', 191)->default('');
        $table->char('gender', 1)->nullable();
        $table->date('birthday')->nullable();
        $table->char('country', 2)->default('ZH')->nullable();
        $table->char('province', 6)->default('')->nullable();
        $table->char('city', 6)->default('')->nullable();
        $table->string('timezone', 30)->default('PRC')->nullable();
        $table->string('locale', 15)->default('zh')->nullable();
        $table->string('mobile', 11)->nullable()->default('');
        $table->boolean('mobile_validated')->nullable()->default(0);
        $table->boolean('email_validated')->nullable()->default(0);
        $table->timestamp('email_validated_at')->nullable();
        $table->boolean('name_validated')->nullable()->default(0);
        $table->boolean('status')->default(0);
        $table->string('api_token', 100)->default('');
        $table->smallInteger('source')->default(0)->nullable();
    }
}
