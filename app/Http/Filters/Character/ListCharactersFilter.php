<?php

namespace BreakingBad\Http\Filters\Character;

use BreakingBad\Libraries\Filter\Filter;

class ListCharactersFilter extends Filter
{
    public function status($status)
    {
        $this->builder->where('status', strtolower($status));
    }

    public function portrayed($portrayed)
    {
        $this->builder->where('portrayed', strtolower($portrayed));
    }

    public function name($name)
    {
        $this->builder->where('name', strtolower($name));
    }

    protected function filters()
    {
        return $this->request->only('status', 'portrayed', 'name');
    }
}
