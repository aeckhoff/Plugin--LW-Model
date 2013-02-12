<?php

/**************************************************************************
*  Copyright notice
*
*  Copyright 2013 Logic Works GmbH
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*  
*  Unless required by applicable law or agreed to in writing, software
*  distributed under the License is distributed on an "AS IS" BASIS,
*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*  See the License for the specific language governing permissions and
*  limitations under the License.
*  
***************************************************************************/

namespace LWddd\baseEventResolver;

class baseGetEntryById extends \LWddd\DomainEventResolver
{
    protected $event;
    
    public function __construct($event)
    {
        parent::__construct($event);
    }
    
    public function getInstance($event)
    {
        return new baseGetEntryById($event);
    }
    
    public function resolve()
    {
        $eventArray = $this->getQueryHandler()->loadEntryById($this->event->getParameterByKey("id"));
        $valueObject = new \LWddd\ValueObject($eventArray);
        
        if ($this->event->getParameterByKey("decorated") == true) {
            $config = $this->event->getParameterByKey('configuration');
            $decorator = $this->getDataValueObjectDecorator();
            $DataValueObject = $decorator->decorate($valueObject);
        }
        else {
            $DataValueObject = $valueObject;
        }
        
        $entry = new $this->ObjectClass();
        $entry->setDataValueObject($DataValueObject);
        $entry->setId($this->event->getParameterByKey("id"));
        $this->event->getResponse()->setDataByKey('Entry', $entry);
        return $this->event->getResponse();        
    }
}