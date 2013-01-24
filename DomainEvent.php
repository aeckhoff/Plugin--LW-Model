<?php

namespace LWddd;

class DomainEvent
{
    
    private $eventName;
    private $response;
    private $parameter = array();
    private $data = array();
    private $history = array();
    private $domainName; 
    
    public function __construct($domainName, $eventName)
    {
        $this->domainName = $domainName;
        $this->eventName = $eventName;
        $this->response = \LWddd\Response::getInstance();
    }

    public static function getInstance($domainName, $eventName)
    {
        return new DomainEvent($domainName, $eventName);
    }
    
    public function getEventName()
    {
        return $this->eventName;
    }
    
    public function getDomainName()
    {
        return $this->domainName;
    }
    
    public function setParameterByKey($key, $value)
    {
        $this->parameter[$key] = $value;
        return $this;
    }
    
    public function getParameterByKey($key)
    {
        return $this->parameter[$key];
    }
    
    public function setDataByKey($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }
    
    public function getDataByKey($key)
    {
        return $this->data[$key];
    }
    
    public function getDataArray()
    {
        return $this->data;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function addEventHistory($message, $modifyingEvent=false)
    {
        $history['message'] = $message;
        $history['modifyingEvent'] = $modified;
        $this->history[] = $history;
        return $this;
    }

    public function getEventHistory()
    {
        return $this->history;
    }

    public function getModifyingEventHistory()
    {
        foreach($this->history as $event) {
            if ($event['modifyingEvent'] === true) {
                $array[] = $event;
            }
        }
        return $array;
    }
}