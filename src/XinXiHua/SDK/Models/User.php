<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-23
 * Time: 16:38
 */

namespace XinXiHua\SDK\Models;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = 'user_id';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'username',
        'name',
        'avatar',
        'gender',
        'job',
        'birthday',
        'email',
        'email_validated',
        'mobile',
        'mobile_validated',
        'country',
        'province',
        'city',
        'timezone',
        'locale',
        'source',
        'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}