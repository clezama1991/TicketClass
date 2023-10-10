@extends('Shared.Layouts.Master')

@section('title')
    @parent
    @lang('Ticket.event_tickets')
@stop

@section('top_nav')
    @include('ManageEvent.Partials.TopNav')
@stop

@section('page_title')
    <i class="ico-ticket mr5"></i>
    @lang('Ticket.event_tickets')
@stop

@section('head')
    <script>
        $(function() {
            $('.sortable').sortable({
                handle: '.sortHandle',
                forcePlaceholderSize: true,
                placeholderClass: 'col-md-4 col-sm-6 col-xs-12',
            }).bind('sortupdate', function(e, ui) {

                var data = $('.sortable .ticket').map(function() {
                    return $(this).data('ticket-id');
                }).get();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('postUpdateTicketsOrder', ['event_id' => $event->id]) }}',
                    dataType: 'json',
                    data: {
                        ticket_ids: data
                    },
                    success: function(data) {
                        showMessage(data.message);
                    },
                    error: function(data) {
                        showMessage(lang("whoops2"));
                    }
                });
            });
        });
    </script>
@stop

@section('menu')
    @include('ManageEvent.Partials.Sidebar')
@stop

@section('page_header')
    <div class="col-md-9">
        <!-- Toolbar -->
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group btn-group-responsive">
                <button data-modal-id='CreateTicket'
                    data-href="{{ route('showCreateTicket', ['event_id' => $event->id]) }}"
                    class='loadModal btn btn-success' type="button"><i class="ico-ticket"></i> @lang('Ticket.create_ticket')
                </button>
            </div>
            @if (false)
                <div class="btn-group btn-group-responsive ">
                    <button data-modal-id='TicketQuestions'
                        data-href="{{ route('showTicketQuestions', ['event_id' => $event->id]) }}" type="button"
                        class="loadModal btn btn-success">
                        <i class="ico-question"></i> @lang('Ticket.questions')
                    </button>
                </div>
                <div class="btn-group btn-group-responsive">
                    <button type="button" class="btn btn-success">
                        <i class="ico-tags"></i> @lang('Ticket.coupon_codes')
                    </button>
                </div>
            @endif
        </div>
        <!--/ Toolbar -->
    </div>
    <div class="col-md-3">        
        @if ($view=='full')
            {!! Form::open([
                'url' => route('showEventTickets', ['event_id' => $event->id, 'sort_by' => $sort_by]),
                'method' => 'get',
            ]) !!}
            <div class="input-group">
                <input name='q' value="{{ $q or '' }}" placeholder="@lang('Ticket.search_tickets')" type="text"
                    class="form-control">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
                </span>
                {!! Form::hidden('sort_by', $sort_by) !!}
            </div>
            {!! Form::close() !!}
        @endif
    </div>
@stop

