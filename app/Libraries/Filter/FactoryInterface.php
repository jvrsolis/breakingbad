<?php

namespace BreakingBad\Libraries\Filter;

use Illuminate\Database\Eloquent\Model;

interface FactoryInterface
{
    /**
     * Create applied filter.
     *
     * @param string $filter
     * @param Model  $model
     *
     * @throws \BreakingBad\Exceptions\NotFoundFilterException
     *
     * @return Filter
     */
    public function make(string $filter, Model $model): Filter;

    /**
     * @param string $namespace
     *
     * @return Factory
     */
    public function setCustomNamespace(string $namespace = ''): Factory;

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void;

    /**
     * @return string
     */
    public function getCustomNamespace(): string;
}
