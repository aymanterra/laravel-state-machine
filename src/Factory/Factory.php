<?php

/*
 * This file is part of the StateMachine package.
 *
 * (c) Alexandre Bacco
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace troojaan\SM\Factory;

use SM\Callback\CallbackFactoryInterface;
use SM\SMException;
use SM\Factory\AbstractFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Factory extends SM\Factory\Factory
{
    /**
     * {@inheritDoc}
     */
    protected function createStateMachine($object, array $config)
    {
        if (!isset($config['state_machine_class'])) {
            $class = 'troojaan\\SM\\StateMachine\\StateMachine';
        } elseif (class_exists($config['state_machine_class'])) {
            $class = $config['state_machine_class'];
        } else {
            throw new SMException(sprintf(
               'Class "%s" for creating a new state machine does not exist.',
                $config['state_machine_class']
            ));
        }

        return new $class($object, $config, $this->dispatcher, $this->callbackFactory);
    }
}
