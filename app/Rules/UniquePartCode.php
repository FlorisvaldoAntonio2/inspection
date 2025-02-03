<?php

namespace App\Rules;

use App\Models\Part;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePartCode implements ValidationRule
{
    protected int $inspectionId;

    public function __construct($inspectionId)
    {
        $this->inspectionId = $inspectionId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exist =Part::where('code', $value)
            ->where('inspection_id', $this->inspectionId)
            ->exists();

        if ($exist) {
            $fail("O código da peça deve ser único na inspeção.");
        }
    }
}
