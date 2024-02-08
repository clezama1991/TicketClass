<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends MyBaseModel
{
    use SoftDeletes;

    protected $dates = ['start_sale_date', 'end_sale_date'];

    
    protected $appends = ['quantity_free','quantity_without_free'];
  
    public function getQuantityFrexeAttribute(){
        $cortesia = 0;
        $orders = $this->orders;
        foreach ($orders as $key => $order) {
            if ($order->order->payment_method == 'free' && $order->order->is_cancelled == false) {
                $cortesia += $order->SumQuantyorderItems();
             }
        }
        
        return $cortesia;
    }

    public function getQuantityFreeAttribute(){
        $free = 0;
        $orders = $this->orders->where('is_cancelled',false);
        foreach ($orders as $key => $order) {
            if ($order->order->payment_method == 'free'  && $order->order->is_cancelled == false) {
                $free+= $order->quantity;
            }
        }
        
        return $free;
    }

    public function getQuantityWithoutFreeAttribute(){
        return $this->quantity_sold - $this->quantity_free;
    }

    
    /**
     * The rules to validate the model.
     *
     * @return array $rules
     */
    protected function getPriceOnlineAttribute()
    {
        
        return '5000';
        
    }

    public function rules()
    {
        $format = config('attendize.default_datetime_format');
        return [
            'title'              => 'required',
            'seat_zone'              => 'required',
            'price'              => 'required|numeric|min:0',
            //'quantity_row'       => 'required|numeric|min:1|integer',
            'description'        => '',
            'start_sale_date'    => 'date_format:"'.$format.'"',
            'end_sale_date'      => 'date_format:"'.$format.'"|after:start_sale_date',
            'quantity_available' => 'required|integer|min:'.($this->quantity_sold + $this->quantity_reserved)
        ];
    }

    /**
     * The validation error messages.
     *
     * @var array $messages
     */
    public $messages = [
        'price.numeric'              => 'The price must be a valid number (e.g 12.50)',
        'title.required'             => 'You must at least give a title for your ticket. (e.g Early Bird)',
        'quantity_available.integer' => 'Please ensure the quantity available is a number.',
    ];
    protected $perPage = 10;

     /**
     * The seats associated with the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    
    public function seats()
    {
        return $this->hasMany(\App\Models\SeatTicket::class);
    }


    public function seat()
    {
        return $this->belongsToMany(\App\Models\SeatTicket::class);
    }

    /**
     * The event associated with the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }

    /**
     * The order associated with the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function order()
    {
        return $this->belongsToMany(\App\Models\Order::class);
    }

    /**
     * The order associated with the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }

    /**
     * The questions associated with the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions()
    {
        return $this->belongsToMany(\App\Models\Question::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    function event_access_codes()
    {
        return $this->belongsToMany(
            EventAccessCodes::class,
            'ticket_event_access_code',
            'ticket_id',
            'event_access_code_id'
        )->withTimestamps();
    }

    /**
     * TODO:implement the reserved method.
     */
    public function reserved()
    {
    }

    /**
     * Parse start_sale_date to a Carbon instance
     *
     * @param string $date DateTime
     */
    public function setStartSaleDateAttribute($date)
    {
        if (!$date) {
            $this->attributes['start_sale_date'] = Carbon::now();
        } else {
            $this->attributes['start_sale_date'] = Carbon::createFromFormat(
                config('attendize.default_datetime_format'),
                $date
            );
        }
    }

    /**
     * Parse end_sale_date to a Carbon instance
     *
     * @param string|null $date DateTime
     */
    public function setEndSaleDateAttribute($date)
    {
        if (!$date) {
            $this->attributes['end_sale_date'] = null;
        } else {
            $this->attributes['end_sale_date'] = Carbon::createFromFormat(
                config('attendize.default_datetime_format'),
                $date
            );
        }
    }

    /**
     * Scope a query to only include tickets that are sold out.
     *
     * @param $query
     */
    public function scopeSoldOut($query)
    {
        $query->where('remaining_tickets', '=', 0);
    }

    /**
     * Get the number of tickets remaining.
     *
     * @return \Illuminate\Support\Collection|int|mixed|static
     */
    public function getQuantityRemainingAttribute()
    {
        if (is_null($this->quantity_available)) {
            return 9999; //Better way to do this?
        }

        return $this->quantity_available - ($this->quantity_sold + $this->quantity_reserved);
    }

    /**
     * Get the number of tickets reserved.
     *
     * @return mixed
     */
    public function getQuantityReservedAttribute()
    {
        $reserved_total = DB::table('reserved_tickets')
            ->where('ticket_id', $this->id)
            ->where('expires', '>', Carbon::now())
            ->sum('quantity_reserved');

        return $reserved_total;
    }

    /**
     * Get the total price of the ticket.
     *
     * @return float|int
     */
    public function getTotalPriceAttribute()
    {
        return $this->getTotalBookingFeeAttribute() + $this->price;
    }

    public function getTotalPriceOnlineAttribute()
    {
        return $this->getTotalBookingFeeOnlineAttribute() + $this->price + $this->price_paypal;
    }

    /**
     * Get the total booking fee of the ticket.
     *
     * @return float|int
     */
    public function getTotalBookingFeeAttribute()
    {
        return $this->getBookingFeeAttribute() + $this->getOrganiserBookingFeeAttribute();
    }

    public function getTotalBookingFeeOnlineAttribute()
    {
        return $this->getBookingFeeOnlineAttribute() + $this->getOrganiserBookingFeeOnlineAttribute();
    }

    /**
     * Get the booking fee of the ticket.
     *
     * @return float|int
     */
    public function getBookingFeeAttribute()
    {
        return (int)ceil($this->price) === 0 ? 0 : round(
            ($this->price * (config('attendize.ticket_booking_fee_percentage') / 100)) + (config('attendize.ticket_booking_fee_fixed')),
            2
        );
    }

    public function getBookingFeeOnlineAttribute()
    {
        return (int)ceil($this->price) === 0 ? 0 : round(
            (($this->price+$this->price_paypal) * (config('attendize.ticket_booking_fee_percentage') / 100)) + (config('attendize.ticket_booking_fee_fixed')),
            2
        );
    }

    /**
     * Get the organizer's booking fee.
     *
     * @return float|int
     */
    public function getOrganiserBookingFeeAttribute()
    {
        return (int)ceil($this->price) === 0 ? 0 : round(
            ($this->price * ($this->event->organiser_fee_percentage / 100)) + ($this->event->organiser_fee_fixed),
            2
        );
    }

    public function getOrganiserBookingFeeOnlineAttribute()
    {
        return (int)ceil($this->price) === 0 ? 0 : round(
            (($this->price+$this->price_paypal) * ($this->event->organiser_fee_percentage / 100)) + ($this->event->organiser_fee_fixed),
            2
        );
    }

    /**
     * Get the maximum and minimum range of the ticket.
     *
     * @return array
     */
    public function getTicketMaxMinRangAttribute()
    {
        $range = [];

        for ($i = $this->min_per_person; $i <= $this->max_per_person; $i++) {
            $range[] = [$i => $i];
        }

        return $range;
    }

    /**
     * Indicates if the ticket is free.
     *
     * @return bool
     */
    public function getIsFreeAttribute()
    {
        return ceil($this->price) == 0;
    }

    /**
     * Return the maximum figure to go to on dropdowns.
     *
     * @return int
     */
    public function getSaleStatusAttribute()
    {
        if ($this->start_sale_date !== null && $this->start_sale_date->isFuture()) {
            return config('attendize.ticket_status_before_sale_date');
        }

        if ($this->end_sale_date !== null && $this->end_sale_date->isPast()) {
            return config('attendize.ticket_status_after_sale_date');
        }

        if ((int)$this->quantity_available > 0 && (int)$this->quantity_remaining <= 0) {
            return config('attendize.ticket_status_sold_out');
        }

        if ($this->event->start_date->lte(Carbon::now())) {
            return config('attendize.ticket_status_off_sale');
        }

        return config('attendize.ticket_status_on_sale');
    }
}
