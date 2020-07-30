<?php

namespace BreakingBad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppearanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'show'         => $this->show,
            'season'       => $this->season
        ];
    }
}
