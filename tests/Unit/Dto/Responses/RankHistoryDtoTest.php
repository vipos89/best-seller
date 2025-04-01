<?php

declare(strict_types=1);

namespace tests\Unit\Dto\Responses;

use App\Services\BookService\Dto\V3\Responses\RankHistoryDto;
use PHPUnit\Framework\TestCase;
class RankHistoryDtoTest extends TestCase
{
    private array $sampleData;

    protected function setUp(): void
    {
        $this->sampleData = [
            'primary_isbn10' => '0123456789',
            'primary_isbn13' => '978-0123456789',
            'rank' => 5,
            'list_name' => 'hardcover-fiction',
            'display_name' => 'Hardcover Fiction',
            'published_date' => '2023-01-01',
            'bestsellers_date' => '2023-01-08',
            'weeks_on_list' => 10,
            'ranks_last_week' => 7,
            'asterisk' => 0,
            'dagger' => 0,
        ];
    }

    public function test_constructor_and_properties()
    {
        $dto = new RankHistoryDto(
            '0123456789',
            '978-0123456789',
            5,
            'hardcover-fiction',
            'Hardcover Fiction',
            '2023-01-01',
            '2023-01-08',
            10,
            7,
            0,
            0
        );

        $this->assertEquals('0123456789', $dto->primary_isbn10);
        $this->assertEquals('978-0123456789', $dto->primary_isbn13);
        $this->assertEquals(5, $dto->rank);
        $this->assertEquals('hardcover-fiction', $dto->list_name);
        $this->assertEquals('Hardcover Fiction', $dto->display_name);
        $this->assertEquals('2023-01-01', $dto->published_date);
        $this->assertEquals('2023-01-08', $dto->bestsellers_date);
        $this->assertEquals(10, $dto->weeks_on_list);
        $this->assertEquals(7, $dto->ranks_last_week);
        $this->assertEquals(0, $dto->asterisk);
        $this->assertEquals(0, $dto->dagger);
    }

    public function test_from_array_creation()
    {
        $dto = RankHistoryDto::fromArray($this->sampleData);

        $this->assertInstanceOf(RankHistoryDto::class, $dto);
        $this->assertEquals($this->sampleData['primary_isbn10'], $dto->primary_isbn10);
        $this->assertEquals($this->sampleData['primary_isbn13'], $dto->primary_isbn13);
        $this->assertEquals($this->sampleData['rank'], $dto->rank);
        $this->assertEquals($this->sampleData['list_name'], $dto->list_name);
        $this->assertEquals($this->sampleData['display_name'], $dto->display_name);
        $this->assertEquals($this->sampleData['published_date'], $dto->published_date);
        $this->assertEquals($this->sampleData['bestsellers_date'], $dto->bestsellers_date);
        $this->assertEquals($this->sampleData['weeks_on_list'], $dto->weeks_on_list);
        $this->assertEquals($this->sampleData['ranks_last_week'], $dto->ranks_last_week);
        $this->assertEquals($this->sampleData['asterisk'], $dto->asterisk);
        $this->assertEquals($this->sampleData['dagger'], $dto->dagger);
    }

    public function test_nullable_ranks_last_week()
    {
        $data = $this->sampleData;
        unset($data['ranks_last_week']);

        $dto = RankHistoryDto::fromArray($data);

        $this->assertNull($dto->ranks_last_week);
    }

    public function test_missing_required_fields_throws_error()
    {
        $this->expectException(\Error::class);

        $invalidData = $this->sampleData;
        unset($invalidData['primary_isbn10']);

        RankHistoryDto::fromArray($invalidData);
    }

    public function test_readonly_properties()
    {
        $dto = RankHistoryDto::fromArray($this->sampleData);

        $this->expectException(\Error::class);
        $dto->rank = 10;
    }

    public function test_type_safety()
    {
        $this->expectException(\TypeError::class);

        $invalidData = $this->sampleData;
        $invalidData['rank'] = 'not-an-integer'; // Неправильный тип для rank

        RankHistoryDto::fromArray($invalidData);
    }

    public function test_with_minimal_data()
    {
        $minimalData = [
            'primary_isbn10' => '1234567890',
            'primary_isbn13' => '978-1234567890',
            'rank' => 1,
            'list_name' => 'test',
            'display_name' => 'Test',
            'published_date' => '2023-01-01',
            'bestsellers_date' => '2023-01-08',
            'weeks_on_list' => 1,
            'asterisk' => 0,
            'dagger' => 0,
        ];

        $dto = RankHistoryDto::fromArray($minimalData);

        $this->assertInstanceOf(RankHistoryDto::class, $dto);
        $this->assertNull($dto->ranks_last_week);
    }
}