
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style type="text/css">
            body {
                font-family: sans-serif;
                background-color: #fff;
                color: #535353;
                margin: 0px;
            }
            
            table {
                border-collapse: collapse;
                padding: 0;
                width: 100%;
				border-radius:10px;
            }
            
            td {
                padding: 0;
				border-radius:10px;
                vertical-align: top;
            }
            
            .ticket-title {
                color: #999;
                font-size: 16px;
                letter-spacing: 0;
                line-height: 16px;
                margin-top: 10px;
            }
            
            .ticket-info {
                color: #535353;
                font-size: 14px;
                line-height: 21px;
            }
            
            .ticket-wrapper {
                border: 1px solid #000;
				border-radius:10px;
                border-top: 12px solid rgb(33,150,243);
                margin: 10px auto 0;
                padding-bottom: 15px;
                width: 850px;
            }
            
            .ticket-wrapper:first-child {
                margin-top: 0;
            }
            
            .ticket-table {}
            
            .ticket-table .first-col {
                width: 570px;
            }
            
            .ticket-logo {
                border-left: 1px solid #ccc;
                text-align: center;
                vertical-align: middle;
            }
            
            .ticket-logo img {
                height: 350px;
                width: 200px;
            }
            
            .ticket-name-div {
                vertical-align: middle;
                border-bottom: 1px solid #ccc;
                margin: 0 12px 0 22px;
                padding: 15px 0px 15px 0;
                text-align: center;
                font-size: 22px;
            }
            
            .ticket-event-longtitle {
                color: #535353;
                font-size: 22px;
				display:flex;
				
            }
            
            .ticket-event-shorttitle {
                color: #535353;
                font-size: 20px;
                letter-spacing: -1px;
                line-height: 22px;
            }
            
            .ticket-event-details {
                border-bottom: 2px solid #ccc;
                margin: 0 12px 0px 22px;
                padding: 15px 0px 15px 0;
                text-align: left;
            }
            
            .ticket-event-details .first-col {
                text-align: left;
                width: 40%;
            }
            
            .ticket-event-details .second-col {
                text-align: right;
                vertical-align: top;
                width: 60%;
            }
            
            .ticket-venue {
                color: #535353;
                font-size: 14px;
                line-height: 21px;
            }
            
            .ticket-venue:first-child {
                font-size: 16px;
            }
            
            .ticket-ticket-details {
                margin: 0 12px 0px 22px;
                text-align: left;
            }
            
            .ticket-ticket-details .first-col {
                border-right: 2px solid #ccc;
                padding-top: 4px;
                text-align: left;
                vertical-align: top;
                width: 150px;
            }
            
            .ticket-ticket-details .second-col {
                padding: 4px 0px 0px 32px;
                text-align: left;
                width: 225px;
            }
            
            .ticket-ticket-details .third-col {
                text-align: right;
            }
            
            .ticket-qr-code{
                height: 95px;
                margin-top: 10px;
                width: 95px;
            }
            
            /* Print specific styles */
            @media print {
                a[href]:after, abbr[title]:after {
                    content: "";
                }
                
                .ticket-wrapper {
                    width: 16cm;
                }
                
                .ticket-table .first-col {
                    width: 13.8cm;
                }
                
                .ticket-logo img {
                    height: auto;
                    max-width: 100%;
                }
                
                .ticket-ticket-details .first-col {
                    width: 4cm;
                }
                
                .ticket-ticket-details .second-col {
                    width: 6cm;
                }
                
                .ticket-ticket-details .third-col {
                    width: 2.5cm;
                }
                
                @page {
                    margin: 1cm;
                }
            }
        </style>
    </head>
    <body>
		
@foreach($attendees as $key_order_item => $attendee)
@if(!$attendee->is_cancelled)
        <!-- Start Ticket -->
        <div class="ticket-wrapper">
			
            <table class="ticket-table">
                <tr>
                    <td class="first-col" style="vertical-align: middle">
						
								<img src="data:image/png;base64, {{$image}}" style="max-width: 100px; position:absolute; margin-top:-30px" />
      						
                        <!-- title -->
                        <div class="ticket-name-div">
                        
							<span class="ticket-event-longtitle" style="">
								{{$event->title}}
