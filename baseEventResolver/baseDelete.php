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

class baseDelete extends \LWddd\DomainEventResolver
{
    protected $event;
    
    public function __construct($event)
    {
        parent::__construct($event);
    }
    
    public function getInstance($event)
    {
        return new baseDelete($event);
    }
    
    public function resolve()
    {
        $event = $this->event->getDataByKey('event');
        $isDeletableSpecification = $this->event->getParameterByKey("isDeletableSpecification");
        if ($isDeletableSpecification->isSatisfiedBy($event)) {
            $ok = $this->getCommandHandler()->deleteEntity($this->event->getParameterByKey("id"));
        }        

        if ($ok) {
            $this->event->getResponse()->setParameterByKey('deleted', true);
        }
        else {
            $this->event->getResponse()->setDataByKey('error', 'error deleting');
            $this->event->getResponse()->setParameterByKey('error', true);
        }                    
        return $this->event->getResponse();
    }
}