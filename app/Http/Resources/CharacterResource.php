<?php

namespace BreakingBad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CharacterResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'birthday'   => $this->birthday,
            'img'        => $this->img,
            'status'     => $this->status,
            'nickname'   => $this->nickname,
            'portrayed'  => $this->portrayed,
            'shows'      => new AppearanceResourceCollection($this->appearances),
            'occupations' => OccupationResource::collection($this->occupations),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
