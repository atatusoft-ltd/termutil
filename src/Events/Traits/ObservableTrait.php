<?php

namespace Atatusoft\Termutil\Events\Traits;

use Assegai\Collections\ItemList;
use Atatusoft\Termutil\Events\Event;
use Atatusoft\Termutil\Events\Interfaces\ObservableInterface;
use Atatusoft\Termutil\Events\Interfaces\ObserverInterface;
use Atatusoft\Termutil\Events\Interfaces\StaticObserverInterface;

/**
 * The trait ObservableTrait. It provides the basic functionality for an observable, such as adding and removing
 * observers and notifying them of events.
 *
 * @package Atatusoft\Termutil\Events\Traits
 */
trait ObservableTrait
{
    /**
     * @var ItemList<ObservableInterface> The observers.
     */
    protected ItemList $observers;
    /**
     * @var ItemList<StaticObserverInterface> The static observers.
     */
    protected ItemList $staticObservers;

    protected function initializeObservers(): void
    {
        /** @var ItemList<ObserverInterface> $observers */
        $observers = new ItemList(ObserverInterface::class);
        $this->observers = $observers;

        /** @var ItemList<StaticObserverInterface> $staticObservers */
        $staticObservers = new ItemList(StaticObserverInterface::class);
        $this->staticObservers = $staticObservers;
    }
    /**
     * Adds observers to this observable.
     *
     * @param ObservableInterface|StaticObserverInterface|class-string ...$observers The observers to add.
     * @return void
     */
    public function addObservers(ObservableInterface|StaticObserverInterface|string ...$observers): void
    {
        foreach ($observers as $observer) {
            if ($observer instanceof ObserverInterface) {
                $this->observers->add($observer);
            } elseif ($observer instanceof StaticObserverInterface) {
                $this->staticObservers->add($observer);
            } elseif (is_string($observer)) {
                if (is_subclass_of($observer, ObservableInterface::class)) {
                    $this->observers->add($observer);
                } elseif (is_subclass_of($observer, StaticObserverInterface::class)) {
                    $this->staticObservers->add($observer);
                }
            }
        }
    }

    /**
     * Removes observers from this observable.
     *
     * @param ObservableInterface|StaticObserverInterface|class-string ...$observers The observers to remove.
     * @return void
     */
    public function removeObservers(ObservableInterface|StaticObserverInterface|string ...$observers): void
    {
        foreach ($observers as $observer) {
            if ($observer instanceof ObserverInterface) {
                $this->observers->remove($observer);
            } elseif ($observer instanceof StaticObserverInterface) {
                $this->staticObservers->remove($observer);
            } elseif (is_string($observer)) {
                if (is_subclass_of($observer, ObservableInterface::class)) {
                    $this->observers->remove($observer);
                } elseif (is_subclass_of($observer, StaticObserverInterface::class)) {
                    $this->staticObservers->remove($observer);
                }
            }
        }
    }

    /**
     * Notifies the observers of this observable of an event.
     *
     * @param Event $event The event to notify the observers of.
     * @return void
     */
    public function notify(Event $event): void
    {
        foreach ($this->observers as $observer) {
            $observer->onNotify($this, $event);
        }

        foreach ($this->staticObservers as $staticObserver) {
            $staticObserver::onNotify($this, $event);
        }
    }
}