<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AppointmentAlreadyBookedException;
use App\Exceptions\AppointmentAlreadyCancelledException;

class AppointmentService
{
    public function createAppointment($user, $serviceId, $appointmentDate)
    {
        return DB::transaction(function () use ($user, $serviceId, $appointmentDate) {
            $exists = Appointment::where('service_id', $serviceId)
                ->where('appointment_date', $appointmentDate)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($exists) {
                throw new AppointmentAlreadyBookedException();
            }

            return Appointment::create([
                'user_id' => $user->id,
                'service_id' => $serviceId,
                'appointment_date' => $appointmentDate,
                'status' => 'pending',
            ]);
        });
    }
    public function cancelAppointment(Appointment $appointment)
    {
        if ($appointment->status === 'cancelled'){
                throw new AppointmentAlreadyCancelledException();
            }
            $appointment->update(['status' => 'cancelled']);
            return $appointment;
    }
}
