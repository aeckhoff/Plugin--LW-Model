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

namespace LwModel\baseCommandResolver;

class baseSave extends \LwModel\ModelCommandResolver
{
    protected $command;
    
    public function __construct($command)
    {
        parent::__construct($command);
    }
    
    public function getInstance($command)
    {
        return new baseSave($command);
    }
    
    protected function getFilteredObject($valueObject)
    {
        $config = $this->command->getParameterByKey('configuration');
        $filter = $this->getDataValueObjectFilter();
        $filteredValueObject = $filter->filter($valueObject);
        return $this->buildEntityFromValueObject($filteredValueObject);
    }
    
    public function resolve()
    {
        $entity = $this->getFilteredObject(new \LwModel\ValueObject($this->command->getDataByKey('postArray')));
        $isValidSpecification = $this->getIsValidSpecification();
        if ($isValidSpecification->isSatisfiedBy($entity)) {
            $result = $this->getCommandHandler()->saveEntity($this->command->getParameterByKey('id'), $entity->getValues());
            $this->postSaveWork($result, $this->command->getParameterByKey('id'), $entity);            
            $this->command->getResponse()->setParameterByKey('saved', true);
        }
        else {
            $this->command->getResponse()->setDataByKey('error', $isValidSpecification->getErrors());
            $this->command->getResponse()->setParameterByKey('error', true);
        }                    
        return  $this->command->getResponse();
    }
}
