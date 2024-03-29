<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
use URL;

class Event extends MyBaseModel
{
    use SoftDeletes;

    protected $dates = ['start_date', 'end_date', 'on_sale_date'];

    protected $appends = ['quantity_sold','quantity_free','quantity_without_free'];
  
    public function getQuantitySoldAttribute(){
        return $this->tickets->sum('quantity_sold');
    }

    public function cortesias(){
        
        return $this->orders->where('payment_method','free')->where('is_cancelled',false);
    }

    public function getQuantityFreeAttribute(){
        
        $cortesia=0;
        $orders = $this->orders->where('payment_method','free')->where('is_cancelled',false);

        foreach ($orders as $key => $order) { 
            $cortesia += $order->SumQuantyorderItems(); 
        }

        return $cortesia;
    }

    public function getQuantityWithoutFreeAttribute(){
        return $this->quantity_sold - $this->quantity_free;
    }

    /**
     * The validation rules.
     *
     * @return array $rules
     */
    public function rules()
    {
        $format = config('attendize.default_datetime_format');
        return [
                'title'               => 'required',
                'description'         => 'required',
                'map_id'                 => 'required',
                'location_venue_name' => 'required_without:venue_name_full',
                'venue_name_full'     => 'required_without:location_venue_name',
                'start_date'          => 'required|date_format:"'.$format.'"',
                'end_date'            => 'required|date_format:"'.$format.'"',
                'organiser_name'      => 'required_without:organiser_id',
                'event_image'         => 'mimes:jpeg,jpg,png|max:3000',
            ];
    }

    /**
     * The validation error messages.
     *
     * @var array $messages
     */
    protected $messages = [
        'title.required'                       => 'You must at least give a title for your event.',
        'map_id.required'                         => 'Please select map to event.',
        'organiser_name.required_without'      => 'Please create an organiser or select an existing organiser.',
        'event_image.mimes'                    => 'Please ensure you are uploading an image (JPG, PNG, JPEG)',
        'event_image.max'                      => 'Please ensure the image is not larger then 3MB',
        'location_venue_name.required_without' => 'Please enter a venue for your event',
        'venue_name_full.required_without'     => 'Please enter a venue for your event',
    ];

