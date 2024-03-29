<script>
    $(function() {
        $('.payment_gateway_options').hide();
        $('#gateway_{{$account->payment_gateway_id}}').show();

        $('.gateway_selector').on('change', function(e) {
            $('.payment_gateway_options').hide();
            $('#gateway_' + $(this).val()).fadeIn();
        });

    });
</script>

{!! Form::model($account, array('url' => route('postEditAccountPayment'), 'class' => 'ajax ')) !!}
<div class="form-group">
    {!! Form::label('payment_gateway_id', trans("ManageAccount.default_payment_gateway"), array('class'=>'control-label ')) !!}
    {!! Form::select('payment_gateway_id', $payment_gateways, $account->payment_gateway_id, ['class' => 'form-control gateway_selector']) !!}
</div>

{{--OpenPay--}}
<section class="payment_gateway_options" id="gateway_{{config('attendize.payment_gateway_openpay')}}">
    <h4>@lang("ManageAccount.openpay_settings")</h4>
    <div class="row">        
        <div class="col-md-12">
            <div class="form-group">
                <label for="openpay[production_mode]">Ambiente</label>
                <select name="openpay[production_mode]" id="openpay[production_mode]" class="form-control">
                    <option value="1" {{ ($account->getGatewayConfigVal(config('attendize.payment_gateway_openpay'), 'production_mode') == '1') ? 'selected' : null}}> Producción</option>                    
                    <option value="0" {{ ($account->getGatewayConfigVal(config('attendize.payment_gateway_openpay'), 'production_mode') == '0') ? 'selected' : null}}> Sandbox</option>                    
                </select>
            </div>
        </div>
        <div class="col-md-12">
        <h4>Datos Ambiente Produccion</h4>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('openpay[apiId1]', trans("ManageAccount.openpay_id"), array('class'=>'control-label ')) !!}
                {!! Form::text('openpay[apiId1]', $account->getGatewayConfigVal(config('attendize.payment_gateway_openpay'), 'apiId1'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('apiUrl1', trans("ManageAccount.openpay_url"), array('class'=>'control-label ')) !!}
                {!! Form::text('openpay[apiUrl1]', $account->getGatewayConfigVal(config('attendize.payment_gateway_openpay'), 'apiUrl1'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('openpay[apiKeyPivate1]', trans("ManageAccount.openpay_secret_key"), array('class'=>'control-label ')) !!}
                {!! Form::text('openpay[apiKeyPivate1]', $account->getGatewayConfigVal(config('attendize.payment_gateway_openpay'), 'apiKeyPivate1'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('apiKeyPublic1', trans("ManageAccount.openpay_publishable_key"), array('class'=>'control-label ')) !!}
                {!! Form::text('openpay[apiKeyPublic1]', $account->getGatewayConfigVal(config('attendize.payment_gateway_openpay'), 'apiKeyPublic1'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
        <div class="col-md-12">
        <h4>Datos Ambiente Sandbox</h4>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('openpay[apiId0]', trans("ManageAccount.openpay_id"), array('class'=>'control-label ')) !!}
                {!! Form::text('openpay[apiId0]', $account->getGatewayConfigVal(config('attendize.payment_gateway_openpay'), 'apiId0'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('apiUrl0', trans("ManageAccount.openpay_url"), array('class'=>'control-label ')) !!}
                {!! Form::text('openpay[apiUrl0]', $account->getGatewayConfigVal(config('attendize.payment_gateway_openpay'), 'apiUrl0'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('openpay[apiKeyPivate0]', trans("ManageAccount.openpay_secret_key"), array('class'=>'control-label ')) !!}
                {!! Form::text('openpay[apiKeyPivate0]', $account->getGatewayConfigVal(config('attendize.payment_gateway_openpay'), 'apiKeyPivate0'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('apiKeyPublic0', trans("ManageAccount.openpay_publishable_key"), array('class'=>'control-label ')) !!}
                {!! Form::text('openpay[apiKeyPublic0]', $account->getGatewayConfigVal(config('attendize.payment_gateway_openpay'), 'apiKeyPublic0'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
    </div>
</section>

{{--Stripe--}}
<section class="payment_gateway_options" id="gateway_{{config('attendize.payment_gateway_stripe')}}">
    <h4>@lang("ManageAccount.stripe_settings")</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('stripe[apiKey]', trans("ManageAccount.stripe_secret_key"), array('class'=>'control-label ')) !!}
                {!! Form::text('stripe[apiKey]', $account->getGatewayConfigVal(config('attendize.payment_gateway_stripe'), 'apiKey'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('publishableKey', trans("ManageAccount.stripe_publishable_key"), array('class'=>'control-label ')) !!}
                {!! Form::text('stripe[publishableKey]', $account->getGatewayConfigVal(config('attendize.payment_gateway_stripe'), 'publishableKey'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
    </div>
</section>

{{--Paypal--}}
<section class="payment_gateway_options"  id="gateway_{{config('attendize.payment_gateway_paypal')}}">
    <h4>@lang("ManageAccount.paypal_settings")</h4>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('paypal[username]', trans("ManageAccount.paypal_username"), array('class'=>'control-label ')) !!}
                {!! Form::text('paypal[username]', $account->getGatewayConfigVal(config('attendize.payment_gateway_paypal'), 'username'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('paypal[password]', trans("ManageAccount.paypal_password"), ['class'=>'control-label ']) !!}
                {!! Form::text('paypal[password]', $account->getGatewayConfigVal(config('attendize.payment_gateway_paypal'), 'password'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('paypal[signature]', trans("ManageAccount.paypal_signature"), array('class'=>'control-label ')) !!}
                {!! Form::text('paypal[signature]', $account->getGatewayConfigVal(config('attendize.payment_gateway_paypal'), 'signature'),[ 'class'=>'form-control'])  !!}
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('paypal[brandName]', trans("ManageAccount.branding_name"), array('class'=>'control-label ')) !!}
                    {!! Form::text('paypal[brandName]', $account->getGatewayConfigVal(config('attendize.payment_gateway_paypal'), 'brandName'),[ 'class'=>'form-control'])  !!}
                    <div class="help-block">
                        @lang("ManageAccount.branding_name_help")
                    </div>
                </div>
            </div>
        </div>


</section>

<div class="row">
    <div class="col-md-12">
        <div class="panel-footer">
            {!! Form::submit(trans("ManageAccount.save_payment_details_submit"), ['class' => 'btn btn-success pull-right']) !!}
        </div>
    </div>
</div>


{!! Form::close() !!}