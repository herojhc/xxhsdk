<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-12-28
 * Time: 12:38
 */

namespace XinXiHua\SDK\Events;

use Illuminate\Queue\SerializesModels;

class FileUploaded
{
    use SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
}