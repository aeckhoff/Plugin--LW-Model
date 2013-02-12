<?php

namespace LWddd;

class DomainEventResolver
{

    protected $dic;
    protected $event;
    protected $baseNamespace;
    
    public function __construct($event)
    {
        $this->event = $event;
    }

    public function setBaseNamespace($baseNamespace)
    {
        $this->baseNamespace = $baseNamespace;
    }
    
    public function setObjectClass($objectClass)
    {
        $this->ObjectClass = $objectClass;
    }
    
    protected function getCommandHandler()
    {
        if (!$this->commandHandler) {
            $class = $this->baseNamespace.'DataHandler\CommandHandler';
            $this->commandHandler = new $class(\lw_registry::getInstance()->getEntry("db"));
        }
        return $this->commandHandler;
    }
    
    protected function getQueryHandler()
    {
        if (!$this->queryHandler) {
            $class = $this->baseNamespace.'DataHandler\QueryHandler';
            $this->queryHandler = new $class(\lw_registry::getInstance()->getEntry("db"));
        }
        return $this->queryHandler;
    }
    
    protected function getDataValueObjectFilter()
    {
        if (!$this->DataValueObjectFilter) {
            $class = $this->baseNamespace.'Service\Filter';
            $this->DataValueObjectFilter = new $class();
        }
        return $this->DataValueObjectFilter;
    }    
    
    protected function getDataValueObjectDecorator()
    {
        if (!$this->DataValueObjectDecorator) {
            $class = $this->baseNamespace.'Service\Decorator';
            $this->DataValueObjectDecorator = new $class();
        }
        return $this->DataValueObjectDecorator;
    }    
    
    public function getIsDeletableSpecification()
    {
        $class = $this->baseNamespace.'Specification\isDeletable';
        $this->event->getResponse()->setDataByKey('isDeletableSpecification', $class::getInstance());
        return $this->event->getResponse();
    }
    
    public function getIsValidSpecification()
    {
        $class = $this->baseNamespace.'Specification\isValid';
        return $class::getInstance();
    }
    
    
    public function buildObjectByArray($data)
    {
        $object = $this->buildObjectById($data['id']);
        return $this->prepareObject($object, $data);
    }    
    
    public function buildAggregateFromQueryResult($items, $decoratorSwitch=false)
    {
        foreach($items as $item) {
            $entities[] = $this->buildEntityFromArray($item, $decoratorSwitch);
        }
        return new \LWddd\EntityAggregate($entities);        
    }    
    
    public function buildEntityFromArray($array, $decoratorSwitch=false)
    {
        $dataValueObject = new \LWddd\ValueObject($array);
        if ($decoratorSwitch==true) {
            $dataValueObject = $this->getDataValueObjectDecorator()->decorate($dataValueObject);
        }
        return $this->buildEntityFromValueObject($dataValueObject);
    }
    
    public function buildEntityFromValueObject($data)
    {
        $entity = new $this->ObjectClass();
        $entity->setDataValueObject($data);
        $entity->setLoaded();
        $entity->setDirty();
        return $entity;
    }    

    protected function postSaveWork($result, $id, $entity)
    {
        if ($result) {
            $entity->setLoaded();
            $entity->unsetDirty();
        }
        else {
            if ($id > 0 ) {
                $entity->setLoaded();
            }
            else {
                $entity->unsetLoaded();
            }
            $entity->setDirty();
            throw new \Exception('An DB Error occured saving the Entity');
        }        
    }     
}