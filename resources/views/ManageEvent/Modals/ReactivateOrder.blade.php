<div role="dialog" class="modal fade " style="display: none;">
    {!! Form::open(array('url' => route('postReactivateOrder', array('order_id' => $order->id)), 'class' => 'closeModalAfter
    ajax')) !!}
    <script>
        $(function () {
            $('input[name=refund_order]').on('change', function () {
                if ($(this).prop('checked')) {
                    $('.refund_options').slideDown();
                } else {
                    $('.refund_options').slideUp();
                }
            });


        });
    </script>
    <style>
        .refund_options {
            display: none;
        }

        .p0 {
            padding: 0;
        }
    </style>

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-cart2"></i>
                    {{ @trans("ManageEvent.reactivate_order_:ref", ["ref"=>$order->order_reference]) }}</h3>
            </div>
            <div class="modal-body">

                
                <div class="help-block h4">
                    @lang("ManageEvent.show_message_confirm_order_reactivate")
                </div>

            </div>

            @if($attendees->count() || !$order->is_refunded)
            <div class="modal-footer">
                {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal'])
                !!}
                {!! Form::submit(trans("ManageEvent.confirm_order_reactivate"), ['class'=>"btn btn-primary"]) !!}
            </div>
            @endif
        </div>
        {!! Form::close() !!}
    </div>
</div>
