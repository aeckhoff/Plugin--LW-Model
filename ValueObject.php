<?php

namespace LwModel;

class ValueObject
{
    private $values;
    
    function __construct($values)
    {
        $this->values = $values;
    }

    function getValueByKey($key)
    {
        return $this->values[$key];
    }
    
    function getValues()
    {
        return $this->values;
    }
    
    function renderView($view)
    {
        foreach($this->values as $key => $value)
        {
            $view->entity[$key] = $value;
        }
    }
}
