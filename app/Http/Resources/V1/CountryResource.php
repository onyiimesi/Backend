<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'name' => (string)$this->name,
            'code' => (string)$this->code,
            'dailcode' => (string)$this->dailcode,
            'flag' => (string)$this->flag
        ];
    }
}
