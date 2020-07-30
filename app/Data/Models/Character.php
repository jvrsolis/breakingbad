<?php

namespace BreakingBad\Data\Models;

/**
 * Class Character
 *
 * @property int $id
 * @property string $name
 * @property string $birthday
 * @property string $img
 * @property string $status
 * @property string $nickname
 * @property string $portrayed
 * @property string $created_at
 * @property string $updated_at
 * @property \Illuminate\Database\Eloquent\Collection $occupations
 * @property \Illuminate\Database\Eloquent\Collection $appearances
 * @property \Illuminate\Database\Eloquent\Collection $shows
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 * @package BreakingBad\Data\Models
 */
class Character extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'        => 'int',
        'name'      => 'string',
        'birthday'  => 'string',
        'img'       => 'string',
        'status'    => 'string',
        'nickname'  => 'string',
        'portrayed' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'birthday',
        'img',
        'status',
        'nickname',
        'portrayed',
        'created_at',
        'updated_at',
    ];

    /**
     * Return the occupations associated with the character
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function occupations()
    {
        return $this->hasMany(Occupation::class);
    }

    /**
     * Return the occupations associated with the character
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function shows()
    {
        $shows = $this->appearances->unique('show')->pluck('show')->toArray();
        return $shows;
    }

    /**
     * Return the appearances associated with the character;
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appearances()
    {
        return $this->hasMany(Appearance::class);
    }

    /**
     * Apply various search filters
     *
     * @param Builder $query
     * @param array $search
     *
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            foreach ($search as $key => $value) {
                if ($value !== '') {
                    switch ($key) {
                        case 'show':
                        case 'season':
                            $query->whereHas('appearances', function($q) use ($key, $value) {
                                $q->where($key, $value);
                            });
                            unset($search[$key]);
                        break;
                    }
                }
            }
        }
        return $this->scopeFilter($query, $search);
    }
}
