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
 
 
        $event = Event::findOrFail(52);

        $activeAccountPaymentGateway = $event->account->getGateway($event->account->payment_gateway_id);
 
        $OPENPAY_ID = $activeAccountPaymentGateway->config['apiId'];
        $OPENPAY_SK = $activeAccountPaymentGateway->config['apiKey'];
        $COUNTRY_CODE = 'MX';
        $OPENPAY_PRODUCTION_MODE = false;
        $OPENPAY_AMOUNT = 500;
        
        // dd($activeAccountPaymentGateway, $OPENPAY_ID, $COUNTRY_CODE);

        try {
            // create instance OpenPay
            $openpay = Openpay::getInstance($OPENPAY_ID, $OPENPAY_SK, $COUNTRY_CODE); //Openpay::getInstance(env('OPENPAY_ID'), env('OPENPAY_SK'));
            
            Openpay::setProductionMode($OPENPAY_PRODUCTION_MODE); //Openpay::setProductionMode(env('OPENPAY_PRODUCTION_MODE'));
            
            // create object customer
            $customer = array(
                'name' => $request->holder_name,
                // 'last_name' => $request->last_name,
                'email' => 'test@gmail.com'
            );

            // create object charge
            $chargeRequest =  array(
                "method" => "card",
                'source_id' => $request->token_id,
                'device_session_id' => $request->deviceIdHiddenFieldName,
                'amount' => 100,
                'description' => 'Cargo terminal virtual a mi merchant',
                'customer' => $customer,
                'send_email' => false,
                'confirm' => false,
                'redirect_url' => 'http://www.openpay.mx/index.html'
            );

            $charge = $openpay->charges->create($chargeRequest);

            return response()->json([
                'data' => $charge->id
            ]);

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