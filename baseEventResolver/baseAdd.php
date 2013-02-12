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

class baseAdd extends \LWddd\DomainEventResolver
{
    protected $event;
    
    public function __construct($event)
    {
        parent::__construct($event);
    }
    
    public function getInstance($event)
    {
        return new baseAdd($event);
    }
    
    public function resolve()
    {
        $valueObject = new \LWddd\ValueObject($this->event->getDataByKey('postArray'));
        $filter = $this->getDataValueObjectFilter();
        $DataValueObjectFiltered = $filter->filter($valueObject);
        $entity = $this->buildEntityFromValueObject($DataValueObjectFiltered);
        $isValidSpecification = $this->getIsValidSpecification();
        if ($isValidSpecification->isSatisfiedBy($entity)) {
            $id = $this->getCommandHandler()->addEntity($entity->getValues());
            $this->postSaveWork($id, $id, $entity);            
            $this->event->getResponse()->setParameterByKey('saved', true);
        }
        else {
            $this->event->getResponse()->setDataByKey('error', $isValidSpecification->getErrors());
            $this->event->getResponse()->setParameterByKey('error', true);
        }                    
        return  $this->event->getResponse();
    }
}