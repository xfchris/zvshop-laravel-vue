<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class PaymentException extends Exception
{
    public function render(): RedirectResponse
    {
        return redirect()->back()->with('error', $this->getMessage());
    }
}
