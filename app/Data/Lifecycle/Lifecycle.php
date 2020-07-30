<?php

namespace BreakingBad\Data\Lifecycle;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

abstract class Lifecycle
{
    /**
     * A flag used in the inserting or updating of a Character to denote whether to run the model's native validation steps.
     * Useful only when there is custom validation is already ran on the Request level such as in the API where custom
     * error coded messages are used instead of default errors and validation is ran before calling this method.
     *
     * @var string
     */
    public const NO_VALIDATE = 'no_validate';

    /**
     * A flag used to dry run through a save process without actually saving to the database.
     *
     * @var string
     */
    public const DRY_RUN = 'dry_run';

    /**
     * A flag used to detach/delete relations if they are not present in the sync
     *
     * @var string
     */
    public const DETACH_SYNC = 'detach_sync';

    /**
     * @return array
     */
    abstract public static function relationships();

    /**
     * Update relationships on a model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     */
    protected static function updateRelationships(Model $model, array $data, array $flags = [])
    {
        foreach (static::relationships() as $relationship_name) {
            if (isset($data[$relationship_name])) {
                $relationship_type = get_class($model->$relationship_name());
                switch ($relationship_type) {
                    case BelongsToMany::class:
                        self::syncBelongsToManyRelationship($model, $relationship_name, $data[$relationship_name], $flags);
                        break;
                    case MorphMany::class:
                    case HasMany::class:
                        self::syncHasManyRelationship($model, $relationship_name, $data[$relationship_name], $flags);
                        break;
                    default:
                        break;
                }
                unset($data[$relationship_name]);
            }
        }
    }

    /**
     * Sync a has many relationship
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param $relationship_name
     * @param array $data
     */
    protected static function syncHasManyRelationship(Model $model, $relationship_name, array $data, array $flags = [])
    {
        $present_ids = [];
        foreach ($data as $related) {
            $conditions = [
                'id' => array_key_exists('id', $related) ? $related['id'] : null
            ];

            $present_ids[] = $model->$relationship_name()->updateOrCreate($conditions, $related)->id;
        }

        if (Arr::get($flags, static::DETACH_SYNC, false) === true) {
            $model->$relationship_name()->whereNotIn('id', $present_ids)->delete();
        }
    }

    /**
     * Sync a belongs to relationship
     *
     * @param \Illuminate\Database\Eloquent\Model  $model
     * @param string $relationship_name
     * @param array  $data
     * @return mixed
     */
    protected static function syncBelongsToManyRelationship(Model $model, $relationship_name, array $data, array $flags = [])
    {
        return $model->$relationship_name()->sync($data);
    }
}
