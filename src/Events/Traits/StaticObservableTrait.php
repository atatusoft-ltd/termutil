<?php

namespace Atatusoft\Termutil\Events\Traits;

use Assegai\Collections\ItemList;
use Atatusoft\Termutil\Events\Event;
use Atatusoft\Termutil\Events\Interfaces\ObservableInterface;
use Atatusoft\Termutil\Events\Interfaces\ObserverInterface;
use Atatusoft\Termutil\Events\Interfaces\StaticObserverInterface;

/**
 * The trait StaticObservableTrait. It provides the basic functionality for a static observable, such as adding and
 * removing observers and notifying them of events.
 *
 * @package Atatusoft\Termutil\Events\Traits;
 */
trait StaticObservableTrait
{
    /**
     * @var ItemList<ObservableInterface> The observers.
     */
    protected static ItemList $observers;
    /**
     * @var ItemList<StaticObserverInterface> The static observers.
     */
    protected static ItemList $staticObservers;

    protected function initializeObservers(): void
    {
        /** @var ItemList<ObserverInterface> $observers */
        $observers = new ItemList(ObserverInterface::class);
        self::$observers = $observers;

        /** @var ItemList<StaticObserverInterface> $staticObservers */
        $staticObservers = new ItemList(StaticObserverInterface::class);
        self::$staticObservers = $staticObservers;
    }
    /**
     * Adds observers to this observable.
     *
     * @param ObservableInterface|StaticObserverInterface ...$observers The observers to add.
     * @return void
     */
    public static function addObservers(ObservableInterface|StaticObserverInterface ...$observers): void
    {
        foreach ($observers as $observer) {
            if ($observer instanceof ObserverInterface) {
                self::$observers->add($observer);
            } elseif ($observer instanceof StaticObserverInterface) {
                self::$staticObservers->add($observer);
            } elseif (is_string($observer)) {
                if (is_subclass_of($observer, ObservableInterface::class)) {
                    self::$observers->add($observer);
                } elseif (is_subclass_of($observer, StaticObserverInterface::class)) {
                    self::$staticObservers->add($observer);
                }
            }
        }
    }

    /**
     * Removes observers from this observable.
     *
     * @param ObservableInterface|StaticObserverInterface ...$observers The observers to remove.
     * @return void
     */
    public static function removeObservers(ObservableInterface|StaticObserverInterface ...$observers): void
    {
        foreach ($observers as $observer) {
            if ($observer instanceof ObserverInterface) {
                self::$observers->remove($observer);
            } elseif ($observer instanceof StaticObserverInterface) {
                self::$staticObservers->remove($observer);
            } elseif (is_string($observer)) {
                if (is_subclass_of($observer, ObservableInterface::class)) {
                    self::$observers->remove($observer);
                } elseif (is_subclass_of($observer, StaticObserverInterface::class)) {
                    self::$staticObservers->remove($observer);
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
    public static function notify(Event $event): void
    {
        foreach (self::$observers as $observer) {
            $observer->onNotify(null, $event);
        }

        foreach (self::$staticObservers as $staticObserver) {
            $staticObserver::onNotify(null, $event);
        }
    }
}