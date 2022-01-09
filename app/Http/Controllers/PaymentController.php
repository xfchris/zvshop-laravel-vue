<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentPayRequest;
use App\Models\Order;
use App\Services\Order\OrderService;
use App\Services\Order\PaymentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        public PaymentService $paymentService,
        public OrderService $orderService
    ) {
    }

    public function accept(string $referenceId): View
    {
        //entrada publica
    }

    public function cancel(string $referenceId): View
    {
        //publica
    }

    //Request: Verificar que la orden no este en estado pendiente o aprobada
    public function pay(PaymentPayRequest $request)
    {
        $this->orderService->updateOrderAddress($request);
        $processUrl = $this->paymentService->pay();

        if ($processUrl) {
            return redirect()->to($processUrl);
        } else {
            return redirect()->back()->with('error', 'Error connecting to the payment gateway.');
        }
    }

    public function retryPay(Order $order)
    {
        $this->authorize('update', $order);

        //Valida que cada uno de los productos existan con su respectivo valor y precio
        return 'retryPay';
    }

    public function details(Order $order)
    {
        $this->authorize('update', $order);

        //Muestro detalle de una factura
        return $order;
    }

    public function showUserOrders(): View
    {
        //usuario dueño o admin "no hay necesidad"
        $orders = $this->paymentService->showUserOrders();

        return $orders;
    }
}
