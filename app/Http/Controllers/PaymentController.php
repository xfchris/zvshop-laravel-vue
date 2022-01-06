<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        return $request->all();
    }
}
