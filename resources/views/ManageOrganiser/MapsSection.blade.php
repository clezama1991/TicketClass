@extends('Shared.Layouts.Master')

@section('title')
@parent
@lang("Organiser.organiser_events")
@stop

@section('page_title')
@lang("Organiser.organiser_name_mapa_seccion", ["name"=>$map->name])
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
                        class="ico-plus"></i> @lang("Organiser.create_map_seccion")</a>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="display: flex;justify-content: flex-end;">
        <div class="btn-toolbar">
            <div class="btn-group btn-group-responsive">
                <a href="{{url('/organiser/'.$organiser->id.'/maps')}}" class="btn btn-success"><i
                        class="ico-back"></i> @lang("basic.back_to_page", ["page"=>'mapas'])</a>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
</div>
<!--Start Attendees table-->
<div class="row">
    <div class="col-md-6">
        {{-- @if($attendees->count()) --}}
        <div class="panel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Fila </th>
                            <th> Columna </th>
                            <th> @lang("Organiser.th_action") </th>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach($details as $key => $row)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$row->rows}}</td>
                            <td>{{$row->cols}}</td>
                            <td>

                                <a data-toggle="modal" data-target="#EditOrganiserMaps{{$row->id}}"
                                    class="btn btn-xs btn-primary"> @lang("basic.edit")</a>

                                <div id="EditOrganiserMaps{{$row->id}}" class="modal fade" role="dialog">
                                    {!! Form::open(array('url' => route('EditOrganiserMapsSection'), 'class' => 'ajax gf')) !!}
                                    {!! Form::hidden('id',$row->id) !!}

                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"> @lang("Organiser.edit_map_seccion") </h4>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {!! Form::label('rows', trans("Organiser.rows"), array('class'=>'control-label
                                                            required')) !!}
                                                            {!! Form::text('rows', $row->rows,
                                                            array(
                                                            'class'=>'form-control',
                                                            'placeholder'=>""
                                                            )) !!}
                                                        </div>
                                                        <div class="form-group">
                                                            {!! Form::label('cols', trans("Organiser.cols"), array('class'=>'control-label
                                                            required')) !!}
                                                            {!! Form::text('cols', $row->cols,
                                                            array(
                                                            'class'=>'form-control',
                                                            'placeholder'=>""
                                                            )) !!}
                                                        </div>
                                 
                                                        <div class="form-group">
                                                            {!! Form::label('coords', trans("Organiser.coords"), array('class'=>'control-label
                                                            required')) !!}
                                                            {!! Form::text('coords', $row->coords,
                                                            array(
                                                            'class'=>'form-control',
                                                            'placeholder'=>""
                                                            )) !!}
                                                        </div>

                                                        <div class="form-group">
                                                          <label for="shape">Tipo de Área</label>
                                                          <select class="form-control" name="shape" id="shape">
                                                            <option value="poly" {{$row->shape=='poly' ? 'selected' : ''}} >Poligono</option>
                                                            <option value="rect" {{$row->shape=='rect' ? 'selected' : ''}}>Cuadrado</option>
                                                            <option value="circle" {{$row->shape=='circle' ? 'selected' : ''}}>Circulo</option>
                                                          </select>
                                                        </div>
                                                        
                                                        <div class="custom-checkbox mb5">
                                                            <h5>@lang("Organiser.th_status")</h5>
                                                            {!! Form::checkbox('active', 1, $row->active, ['id' =>
                                                            'active'.$row->id, 'data-toggle' => 'toggle']) !!}
                                                            {!! Form::label('active'.$row->id,'Activo') !!}

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close
                                                btn-danger",'data-dismiss'=>'modal']) !!}
                                                {!! Form::submit(trans("Organiser.edit_map_seccion"), ['class'=>"btn
                                                btn-success"]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    {!! Form::close() !!}
                                </div>

                                <a data-modal-id="CancelAttendee" href="javascript:void(0);"
                                    data-href="{{route('showCancelMapsSection', ['map_id'=>$row->id])}}"
                                    class="loadModal btn btn-xs btn-danger"> @lang("basic.cancel")</a>

                            </td>
                        </tr>
                    
                        @endforeach 
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="col-md-6"> 
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="ico-map mr5 ellipsis"></i>
                    Mapa
                </h3>
            </div>

            <div class="panel-body">
                @if($map)
                <div class="row">
                    <div class="col-md-12">
                        <h4>{{$map->name}}</h4>
                     
                        {{-- {{$map->sections->pluck('coords','maps_id')->toArray()}} --}}
                        <img src="{{asset($map->url)}}" alt="" class="img-responsive"> 
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<div id="createNewMaps" class="modal fade" role="dialog">
    {!! Form::open(array('url' => route('CreateOrganiserMapsSection'), 'class' => 'ajax gf')) !!}
    {!! Form::hidden('maps_id',$map->id) !!}

    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">@lang("Organiser.create_map_seccion")</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('rows', trans("Organiser.rows"), array('class'=>'control-label
                            required')) !!}
                            {!! Form::text('rows', Input::old('rows'),
                            array(
                            'class'=>'form-control',
                            'placeholder'=>""
                            )) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('cols', trans("Organiser.cols"), array('class'=>'control-label
                            required')) !!}
                            {!! Form::text('cols', Input::old('cols'),
                            array(
                            'class'=>'form-control',
                            'placeholder'=>""
                            )) !!}
                        </div>
 
                        <div class="form-group">
                            {!! Form::label('coords', trans("Organiser.coords"), array('class'=>'control-label
                            required')) !!}
                            {!! Form::text('coords', Input::old('coords'),
                            array(
                            'class'=>'form-control',
                            'placeholder'=>""
                            )) !!}
                        </div>
 
                        <div class="form-group">
                            <label for="shape">Tipo de Área</label>
                            <select class="form-control" name="shape" id="shape">
                              <option value="poly" selected>Poligono</option>
                              <option value="rect">Cuadrado</option>
                              <option value="circle">Circulo</option>
                            </select>
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
                {!! Form::submit(trans("Organiser.create_map_seccion"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div>
    </div>

    {!! Form::close() !!}
</div>

@stop