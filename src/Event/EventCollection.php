<?php
declare(strict_types=1);

namespace Eventful\Event;

class EventCollection implements \IteratorAggregate, \Countable
{
    protected $events;

    public function __construct(array $events = [])
    {
        $this->events = $events;
    }

    public function getIterator()
    {
        if ($this->events === null) {
            throw new \Exception('This calendar needs to be populated with events.');
        }
        return new \ArrayIterator($this->events);
    }

    public function count()
    {
        return count($this->events);
    }

}