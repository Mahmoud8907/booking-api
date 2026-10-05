<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AppointmentAlreadyBookedException;

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
}
