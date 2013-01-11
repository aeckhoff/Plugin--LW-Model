<?php

namespace LWddd;
use \Exception as Exception;

class Entity
{
    public function __construct($id=false)
    {
        $this->unsetLoaded();
        $id = intval($id);
        if (!$id) {
            $this->id = false;
        }
        else {
            $this->id = $id;
        }
    }
    
    public function setDataValueObject(\LWddd\ValueObject $valueObject)
    {
        $this->valueObject = $valueObject;
        if ($this->id > 0 ) {
            $this->setLoaded();
            $this->setDirty();
        }
        else {
            $this->unsetLoaded();
            $this->setDirty();
        }
    }
    
    public function setLoaded()
    {
        $this->loaded = true;
    }
    
    public function unsetLoaded()
    {
        $this->loaded = false;
    }
    
    public function isLoaded()
    {
        return $this->loaded;
    }
    
    public function setDirty()
    {
        $this->dirty = true;
    }
    
    public function unsetDirty()
    {
        $this->dirty = false;
    }
    
    public function isDirty()
    {
        return $this->dirty;
    }
    
    public function getValueByKey($key)
    {
        return $this->valueObject->getValueByKey($key);
    }
    
    public function getValues()
    {
        return $this->valueObject->getValues();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function renderView($view)
    {
        $view->entity = $this->valueObject->getValues();
    }
    
    public function finishSaveResult($result)
    {
        if ($result) {
            $this->setLoaded();
            $this->unsetDirty();
        }
        else {
            if ($this->id > 0 ) {
                $this->setLoaded();
            }
            else {
                $this->unsetLoaded();
            }
            $this->setDirty();
            throw new Exception('An DB Error occured saving the Entity');
        }
        return $result;        
    }
}