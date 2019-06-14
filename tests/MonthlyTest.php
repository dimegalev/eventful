<?php
declare(strict_types=1);

namespace Tests;

use Eventful\Recurrence\Type\Monthly;

class MonthlyTest extends AbstractMonthYearTestCases
{
    protected $recurrenceType;

    public function setUp(): void
    {
        parent::setUp();

        $start = new \DateTime("2019-07-01 00:00:00");
        $end = new \DateTime("2019-07-31 00:00:00");
        $monthly = new Monthly($start, $end);
        $this->recurrenceType = $monthly;
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
    public function setting_week_of_month_returns_as_many_events_as_days_are_set()
    {
        $this->recurrenceType->setWeekOfMonth('second');
        $occurrences = $this->recurrenceType->generateOccurrences();
        $this->assertCount(count($this->recurrenceType->getDays()), $occurrences);
    }

    /**
     * @test
     */
    public function changing_the_interval_returns_correct_number_of_events_for_day_in_month()
    {
        $end = new \DateTime("2019-11-30 00:00:00");
        $this->recurrenceType->setEnd($end);
        $this->recurrenceType->setInterval(2);
        $occurrences = $this->recurrenceType->generateOccurrences();
        $this->assertCount(3, $occurrences);
    }

    /**
     * @test
     */
    public function changing_the_interval_returns_correct_number_of_events_for_week_in_month()
    {
        $end = new \DateTime("2019-11-30 00:00:00");
        $this->recurrenceType->setEnd($end);
        $this->recurrenceType->setWeekOfMonth('second');
        $occurrences = $this->recurrenceType->generateOccurrences();
        $this->assertCount(35, $occurrences);
    }
}