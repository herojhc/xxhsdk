<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-23
 * Time: 17:48
 */

namespace XinXiHua\SDK\Events;

use Illuminate\Queue\SerializesModels;

class Login
{
    use SerializesModels;

    /**
     * The authenticated corp.
     *
     * @var \XinXiHua\SDK\Models\Corporation
     */
    public $corp;

    /**
     * Create a new event instance.
     *
     * @param  \XinXiHua\SDK\Models\Corporation $corp
     * @return void
     */
    public function __construct($corp)
    {
        $this->corp = $corp;
    }
}