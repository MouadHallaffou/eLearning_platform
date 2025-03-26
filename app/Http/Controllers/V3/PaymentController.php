<?php

namespace App\Http\Controllers\V3;

use Stripe\Stripe;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\EnrollmentRepositoryInterface;

class PaymentController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentRepositoryInterface $enrollmentRepository)
    {
        $this->enrollmentService = $enrollmentRepository;
    }

    public function checkout($course_id){
        Stripe::setApiKey("pk_test_51R6QXfLE4hmSPgNP2bisjAwwgoeISOk4FHDzCqhsbuhBKg8QYv3BmmqWJrcIep8wGmYvdk5YLGYT62ENmpOHV9M0001Xl51It8");
        $course = Course::findOrFail($course_id);
        $session = Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'mad',
                        'product_data' => [
                            'name' => $course->title,
                        ],
                        'unit_amount'  => $course->price * 100,
                    ],
                    'quantity'   => 1,
                ],
            ],
            'mode'        => 'payment',
            'success_url' => route('payment.success',$course),
            'cancel_url'  => route('payment.checkout',$course_id),
        ]);
        return response()->json([
            "message"=>'success',
            "url"=>$session->url
        ]);
    }

    public function success($course_id)
    {
        try {
            $user = Auth::user();

            $payment = Payment::where("user_id", $user->id)
                ->where("course_id", $course_id)
                ->where("payment_status", "pending")
                ->first();

            if (!$payment) {
                return response()->json([
                    "message" => 'Aucun paiement en attente trouvé !'
                ], 404);
            }

            $token_payment = session()->get("Session_token_payment");
           
            $payment->update([
                'payment_status' => 'payed'
            ]);

            $this->enrollmentService->enrollUser(
                $user->id,
                $course_id  
            );

            return response()->json([
                "message" => 'Paiement réussi et inscription confirmée !'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ]);
        }
    }
}