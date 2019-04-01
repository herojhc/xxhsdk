<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-23
 * Time: 16:37
 */

namespace XinXiHua\SDK\Models;


use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $primaryKey = 'contact_id';

    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_id',
        'name',
        'code',
        'gender',
        'birthday',
        'mobile',
        'email',
        'open_id',
        'avatar',
        'corp_id',
        'user_id',
        'is_admin',
        'is_sub_admin',
        'is_employee'
    ];
}