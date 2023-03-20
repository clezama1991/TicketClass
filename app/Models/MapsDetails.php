<?php

namespace App\Models;

use App\Attendize\Utils;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class MapsDetails extends MyBaseModel
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array $dates
     */
    public $dates = ['deleted_at'];

    public $appends = ['combine'];

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
        'maps_id',
        'rows',
        'cols',
        'coords',
        'active'
    ];

    public function getcombineAttribute()
    {
        return $this->rows.''.$this->cols;
    }
}
