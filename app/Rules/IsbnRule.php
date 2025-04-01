<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsbnRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = is_string($value) ? strtoupper(str_replace(['-', ' '], '', $value)) : '';

        if (!$this->isValidISBN10($value) && !$this->isValidISBN13($value)) {
            $fail('The :attribute is not a valid ISBN.');
        }
    }

    private function isValidISBN10(string $value): bool
    {
        if (!$this->isValidLength($value, 10)) {
            return false;
        }

        if (!$this->isCorrectFormat($value, '/^\d{9}[\dX]$/')) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $digit = ($value[$i] === 'X') ? 10 : (int)$value[$i];
            $sum += $digit * ($i + 1);
        }

        return $sum % 11 === 0;
    }

    private function isValidISBN13(string $value): bool
    {
        if (!$this->isValidLength($value, 13)) {
            return false;
        }

        if (!$this->isCorrectFormat($value, '/^\d{13}$/')) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $digit = (int)$value[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }

        return $sum % 10 === 0;
    }

    private function isValidLength(string $value, int $length): bool
    {
        return strlen($value) === $length;
    }

    private function isCorrectFormat(string $value, string $pattern): bool
    {
        return preg_match($pattern, $value) === 1;
    }
}