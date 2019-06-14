<?php
declare(strict_types=1);

namespace Eventful\Recurrence\Type;

use Eventful\Event\Event;
use Eventful\Event\EventCollection;
use Eventful\Recurrence\RecurrenceInterface;

class Weekly implements RecurrenceInterface
{
    protected $start;
    protected $end;
    protected $interval = 1;

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

    public function generateOccurrences()
    {
        $events = array();

        while ($this->end >= $this->start) {
            $events[] = new Event($this->start, $this->start);
            if (intval($this->start->format('N')) === 7 && $this->interval > 1) {
                $this->start->modify('+' . $this->interval - 1 . ' week');
            }
            $this->start->modify('+1 day');
        }

        return new EventCollection($events);
    }
}