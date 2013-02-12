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

class baseSave extends \LWddd\DomainEventResolver
{
    protected $event;
    
    public function __construct($event)
    {
        parent::__construct($event);
    }
    
    public function getInstance($event)
    {
        return new baseSave($event);
    }
    
    protected function getFilteredObject($valueObject)
    {
        $config = $this->event->getParameterByKey('configuration');
        $filter = $this->getDataValueObjectFilter();
        $filteredValueObject = $filter->filter($valueObject);
        return $this->buildEntityFromValueObject($filteredValueObject);
    }
    
    public function resolve()
    {
        $entity = $this->getFilteredObject(new \LWddd\ValueObject($this->event->getDataByKey('postArray')));
        $isValidSpecification = $this->getIsValidSpecification();
        if ($isValidSpecification->isSatisfiedBy($entity)) {
            $result = $this->getCommandHandler()->saveEntity($this->event->getParameterByKey('id'), $entity->getValues());
            $this->postSaveWork($result, $this->event->getParameterByKey('id'), $entity);            
            $this->event->getResponse()->setParameterByKey('saved', true);
        }
        else {
            $this->event->getResponse()->setDataByKey('error', $isValidSpecification->getErrors());
            $this->event->getResponse()->setParameterByKey('error', true);
        }                    
        return  $this->event->getResponse();
    }
}