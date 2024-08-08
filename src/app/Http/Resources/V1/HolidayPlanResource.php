<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\ParticipantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HolidayPlanResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'location' => $this->location,
            'participants' => ParticipantResource::collection($this->whenLoaded('participants'))
        ];
    }
}
