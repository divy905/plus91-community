<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RazorpayController extends Controller
{
    protected $api;
    public function __construct()
    {
        $this->api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    public function payToBookEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $event = Event::findOrFail($request->event_id);
        if (!empty($event->amount) && $event->amount > 0) {
            $amountInPaise = (int) ($event->amount * 100);
            $orderData = [
                'receipt'   => 'order_rcptid_' . $event->id,
                'amount'    => $amountInPaise,
                'currency'  => 'INR'
            ];

            try {
                $razorpayOrder = $this->api->order->create($orderData);
                $transaction = new Transaction();
                $transaction->user_id = Auth::id();
                $transaction->event_id = $event->id;
                $transaction->transaction_id = null;
                $transaction->mer_transaction_id = $razorpayOrder['id'];
                $transaction->razorpay_order_id = $razorpayOrder['id'];
                $transaction->amount = $event->amount;
                $transaction->status = 'created';
                $transaction->is_joined = 1;
                $transaction->save();

                return response()->json([
                    'result' => true,
                    'message' => 'Event booked successfully.',
                    'transaction' => $transaction,
                    'razorpay_key' => env('RAZORPAY_KEY')
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'result' => false,
                    'message' => 'Failed to create Razorpay order: ' . $e->getMessage()
                ], 500);
            }
        } else {
            // for free event 
            $transaction = new Transaction();
            $transaction->user_id = Auth::id();
            $transaction->event_id = $event->id;
            $transaction->transaction_id = null;
            $transaction->mer_transaction_id = null;
            $transaction->razorpay_order_id = null;
            $transaction->amount = '0';
            $transaction->status = 'free';
            $transaction->is_joined = 1;
            $transaction->save();

            return response()->json([
                'result' => true,
                'message' => 'Event booked successfully.',
                'transaction' => $transaction,
                'razorpay_key' => env('RAZORPAY_KEY')
            ]);
        }
    }



    public function updatePaymentResponse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'razorpay_order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }
        if ($request->payment_status == 'success') {
            try {
                $razorpayOrderId = $request->razorpay_order_id;
                $transaction = Transaction::where('razorpay_order_id', $razorpayOrderId)->first();

                if (!$transaction) {
                    return response()->json([
                        'result' => false,
                        'message' => 'Transaction not found'
                    ], 404);
                }

                $transaction->status = 'success';
                $transaction->payment_id = $request->payment_id;
                $transaction->save();

                return response()->json([
                    'result' => true,
                    'message' => 'Event booked successfully.',
                    'transaction' => $transaction
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'result' => false,
                    'message' => 'Failed to update payment',
                    'error' => $e->getMessage()
                ], 400);
            }
        } else {
            return response()->json([
                'result' => true,
                'message' => 'Sorry, Your Event is not booked due to payment failed.',
                'transaction' => 'Failed'
            ]);
        }
    }
}
