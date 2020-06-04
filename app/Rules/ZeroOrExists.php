<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule as ValidationRule;

class ZeroOrExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table, string $idField = '')
    {
        $this->table = $table;
        $this->idField = $idField;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $field = (strlen($this->idField) > 0) ? $this->idField : $attribute;
        $this->attribute = $attribute;
        return intval($value) === $value && (
            $value == 0 ||
            ValidationRule::exists($this->table)->where(function($query) use ($field, $value) {
                $query->where($field, $value);
            })
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->attribute.' is invalid or record does not exists on table';
    }
}
