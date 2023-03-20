<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Maps extends MyBaseModel
{
    use SoftDeletes;

    
    protected $table = 'maps';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array $dates
     */
    public $dates = ['deleted_at'];


    /**
     * The validation error messages.
     *
     * @var array $messages
     */
    protected $messages = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array $fillable
     */
    protected $fillable = [
        'organiser_id',
        'name',
        'description',
        'url',
        'active',
    ];

    public function sections()
    {
        return $this->hasMany(\App\Models\MapsDetails::class);
    } 

}
