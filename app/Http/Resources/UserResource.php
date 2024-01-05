<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'state' => $this->whenLoaded('state', $this->state->name),
            'country' => $this->whenLoaded('state.country', $this->state->country->name),
            'last_login' => $this->last_login,
            'created_at' => $this->created_at,
        ];

        if ($this->token) {
            $user['token'] = $this->token;
        }
        return $user;
    }

}
