<?php
/**
 * @author @fabfuel <fabian@fabfuel.de>
 * @created 14.11.14, 08:39 
 */
namespace Fabfuel\Prophiler\Plugin\Phalcon\Mvc;

use Fabfuel\Prophiler\Plugin\PluginAbstract;
use Phalcon\Events\Event;
use Phalcon\Mvc\DispatcherInterface;

/**
 * Class DispatcherPlugin
 * @package Rocket\Toolbar\Plugin
 */
class DispatcherPlugin extends PluginAbstract
{
    /**
     * @var string
     */
    protected $tokenDispatch;

    /**
     * @var string
     */
    protected $tokenRoute;

    /**
     * Start dispatch loop benchmark
     *
     * @param Event $event
     */
    public function beforeDispatchLoop(Event $event)
    {
        $name = get_class($event->getSource()) . '::dispatchLoop';
        $this->tokenDispatch = $this->getProfiler()->start($name, [], 'Dispatcher');
    }

    /**
     * Stop dispatch loop benchmark
     */
    public function afterDispatchLoop()
    {
        $this->getProfiler()->stop($this->tokenDispatch);
    }

    /**
     * Start execute route benchmark
     *
     * @param Event $event
     * @param DispatcherInterface $dispatcher
     */
    public function beforeExecuteRoute(Event $event, DispatcherInterface $dispatcher)
    {
        $name = get_class($event->getSource()) . '::executeRoute';
        $metadata = [
            'class' => get_class($dispatcher->getActiveController()),
            'controller' => $dispatcher->getControllerName(),
            'action' => $dispatcher->getActionName(),
            'params' => $dispatcher->getParams(),
        ];

        $this->tokenRoute = $this->getProfiler()->start($name, $metadata, 'Dispatcher');
    }

    /**
     * Stop dispatch loop benchmark
     */
    public function afterExecuteRoute()
    {
        $this->getProfiler()->stop($this->tokenRoute);
    }
}
