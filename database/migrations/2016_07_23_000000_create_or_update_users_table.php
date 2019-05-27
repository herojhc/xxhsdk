<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrUpdateUsersTable extends Migration
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
                'country_id',
                'country',
                'province_id',
                'province',
                'city_id',
                'city',
                'address',
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
                $table->integer('user_id');
                $table->primary('user_id');
            }
        }
        if (Schema::hasColumn('users', 'password')) {
            $table->string('password')->nullable()->default('')->change();
        }

        $name = $table->string('name', 150)->default('');
        if (Schema::hasColumn('users', 'name')) {
            $name->change();
        }

        $username = $table->string('username', 50)->default('')->nullable()->comment('用户名');
        if (Schema::hasColumn('users', 'username')) {
            $username->change();
        }

        $job = $table->string('job', 20)->default('')->nullable()->comment('工作');
        if (Schema::hasColumn('users', 'job')) {
            $job->change();
        }

        $avatar = $table->string('avatar')->default('')->nullable()->comment('头像');
        if (Schema::hasColumn('users', 'avatar')) {
            $avatar->change();
        }

        $gender = $table->string('gender', 1)->nullable()->comment('性别1男2女0保密');
        if (Schema::hasColumn('users', 'gender')) {
            $gender->change();
        }

        $birthday = $table->date('birthday')->nullable()->comment('出生日期');
        if (Schema::hasColumn('users', 'birthday')) {
            $birthday->change();
        }

        $country_id = $table->integer('country_id')->default(0)->nullable();
        if (Schema::hasColumn('users', 'country_id')) {
            $country_id->change();
        }

        $province_id = $table->integer('province_id')->default(0)->nullable();
        if (Schema::hasColumn('users', 'province_id')) {
            $province_id->change();
        }

        $city_id = $table->integer('city_id')->default(0)->nullable();
        if (Schema::hasColumn('users', 'city_id')) {
            $city_id->change();
        }

        $country = $table->string('country')->default('中国')->nullable()->comment('国家');
        if (Schema::hasColumn('users', 'country')) {
            $country->change();
        }

        $province = $table->string('province')->default('')->nullable()->comment('省份');
        if (Schema::hasColumn('users', 'province')) {
            $province->change();
        }

        $city = $table->string('city')->default('')->nullable()->comment('城市');
        if (Schema::hasColumn('users', 'city')) {
            $city->change();
        }

        $address = $table->string('address')->default('')->nullable()->comment('详细地址');
        if (Schema::hasColumn('users', 'address')) {
            $address->change();
        }

        $timezone = $table->string('timezone', 30)->default('PRC')->nullable()->comment('时区');
        if (Schema::hasColumn('users', 'timezone')) {
            $timezone->change();
        }

        $locale = $table->string('locale', 15)->default('zh')->nullable()->comment('地区');
        if (Schema::hasColumn('users', 'locale')) {
            $locale->change();
        }

        $mobile = $table->string('mobile', 11)->nullable()->default('')->comment('手机');
        if (Schema::hasColumn('users', 'mobile')) {
            $mobile->change();
        }

        $mobile_validated = $table->boolean('mobile_validated')->nullable()->default(0)->comment('手机认证');
        if (Schema::hasColumn('users', 'mobile_validated')) {
            $mobile_validated->change();
        }

        $email = $table->string('email')->nullable()->default('')->comment('邮箱');
        if (Schema::hasColumn('users', 'email')) {
            $email->change();
        }

        $email_validated = $table->boolean('email_validated')->nullable()->default(0)->comment('邮箱认证');
        if (Schema::hasColumn('users', 'email_validated')) {
            $email_validated->change();
        }

        $email_validated_at = $table->dateTime('email_validated_at')->nullable()->comment('邮箱认证时间');
        if (Schema::hasColumn('users', 'email_validated_at')) {
            $email_validated_at->change();
        }
        $name_validated_at = $table->boolean('name_validated')->nullable()->default(0)->comment('实名认证');
        if (Schema::hasColumn('users', 'name_validated')) {
            $name_validated_at->change();
        }
        $status = $table->boolean('status')->default(0)->comment('状态1激活0未激活-1已废弃');
        if (Schema::hasColumn('users', 'status')) {
            $status->change();
        }
        $api_token = $table->string('api_token', 100)->default('')->nullable()->comment('API_TOKEN');
        if (Schema::hasColumn('users', 'api_token')) {
            $api_token->change();
        }

        $remember_token = $table->string('remember_token')->default('')->nullable();
        if (Schema::hasColumn('users', 'remember_token')) {
            $remember_token->change();
        }

        $source = $table->smallInteger('source')->default(0)->nullable()->comment('来源');
        if (Schema::hasColumn('users', 'source')) {
            $source->change();
        }
    }
}
