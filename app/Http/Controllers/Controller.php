<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\InstructorWorkTime;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;
use Laravel\Cashier\Plan\Contracts\PlanRepository;
use Mollie\Api\Resources\Customer;
use Mollie\Laravel\Facades\Mollie;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    /**
     * Send a success response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendSuccess($data, string $message = null, int $code = Response::HTTP_OK)
    {

        return response()->json([
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Send an error response.
     *
     * @param string|null $message
     * @param int $code
     * @param array|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendError(string $message = null, int $code = Response::HTTP_BAD_REQUEST, array $errors = null)
    {
        $response = [
            'success' => false,
            'code' => $code,
            'message' => $message,
            'data' => []
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }


    public function test()
    {
        $payments = Mollie::api()->payments()->page();
        return $payments;

        // foreach ($payments as $payment) {
        //     Mollie::api()->payments()->delete($payment->id);
        // }
        // return Mollie::api()->payments()->page();

        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'value' => '20.00',
                'currency' => 'EUR'
            ],
            'description' => "by some course",
            'redirectUrl' => route('cc')
        ]);
        return Mollie::api()->payments()->get($payment->id);
        dd($payment);
        $user = User::find(1);
        return $user->findInvoice(2);
        // $stripePriceId = 'price_deluxe_album';

        // $quantity = 1;

        // return $user->checkout([$stripePriceId => $quantity], []);
        // $user->newSubscription('basic_plan', 'monthly')
        $user->newSubscription('main', 'main')
            // ->trialDays(10)
            // ->withCoupon('MADRID2019')
            ->create();
        return $user;
    }

    public function createPayment()
    {


        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'value' => '20.00',
                'currency' => 'EUR'
            ],
            'description' => "by some course",
            'redirectUrl' => route('cc')
        ]);

        // return json_encode($payment);
        return  [
            'getMobileAppCheckoutUrl' => $payment->getMobileAppCheckoutUrl(),
            'id' => $payment->id,
            'links' => $payment->_links,
            'amount' => $payment->amount,
            'status' => $payment->status
        ];
    }

    public function getPayments()
    {
        $payments = collect(Mollie::api()->payments()->page());
        return $payments;
        $payments = Mollie::api()->payments()->iterator();


        $payments = Mollie::api()->customerPayments()->createForId('cst_8822Lvra6H', [
            'amount' => [
                'value' => '20.00',
                'currency' => 'EUR'
            ],
            'description' => "by some course",
            'redirectUrl' => route('cc')
        ]);


        return $payments;
    }
}
