<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-12-21
 * Time: 10:25
 */

namespace XinXiHua\SDK\Middleware;


class Authorize
{

    public function handle($request, \Closure $next)
    {
        // TODO

        return $next($request);
    }

}