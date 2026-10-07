<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Appointment;


class AppointmentController extends Controller
{
    private AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }
    public function index(Request $request)
    {
        $user = $request->user();
        $appointments = $user->appointments()->with('service')->paginate(10);
        return AppointmentResource::collection($appointments);
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
    public function show(Appointment $appointment)
    {
        Gate::authorize('view', $appointment);
        $appointment->load('service');
        return new AppointmentResource($appointment);
    }
    public function cancel(Appointment $appointment)
    {
        Gate::authorize('cancel', $appointment);
        $appointment = $this->appointmentService->cancelAppointment($appointment);
        return new AppointmentResource($appointment);
    }
}
