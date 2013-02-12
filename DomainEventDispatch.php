<?php

namespace LWddd;

class DomainEventDispatch
{
    public function __construct()
    {
    }

    public static function getInstance()
    {
        return new DomainEventDispatch();
    }
    
    public function execute($package, $domain, $eventName, $parameterArray=array(), $dataArray=array())
    {
        $event = \LWddd\DomainEvent::getInstance($domain, $eventName);
        foreach($parameterArray as $key => $value) {
            $event->setParameterByKey($key, $value);
        }
        foreach($dataArray as $key => $value) {
            $event->setDataByKey($key, $value);
        }
        if (substr($event->getEventName(), 0, 4) == "base") {
            $class = "\\LWddd\\baseEventResolver\\".$event->getEventName();
            $eventResolver = $class::getInstance($event);
            $eventResolver->setBaseNamespace("\\".$package."\\Domain\\".$event->getDomainName()."\\");
            $eventResolver->setObjectClass("\\".$package."\\Domain\\".$event->getDomainName()."\\Object\\".strtolower($event->getDomainName()));
            return $eventResolver->resolve();
        }
        else {
            $class = "\\".$package."\\Domain\\".$event->getDomainName()."\\EventResolver\\".$event->getEventName();
            return $class::getInstance($event)->resolve();
        }        
        
        
        $class = "\\".$package."\\Domain\\".$domain."\\EventResolver\\".$eventName;
        return $class::getInstance($event)->resolve();        
    }
}