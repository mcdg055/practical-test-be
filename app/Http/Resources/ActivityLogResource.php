<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'description' => $this->description,
            'log_name'    => $this->log_name,
            'event'       => $this->event,
            'causer'      => $this->causer?->only(['id', 'name', 'email']),
            'properties'  => $this->properties,
            'subject_type' => $this->subject_type,
            'subject_id'  => $this->subject_id,
            'created_at'  => $this->created_at->toIso8601String(),
        ];
    }
}
