<?php

namespace Atatusoft\Termutil\Events\Interfaces;

use Atatusoft\Termutil\Events\Event;

/**
 * Interface StaticObserverInterface. An interface for static observers.
 *
 * @package Atatusoft\Termutil\Events\Interfaces
 */
interface StaticObserverInterface
{
    /**
     * Handles the notification of an event. This method is called when an observable notifies its observers of an event.
     *
     * @param ObservableInterface $observable The observable that is notifying its observers.
     * @param Event $event The event that occurred. This event contains information about the event that occurred and any relevant data.
     */
    public static function onNotify(ObservableInterface $observable, Event $event): void;
}