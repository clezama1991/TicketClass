@extends('Shared.Layouts.Master')

@section('title')
@parent
@lang("Organiser.organiser_events")
@stop

@section('page_title')
@lang("Organiser.organiser_name_mapa", ["name"=>$organiser->name])
@stop

@section('top_nav')
@include('ManageOrganiser.Partials.TopNav')
@stop

@section('head')
<style>
    .page-header {
        display: none;
    }
</style>
<script>
    $(function () {
            $('.colorpicker').minicolors({
                changeDelay: 500,
                change: function () {
                    var replaced = replaceUrlParam('{{route('showOrganiserHome', ['organiser_id'=>$organiser->id])}}', 'preview_styles', encodeURIComponent($('#OrganiserPageDesign form').serialize()));
                    document.getElementById('previewIframe').src = replaced;
                }
            });

        });

        @include('ManageOrganiser.Partials.OrganiserCreateAndEditJS')
</script>
@stop

@section('menu')
@include('ManageOrganiser.Partials.Sidebar')
@stop

@section('page_header')

@stop

@section('content')

<div class="row">
    <div class="col-md-9">
        <div class="btn-toolbar">
            <div class="btn-group btn-group-responsive">
                <a href="#" data-toggle="modal" data-target="#createNewMaps" class="btn btn-success"><i
                        class="ico-plus"></i> @lang("Organiser.create_map")</a>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
