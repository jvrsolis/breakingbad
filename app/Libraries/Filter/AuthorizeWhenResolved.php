<?php

namespace BreakingBad\Libraries\Filter;

interface AuthorizeWhenResolved
{
    /**
     * Authorize the given filter instance.
     */
    public function authorizeResolved();
}
