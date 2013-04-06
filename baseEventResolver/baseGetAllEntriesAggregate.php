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

class baseGetAllEntriesAggregate extends \LWddd\DomainEventResolver
{
    protected $event;
    
    public function __construct($event)
    {
        parent::__construct($event);
    }
    
    public function getInstance($event)
    {
        return new baseGetAllEntriesAggregate($event);
    }
    
    public function resolve()
    {
        $items = $this->getQueryHandler()->loadAllEntries();
        $aggregate = $this->buildAggregateFromQueryResult($items);
        $this->event->getResponse()->setDataByKey('allEntriesAggregate', $aggregate);
        return $this->event->getResponse();        
    }
}