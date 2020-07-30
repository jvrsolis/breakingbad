<?php

namespace BreakingBad\Data\Models;

/**
 * Class Occupation
 *
 * @property int $id
 * @property string $name
 * @property int $character_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @package BreakingBad\Data\Models
 */
class Occupation extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'           => 'int',
        'name'         => 'string',
        'character_id' => 'int',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'character_id', 'created_at', 'updated_at',
    ];

    /**
     * Return the character the occupation belongs to
     *
     * @return \BreakingBad\Data\Models\Character|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
