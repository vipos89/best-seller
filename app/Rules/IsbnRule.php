<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsbnRule implements ValidationRule
{
    private const string ISBN10_FORMAT_PATTERN = '/^\d{9}[\dX]$/';
    private const string ISBN13_FORMAT_PATTERN = '/^\d{13}$/';

    private const int ISBN10_LEN = 10;
    private const int ISBN13_LEN = 13;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = is_string($value) ? strtoupper(str_replace(['-', ' '], '', $value)) : '';

        if (!$this->isValidISBN10($value) && !$this->isValidISBN13($value)) {
            $fail('The :attribute is not a valid ISBN.');
        }
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isValidISBN10(string $value): bool
    {
        if (!$this->isValidLength($value, self::ISBN10_LEN)) {
            return false;
        }

        if (!$this->isCorrectFormat($value, self::ISBN10_FORMAT_PATTERN)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $digit = ($value[$i] === 'X') ? 10 : (int)$value[$i];
            $sum += $digit * ($i + 1);
        }

        return $sum % 11 === 0;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isValidISBN13(string $value): bool
    {
        if (!$this->isValidLength($value, self::ISBN13_LEN)) {
            return false;
        }

        if (!$this->isCorrectFormat($value, self::ISBN13_FORMAT_PATTERN)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $digit = (int)$value[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }

        return $sum % 10 === 0;
    }

    /**
     * @param string $value
     * @param int $length
     * @return bool
     */
    private function isValidLength(string $value, int $length): bool
    {
        return strlen($value) === $length;
    }

    private function isCorrectFormat(string $value, string $pattern): bool
    {
        return preg_match($pattern, $value) === 1;
    }
}
