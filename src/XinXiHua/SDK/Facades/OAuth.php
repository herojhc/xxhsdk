<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2021-01-11
 * Time: 11:36
 */

namespace XinXiHua\SDK\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static bool check()
 * @method static \XinXiHua\SDK\Models\OauthUser|null oauth()
 * @method static string|null id()
 * @method static void login(array $oauth)
 *
 * @see \XinXiHua\SDK\Auth\OauthManager
 */
class OAuth extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'oauth';
    }
}