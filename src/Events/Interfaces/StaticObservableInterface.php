<?php

namespace Atatusoft\Termutil\Events\Interfaces;

use Atatusoft\Termutil\Events\Event;

interface StaticObservableInterface
{
    /**
     * Adds observers to this observable.
     *
     * @param ObserverInterface|StaticObserverInterface|class-string ...$observers The observers to add.
     * @return void
     */
    public static function addObservers(ObserverInterface|StaticObserverInterface|string ...$observers): void;

    /**
     * Removes observers from this observable.
     *
     * @param ObserverInterface|StaticObserverInterface|class-string ...$observers The observers to remove.
     * @return void
     */
    public static function removeObservers(ObserverInterface|StaticObserverInterface|string ...$observers): void;

    /**
     * Notifies the observers of this observable of an event.
     *
     * @param Event $event The event to notify the observers of.
     * @return void
     */
    public static function notify(Event $event): void;
}