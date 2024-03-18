<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Openpay;
use Exception;
use OpenpayApiError;
use OpenpayApiAuthError;
use OpenpayApiRequestError;
use OpenpayApiConnectionError;
use OpenpayApiTransactionError;
use Illuminate\Http\JsonResponse;
use App\Models\Event;

  class OpenPayController extends Controller
{
    /**
     * Create charge in OpenPay
     * https://www.openpay.mx/docs/api/?php#con-id-de-tarjeta-o-token
     * 
     */
    public function form()
    {
        
        return view('Public.Openpay.card');

    }

    public function store(Request $request)
    {
 
 
        $event = Event::findOrFail(61);

        $activeAccountPaymentGateway = $event->account->getGateway($event->account->payment_gateway_id);
 
        $OPENPAY_ID = 'mxovvyuwdcugptyk5j15';
        $OPENPAY_SK = 'sk_18f9ae7d01304a5faf0f27a71080e238';
        $COUNTRY_CODE = 'MX';
        $OPENPAY_PRODUCTION_MODE = false;
        $OPENPAY_AMOUNT = 500;
        
        // dd($activeAccountPaymentGateway, $OPENPAY_ID, $COUNTRY_CODE);

        try {
            // create instance OpenPay
            $openpay = Openpay::getInstance($OPENPAY_ID, $OPENPAY_SK, $COUNTRY_CODE); //Openpay::getInstance(env('OPENPAY_ID'), env('OPENPAY_SK'));
            
            Openpay::setProductionMode($OPENPAY_PRODUCTION_MODE); //Openpay::setProductionMode(env('OPENPAY_PRODUCTION_MODE'));

            $customer = array(
                'name' => $request->holder_name,
                'last_name' => null,
                'phone_number' => null,
                'email' => 'test@gmail.com');

            $chargeRequest = array(
                'method' => 'card',
                'source_id' => $request->token_id,
                'amount' => 100,
                'currency' => 'MXN',
                "use_3d_secure"=>true,
                "redirect_url"=>route('postCompletedOrder', ['event_id' => 61]),
                'description' => 'asdad',
                'order_id' =>  rand(),
                'device_session_id' => $request->deviceIdHiddenFieldName,
                'customer' => $customer);

            $charge = $openpay->charges->create($chargeRequest);
            // dd($charge, $charge->payment_method->url);

            return redirect($charge->payment_method->url);
            
            return [
                'data' => $charge,
                'error_code' => '200',
                'description' => 'success'
            ];

        } catch (OpenpayApiTransactionError $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        } catch (OpenpayApiRequestError $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        } catch (OpenpayApiConnectionError $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        } catch (OpenpayApiAuthError $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        } catch (OpenpayApiError $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => [
                    'category' => $e->getCategory(),
                    'error_code' => $e->getErrorCode(),
                    'description' => $e->getMessage(),
                    'http_code' => $e->getHttpCode(),
                    'request_id' => $e->getRequestId()
                ]
            ]);
        }
    }
} 