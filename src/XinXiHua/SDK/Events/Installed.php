<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-23
 * Time: 23:36
 */

namespace XinXiHua\SDK\Events;


use Illuminate\Queue\SerializesModels;

class Installed
{

    use SerializesModels;

    /**
     * The installed corp.
     *
     * @var \XinXiHua\SDK\Models\Corporation
     */
    public $corp;
    /**
     * The installed user.
     *
     * @var \XinXiHua\SDK\Models\User
     */
    public $user;
    /**
     * The installed contact.
     *
     * @var \XinXiHua\SDK\Models\Contact
     */
    public $contact;

    /**
     * Create a new event instance.
     *
     * @param  \XinXiHua\SDK\Models\Corporation $corp
     * @param  \XinXiHua\SDK\Models\User $user
     * @param  \XinXiHua\SDK\Models\Contact $contact
     * @return void
     */
    public function __construct($corp, $user, $contact)
    {
        $this->corp = $corp;
        $this->user = $user;
        $this->contact = $contact;
    }
}