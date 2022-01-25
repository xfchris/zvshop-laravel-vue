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
        $this->authorize('update', $this->orderService->getOrderByUser());
        $this->orderService->updateOrderAddress($request);
        $response = $this->paymentService->pay();

        return redirect()->to($response->processUrl);
    }

    public function retryPay(Order $order): RedirectResponse
    {
        $this->authorize('update', $order);
        $response = $this->paymentService->pay($order);

        return redirect()->to($response->processUrl);
    }

    public function details(Order $order): View
    {
        $this->authorize('update', $order);
        return view('store.payments.details', ['orders' => [$order]]);
    }

    public function showUserOrders(): View
    {
        $orders = $this->paymentService->showUserOrders();
        return view('store.payments.index', ['orders' => $orders]);
    }
}
