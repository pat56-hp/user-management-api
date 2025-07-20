<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->user->nom,
            'ip' => $this->ip,
            'navigator' => $this->navigator,
            'action' => $this->action,
            'pays' => $this->pays,
            'codepays' => $this->codepays,
            'created_at' => $this->created_at->format('d/m/Y à H:i'),
        ];
    }
}