</span>
                        </div>
                        <!-- /.ticket-name-div -->
                        <!-- venue details start -->
                        <div class="ticket-event-details">
                            <table>
                                <tr>
                                    <td class="first-col">
                                        <div class="ticket-info">
                                          {{$event->startDateFormattedGeneric('D, d M Y')}}
                                        </div>
                                        <!-- /.ticket-info -->
                                        <div class="ticket-title">
                                            HORA
                                        </div>
                                        <!-- /.ticket-title -->
                                        <div class="ticket-info">
                                          {{$event->startDateFormattedGeneric('H:i A')}}
                                        </div>
                                        <!-- /.ticket-info -->
                                    </td>
                                    <!-- /.first-col -->
                                    <td class="second-col">
                                        <div class="ticket-venue">
                                            DIRECCION
                                        </div>
                                        <!-- /.ticket-venue -->
                                        <div class="ticket-venue">
                                            {{$event->venue_name}}
                                        </div>
                                        <!-- /.ticket-venue -->
                                        <div class="ticket-venue">
											{{$event->FullAddress}}
                                        </div>
                                        <!-- /.ticket-venue -->
                                    </td>
                                    <!-- /.second-col -->
                                </tr>
                            </table>
                        </div>
                        <!-- /.ticket-event-details -->
                        <!-- ticket details start -->
                        <div class="ticket-ticket-details">
							<br>
                            <table>
                                <tr>
                                    <td class="first-col">
                                        <div class="ticket-title">
                                            TICKET #
                                        </div>
                                        <!-- /.ticket-title -->
                                        <div class="ticket-info">
                                            {{$attendee->reference}}
                                        </div>
                                        <!-- /.ticket-info -->
                                        <div class="ticket-title">
                                            PRECIO
                                        </div>
                                        <!-- /.ticket-title -->
                                        <div class="ticket-info">
                                            
            @if ($order->payment_method=='free')
            Cortesia
        @else
            
          @php
            // Calculating grand total including tax
            $grand_total = $attendee->ticket->total_price;
            $tax_amt = ($grand_total * $event->organiser->tax_value) / 100;
            $grand_total = $attendee->ticket->price_neto;
          @endphp
          
          {{money($grand_total, $order->event->currency)}}  
          
          @if ($attendee->ticket->price_service && $attendee->ticket->price_service>0) 
            ( Mas {{money($attendee->ticket->price_service, $order->event->currency)}} @lang("Public_ViewEvent.inc_fees"))
          @endif 

          @if ($event->organiser->tax_name && $tax_amt>0) 
            (inc. {{money($tax_amt, $order->event->currency)}} {{$event->organiser->tax_name}})
            
          @endif 

        @endif

                                        </div>
                                        <!-- /.ticket-info -->
                                    </td>
                                    <!-- /.first-col -->
                                    <td class="second-col">
                                        <div class="ticket-title">
                                           <!-- ASISTENTE -->
                                        </div>
                                        <!-- /.ticket-title -->
                                        <div class="ticket-info">
                                          <!--              {{$attendee->first_name}}  -->

                                        </div>
										<br>
                                        <!-- /.ticket-info -->
                                        <div class="ticket-title">
                                            ENTRADA/ASIENTO
                                        </div>
                                        <!-- /.ticket-title -->
                                        <div class="ticket-info">
                                            {{$attendee->ticket->title}} 
            - Silla:
            @if($attendee->seats)
                  <span class="ticket--info--subtitle">
                      {{{$attendee->seats->seat()}}}
                  </span>
                  @else
                  N/A
              @endif  
                                        </div>
                                        <!-- /.ticket-info -->
                                    </td>
                                    <!-- /.second-col -->
                                    <td class="third-col">
										<br>	
                                        <a href="#" target="_blank">
										{!! DNS2D::getBarcodeSVG($attendee->private_reference_number, "QRCODE", 4, 4) !!} 

                                         </a>
                                    </td>
                                    <!-- /.third-col -->
                                </tr>
                            </table>
                        </div>
                        <!-- /.ticket-ticket-details -->
                    </td>
                    <!-- /.first-col -->
                    <td class="ticket-logo" style="width:100px">
						
      <img src="{{$bg}}"  />
                     </td>
                    <!-- /.ticket-logo -->
                </tr>
            </table>
            <!-- /.ticket-table -->
        </div>
        <!-- /.ticket-wrapper -->
        <!-- End Ticket -->
    </body>
              @endif
            @endforeach
</html>