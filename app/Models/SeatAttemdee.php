<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SeatAttemdee extends Model
{
    use SoftDeletes;

    protected $table = 'seat_attemdees';

     /**
     * The seats associated with the seat attemdee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function seatTicket()
    {
        return $this->belongsToMany(\App\Models\SeatTicket::class);
    }


     /**
     * The attemdee associated with the seats.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attemdee()
    {
        return $this->belongsToMany(\App\Models\Attendee::class);
    }

}
