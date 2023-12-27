<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class AnyOfRule implements ValidationRule
{
    protected array $rules = [];
    protected string $message = 'The :attribute field has unsupported format.';

    public function __construct(mixed ...$rules)
    {
        $this->rules = $rules;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $data = ['value' => $value];
        $isFails = true;

        foreach ($this->rules as $r) {
            $isFails &= Validator::make($data, ['value' => $r])->fails();
        }

        if ($isFails) {
            $fail($this->message);
        }
    }
}
