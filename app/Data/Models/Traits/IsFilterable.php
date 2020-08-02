<?php

namespace BreakingBad\Data\Models\Traits;

use Carbon\Carbon as Date;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use BreakingBad\Libraries\QueryFilter\QueryFilter;

/**
 * Trait IsFilterable
 *
 * @package BreakingBad\Data\Models\Traits
 */
trait IsFilterable
{
    /**
     * Get the name of the model's table statically. (Caveat: Does not work with partitions)
     *
     * @return string
     */
    public static function table(...$args): string
    {
        return self::new (...$args)->getTable();
    }

    /**
     * Get the name of the model's columns statically. (Caveat: Does not work with partitions)
     *
     * @return array
     */
    public static function columns(...$args): array
    {
        return self::new (...$args)->getColumns();
    }

    /**
     * Return columns from a table in a presentable format
     *
     * @return void
     */
    public static function presentableColumns(...$args)
    {
        return collect(self::columns(...$args))->mapWithKeys(function ($column) {
            return [$column => Str::titlelize($column)];
        });
    }

    /**
     * Convert a query builder object from a model to raw sql with binding included.
     *
     * @see \Illuminate\Database\Query\Builder::toSql() For sql without bindings included.
     * @see \Illuminate\Database\Query\Builder::getBindings() For retieval of sql bindings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return string
     */
    public function scopeToRawSQL(Builder $query): string
    {
        return DB::raw(Str::replaceArray('?', $query->getBindings(), $query->toSql()));
    }

    /**
     * Scope using specified orders
     *
     * @param \Illuminate\Database\Eloquent\Builder $query   The query object to apply filters on.
     * @param  array|null  $filters  The filters to apply
     *
     * @return \Illuminate\Database\Eloquent\Builder $query  The resulting query after filters have been applied.
     */
    public function scopeOrders($query, array $orders)
    {
        $table = $this->getTable();
        foreach ($orders as $field => $direction) {
            if (is_int($field)) {
                $field     = $direction;
                $direction = 'ASC';
            }
            $query->orderByRaw("${$table}.{$field} {$direction}");
        }
    }

    /**
     * Scope to clear existing orderby statements
     *
     * @param \Illuminate\Database\Eloquent\Builder $query   The query object to remove the orders from
     *
     * @return \Illuminate\Database\Eloquent\Builder $query  The resulting query after removing the orders.
     */
    public function scopeClearOrders($query)
    {
        $query->getQuery()->orders = [];
        return $query;
    }

    /**
     * Scope to clear existing group statements
     *
     * @param \Illuminate\Database\Eloquent\Builder $query   The query object to remove the orders from
     *
     * @return \Illuminate\Database\Eloquent\Builder $query  The resulting query after removing the orders.
     */
    public function scopeClearGroups($query)
    {
        $query->getQuery()->groups = null;
        return $query;
    }

    /**
     * Filter a result set.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Scope using specified generic column filters
     *
     * @param \Illuminate\Database\Eloquent\Builder $query   The query object to apply filters on.
     * @param  array|null  $filters  The filters to apply
     *
     * @return \Illuminate\Database\Eloquent\Builder $query  The resulting query after filters have been applied.
     */
    public function scopeSearch(Builder $query, ?array $filters = null): Builder
    {
        $table = $this->getTable();
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if ($value !== '') {
                    switch ($key) {
                        case strpos($key, '_from') !== false:
                            $field = substr($key, 0, strpos($key, '_from'));
                            $date  = Date::parse($value)->timestamp;
                            $query->where(DB::raw("{$table}.{$field}"), '>=', $date);
                            break;
                        case strpos($key, '_to') !== false:
                            $field = substr($key, 0, strpos($key, '_to'));
                            $date  = Date::parse($value)->timestamp;
                            $query->where(DB::raw("{$table}.{$field}"), '<=', $date);
                            break;
                        default:
                            if (!is_array($value)) {
                                if (is_numeric($value)) {
                                    $query->whereRaw(DB::raw("{$table}.{$key} = {$value}"));
                                } elseif (is_string($value)) {
                                    $query->whereRaw(DB::raw("{$table}.{$key} LIKE '%{$value}%'"));
                                }
                            } else {
                                if (count($value) <= 5) {
                                    $query->where(static function ($q) use ($table, $key, $value) {
                                        foreach ($value as $item) {
                                            if (is_numeric($item)) {
                                                $q->orWhereRaw(DB::raw("{$table}.{$key} = {$item}"));
                                            } elseif (is_string($item)) {
                                                $q->orWhereRaw(DB::raw("{$table}.{$key} LIKE '%{$item}%'"));
                                            }
                                        }
                                    });
                                } else {
                                    $query->whereIn(DB::raw("{$table}.{$key}"), $value);
                                }
                            }
                            break;
                    }
                }
            }
        }

        return $query;
    }
}
