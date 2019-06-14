<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Eventful\Exceptions\DayOfMonthOutOfBounds;
use Eventful\Exceptions\WeekOfMonthOutOfBounds;

abstract class AbstractMonthYearTestCases extends TestCase
{
    protected $recurrenceType;

    /**
     * @test
     */
    public function setting_valid_numeric_week_of_month_finds_the_correct_week_of_month_word()
    {
        $this->recurrenceType->setWeekOfMonth(2);
        $this->assertEquals('SECOND', $this->recurrenceType->getWeekOfMonth());
        $this->recurrenceType->setWeekOfMonth(5);
        $this->assertEquals('LAST', $this->recurrenceType->getWeekOfMonth());
    }

    /**
     * @test
     */
    public function setting_invalid_numeric_week_of_month_throws_exception()
    {
        $this->expectException(WeekOfMonthOutOfBounds::class);
        $this->recurrenceType->setWeekOfMonth(6);
    }

    /**
     * @test
     */
    public function setting_valid_string_week_of_month_finds_the_correct_week_of_month_word()
    {
        $this->recurrenceType->setWeekOfMonth('THIRD');
        $this->assertEquals('THIRD', $this->recurrenceType->getWeekOfMonth());
    }

    /**
     * @test
     */
    public function setting_lowercase_week_of_month_finds_the_correct_week_of_month_word()
    {
        $this->recurrenceType->setWeekOfMonth('second');
        $this->assertEquals('SECOND', $this->recurrenceType->getWeekOfMonth());
    }

    /**
     * @test
     */
    public function setting_invalid_string_week_of_month_throws_exception()
    {
        $this->expectException(WeekOfMonthOutOfBounds::class);
        $this->recurrenceType->setWeekOfMonth("FIFTH");
    }

    /**
     * @test
     */
    public function setting_week_of_month_overrides_day_of_month()
    {
        $this->recurrenceType->setDayOfMonth(11);
        $this->recurrenceType->setWeekOfMonth('second');
        $this->assertNull($this->recurrenceType->getDayOfMonth());
    }

    /**
     * @test
     */
    public function setting_invalid_day_of_month_throws_exception()
    {
        $this->expectException(DayOfMonthOutOfBounds::class);
        $this->recurrenceType->setDayOfMonth(32);
    }

    /**
     * @test
     */
    public function setting_day_of_month_overrides_week_of_month()
    {
        $this->recurrenceType->setWeekOfMonth('second');
        $this->recurrenceType->setDayOfMonth(11);
        $this->assertNull($this->recurrenceType->getWeekOfMonth());
    }
}