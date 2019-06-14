<?php
declare(strict_types=1);

namespace Tests;

use Eventful\Exceptions\MonthOfYearOutOfBounds;
use Eventful\Recurrence\Type\Yearly;

class YearlyTest extends AbstractMonthYearTestCases
{
    protected $recurrenceType;

    public function setUp(): void
    {
        parent::setUp();

        $start = new \DateTime("2019-01-01 00:00:00");
        $end = new \DateTime("2019-12-31 00:00:00");
        $yearly = new Yearly($start, $end);
        $this->recurrenceType = $yearly;
    }

    /**
     * @test
     */
    public function generate_function_returns_collection_of_events()
    {
        $occurrences = $this->recurrenceType->generateOccurrences();
        $this->assertInstanceOf(\Eventful\Event\EventCollection::class, $occurrences);
    }

    /**
     * @test
     */
    public function setting_valid_numeric_month_of_year_finds_the_correct_month_of_year_word()
    {
        $this->recurrenceType->setMonthOfYear(2);
        $this->assertEquals('february', $this->recurrenceType->getMonthOfYear());
        $this->recurrenceType->setMonthOfYear(5);
        $this->assertEquals('may', $this->recurrenceType->getMonthOfYear());
    }

    /**
     * @test
     */
    public function setting_invalid_numeric_month_of_year_throws_exception()
    {
        $this->expectException(MonthOfYearOutOfBounds::class);
        $this->recurrenceType->setMonthOfYear(13);
    }

    /**
     * @test
     */
    public function setting_valid_string_month_of_year_finds_the_correct_month_of_year_word()
    {
        $this->recurrenceType->setMonthOfYear('march');
        $this->assertEquals('march', $this->recurrenceType->getMonthOfYear());
    }

    /**
     * @test
     */
    public function setting_lowercase_month_of_year_finds_the_correct_month_of_year_word()
    {
        $this->recurrenceType->setMonthOfYear('APRIL');
        $this->assertEquals('april', $this->recurrenceType->getMonthOfYear());
    }

    /**
     * @test
     */
    public function setting_invalid_string_month_of_year_throws_exception()
    {
        $this->expectException(MonthOfYearOutOfBounds::class);
        $this->recurrenceType->setMonthOfYear("ARPIL");
    }

    /**
     * @test
     */
    public function changing_the_interval_returns_correct_number_of_events_for_day_in_month()
    {
        $end = new \DateTime("2022-12-31 00:00:00");
        $this->recurrenceType->setEnd($end);
        $this->recurrenceType->setInterval(2);
        $occurrences = $this->recurrenceType->generateOccurrences();
        $this->assertCount(2, $occurrences);
    }
}