@section('content')
    @if ($tickets->count())
        <div class="row">
            <div class="col-md-3 col-xs-6">
                <div class='order_options'>
                    <span class="event_count">@lang('Ticket.n_tickets', ['num' => $tickets->count()])</span>
                </div>
            </div>
            <div class="col-md-2 col-xs-2 col-md-offset-{{$view=='full'?'5':'7'}}">
                <div class='order_options'>
                    @if ($view=='full')
                        <a href="{{route('showEventTickets', ['event_id' => $event->id, 'view' => 'compact'])}}" class="btn btn-success btn-block" type="button"><i class="ico-table"></i> Vista Compacta </a>
                    @else
                        <a href="{{route('showEventTickets', ['event_id' => $event->id, 'view' => 'full'])}}" class="btn btn-success btn-block" type="button"><i class="ico-grid"></i> Vista Completa </a>
                    @endif
             </div>
             </div>     
             @if ($view=='full')
            <div class="col-md-2 col-xs-4">
                <div class='order_options'>
                    {!! Form::select('sort_by_select', $allowed_sorts, $sort_by, ['class' => 'form-control pull right']) !!}
                </div>
            </div>
            @endif
        </div>
    @endif
    <!--Start ticket table-->
    <div class="row sortable">

        
        @if ($view=='compact')
        
        <div class="col-md-12">
        <table class="table table-bordered table-condensed DataTable">
            <thead>
                <tr id="filtros">
                    <th>Grupo</th>
                    <th>Entrada</th>
                    <th>Estatus</th>
                    <th>Mapa</th>
                    <th>Total Tickets</th>
                    <th>Precio</th>
                    <th>Vendido</th>
                    <th>Ingresos</th>
                    <th>Restante</th>
                 </tr>
                <tr>
                    <th>Grupo</th>
                    <th>Entrada</th>
                    <th>Estatus</th>
                    <th>Mapa</th>
                    <th>Total Tickets</th>
                    <th>$ Precio</th>
                    <th>Vendido</th>
                    <th>$ Ingresos</th>
                    <th>Restante</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Grupo</th>
                    <th>Entrada</th>
                    <th>Estatus</th>
                    <th>Mapa</th>
                    <th>Total Tickets</th>
                    <th>$ Precio</th>
                    <th>Vendido</th>
                    <th>$ Ingresos</th>
                    <th>Restante</th>
                    <th>Opciones</th>
                </tr>
            </tfoot>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr> 
                        <td>{{ $ticket->group_zone }}</td> 
                        <td>{{ $ticket->title }}</td>
                        <td>
                            @if ($ticket->sale_status === config('attendize.ticket_status_on_sale'))
                                @if ($ticket->is_paused)
                                    @lang('Ticket.ticket_sales_paused') &nbsp;
                                    <span class="pauseTicketSales label label-info"
                                        data-id="{{ $ticket->id }}"
                                        data-route="{{ route('postPauseTicket', ['event_id' => $event->id]) }}">
                                        <i class="ico-play4"></i> @lang('Ticket.resume')
                                    </span>
                                @else
                                    @lang('Ticket.on_sale') &nbsp;
                                    <span class="pauseTicketSales label label-info"
                                        data-id="{{ $ticket->id }}"
                                        data-route="{{ route('postPauseTicket', ['event_id' => $event->id]) }}">
                                        <i class="ico-pause"></i> @lang('Ticket.pause')
                                    </span>
                                @endif
                            @else
                                {{ \App\Models\TicketStatus::find($ticket->sale_status)->name ?? null }}
                            @endif
                        </td>
                        <td>{{ $ticket->seat_zone ?? 'S/I' }}</td>
                        <td class="text-right">{{ $ticket->quantity_available === null ? '∞' : $ticket->quantity_available }}</td>
                        <td class="text-right">{{ $ticket->is_free ? trans('Order.free') : $ticket->price }}</td> 
                        <td class="text-right">{{ $ticket->quantity_sold }}</td>
                        <td class="text-right">{{ money($ticket->sales_volume + $ticket->organiser_fees_volume, $event->currency) }}</td>
                        <td class="text-right">{{ $ticket->quantity_available === null ? '∞' : $ticket->quantity_remaining }}</td>
                        <td>
                            
                            <a href="javascript:void(0);"  data-modal-id='ticket-{{ $ticket->id }}' data-href="{{ route('showEditTicket', ['event_id' => $event->id, 'ticket_id' => $ticket->id]) }}" title="Editar Entrada" class="btn btn-info loadModal">
                                <i
                                                        class="ico-edit"></i>
                            </a>
                            
                            <button data-modal-id="newSaleTicket"  title="Vender Entradas"
                                                    data-href="{{ route('newSalesTickets', ['ticket_id' => $ticket->id, 'price' => $ticket->price]) }}"
                                                    type="button" class="btn btn-success loadModal"><i
                                                        class="ico-shopping-cart"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>    
        </table>
        </div>

        @elseif ($view=='full')
            @if ($tickets->count())
                <div class="col-md-12">  
                    <div class="panel-group" id="bgOptions">
                        @php
                            $i =0;
                        @endphp
                        @foreach ($tickets_all as $group => $tickets) 
                            <div class="panel panel-default"  style="margin-bottom: 10px;">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#bgOptions" href="#{{str_replace(' ', '', $group)}}"
                                        class="{{($event->loop == 0) ? 'collapsed' : ''}}">
                                            <span class="arrow mr5"></span> {{ $group }} <span class="event_count">
                                            ( @lang('Ticket.n_tickets', ['num' => count($tickets_all[$group])]) )
                                            </span>
                                        </a>
                                    </h4>
                                </div> 
                                
                                <div id="{{str_replace(' ', '', $group)}}" class="panel-body panel-collapse {{($i == 0) ? 'in' : 'collapse'}}"">
                                    @foreach ($tickets as $ticket)
                                        <div id="ticket_{{ $ticket->id }}" class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom: 25px;">
                                            <div class="panel panel-success ticket" data-ticket-id="{{ $ticket->id }}">
                                                <div style="cursor: pointer;" data-modal-id='ticket-{{ $ticket->id }}'
                                                    data-href="{{ route('showEditTicket', ['event_id' => $event->id, 'ticket_id' => $ticket->id]) }}"
                                                    class="panel-heading loadModal">
                                                    <h3 class="panel-title">
                                                        @if ($ticket->is_hidden)
                                                            <i title="@lang('Ticket.this_ticket_is_hidden')"
                                                                class="ico-eye-blocked ticket_icon mr5 ellipsis"></i>
                                                        @else
                                                            <i class="ico-ticket ticket_icon mr5 ellipsis"></i>
                                                        @endif
                                                        {{ $ticket->title }}
                                                        <span class="pull-right">
                                                            {{ $ticket->is_free ? trans('Order.free') : money($ticket->price, $event->currency) }}
                                                        </span>
                                                    </h3>
                                                </div>
                                                <div class='panel-body'>
                                                    <ul class="nav nav-section nav-justified mt5 mb5">
                                                        <li>
                                                            <div class="section">
                                                                <h4 class="nm">{{ $ticket->quantity_sold }}</h4>

                                                                <p class="nm text-muted">@lang('Ticket.sold')</p>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="section">
                                                                <h4 class="nm">
                                                                    {{ $ticket->quantity_available === null ? '∞' : $ticket->quantity_remaining }}
                                                                </h4>

                                                                <p class="nm text-muted">@lang('Ticket.remaining')</p>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="section">
                                                                <h4 class="nm hint--top"
                                                                    title="{{ money($ticket->sales_volume, $event->currency) }} + {{ money($ticket->organiser_fees_volume, $event->currency) }} @lang('Order.organiser_booking_fees')">
                                                                    {{ money($ticket->sales_volume + $ticket->organiser_fees_volume, $event->currency) }}
                                                                    <sub title="@lang('Ticket.doesnt_account_for_refunds').">*</sub>
                                                                </h4>
                                                                <p class="nm text-muted">@lang('Ticket.revenue')</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="panel-footer" style="height: 56px;
                                                margin-left: 0px;
                                                margin-right: 0px;
                                                padding-bottom: 0px;
                                                margin-bottom: 0px;">
                                                    <div class="row">
                                                        <div class="sortHandle col-md-2" title="@lang('basic.drag_to_reorder')">
                                                            <i class="ico-paragraph-justify"></i>
                                                        </div>
                                                        <div class="col-md-7">
                                                            <ul class="nav nav-section nav-justified">
                                                                <li>
                                                                    <a href="javascript:void(0);">
                                                                        @if ($ticket->sale_status === config('attendize.ticket_status_on_sale'))
                                                                            @if ($ticket->is_paused)
                                                                                @lang('Ticket.ticket_sales_paused') &nbsp;
                                                                                <span class="pauseTicketSales label label-info"
                                                                                    data-id="{{ $ticket->id }}"
                                                                                    data-route="{{ route('postPauseTicket', ['event_id' => $event->id]) }}">
                                                                                    <i class="ico-play4"></i> @lang('Ticket.resume')
                                                                                </span>
                                                                            @else
                                                                                @lang('Ticket.on_sale') &nbsp;
                                                                                <span class="pauseTicketSales label label-info"
                                                                                    data-id="{{ $ticket->id }}"
                                                                                    data-route="{{ route('postPauseTicket', ['event_id' => $event->id]) }}">
                                                                                    <i class="ico-pause"></i> @lang('Ticket.pause')
                                                                                </span>
                                                                            @endif
                                                                        @else
                                                                            {{ \App\Models\TicketStatus::find($ticket->sale_status)->name ?? null }}
                                                                        @endif
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button data-modal-id="newSaleTicket"
                                                                data-href="{{ route('newSalesTickets', ['ticket_id' => $ticket->id, 'price' => $ticket->price]) }}"
                                                                type="button" class="btn btn-block btn-success loadModal"><i
                                                                    class="ico-shopping-cart"></i></button>
                                                        </div>
                                                        <div class="col-md-2" style="display: contents;">
                                                            <span class="btn btn-info">{{ $ticket->seat_zone ?? 'S/I' }}</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @php
                                    $i++;
                                @endphp
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                @if ($q)
                    @include('Shared.Partials.NoSearchResults')
                @else
                    @include('ManageEvent.Partials.TicketsBlankSlate')
                @endif
            @endif
        @endif
    </div><!--/ end ticket table-->
    <div class="row">
        <div class="col-md-12">
            <br>
            <br>
            <br>
            <br>
            {{-- {!! $tickets->appends(['q' => $q, 'sort_by' => $sort_by])->render() !!} --}}
        </div>
    </div>
@stop
