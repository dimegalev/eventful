<?php
declare(strict_types=1);

namespace Eventful\Recurrence\Type;

use Eventful\Event\Event;
use Eventful\Event\EventCollection;
use Eventful\Exceptions\DayOfMonthOutOfBounds;
use Eventful\Recurrence\RecurrenceInterface;
use Eventful\Exceptions\WeekOfMonthOutOfBounds;

class Monthly implements RecurrenceInterface
{
    protected $start;

    protected $end;

    protected $interval = 1;

    protected $dayOfMonth = 1;

    protected $weekOfMonth;

    private $monthWeeks = [
        'FIRST' => 1,
        'SECOND' => 2,
        'THIRD' => 3,
        'FOURTH' => 4,
        'LAST' => 5
    ];

    protected $days = array(
        'monday' => 1,
        'tuesday' => 2,
        'wednesday' => 3,
        'thursday' => 4,
        'friday' => 5,
        'saturday' => 6,
        'sunday' => 7
    );

    public function __construct(\DateTime $start, \DateTime $end)
    {
        $this->setStart($start);
        $this->setEnd($end);
    }

    public function setStart(\DateTime $start) : self
    {
        $this->start = $start;
        return $this;
    }

    public function getStart() : \DateTime
    {
        return $this->start;
    }

    public function setEnd(\DateTime $end) : self
    {
        $this->end = $end;
        return $this;
    }

    public function getEnd() : \DateTime
    {
        return $this->end;
    }

    public function setInterval(int $interval) : self
    {
        $this->interval = $interval;
        return $this;
    }

    public function getInterval() : int
    {
        return $this->interval;
    }

    public function setWeekOfMonth($weekOfMonth) : self
    {
        if (is_numeric($weekOfMonth)) {
            if ($weekOfMonth = array_search($weekOfMonth, $this->monthWeeks)) {
                $this->weekOfMonth = $weekOfMonth;
            } else {
                throw new WeekOfMonthOutOfBounds("Week of month must be between 1 to 5 when setting it as numeric.");
            }
        } else {
            if (key_exists(strtoupper($weekOfMonth), $this->monthWeeks)) {
                $this->weekOfMonth = strtoupper($weekOfMonth);
            } else {
                throw new WeekOfMonthOutOfBounds("Week of month must be (case insensitive) FIRST, SECOND, THIRD, FORTH or LAST.");
            }
        }
        $this->dayOfMonth = null;
        return $this;
    }

    public function getWeekOfMonth()
    {
        return $this->weekOfMonth;
    }

    public function setDayOfMonth(int $dayOfMonth) : self
    {
        if ($dayOfMonth < 1 || $dayOfMonth > 31) {
            throw new DayOfMonthOutOfBounds();
        }
        $this->dayOfMonth = $dayOfMonth;
        $this->weekOfMonth = null;
        return $this;
    }

    public function getDayOfMonth()
    {
        return $this->dayOfMonth;
    }

    public function setDays(array $days) : self
    {
        return $this;
    }

    public function getDays() : array
    {
        return $this->days;
    }

    public function generateOccurrences()
    {
        $events = array();

        if (!is_null($this->dayOfMonth)) {
            $this->start->setDate(intval($this->start->format('Y')), intval($this->start->format('m')), $this->dayOfMonth);
            while ($this->end >= $this->start) {
                $events[] = new Event($this->start, $this->start);
                $this->start->modify("+$this->interval month");
            }
        } else {
            while ($this->end >= $this->start) {
                foreach ($this->days as $day => $dayNumber) {
                    $this->start->modify("$this->weekOfMonth $day of {$this->start->format('F')} {$this->start->format('Y')}");

                    if ($this->end >= $this->start) {
                        $events[] = new Event($this->start, $this->start);
                    }
                }
                $this->start->modify("+$this->interval month");
            }
        }

        return new EventCollection($events);
    }

}