<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SeatTicket extends Model
{
    use SoftDeletes;

    protected $table = 'seats_tickets';

    
    public static function boot()
    {
        parent::boot();

        SeatTicket::observe(new SeatTicketObserver);
    }

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
     * The seats associated with the seat attemdee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getEventId()
    {
        return $this->ticket ? $this->ticket->event_id : 0;
    }

    /**
     * The tickets associated with the seats.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ticket()
    {
        return $this->belongsTo(\App\Models\Ticket::class);
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
        $abecedario = range('A', 'Z');         
        return  $abecedario[$this->row-1].''.$this->column;
    }
    
    public function seatRows(){
        $abecedario = range('A', 'Z');         
        return  $abecedario[$this->row-1];
    }
    
}