</div>
<!--Start Attendees table-->
<div class="row">
    <div class="col-md-12">
        {{-- @if($attendees->count()) --}}
        <div class="panel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th> @lang("Organiser.th_name") </th>
                            <th> @lang("Organiser.th_descr") </th>
                            <th> @lang("Organiser.th_image") </th>
                            <th> @lang("Organiser.th_status") </th>
                            <th> @lang("Organiser.th_action") </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendees as $attendee)
                        <tr>
                            <td>{{$attendee->name}}</td>
                            <td>{{$attendee->description}}</td>
                            <td><img src="{{asset($attendee->url)}}" alt="" style="max-width: 250px"> </td>
                            <td>{{($attendee->active) ? 'Activo' : 'Inactivo'}}</td>
                            <td>
                                <a data-toggle="modal" data-target="#EditOrganiserMaps{{$attendee->id}}"
                                    class="btn btn-xs btn-primary"> @lang("basic.edit")</a>

                                <div id="EditOrganiserMaps{{$attendee->id}}" class="modal fade" role="dialog">
                                    {!! Form::open(array('url' => route('EditOrganiserMaps'), 'class' => 'ajax gf')) !!}
                                    {!! Form::hidden('id',$attendee->id) !!}

                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"> @lang("Organiser.edit_map") </h4>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {!! Form::label('name', trans("Organiser.th_name"),
                                                            array('class'=>'control-label required')) !!}
                                                            {!! Form::text('name', $attendee->name,
                                                            array(
                                                            'class'=>'form-control',
                                                            'placeholder'=>trans("ManageEvent.ticket_title_placeholder")
                                                            )) !!}
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('description', trans("Organiser.th_descr") ,
                                                            array('class'=>'control-label
                                                            required')) !!}

                                                            {!! Form::textarea('description', $attendee->description,
                                                            array(
                                                            'class'=>'form-control',
                                                            'rows' => '5'
                                                            )) !!}
                                                        </div>
                                                        <div class="form-group">
                                                            {!! Form::label('url', trans("Organiser.th_image_now"),
                                                            array('class'=>'control-label required')) !!}
                                                            <div style="display: block">

                                                                <img src="{{asset($attendee->url)}}" alt=""
                                                                    style="max-width: 250px">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            {!! Form::label('url', trans("Organiser.th_image"),
                                                            array('class'=>'control-label required')) !!}
                                                            {!! Form::file('map_image') !!}
                                                        </div>

                                                        <div class="custom-checkbox mb5">
                                                            <h5>@lang("Organiser.th_status")</h5>
                                                            {!! Form::checkbox('active', 1, $attendee->active, ['id' =>
                                                            'active'.$attendee->id, 'data-toggle' => 'toggle']) !!}
                                                            {!! Form::label('active'.$attendee->id,'Activo') !!}

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close
                                                btn-danger",'data-dismiss'=>'modal']) !!}
                                                {!! Form::submit(trans("Organiser.edit_map"), ['class'=>"btn
                                                btn-success"]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    {!! Form::close() !!}
                                </div>

                                <a data-modal-id="CancelAttendee" href="{{url('/organiser/'.$organiser->id.'/maps/'.$attendee->id.'/sections')}}" class="btn btn-xs btn-info"> @lang("Organiser.gestion")</a>

                                <a data-modal-id="CancelAttendee" href="javascript:void(0);"
                                    data-href="{{route('showCancelMaps', ['map_id'=>$attendee->id])}}"
                                    class="loadModal btn btn-xs btn-danger"> @lang("basic.cancel")</a>

                            </td>
                        </tr>
                        {{-- <td>
                            <a data-modal-id="MessageAttendee" href="javascript:void(0);" class="loadModal"
                                data-href="{{route('showMessageAttendee', ['attendee_id'=>$attendee->id])}}">
                                {{$attendee->email}}</a>
                        </td>
                        <td>
                            {{{$attendee->ticket->title}}}
                        </td>
                        <td>
                            <a href="javascript:void(0);" data-modal-id="view-order-{{ $attendee->order->id }}"
                                data-href="{{route('showManageOrder', ['order_id'=>$attendee->order->id])}}"
                                title="View Order #{{$attendee->order->order_reference}}" class="loadModal">
                                {{$attendee->order->order_reference}}
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">@lang("basic.action") <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    @if($attendee->email)
                                    <li><a data-modal-id="MessageAttendee" href="javascript:void(0);"
                                            data-href="{{route('showMessageAttendee', ['attendee_id'=>$attendee->id])}}"
                                            class="loadModal"> @lang("basic.message")</a></li>
                                    @endif
                                    <li><a data-modal-id="ResendTicketToAttendee" href="javascript:void(0);"
                                            data-href="{{route('showResendTicketToAttendee', ['attendee_id'=>$attendee->id])}}"
                                            class="loadModal"> @lang("ManageEvent.resend_ticket")</a></li>
                                    <li><a
                                            href="{{route('showExportTicket', ['event_id'=>$event->id, 'attendee_id'=>$attendee->id])}}">@lang("ManageEvent.download_pdf_ticket")</a>
                                    </li>
                                </ul>
                            </div>

                            <a data-modal-id="EditAttendee" href="javascript:void(0);"
                                data-href="{{route('showEditAttendee', ['event_id'=>$event->id, 'attendee_id'=>$attendee->id])}}"
                                class="loadModal btn btn-xs btn-primary"> @lang("basic.edit")</a>

                            <a data-modal-id="CancelAttendee" href="javascript:void(0);"
                                data-href="{{route('showCancelAttendee', ['event_id'=>$event->id, 'attendee_id'=>$attendee->id])}}"
                                class="loadModal btn btn-xs btn-danger"> @lang("basic.cancel")</a>
                        </td>
                        </tr> --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="col-md-12">
    </div>
</div>

<div id="createNewMaps" class="modal fade" role="dialog">
    {!! Form::open(array('url' => route('CreateOrganiserMaps'), 'class' => 'ajax gf')) !!}
    {!! Form::hidden('organiser_id',$organiser->id) !!}

    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">@lang("Organiser.create_map")</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('name', trans("Organiser.th_name"), array('class'=>'control-label
                            required')) !!}
                            {!! Form::text('name', Input::old('name'),
                            array(
                            'class'=>'form-control',
                            'placeholder'=>""
                            )) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('description', trans("Organiser.th_descr"), array('class'=>'control-label
                            required')) !!}

                            {!! Form::textarea('description', Input::old('description'),
                            array(
                            'class'=>'form-control',
                            'rows' => '5'
                            )) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('url', trans("Organiser.th_image"), array('class'=>'control-label
                            required')) !!}
                            {!! Form::file('map_image') !!}
                        </div>

                        <div class="custom-checkbox mb5">
                            <h5>@lang("Organiser.th_status")</h5>
                            {!! Form::checkbox('active', 1, 1, ['id' => 'active', 'data-toggle' => 'toggle']) !!}
                            {!! Form::label('active','Activo') !!}

                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal'])
                !!}
                {!! Form::submit(trans("Organiser.create_map"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div>
    </div>

    {!! Form::close() !!}
</div>

@stop