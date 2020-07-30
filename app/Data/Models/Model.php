<?php

namespace BreakingBad\Data\Models;

use BreakingBad\Data\Models\Traits\IsFilterable;
use BreakingBad\Data\Models\Traits\IsValidatable;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * Class Model
 */
class Model extends BaseModel
{
    use IsValidatable, IsFilterable;

    /**
     * Listen for model events
     */
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if ($model->validate) {
                return $model->validate();
            }
            return true;
        });
    }

    /**
     * Return a new static instance of the model
     *
     * @param  array  $args
     *
     * @return self
     */
    public static function new(...$args): self
    {
        return with(new static(...$args));
    }

    /**
     * Determine if the current model has a corresponding database record
     *
     * @return bool
     */
    public function isNew(): bool
    {
        return empty($this->id);
    }
}
