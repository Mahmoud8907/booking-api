<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;


class AppointmentController extends Controller
{
    private AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }
    public function store(StoreAppointmentRequest $request)
    {
        $user = $request->user();

        $appointment = $this->appointmentService->createAppointment(
            $user,
            $request->service_id,
            $request->appointment_date
        );
        return new AppointmentResource($appointment);
    }
}