    /**
     * The questions associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions()
    {
        return $this->belongsToMany(\App\Models\Question::class, 'event_question');
    }

    /**
     * The questions associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions_with_trashed()
    {
        return $this->belongsToMany(\App\Models\Question::class, 'event_question')->withTrashed();
    }

    /**
     * The attendees associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendees()
    {
        return $this->hasMany(\App\Models\Attendee::class);
    }

    /**
     * The images associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(\App\Models\EventImage::class);
    }

    /**
     * The images associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imageCover()
    {
        return $this->front_image_path;
        // return (count($this->images)>0) ? $this->images[0]->image_path : null;
    }

    /**
     * The messages associated with the event.
     *
     * @return mixed
     */
    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class)->orderBy('created_at', 'DESC');
    }

    /**
     * The tickets associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class);
    }
    
    public function tickets_group_zone($is_hidden = false)
    {   
        $secciones = [];
        if($is_hidden){
            foreach ($this->tickets->where('is_hidden',false) as $key => $tickets) { 
                if(!in_array($tickets->group_zone, $secciones)){
                    $secciones[] = $tickets->group_zone;
                }  
            }
        }else{
            foreach ($this->tickets as $key => $tickets) { 
                if(!in_array($tickets->group_zone, $secciones)){
                    $secciones[] = $tickets->group_zone;
                }  
            }
        }
 
        return $secciones;
    }

    /**
     * The stats associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stats()
    {
        return $this->hasMany(\App\Models\EventStats::class);
    }

    /**
     * The affiliates associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function affiliates()
    {
        return $this->hasMany(\App\Models\Affiliate::class);
    }

    /**
     * The orders associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function amount_orders()
    {
        $total = 0;
        foreach ($this->orders->where('payment_gateway_id',null) as $key => $value) {
            $total += $value->getOrganiserAmountAmountAttribute();
        }
        return $total;
    }

    public function amount_orders_online()
    {
        $total = 0;
        foreach ($this->orders->where('payment_gateway_id','!=',null) as $key => $value) {
            $total += $value->getOrganiserAmountAmountAttribute();
        }
        return $total;
    }

    public function amount_orders_online_without_paypal()
    {
        $total = 0;
        foreach ($this->orders->where('payment_gateway_id','!=',null) as $key => $value) {
            $total += $value->getOrganiserAmountAttribute();
        }
        $comision_deduccion =  $total * 1.06  - $total;
        return round($total - $comision_deduccion, 0);
    }

    public function amout_commision_paypal()
    {
        $total = 0;
        foreach ($this->orders->where('payment_gateway_id','!=',null) as $key => $value) {
            $total += $value->getOrganiserAmountAttribute();
        }
        $comision_deduccion =  $total * 1.06  - $total;
        return round($comision_deduccion, 0);
    }

    /**
     * The access codes associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function access_codes()
    {
        return $this->hasMany(\App\Models\EventAccessCodes::class, 'event_id', 'id');
    }

    /**
     * The account associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class);
    }

    /**
     * The currency associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(\App\Models\Currency::class);
    }

    /**
     * The organizer associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organiser()
    {
        return $this->belongsTo(\App\Models\Organiser::class);
    }

    /**
     * Get the embed url.
     *
     * @return mixed
     */
    public function getEmbedUrlAttribute()
    {
        return str_replace(['http:', 'https:'], '', route('showEmbeddedEventPage', ['event' => $this->id]));
    }

    /**
     * Get the fixed fee.
     *
     * @return mixed
     */
    public function getFixedFeeAttribute()
    {
        return config('attendize.ticket_booking_fee_fixed') + $this->organiser_fee_fixed;
    }

    /**
     * Get the percentage fee.
     *
     * @return mixed
     */
    public function getPercentageFeeAttribute()
    {
        return config('attendize.ticket_booking_fee_percentage') + $this->organiser_fee_percentage;
    }

    /**
     * Parse start_date to a Carbon instance
     *
     * @param string $date DateTime
     */
    public function setStartDateAttribute($date)
    {
        $format = config('attendize.default_datetime_format');
        $this->attributes['start_date'] = Carbon::createFromFormat($format, $date);
    }

    /**
     * Format start date from user preferences
     * @return String Formatted date
     */
    public function startDateFormatted()
    {
        return $this->start_date->format(config('attendize.default_datetime_format'));
    }

    public function startDateFormattedGeneric($format)
    {
        
        $shortDayNames = [
            'Sun' =>'Domingo',
            'Mon' =>'Lunes', 
            'Tue' =>'Martes', 
            'Wed' =>'Miércoles', 
            'Thu' =>'Jueves', 
            'Fri' =>'Viernes', 
            'Sat' =>'Sábado'
        ];

        $shortMonthNames = [
            'Jan' =>'Enero', 
            'Feb' =>'Febrero', 
            'Mar' =>'Mazo', 
            'Apr' =>'Abril', 
            'May' =>'Mayo', 
            'Jun' =>'Junio', 
            'Jul' =>'Julio', 
            'Aug' =>'Agosto', 
            'Sep' =>'Septiembre', 
            'Oct' =>'Octubre', 
            'Nov' =>'Noviembre', 
            'Dec' =>'Diciembre'
        ];

        $result = $this->start_date->format($format);

     
        foreach ($shortMonthNames as $keyM => $valueM) {
            if( strpos( $result, $keyM ) !== false) {
               $result = str_replace($keyM, $valueM, $result);
            }
        }
        foreach ($shortDayNames as $key => $value) {
            if( strpos( $result, $key ) !== false) {
                $result = str_replace($key, $value, $result);
            }
        }

 
        return $result;
    }

    /**
     * Parse end_date to a Carbon instance
     *
     * @param string $date DateTime
     */
    public function setEndDateAttribute($date)
    {
        $format = config('attendize.default_datetime_format');
        $this->attributes['end_date'] = Carbon::createFromFormat($format, $date);
    }

    /**
     * Format end date from user preferences
     * @return String Formatted date
     */
    public function endDateFormatted()
    {
        return $this->end_date->format(config('attendize.default_datetime_format'));
    }

    /**
     * Indicates whether the event is currently happening.
     *
     * @return bool
     */
    public function getHappeningNowAttribute()
    {
        return Carbon::now()->between($this->start_date, $this->end_date);
    }

    /**
     * Get the currency symbol.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCurrencySymbolAttribute()
    {
        return $this->currency->symbol_left;
    }

    /**
     * Get the currency code.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCurrencyCodeAttribute()
    {
        return $this->currency->code;
    }

    /**
     * Return an array of attendees and answers they gave to questions at checkout
     *
     * @return array
     */
    public function getSurveyAnswersAttribute()
    {
        $rows[] = array_merge([
            'Order Ref',
            'Attendee Name',
            'Attendee Email',
            'Attendee Ticket'
        ], $this->questions->pluck('title')->toArray());

        $attendees = $this->attendees()->has('answers')->get();

        foreach ($attendees as $attendee) {
            $answers = [];

            foreach ($this->questions as $question) {
                if (in_array($question->id, $attendee->answers->pluck('question_id')->toArray())) {
                    $answers[] = $attendee->answers->where('question_id', $question->id)->first()->answer_text;
                } else {
                    $answers[] = null;
                }
            }

            $rows[] = array_merge([
                $attendee->order->order_reference,
                $attendee->full_name,
                $attendee->email,
                $attendee->ticket->title
            ], $answers);
        }

        return $rows;
    }

    /**
     * Get the embed html code.
     *
     * @return string
     */
    public function getEmbedHtmlCodeAttribute()
    {
        return "<!--Attendize.com Ticketing Embed Code-->
                <iframe style='overflow:hidden; min-height: 100vh;' frameBorder='0' seamless='seamless' width='100%' height='100%' src='" . $this->embed_url . "' vspace='0' hspace='0' scrolling='auto' allowtransparency='true'></iframe>
                <!--/Attendize.com Ticketing Embed Code-->";
    }

    /**
     * Get a usable address for embedding Google Maps
     *
     */
    public function getMapAddressAttribute()
    {
        $string = $this->venue . ','
            . $this->location_street_number . ','
            . $this->location_address_line_1 . ','
            . $this->location_address_line_2 . ','
            . $this->location_state . ','
            . $this->location_post_code . ','
            . $this->location_country;

        return urlencode($string);
    }

    public function getFullAddressAttribute()
    {
        $string = $this->venue_name . ','
            . $this->location_address_line_1 . ','
            . $this->location_state . ','
            . $this->location_post_code . ','
            . $this->location_country;

        return $string;
    }

    /**
     * Get the big image url.
     *
     * @return string
     */
    public function getBgImageUrlAttribute()
    {
        return URL::to('/') . '/' . $this->bg_image_path;
    }

    /**
     * Get the url of the event.
     *
     * @return string
     */
    public function getEventUrlAttribute()
    {
        return route("showEventPage", ["event_id"=>$this->id, "event_slug"=>Str::slug($this->title)]);
        //return URL::to('/') . '/e/' . $this->id . '/' . Str::slug($this->title);
    }

    /**
     * Get the sales and fees volume.
     *
     * @return \Illuminate\Support\Collection|mixed|static
     */
    public function getSalesAndFeesVoulmeAttribute()
    {
        return $this->sales_volume + $this->organiser_fees_volume;
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @return array $dates
     */
    public function getDates()
    {
        return ['created_at', 'updated_at', 'start_date', 'end_date'];
    }

    public function getIcsForEvent()
    {
        $siteUrl = URL::to('/');
        $eventUrl = $this->getEventUrlAttribute();

        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $timestamp = new Carbon();

        $icsTemplate = <<<ICSTemplate
BEGIN:VCALENDAR
VERSION:2.0
PRODID:{$siteUrl}
BEGIN:VEVENT
UID:{$eventUrl}
DTSTAMP:{$timestamp->format('Ymd\THis\Z')}
DTSTART:{$start_date->format('Ymd\THis\Z')}
DTEND:{$end_date->format('Ymd\THis\Z')}
SUMMARY:$this->title
LOCATION:{$this->venue_name}
DESCRIPTION:{$this->description}
END:VEVENT
END:VCALENDAR
ICSTemplate;

        return $icsTemplate;
    }

    /**
     * @param integer $accessCodeId
     * @return bool
     */
    public function hasAccessCode($accessCodeId)
    {
        return (is_null($this->access_codes()->where('id', $accessCodeId)->first()) === false);
    }
    public function seat_zone_ocupadas()
    {
        return $this->tickets->where('is_hidden', false)->pluck('seat_zone')->toArray();
    }

    
    /**
     * The currency associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function map()
    {
        return $this->belongsTo(\App\Models\Maps::class);
    }


    public function sections_map()
    {
        return ($this->map) ? $this->map->sections : [];
    }


    public function image_map()
    {
        return ($this->map) ? $this->map->url : null;
    }

    public function seat_row()
    {
        
        $tickets_id = $this->tickets->pluck('id')->toArray();
 
        $SeatTicket = SeatTicket::whereIn('ticket_id',$tickets_id)->get()->last();

        return $SeatTicket->row ?? 0;

    }


}
