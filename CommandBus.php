<?php

namespace LwModel;

class CommandBus
{
    private $handlers;
    
    public function __construct()
    {
        $this->config = \lw_registry::getInstance()->getEntry("config");
    }
    
    public function register($commandClassName, $handler)
    {
        $this->handlers[$commandClassName] = $handler;
    }

    public function handle($command)
    {
        $handler = $this->handlers[$command->getCommandName()];
        $handler->handle($command);
    }
}
