<?php

namespace BreakingBad\Data\Adapters;

use BreakingBad\Data\Models\Appearance;
use BreakingBad\Data\Models\Character;
use BreakingBad\Data\Models\Occupation;
use BreakingBad\Data\Structures\ExternalCharacter;
use Illuminate\Database\Eloquent\Collection;
use BreakingBad\Data\Enums\Show;

/**
 * Class CharacterAdapater
 */
class CharacterAdapter
{
    /**
     * Adapt the given object to a new object
     *
     * @param ExternalCharacter $character
     *
     * @return Character
     */
    public static function adapt(ExternalCharacter $extCharacter): Character
    {
        $character = Character::make([
            'id'        => $extCharacter->char_id,
            'name'      => $extCharacter->name,
            'birthday'  => $extCharacter->birthday,
            'img'       => $extCharacter->img,
            'status'    => strtolower($extCharacter->status),
            'nickname'  => $extCharacter->nickname,
            'portrayed' => $extCharacter->portrayed,
        ]);

        $appearances = new Collection;
        foreach ($extCharacter->appearance as $season) {
            $appearances->push(Appearance::make([
                'character_id' => $extCharacter->char_id,
                'show'         => Show::BREAKING_BAD,
                'season'       => $season,
            ]));
        }

        foreach ($extCharacter->better_call_saul_appearance as $season) {
            $appearances->push(Appearance::make([
                'character_id' => $extCharacter->char_id,
                'show'         => Show::BETTER_CALL_SAUL,
                'season'       => $season,
            ]));
        }
        $character->setRelation('appearances', $appearances);

        $occupations = new Collection;
        foreach ($extCharacter->occupation as $occupation) {
            $occupations->push(Occupation::make([
                'character_id' => $extCharacter->char_id,
                'name'         => $occupation,
            ]));
        }

        $character->setRelation('occupations', $occupations);

        return $character;
    }
}
