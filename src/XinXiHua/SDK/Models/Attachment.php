<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-03-06
 * Time: 10:52
 */

namespace XinXiHua\SDK\Models;


use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'filename',
        'original_name',
        'real_path',
        'mime',
        'size',
        'md5',
        'sha1',
        'url',
        'is_image',
        'platform_attachment_id'
    ];
}