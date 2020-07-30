<?php

namespace BreakingBad\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AppearanceResourceCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = 'BreakingBad\Http\Resources\AppearanceResource';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->groupBy('show')->map(function ($item, $key) {
            return $item->pluck('season');
        });
    }
}
