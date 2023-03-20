<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SeatTicket extends Model
{
    use SoftDeletes;

    protected $table = 'seats_tickets';

     /**
     * The seats associated with the seat attemdee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function seatAttemdee()
    {
        return $this->belongsToMany(\App\Models\SeatAttemdee::class);
    }

    /**
     * The tickets associated with the seats.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class);
    }

    public function seat(){
        return 'F'.$this->row.'-A'.$this->column;
    }
    
}
