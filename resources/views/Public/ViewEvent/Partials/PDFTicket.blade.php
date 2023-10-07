<html lang="en"><head>
    <meta charset="UTF-8">
    
   
      <meta name="apple-mobile-web-app-title" content="CodePen">
     
    <title>Ticket(s)</title>
   
     <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&amp;display=swap">
  <style>
  .ticket {
    display: flex;
    font-family: Roboto;
    margin: 16px;
    border: 1px solid #E0E0E0;
    position: relative;
        border-radius: 10px;
  }
  .ticket:before {
    content: "";
    width: 32px;
    height: 32px;
    background-color: #fff;
    border: 1px solid #E0E0E0;
    border-top-color: transparent;
    border-left-color: transparent;
    position: absolute;
    transform: rotate(-45deg);
    left: -18px;
    top: 50%;
    margin-top: -16px;
    border-radius: 50%;
  }
  .ticket:after {
    content: "";
    width: 32px;
    height: 32px;
    background-color: #fff;
    border: 1px solid #E0E0E0;
    border-top-color: transparent;
    border-left-color: transparent;
    position: absolute;
    transform: rotate(135deg);
    right: -18px;
    top: 50%;
    margin-top: -16px;
    border-radius: 50%;
  }
  .ticket--start {
    position: relative;
    border-right: 1px dashed #E0E0E0;
  }
  .ticket--start:before {
    content: "";
    width: 32px;
    height: 32px;
    background-color: #fff;
    border: 1px solid #E0E0E0;
    border-top-color: transparent;
    border-left-color: transparent;
    border-right-color: transparent;
    position: absolute;
    transform: rotate(-45deg);
    left: -18px;
    top: -2px;
    margin-top: -16px;
    border-radius: 50%;
  }
  .ticket--start:after {
    content: "";
    width: 32px;
    height: 32px;
    background-color: #fff;
    border: 1px solid #E0E0E0;
    border-top-color: transparent;
    border-left-color: transparent;
    border-bottom-color: transparent;
    position: absolute;
    transform: rotate(-45deg);
    left: -18px;
    top: 100%;
    margin-top: -16px;
    border-radius: 50%;
  }
  .ticket--start > img {
    display: block;
    padding: 24px;
    height: 270px;
  }
  .ticket--center {
    padding: 24px;
    flex: 1;
  }
  .ticket--center--row {
    display: flex;
  }
  .ticket--center--row:not(:last-child) {
    padding-bottom: 48px;
  }
  .ticket--center--row:first-child span {
    color: #4872b0;
    text-transform: uppercase;
    line-height: 24px;
    font-size: 13px;
    font-weight: 500;
  }
  .ticket--center--row:first-child strong {
    font-size: 20px;
    font-weight: 400;
    text-transform: uppercase;
  }
  .ticket--center--col {
    display: flex;
    flex: 1;
    width: 50%;
    box-sizing: border-box;
    flex-direction: column;
  }
  .ticket--center--col:not(:last-child) {
    padding-right: 16px;
  }
  .ticket--end {
    padding: 24px;
    background-color: #4872b0;
    display: flex;
    flex-direction: column;
    /*position: relative;*/
  }
  .ticket--endx:before {
    content: "";
    width: 32px;
    height: 32px;
    background-color: #fff;
    border: 1px solid #E0E0E0;
    border-top-color: transparent;
    border-right-color: transparent;
    border-bottom-color: transparent;
    position: absolute;
    transform: rotate(-45deg);
    right: -18px;
    top: -2px;
    margin-top: -16px;
    border-radius: 50%;
  }
  .ticket--endx:after {
    content: "";
    width: 32px;
    height: 32px;
    background-color: #fff;
    border: 1px solid #E0E0E0;
    border-right-color: transparent;
    border-left-color: transparent;
    border-bottom-color: transparent;
    position: absolute;
    transform: rotate(-45deg);
    right: -18px;
    top: 100%;
    margin-top: -16px;
    border-radius: 50%;
  }
  .ticket--end > div:first-child {
    flex: 1;
  }
  .ticket--end > div:first-child > img {
    width: 128px;
    padding: 4px;
    background-color: #fff;
  }
  .ticket--end > div:last-child > img {
    display: block;
    margin: 0 auto;
    filter: brightness(0) invert(1);
    opacity: 0.64;
  }
  .ticket--info--title {
    text-transform: uppercase;
    color: #757575;
    font-size: 13px;
    line-height: 24px;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .ticket--info--subtitle {
    font-size: 14px;
    line-height: 24px;
    font-weight: 500;
    color: #4872b0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .ticket--info--content {
    font-size: 13px;
    line-height: 24px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  </style>
  
    <script>
    window.console = window.console || function(t) {};
  </script>
  
    
    
  </head>
  
  <body translate="no">
      
            @foreach($attendees as $key_order_item => $attendee)
                  @if(!$attendee->is_cancelled)
    <div class="ticket" style="padding:10px; padding-bottom:20px">
        
        
        
        
          <table class="table" width="100%"> 
              <tbody>
                  <tr> 
                      <td width="100%">
                           
                           
                           
                           
                           
                          <table class="table" width="100%"> 
                              <tbody>
                                  <tr class="ticket--center--row"> 
                                      <td colspan="2"> 
                                          
                                          <table class="table"> 
                                              <tbody>
                                                  <tr> 
                                                      <td class="ticket--info--title">EVENTO</td>
                                                  </tr> 
                                                  <tr> 
                                                      <td>
          <strong>{{$event->title}}</strong></td>
                                                  </tr> 
                                              </tbody>
                                          </table>
                          
                                      </td>
                                  </tr>  
                                  <tr class="ticket--center--row"> 
                                    
                                      <td  colspan="2"> 
                                          
                                          <table class="table"> 
                                              <tbody>
                                                  <tr> 
                                                      <td class="ticket--info--title">REF. DE ASISTENTE</td>
                                                  </tr> 
                                                  <tr> 
                                                      <td class="ticket--info--subtitle"> {{$attendee->reference}} /  @lang("Order.order_ref"): Fernando Vazquez</td>
                                                  </tr> 
                                              </tbody>
                                          </table>
                          
                                      </td>
                                  </tr>  
                                  <tr class="ticket--center--row"> 
                                      <td  width="50%"> 
                                          
                                          <table class="table"> 
                                              <tbody>
                                                  <tr> 
                                                      <td><span class="ticket--info--title">@lang("Ticket.start_date_time")</span></td>
                                                  </tr> 
                                                  <tr> 
                                                      <td>        <span class="ticket--info--subtitle">{{$event->startDateFormatted()}}</span>
  </td>
                                                  </tr> 
                                              </tbody>
                                          </table>
                          
                                      </td>
                                      <td  width="50%">  
                                          <table class="table"> 
                                              <tbody>
                                                  <tr> 
                                                      <td>        <span class="ticket--info--title">@lang("Ticket.venue")</span>
  </td>
                                                  </tr> 
                                                  <tr> 
                                                      <td>
                                                          
          <span class="ticket--info--subtitle">{{$event->venue_name}}</span>
          <span class="ticket--info--content">{{$event->FullAddress}} </span></td>
                                                  </tr> 
                                              </tbody>
                                          </table>
                          
                                      </td>
                                  </tr>  
                                  <tr class="ticket--center--row"> 
                                      <td  colspan="2"> 
                                          
                                          <table class="table"> 
                                              <tbody>
                                                  <tr> 
                                                      <td class="ticket--info--title">TIPO DE ENTRADA / PRECIO</td>
                                                  </tr> 
                                                  <tr> 
                                                      <td class="ticket--info--subtitle">
                                                          
                                                          
                                                          
                                        {{$attendee->ticket->title}} 
                                        
                                        @if($attendee->seats)
                                              <h4>
                                                  {{{$attendee->seats->seat()}}}
                                              </h4>
                                          @endif  
                                          / 
                                        @php
                                            // Calculating grand total including tax
                                            $grand_total = $attendee->ticket->total_price;
                                            $tax_amt = ($grand_total * $event->organiser->tax_value) / 100;
                                            $grand_total = $attendee->ticket->price_neto;
                                        @endphp
                                        {{money($grand_total, $order->event->currency)}} @if ($attendee->ticket->price_service) ( Mas {{money($attendee->ticket->price_service, $order->event->currency)}} @lang("Public_ViewEvent.inc_fees")) @endif @if ($event->organiser->tax_name) (inc. {{money($tax_amt, $order->event->currency)}} {{$event->organiser->tax_name}})
                                        <br><br>{{$event->organiser->tax_name}} ID: {{ $event->organiser->tax_id }}
                                        @endif</td>
                                                  </tr> 
                                              </tbody>
                                          </table>
                          
                                      </td>
                                  </tr>  
                                  <tr class="ticket--center--row"> 
                                  
                                      <td colspan="2"> 
                                          
                                      </td>
                                  </tr>  
                              </tbody>
                          </table>
                          
                          
                          
                          
                           
                      </td>
                      <td style="background-color: #4872b0;">
                                        <div class="ticket--end">
                                          <div>
                                          <div style="background:white;text-align: -webkit-center;padding: 10px;">
                                              {!! DNS2D::getBarcodeSVG($attendee->private_reference_number, "QRCODE", 4, 4) !!}</div></div>
                                          <div> <br><br><br> <img src="data:image/png;base64, {{$image}}" style="max-width: 100px;"/></div>
                                          
                                          
                                        </div></td>
                  </tr> 
              </tbody>
          </table>
        <hr style="border: 1px dashed; color:#e5e5e5;">
        
          <table class="table" width="100%"> 
          
              <tbody>
                  <tr>  aqui el cintillo
                  </tr> 
              </tbody>
          </table>
        
    </div>
    
                @endif
              @endforeach
         
  
  </body></html>