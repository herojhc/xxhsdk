<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-23
 * Time: 16:37
 */

namespace XinXiHua\SDK\Models;


use Illuminate\Database\Eloquent\Model;

class Corporation extends Model
{
    protected $primaryKey = 'corp_id';

    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'corp_id',
        'name',
        'code',
        'logo',
        'tel',
        'introduce',
        'province_id',
        'city_id',
        'province',
        'city',
        'address',
        'level',
        'is_verified',
        'type',// 1个人2企业
        'status'
    ];
}