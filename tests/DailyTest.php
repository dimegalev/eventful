<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Eventful\Recurrence\Type\Daily;

class DailyTest extends TestCase
{
    protected $recurrenceType;

    public function setUp(): void
    {
        parent::setUp();

        $start = new \DateTime("2019-07-01 00:00:00");
        $end = new \DateTime("2019-07-31 00:00:00");
        $daily = new Daily($start, $end);
        $this->recurrenceType = $daily;
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
    public function changing_the_interval_returns_correct_number_of_events()
    {
        $this->recurrenceType->setInterval(5);
        $occurrences = $this->recurrenceType->generateOccurrences();
        $this->assertCount(7, $occurrences);
    }
}