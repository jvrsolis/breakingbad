<?php

namespace BreakingBad\Data\Enums;

use ReflectionClass;

/**
 * Class Show
 */
class Show
{
    /** @var string */
    const BREAKING_BAD = 'Breaking Bad';

    /** @var string */
    const BETTER_CALL_SAUL = 'Better Call Saul';

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
