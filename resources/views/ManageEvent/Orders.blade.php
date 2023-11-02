@extends('Shared.Layouts.Master')

@section('title')
@parent

@lang("Event.event_orders")
@stop

@section('top_nav')
@include('ManageEvent.Partials.TopNav')
@stop

@section('menu')
@include('ManageEvent.Partials.Sidebar')
@stop

@section('page_title')
<i class='ico-cart mr5'></i>
@lang("Event.event_orders")
<span class="page_title_sub_title hide">
    {{-- {{ @trans("Event.showing_num_of_orders", [30, \App\Models\Order::scope()->count()]) }} --}}
</span>
@stop

@section('head')

@stop

@section('page_header')



<div class="col-md-12 col-sm-6">
<div class="row">            
    {!! Form::open(array('url' => route('showEventOrders', ['event_id'=>$event->id,'sort_by'=>$sort_by]), 'method' => 'get')) !!}
    <div class="col-md-2">
        <div class="form-group">
            <label for="date_start">F. Inicio</label>
            <input id='date_start' name='date_start' value="{{$date_start}}" placeholder="@lang('Order.search_placeholder')" type="date" class="form-control">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="date_end">F. Fin</label>
            <input id='date_end' name='date_end' value="{{$date_end}}" placeholder="@lang('Order.search_placeholder')" type="date" class="form-control">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="user_id">Usuarios</label>
            <select name="user_id" id="user_id" class="form-control">
                <option value="" {{$user_id=='' ? 'selected' : ''}}>Todos</option>
                <option value="linea" {{$user_id=='linea' ? 'selected' : ''}}>En Línea</option>
                @foreach ($users as $item)
                    <option value="{{$item->id}}" {{$user_id==$item->id ? 'selected' : ''}}> {{$item->FullName}}</option>                    
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="status">Estado</label>
            <select name="status" id="status" class="form-control">
                <option value="" {{$status=='' ? 'selected' : ''}}>Todos</option>
                @foreach ($type_status as $item)
                    <option value="{{$item->id}}" {{$status==$item->id ? 'selected' : ''}}> {{$item->name}}</option>                    
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <label for="">Buscar</label>
            <input name='q' value="{{$q}}" placeholder="Buscar" type="text" class="form-control">
            <span class="input-group-btn">
            </span>
        </div>
    </div>

    <div class="col-md-2">
         <div class="form-group"> 
            <label class="text-white">0</label>
             <span class="input-group-btn">
                 <button class="btn btn-default btn-block" type="submit"><i class="ico-search" style="margin-right: 5px"></i>Filtrar</button>
             </span>
         </div>
     </div>

    {!! Form::close() !!}
</div>
</div>

<div class="col-md-12 col-sm-6">
    <div class="row">
        <div class="col-sm-2">
            <div class="stat-box">
                <h3>{{ money($importes, $event->currency) }}</h3>
                <span>Importes</span>
            </div> 
        </div>
        <div class="col-sm-2">
            <div class="stat-box">
                <h3>{{ money($importes_card, $event->currency) }}</h3>
                <span>Tarjeta</span>
            </div> 
        </div>
        <div class="col-sm-2">
            <div class="stat-box">
                <h3>{{ money($importes_cash, $event->currency) }}</h3>
                <span>Efectivo</span>
            </div> 
        </div>
        <div class="col-sm-2">
            <div class="stat-box">
                <h3>{{$pedidos}}</h3>
                <span>Pedidos</span>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="stat-box">
                <h3>{{$entradas}}</h3>
                <span>Entradas</span>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="stat-box">
                <h3>{{$completados}} / {{$otros}}</h3>
                <span>Compl. / Otros</span>
            </div>
        </div>
    </div>
    </div>
    
