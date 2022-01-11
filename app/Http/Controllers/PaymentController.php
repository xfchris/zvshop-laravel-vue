<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentPayRequest;
use App\Models\Order;
use App\Services\Order\OrderService;
use App\Services\Order\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        public PaymentService $paymentService,
        public OrderService $orderService
    ) {
    }

    public function changeStatus(string $referenceId): RedirectResponse
    {
        [$paymentId, $status] = $this->paymentService->changeStatus($referenceId);

        return redirect()->route('payment.details', $paymentId)
                         ->with('info', trans('app.payment.info_status_changed', ['status' => strtolower($status)]));
    }

    public function pay(PaymentPayRequest $request): RedirectResponse
    {
        $this->orderService->updateOrderAddress($request);
        $response = $this->paymentService->pay();

        if ($response->processUrl) {
            return redirect()->to($response->processUrl);
        } else {
            return redirect()->back()->with('error', $response->statusResponse->message);
        }
    }

    public function retryPay(Order $order): RedirectResponse
    {
        $response = $this->paymentService->pay($order);

        if ($response->processUrl) {
            return redirect()->to($response->processUrl);
        } else {
            return redirect()->back()->with('error', $response->statusResponse->message);
        }
    }

    public function details(Order $order): View
    {
        return view('store.payments.details', ['orders' => [$order]]);
    }

    public function showUserOrders(): View
    {
        $orders = $this->paymentService->showUserOrders();
        return view('store.payments.index', ['orders' => $orders]);
    }
}
