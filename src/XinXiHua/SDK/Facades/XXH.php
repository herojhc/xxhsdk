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
}