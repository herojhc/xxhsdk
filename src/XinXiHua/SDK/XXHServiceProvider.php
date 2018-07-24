<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-19
 * Time: 15:28
 */

namespace XinXiHua\SDK;

use Illuminate\Support\ServiceProvider;
use XinXiHua\SDK\Auth\XXHManager;

class XXHServiceProvider extends ServiceProvider
{


    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__ . '/../../../config/xxh-sdk.php' => config_path('xxh-sdk.php'),
            ], 'xxh-sdk-config');
        }


    }

    /**
     * Register Passport's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $this->publishes([
            __DIR__ . '/../../../database/migrations' => database_path('migrations'),
        ], 'xxh-sdk-migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/xxh-sdk.php',
            'xxh-sdk'
        );

        // 启动信息化
        $this->app->singleton('xxh', function () {
            return new XXHManager($this->app);
        });

    }
}