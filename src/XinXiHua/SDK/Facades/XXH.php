<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-19
 * Time: 15:55
 */

namespace XinXiHua\SDK\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Route;


/**
 * @method static bool check()
 * @method static \XinXiHua\SDK\Models\Corporation|null corp()
 * @method static int|null id()
 * @method static void login(\XinXiHua\SDK\Models\Corporation $corp)
 * @method static \XinXiHua\SDK\Models\Corporation loginUsingId(mixed $id)
 *
 * @see \XinXiHua\SDK\Auth\XXHMananger
 */
class XXH extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'xxh';
    }

    /**
     * Register the typical authentication routes for an application.
     *
     * @return void
     */
    public static function routes()
    {
        self::homeRoutes();
        self::adminRoutes();
    }

    /**
     * Register the typical authentication routes for an application.
     *
     * @return void
     */
    public static function adminRoutes()
    {
        Route::get('admin/login', '\XinXiHua\SDK\Http\Controllers\Admin\LoginController@login')->name('admin.login');
        Route::post('admin/logout', '\XinXiHua\SDK\Http\Controllers\Admin\LoginController@logout')->name('admin.logout');
        Route::get('admin/callback', '\XinXiHua\SDK\Http\Controllers\Admin\LoginController@callback')->name('admin.callback');
    }

    /**
     * Register the typical authentication routes for an application.
     *
     * @return void
     */
    public static function homeRoutes()
    {
        Route::get('login', '\XinXiHua\SDK\Http\Controllers\LoginController@login')->name('login');
        Route::post('logout', '\XinXiHua\SDK\Http\Controllers\LoginController@logout')->name('logout');
        Route::get('callback', '\XinXiHua\SDK\Http\Controllers\LoginController@callback')->name('callback');
        Route::any('serve', '\XinXiHua\SDK\Http\Controllers\ServeController@serve')->name('serve');
        Route::get('oauth', '\XinXiHua\SDK\Http\Controllers\LoginController@oauth')->name('oauth');

    }


}