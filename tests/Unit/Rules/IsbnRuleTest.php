<?php

namespace tests\Unit\Rules;

use App\Rules\IsbnRule;
use PHPUnit\Framework\TestCase;
class IsbnRuleTest extends TestCase
{
    private IsbnRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new IsbnRule();
    }

    public function test_it_validates_valid_isbn10(): void
    {
        $validIsbns = [
            '0306406152', // Valid ISBN-10
            '0553418025',
            '0123456789',
            '123456789X', // With X as check digit
        ];

        foreach ($validIsbns as $isbn) {
            $failed = false;
            $fail = function () use (&$failed) {
                $failed = true;
            };

            $this->rule->validate('isbn', $isbn, $fail);
            $this->assertFalse($failed, "Failed to validate valid ISBN-10: {$isbn}");
        }
    }

    public function test_rejects_invalid_isbn10(): void
    {
        $invalidIsbns = [
            '0306406153', // Wrong check digit
            '1234567890',
            'ABCDEFGHIJ', // Non-numeric
            '123456789',  // Too short
            '12345678901', // Too long
            '12345678X', // X in wrong position
        ];

        foreach ($invalidIsbns as $isbn) {
            $failed = false;
            $fail = function () use (&$failed) {
                $failed = true;
            };

            $this->rule->validate('isbn', $isbn, $fail);
            $this->assertTrue($failed, "Failed to reject invalid ISBN-10: {$isbn}");
        }
    }

    public function test_it_validates_valid_isbn13(): void
    {
        $validIsbns = [
            '9780306406157', // Valid ISBN-13
            '9783161484100', // Valid ISBN-13
            '9780451524935', // Valid ISBN-13
            '9780000000002', // Edge case
        ];

        foreach ($validIsbns as $isbn) {
            $failed = false;
            $fail = function () use (&$failed) {
                $failed = true;
            };

            $this->rule->validate('isbn', $isbn, $fail);
            $this->assertFalse($failed, "Failed to validate valid ISBN-13: {$isbn}");
        }

        foreach ($validIsbns as $isbn) {
            $failed = false;
            $fail = function () use (&$failed) {
                $failed = true;
            };

            $this->rule->validate('isbn', $isbn, $fail);
            $this->assertFalse($failed, "Failed to validate valid ISBN-13: {$isbn}");
        }
    }

    public function test_it_rejects_invalid_isbn13(): void
    {
        $invalidIsbns = [
            '9780306406158', // Wrong check digit
            '1234567890123',
            '978ABCDEFGHIJ', // Non-numeric
            '978123456789',  // Too short
            '97812345678901', // Too long
        ];

        foreach ($invalidIsbns as $isbn) {
            $failed = false;
            $fail = function () use (&$failed) {
                $failed = true;
            };

            $this->rule->validate('isbn', $isbn, $fail);
            $this->assertTrue($failed, "Failed to reject invalid ISBN-13: {$isbn}");
        }
    }

    public function test_it_accepts_either_isbn10_or_isbn13(): void
    {
        $validCases = [
            '0306406152', // ISBN-10
            '9780306406157', // ISBN-13
        ];

        foreach ($validCases as $isbn) {
            $failed = false;
            $fail = function () use (&$failed) {
                $failed = true;
            };

            $this->rule->validate('isbn', $isbn, $fail);
            $this->assertFalse($failed, "Failed to accept valid ISBN: {$isbn}");
        }
    }

    public function test_it_rejects_non_isbn_values(): void
    {
        $invalidCases = [
            '',
            'invalid',
            '12345',
            "0306406153",
            "978ABCDEFGHIJ",
            "9781234567890123"
        ];

        foreach ($invalidCases as $isbn) {
            $failed = false;
            $fail = function () use (&$failed) {
                $failed = true;
            };

            $this->rule->validate('isbn', $isbn, $fail);
            $this->assertTrue($failed, "Failed to reject non-ISBN value: {$isbn}");
        }
    }

    public function test_it_handles_non_string_values(): void
    {
        $invalidCases = [
            null,
            1234567890,
            9780306406157,
            true,
            ['0306406152'],
        ];

        foreach ($invalidCases as $isbn) {
            $failed = false;
            $fail = function () use (&$failed) {
                $failed = true;
            };

            $this->rule->validate('isbn', $isbn, $fail);
            $this->assertTrue($failed, "Failed to reject non-string value");
        }
    }
}
