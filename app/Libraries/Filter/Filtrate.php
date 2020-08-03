<?php

namespace BreakingBad\Libraries\Filter;

use Illuminate\Support\Facades\Facade as BaseFacade;

class Filtrate extends BaseFacade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'filtrate';
    }
}
