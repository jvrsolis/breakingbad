<?php

namespace BreakingBad\Data\Lifecycle;

use Exception;
use Illuminate\Support\Arr;
use BreakingBad\Services\API;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use BreakingBad\Data\Models\Character;
use BreakingBad\Data\Adapters\CharacterAdapter;
use BreakingBad\Data\Structures\ExternalCharacter;

/**
 * Class CharacterLifecycle
 *
 * A class used to encapsulate the business logic of creating, editing, deleting, and searching as well
 * as any other actions this model asked to do.
 *
 * This class takes in ids, existing model objects, and array's of attributes corresponding to
 * the models columns. Any transformation/normalization of data needs to occur at the request level before reaching the lifecycle methods and any result
 * given that needs to be re-transformed needs to occur at the view or resource/response level.
 * This ensures consistency and separation of concerns in using this class to engage with the underlying model and database.
 *
 * @package Lifecycle
 */
class CharacterLifecycle extends Lifecycle
{
    /**
     * Create a new character
     * Safely creates a new character, if an error occurs all transactions are automatically rolled-back
     *
     * @param  array           $data
     * @param  array           $flags  An set of key => values that affect the method's various steps.
     *
     * @return \BreakingBad\Data\Models\Character
     */
    public static function create(array $data, array $flags = []): Character
    {
        $relations = Arr::only($data, self::relationships(), []);
        $data   = Arr::except($data, self::relationships());

        $character = Character::make($data);
        $character = DB::transaction(static function () use ($character, $relations, $flags) {
            return self::store($character, $relations, $flags);
        });

        return $character;
    }

    /**
     * Edits a character
     * Safely edits a character, if an error occurs all transactions are automatically rolled-back
     *
     * @param  array  $search             The array of data to use as search criteria for finding the record to edit
     * @param  array  $replace            The array of data that will replace values from the found record.
     * @param  array  $flags              An set of key => values that affect the method's various steps.
     *
     * @return \BreakingBad\Data\Models\Character
     */
    public static function edit(array $search, array $replace, array $flags = []): Character
    {
        $isClean = true;
        $character = self::find($search, $flags);
        $data   = Arr::except($replace, self::relationships());
        $relations = Arr::only($replace, self::relationships(), []);

        foreach ($data as $attribute => $value) {
            $character->$attribute = $value;
        }
        $isClean = $isClean && $character->isClean();

        if (!empty($relations)) {
            $character->with($relations);
            foreach ($relations as $relation) {
                foreach($relation as $attribute => $value) {
                    $character->$relation->$attribute = $value;
                }
                $isClean = $isClean && $character->$relation->isClean();
            }
        }

        if (!$isClean) {
            $character = DB::transaction(static function () use ($character, $relations, $flags) {
                return self::store($character, $relations, $flags);
            });
        }

        return $character;
    }

    /**
     * Store a new character to the database given a set of attributes
     *
     * @param  array  $data
     *                 {...attributes, ...relations}
     * @param  array  $flags
     *
     * @return \BreakingBad\Data\Models\Character
     * @throws \Illuminate\Validation\ValidationException
     */
    private static function store(Character $character, array $relations, array $flags = []): Character
    {
        $character->validate = !Arr::get($flags, static::NO_VALIDATE, false) === true;
        Arr::get($flags, static::DRY_RUN, false) === true ? $character->validate() : $character->save();

        if (!empty($relations)) {
            static::updateRelationships($character, $relations);
        }

        return $character;
    }

    /**
     * Destroy all characters
     *
     * @param  int|array  $ids  The id or ids to delete
     *
     * @param  array      $flags
     *
     * @return bool
     */
    public static function delete($ids, array $flags = []): bool
    {
        $ids   = (array) $ids;
        $count = 0;
        DB::beginTransaction();
        try {
            $count = Character::destroy($ids);
            DB::commit();
            if (Arr::get($flags, static::DRY_RUN, false)) {
                DB::rollBack();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }

        return $count === count($ids);
    }

    /**
     * Search a set of characters
     *
     * @param array $attributes
     * @return \Illuminate\Support\Collection|Illuminate\Pagination\LengthAwarePaginator
     */
    public static function search(array $attributes, array $options = [])
    {
        $query =
        if ($options !== []) {
            $query->orderBy($options['column'], $options['direction']);
        }
        return $query;
    }

    /**
     * Find a single character
     *
     * @param array $attributes
     *
     * @return \BreakingBad\Data\Models\Character
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function find(array $attributes, array $flags = []): Character
    {
        return Character::query()->filter($attributes, $flags)->firstOrFail();
    }

    /**
     * Validate a single character
     *
     * @param array $attributes
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validate(array $attributes): bool
    {
        return Character::new($attributes)->validate(false, false, false);
    }

   /**
     * Sync the set of characters from the external source
     *
     * @return void
     */
    public static function sync(): void
    {
        $data = collect(API::characters());
        $data->map(function ($item) {
            $externalCharacter = ExternalCharacter::make($item);
            if ($externalCharacter->validate() && (($character = Character::find($externalCharacter->char_id)) !== null)) {
                $newCharacter = CharacterAdapter::adapt($externalCharacter);
                self::edit($character->toArray(), $newCharacter->toArray());
            } elseif ($externalCharacter->validate() && Character::find($externalCharacter->char_id) === null) {
                $character = CharacterAdapter::adapt($externalCharacter);
                self::create($character->toArray());
            }
        });
    }

    /**
     * Return the set of relations that can be found on the model
     *
     * @return void
     */
    public static function relationships()
    {
        return [
            'occupations',
            'appearances'
        ];
    }
}
