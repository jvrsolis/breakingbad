<?php

namespace BreakingBad\Data\Enums;

use ReflectionClass;

/**
 * Class CharacterStatus
 * Valid values for the character->status field
 */
class CharacterStatus
{
    /** @var string */
    const ALIVE = 'alive';

    /** @var string */
    const DECEASED = 'deceased';

    /** @var string */
    const PRESUMED_DEAD = 'presumed dead';

    /** @var string */
    const QUESTIONABLE = '?';

    /** @var string */
    const UNKNOWN = 'unknown';

    /**
     * Return all domain statuses
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function all(): array
    {
        $class = new ReflectionClass(self::class);
        return $class->getConstants();
    }
}
