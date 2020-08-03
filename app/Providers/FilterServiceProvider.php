<?php

namespace BreakingBad\Providers;

use Illuminate\Support\Collection;
use BreakingBad\Libraries\Filter\Builder;
use BreakingBad\Libraries\Filter\Factory;
use BreakingBad\Console\Commands\MakeFilter;
use BreakingBad\Libraries\Filter\FactoryInterface;
use BreakingBad\Libraries\Filter\AuthorizeWhenResolved;
use Illuminate\Support\ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->registerMacros();

        $this->app->afterResolving(AuthorizeWhenResolved::class, static function ($resolved) {
            $resolved->authorizeResolved();
        });
    }

    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        $this->registerBindings();
        $this->registerConsole();
    }

    private function registerBindings()
    {
        $this->app->bind(FactoryInterface::class, Factory::class);

        $this->app->singleton('filtrate', static function () {
            return new Builder(new Factory);
        });
    }

    /**
     * Register console commands.
     */
    protected function registerConsole(): void
    {
        $this->commands(MakeFilter::class);
    }

    private function registerMacros()
    {
        Collection::macro('getFilters', function () {
            $filters = $this->filter(static function ($value, $filter) {
                if (is_array($value)) {
                    $result = [];
                    array_walk_recursive($value, static function ($val) use (&$result) {
                        if (!empty($val)) {
                            $result[] = $val;
                        }
                    });

                    return !empty($result);
                }

                return !empty($value);
            });

            return $filters->all();
        });
    }
}
