<?php

namespace App\Exceptions;

use Exception;

class AppointmentAlreadyCancelledException extends Exception
{
    public function __construct()
    {
        parent::__construct('Appointment is already cancelled');
    }
    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 409);
    }
}
