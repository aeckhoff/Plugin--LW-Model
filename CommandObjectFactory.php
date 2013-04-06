<?php

namespace LwModel;

class CommandObjectFactory 
{
    public function __construct(\LwModel\ModelCommand $ModelCommand)
    {
        $this->ModelCommand = $ModelCommand;
    }
    
    public function setEmptyEntity($entity)
    {
        $this->entity = $entity;
    }
    
    public function generate()
    {
        $valueObject = $this->filterService->filter($this->ModelCommand->getValueObject());
        $valueObject = $this->validateService->validate($valueObject);
        if ($this->validateService->isValid()) {
            $this->entity->setValid(true);
        }
        
        $values = $valueObject->getValues();
        foreach ($values as $key => $value) {
            $this->entity->setValueByKey($key, $value);
        }
        $commandObject = new \LwModel\CommandObject();
        $commandObject->setModelCommand($this->ModelCommand);
        $commandObject->setEntity($this->entity);
        return $commandObject;
    }
}
