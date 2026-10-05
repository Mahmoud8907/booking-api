<?php

namespace App\Exceptions;

use Exception;

class AppointmentAlreadyBookedException extends Exception

{
    public function __construct()
    {
        parent::__construct('This appointment time is already booked');
    }
    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 409);
    }
}
