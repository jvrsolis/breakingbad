<?php

namespace BreakingBad\Data\Models;

/**
 * Class Appearance
 *
 * @property int $id
 * @property string $character_id
 * @property int    $season
 * @property string $created_at
 * @property string $updated_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @package BreakingBad\Data\Models
 */
class Appearance extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'           => 'int',
        'show'       => 'string',
        'season'    => 'int',
        'character_id' => 'int',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'character_id', 'show', 'season'
    ];

    /**
     * Return the character the appearance belongs to.
     *
     * @return \BreakingBad\Data\Models\Character
     */
    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
