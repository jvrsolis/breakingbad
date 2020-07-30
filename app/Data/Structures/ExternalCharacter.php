<?php

namespace BreakingBad\Data\Structures;

use Illuminate\Support\Facades\Validator;
use BreakingBad\Data\Enums\CharacterStatus;

/**
 * Class ExternalCharacter
 */
class ExternalCharacter
{
    /** @var int */
    public $char_id = 0;

    /** @var string */
    public $name = '';

    /** @var string */
    public $birthday = '';

    /** @var array */
    public $occupation = [];

    /** @var string */
    public $img = '';

    /** @var string */
    public $status = '';

    /** @var string */
    public $nickname = '';

    /** @var array */
    public $appearance = [];

    /** @var string */
    public $portrayed = '';

    /** @var string */
    public $category = '';

    /** @var array */
    public $better_call_saul_appearance = [];

    /**
     * Construct a new instance
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $item) {
            if ($this->$key !== null) {
                $this->$key = $item;
            }
        }
    }

    /**
     * Create a new instance
     *
     * @param array $items
     *
     * @return self
     */
    public static function make(array $items = []): self
    {
        return new static($items);
    }

    /**
     * Return all values as an array
     *
     * @return array
     */
    public function all()
    {
        return [
            'char_id'                     => $this->char_id,
            "name"                        => $this->name,
            "birthday"                    => $this->birthday,
            "occupation"                  => $this->occupation,
            "img"                         => $this->img,
            "status"                      => strtolower($this->status),
            "nickname"                    => $this->nickname,
            "appearance"                  => $this->appearance,
            "portrayed"                   => $this->portrayed,
            "category"                    => $this->category,
            "better_call_saul_appearance" => $this->better_call_saul_appearance,
        ];
    }

    /**
     * Validate the character
     *
     * @return bool
     */
    public function validate(): bool
    {
        $validator = Validator::make($this->all(), [
            'char_id'                       => 'required|int',
            "name"                          => 'string',
            "birthday"                      => 'string',
            "appearance"                    => 'array',
            "occupation.*"                  => 'string',
            "img"                           => 'string|url',
            "status"                        => 'string|in:' . implode(',', CharacterStatus::all()),
            "nickname"                      => 'string',
            "appearance"                    => 'array',
            "appearance.*"                  => 'int',
            "portrayed"                     => 'string',
            "category"                      => 'string',
            "better_call_saul_appearance"   => 'array',
            "better_call_saul_appearance.*" => 'int',
        ]);

        if ($validator->fails()) {
            return false;
        }

        return true;
    }
}
