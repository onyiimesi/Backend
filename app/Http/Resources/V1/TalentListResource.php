<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TalentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uniqueId' => (string)$this->uuid,
            'first_name' => (string)$this->first_name,
            'last_name' => (string)$this->last_name,
            'email' => (string)$this->email,
            'skill_title' => (string)$this->skill_title,
            'compensation' => (string)$this->compensation,
            'rate' => (string)$this->rate,
            'location' => (string)$this->location,
            'image' => (string)$this->image,
            'portfolio' => $this->portfolios->map(function($port) {
                return [
                    'title' => $port->title,
                    'client_name' => $port->client_name,
                    'job_type' => $port->job_type,
                    'location' => $port->location,
                    'rate' => $port->rate,
                    'tags' => json_decode($port->tags),
                    'cover_image' => $port->cover_image,
                    'body' =>  $port->body
                ];
            })->toArray(),
        ];
    }
}
