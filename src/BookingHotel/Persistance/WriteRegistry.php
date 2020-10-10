<?php

declare(strict_types=1);

namespace Katas\BookingHotel\Persistance;


use Katas\BookingHotel\Domain\DomainEvent;

class WriteRegistry implements Observable
{
    /** @var array|DomainEvent[classname] */
    private array $events;
    /** @var array|Observer[] */
    private array $observers;
    private EventProjectionMapper $projectionMapper;

    public function __construct(EventProjectionMapper $projectionMapper)
    {
        $this->events = [];
        $this->projectionMapper = $projectionMapper;
    }

    public function add(DomainEvent $event)
    {
        $this->events[get_class($event)][] = $event;
        $projection = $this->projectionMapper->map($event);
        $this->notifyObservers($projection);
    }

    private function notifyObservers(Projection $projection)
    {
        // TODO decouple by sending serialized events on an EventBus
        foreach ($this->observers as $observer) {
            if (!$observer->supports($projection)) {
                continue;
            }
            $observer->update($projection);
        }
    }

    public function attach(Observer $observer): void
    {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer): void
    {
        $key = array_search($observer, $this->observers, true);
        if($key){
            unset($this->observers[$key]);
        }
    }
}
