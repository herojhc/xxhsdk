<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-19
 * Time: 15:55
 */

namespace Illuminate\Support\Facades;


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
        Route::get('login', 'XinXiHua\SDK\Http\Controllers\LoginController@login')->name('login');
        Route::post('logout', 'XinXiHua\SDK\Http\Controllers\LoginController@logout')->name('logout');
        Route::get('callback', 'XinXiHua\SDK\Http\Controllers\LoginController@callback')->name('callback');
        Route::get('admin', 'XinXiHua\SDK\Http\Controllers\LoginController@admin')->name('admin');
        Route::any('serve', 'XinXiHua\SDK\Http\Controllers\LoginController@serve')->name('serve');

    }


}