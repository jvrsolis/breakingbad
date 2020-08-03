<?php

namespace BreakingBad\Libraries\Filter;

use Illuminate\Database\Eloquent\Builder as BaseBuilder;
use Illuminate\Database\Eloquent\Model;

class Builder
{
    /**
     * The filter factory.
     *
     * @var
     */
    protected $filterFactory;

    /**
     * Custom filters namespace.
     *
     * @var string
     */
    protected $filterNamespace = '';

    /**
     * Builder constructor.
     *
     * @param Factory $filterFactory
     */
    public function __construct(Factory $filterFactory)
    {
        $this->filterFactory = $filterFactory;
    }

    /**
     * Create a new Builder for a request and model.
     *
     * @param string|Model|BaseBuilder $query
     * @param array|string                        $filters
     *
     * @throws BreakingBad\Exceptions\NotFoundFilterException
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function to($query, $filters): BaseBuilder
    {
        /** @var BaseBuilder $query */
        $query = $this->resolveQuery($query);
        if (empty($filters)) {
            return $query;
        }
        $filters = (array) $filters;
        $this->applyFilters($query, $this->getFilters($filters));

        return $query;
    }

    /**
     * Set custom filters namespace.
     *
     * @param string $namespace
     *
     * @return Filtrate
     */
    public function setFilterNamespace(string $namespace = ''): self
    {
        $this->filterNamespace = $namespace;

        return $this;
    }

    /**
     * Resolve the incoming query to filter.
     *
     * @param string|Model|BaseBuilder $query
     *
     * @return BaseBuilder
     */
    private function resolveQuery($query): BaseBuilder
    {
        if (is_string($query)) {
            return $query::query();
        }

        if ($query instanceof Model) {
            return $query->query();
        }

        return $query;
    }

    /**
     * Returns only filters that have value.
     *
     * @param array $filters
     *
     * @return array
     */
    private function getFilters(array $filters = []): array
    {
        return collect($filters)->getFilters();
    }

    /**
     * Apply filters to Query Builder.
     *
     * @param BaseBuilder $query
     * @param array   $filters
     *
     * @throws BreakingBad\Exceptions\NotFoundFilterException
     *
     * @return BaseBuilder
     */
    private function applyFilters(BaseBuilder $query, array $filters): BaseBuilder
    {
        foreach ($filters as $filter => $value) {
            if (is_numeric($filter) && is_string($value)) {
                $query = $this->filterFactory->setCustomNamespace($this->filterNamespace)
                                         ->make($value, $query->getModel())
                                         ->apply($query);
            } else {
                $query = $this->filterFactory->setCustomNamespace($this->filterNamespace)
                                         ->make($filter, $query->getModel())
                                         ->apply($query, $value);
            }
        }

        return $query;
    }
}
