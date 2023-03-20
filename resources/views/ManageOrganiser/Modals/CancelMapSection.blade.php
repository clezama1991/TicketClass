<div role="dialog"  class="modal fade " style="display: none;">
    {!! Form::open(array('url' => route('CancelOrganiserMapsSection'), 'class' => 'ajax gf')) !!}
     <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-cancel"></i>
                    Cancelar Sección {{ $detail->rows }}{{ $detail->cols }}</h3>
            </div>
            <div class="modal-body">
                <p>
                    {{ @trans("organiser.alert_cancel_section") }}
                </p>
                
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::hidden('map_id', $detail->id) !!}
               {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit(trans("organiser.confirm_cancel"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>

