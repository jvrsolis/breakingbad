<?php

namespace BreakingBad\Data\Models\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Trait IsValidatable
 *
 * @package BreakingBad\Data\Models\Traits
 */
trait IsValidatable
{
    /**
     * Error message bag
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Determine if the model should validate on save
     *
     * @var array
     */
    public $validate = true;

    /**
     * Validator instance
     *
     * @var \Illuminate\Validation\Validators
     */
    protected static $validator;

    public static function validator()
    {
        if (!self::$validator) {
            static::$validator = App::make('validator');
        }
        return static::$validator;
    }

    /**
     * Return the default rules.
     *
     * @return array
     */
    public static function rules(): array
    {
        return [];
    }

    /**
     * Return the default messages
     *
     * @return array
     */
    public static function messages(): array
    {
        return [];
    }

    /**
     * Validates current attributes against rules
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(bool $strict = true)
    {
        $v = self::validator()->make($this->attributes, static::rules(), static::messages());

        if ($v->passes()) {
            return true;
        }

        $this->setErrors($v->messages());

        foreach ($this->errors as $field => $fieldErrors) {
            foreach ($fieldErrors as $error) {
                Log::error($error);
            }
        }

        if ($strict === true) {
            throw new ValidationException($v);
        }

        return false;
    }

    /**
     * Set error message bag
     *
     * @var Illuminate\Support\MessageBag
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Retrieve error message bag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Inverse of wasSaved
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function setValidateAttribute($value)
    {
        $this->validate = $value;
    }
}
