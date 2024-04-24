<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class OrderComments extends Model
{ 

    protected $table = 'order_comments';
 
    protected $fillable = [
        'comment',
        'order_id', 
    ];
    
}
