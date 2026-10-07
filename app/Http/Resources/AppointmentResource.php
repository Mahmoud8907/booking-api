<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'service_id' => $this->service_id,
            'service' => [
                'id' => $this->service?->id,
                'name' => $this->service?->name,
                'price' => $this->service?->price,
                'duration' => $this->service?->duration,
            ],
            'appointment_date' => $this->appointment_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
