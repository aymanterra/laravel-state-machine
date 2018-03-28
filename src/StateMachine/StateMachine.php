<?php

/*
 * This file is part of the StateMachine package.
 *
 * (c) Alexandre Bacco
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace troojaan\SM\StateMachine;

use SM\Callback\CallbackFactory;
use SM\Callback\CallbackFactoryInterface;
use SM\Callback\CallbackInterface;
use SM\Event\SMEvents;
use SM\Event\TransitionEvent;
use SM\SMException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class StateMachine extends SM\StateMachine\StateMachine
{
    /**
     * {@inheritDoc}
     */
    public function can($transition)
    {
        if (!isset($this->config['transitions'][$transition])) {
            throw new SMException(sprintf(
                'Transition "%s" does not exist on object "%s" with graph "%s"',
                $transition,
                get_class($this->object),
                $this->config['graph']
            ));
        }

        $availabilities = [];

        foreach ($this->getState() as $state) {
            if (!in_array($state, $this->config['transitions'][$transition]['from'])) {
                array_push($availabilities, false);
            } else {
                $can = true;
                $event = new TransitionEvent($transition, $this->getState(), $this->config['transitions'][$transition], $this);
                if (null !== $this->dispatcher) {
                    $this->dispatcher->dispatch(SMEvents::TEST_TRANSITION, $event);

                    $can = !$event->isRejected();
                }

                array_push($availabilities, $can && $this->callCallbacks($event, 'guard'));
            }
        }

        if (isset($this->config['transitions'][$transition]['dependent']) && $this->config['transitions'][$transition]['dependent']) {
            if (in_array(false, $availabilities)) {
                return false;
            }
            return true;
        }

        if (in_array(true, $availabilities)) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function apply($transition, $soft = false)
    {
        if (!$this->can($transition)) {
            if ($soft) {
                return false;
            }

            throw new SMException(sprintf(
                'Transition "%s" cannot be applied on state "%s" of object "%s" with graph "%s"',
                $transition,
                $this->getState(),
                get_class($this->object),
                $this->config['graph']
            ));
        }

        $event = new TransitionEvent($transition, $this->getState(), $this->config['transitions'][$transition], $this);

        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch(SMEvents::PRE_TRANSITION, $event);

            if ($event->isRejected()) {
                return false;
            }
        }

        $this->callCallbacks($event, 'before');

        $this->setState($this->config['transitions'][$transition]['to'], $this->config['transitions'][$transition]['from'], $transition);

        $this->callCallbacks($event, 'after');

        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch(SMEvents::POST_TRANSITION, $event);
        }

        return true;
    }

    /**
     * Set a new state to the underlying object
     *
     * @param string $state
     *
     * @throws SMException
     */
    protected function setState($toStates, $fromStates, $transition)
    {
        foreach ($toStates as $toState) {
            if (!in_array($toState, $this->config['states'])) {
                throw new SMException(sprintf(
                    'Cannot set the state to "%s" to object "%s" with graph %s because it is not pre-defined.',
                    $toState,
                    get_class($this->object),
                    $this->config['graph']
                ));
            }
        }
        // the final new states that need to be saved in DB
        $newStates = [];

        // the current states which returns from DB
        $currentStates = $this->object[$this->config['property_path']];

        // removes the current transition  states-from from current states (not all current states)
        $newStates = array_diff($currentStates, $fromStates);

        // add the current transition states-to to current states
        $newStates = array_merge($newStates, $toStates);

        // set the new states
        $accessor = new PropertyAccessor();
        $accessor->setValue($this->object, $this->config['property_path'], $newStates);
    }
}
