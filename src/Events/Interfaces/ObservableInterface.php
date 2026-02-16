<?php

namespace Atatusoft\Termutil\Events\Interfaces;

use Atatusoft\Termutil\Events\Event;

/**
 * Interface ObservableInterface. Represents an observable object that can be observed by observers.
 *
 * @package Atatusoft\Termutil\Events\Interfaces
 */
interface ObservableInterface
{
    /**
     * Adds observers to this observable.
     *
     * @param ObservableInterface|StaticObserverInterface|class-string ...$observers The observers to add.
     * @return void
     */
    public function addObservers(ObservableInterface|StaticObserverInterface|string ...$observers): void;

    /**
     * Removes observers from this observable.
     *
     * @param ObservableInterface|StaticObserverInterface|class-string ...$observers The observers to remove.
     * @return void
     */
    public function removeObservers(ObservableInterface|StaticObserverInterface|string ...$observers): void;

    /**
     * Notifies the observers of this observable of an event.
     *
     * @param Event $event The event to notify the observers of.
     * @return void
     */
    public function notify(Event $event): void;
}