<div class="col-md-12 col-sm-6">
    <!-- Toolbar -->
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group btn-group btn-group-responsive">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <i class="ico-users"></i> @lang("basic.export") <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{route('showExportOrders', ['event_id'=>$event->id,'export_as'=>'xlsx'])}}">@lang("File_format.Excel_xlsx")</a></li>
                <li><a href="{{route('showExportOrders', ['event_id'=>$event->id,'export_as'=>'xls'])}}">@lang("File_format.Excel_xls")</a></li>
                <li><a href="{{route('showExportOrders', ['event_id'=>$event->id,'export_as'=>'csv'])}}">@lang("File_format.csv")</a></li>
                <li><a href="{{route('showExportOrders', ['event_id'=>$event->id,'export_as'=>'html'])}}">@lang("File_format.html")</a></li>
            </ul>
        </div>
        
    </div>
    <!--/ Toolbar -->
</div>
@stop


@section('content')
<!--Start Attendees table-->
<div class="row">

    @if($orders->count())

        <div class="col-md-12">

            <!-- START panel -->
            <div class="panel">
                <div class="panel-body">
                    <div class="table-responsive ">
                        
                    <table class="table table-bordered table-condensed DataTable">
                        <thead> 
                            <tr>
                                <th>{{ trans("Order.order_ref") }}</th>
                                <th>{{ trans("Order.order_date") }}</th>
                                <th>{{ trans("Attendee.name") }}</th>
                                <th>{{ trans("Attendee.email") }}</th>
                                <th>{{ trans("Order.amount") }}</th>
                                <th>{{ trans("Order.status") }}</th> 
                                <th>Opciones</th>
                            </tr>
                        </thead> 
                        <tbody> 
                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    <a href='javascript:void(0);' data-modal-id='view-order-{{ $order->id }}' data-href="{{route('showManageOrder', ['order_id'=>$order->id])}}" title="@lang("Order.view_order_num", ["num"=>$order->order_reference])" class="loadModal">
                                        {{$order->order_reference}}
                                    </a>
                                </td>
                                <td>
                                    {{ $order->created_at->format(config('attendize.default_datetime_format')) }}
                                </td>
                                <td>
                                    {{$order->first_name.' '.$order->last_name}}
                                </td>
                                <td>
                                    <a href="javascript:void(0);" class="loadModal"
                                        data-modal-id="MessageOrder"
                                        data-href="{{route('showMessageOrder', ['order_id'=>$order->id])}}"
                                    > {{$order->email}}</a>
                                </td>
                                <td>
                                    <a href="#" class="hint--top" data-hint="{{money($order->amount, $event->currency)}} +{{money($order->services_fee, $event->currency)}} + {{money($order->organiser_booking_fee, $event->currency)}} @lang("Order.organiser_booking_fees")">
                                        {{money($order->services_fee + $order->amount + $order->organiser_booking_fee + $order->taxamt, $event->currency)}}
                                        @if($order->is_refunded || $order->is_partially_refunded)

                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <span class="label label-{{(!$order->is_payment_received || $order->is_refunded || $order->is_partially_refunded) ? 'warning' : 'success'}}">
                                        {{$order->orderStatus->name}}
                                    </span>
                                </td>
                                <td class="text-center">

                                    @if ($order->is_cancelled)
                                                                        
                                    @else
                                    
                                        <a href="javascript:void(0);" data-modal-id="cancel-order-{{ $order->id }}" data-href="{{route('showCancelOrder', ['order_id'=>$order->id])}}" title="@lang("Order.cancel_order")" class="btn btn-xs btn-danger loadModal">
                                            @lang("Order.refund/cancel")
                                        </a>
                                                
                                    @endif
                                    <a data-modal-id="view-order-{{ $order->id }}" data-href="{{route('showManageOrder', ['order_id'=>$order->id])}}" title="@lang("Order.view_order")" class="btn btn-xs btn-primary loadModal">@lang("Order.details")</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>    
                    </table>
{{-- 
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                {!! Html::sortable_link(trans("Order.order_ref"), $sort_by, 'order_reference', $sort_order, ['q' => $q, 'date_start' => $date_start, 'date_end' => $date_end, 'status' => $status , 'page' => $orders->currentPage()]) !!}
                                </th>
                                <th>
                                {!! Html::sortable_link(trans("Order.order_date"), $sort_by, 'created_at', $sort_order, ['q' => $q, 'date_start' => $date_start, 'date_end' => $date_end, 'status' => $status , 'page' => $orders->currentPage()]) !!}
                                </th>
                                <th>
                                {!! Html::sortable_link(trans("Attendee.name"), $sort_by, 'first_name', $sort_order, ['q' => $q, 'date_start' => $date_start, 'date_end' => $date_end, 'status' => $status , 'page' => $orders->currentPage()]) !!}
                                </th>
                                <th>
                                {!! Html::sortable_link(trans("Attendee.email"), $sort_by, 'email', $sort_order, ['q' => $q, 'date_start' => $date_start, 'date_end' => $date_end, 'status' => $status , 'page' => $orders->currentPage()]) !!}
                                </th>
                                <th>
                                {!! Html::sortable_link(trans("Order.amount"), $sort_by, 'amount', $sort_order, ['q' => $q, 'date_start' => $date_start, 'date_end' => $date_end, 'status' => $status , 'page' => $orders->currentPage()]) !!}
                                </th>
                                <th>
                                {!! Html::sortable_link(trans("Order.status"), $sort_by, 'order_status_id', $sort_order, ['q' => $q, 'date_start' => $date_start, 'date_end' => $date_end, 'status' => $status , 'page' => $orders->currentPage()]) !!}
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    <a href='javascript:void(0);' data-modal-id='view-order-{{ $order->id }}' data-href="{{route('showManageOrder', ['order_id'=>$order->id])}}" title="@lang("Order.view_order_num", ["num"=>$order->order_reference])" class="loadModal">
                                        {{$order->order_reference}}
                                    </a>
                                </td>
                                <td>
                                    {{ $order->created_at->format(config('attendize.default_datetime_format')) }}
                                </td>
                                <td>
                                    {{$order->first_name.' '.$order->last_name}}
                                </td>
                                <td>
                                    <a href="javascript:void(0);" class="loadModal"
                                        data-modal-id="MessageOrder"
                                        data-href="{{route('showMessageOrder', ['order_id'=>$order->id])}}"
                                    > {{$order->email}}</a>
                                </td>
                                <td>
                                    <a href="#" class="hint--top" data-hint="{{money($order->amount, $event->currency)}} +{{money($order->services_fee, $event->currency)}} + {{money($order->organiser_booking_fee, $event->currency)}} @lang("Order.organiser_booking_fees")">
                                        {{money($order->services_fee + $order->amount + $order->organiser_booking_fee + $order->taxamt, $event->currency)}}
                                        @if($order->is_refunded || $order->is_partially_refunded)

                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <span class="label label-{{(!$order->is_payment_received || $order->is_refunded || $order->is_partially_refunded) ? 'warning' : 'success'}}">
                                        {{$order->orderStatus->name}}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0);" data-modal-id="cancel-order-{{ $order->id }}" data-href="{{route('showCancelOrder', ['order_id'=>$order->id])}}" title="@lang("Order.cancel_order")" class="btn btn-xs btn-danger loadModal">
                                                    @lang("Order.refund/cancel")
                                                </a>
                                    <a data-modal-id="view-order-{{ $order->id }}" data-href="{{route('showManageOrder', ['order_id'=>$order->id])}}" title="@lang("Order.view_order")" class="btn btn-xs btn-primary loadModal">@lang("Order.details")</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table> --}}
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            {{-- {!!$orders->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order, 'q' => $q, 'date_start' => $date_start, 'date_end' => $date_end, 'status' => $status])->render()!!} --}}
        </div>

    @else

        @if($q)
        @include('Shared.Partials.NoSearchResults')
        @else
        @include('ManageEvent.Partials.OrdersBlankSlate')
        @endif

    @endif
</div>    <!--/End attendees table-->
@stop
