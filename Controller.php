<?php

namespace LwModel;
use \lw_response as lwResponse;
use \lw_request as lwRequest;
use \LwModel\ValueObject as valueObject;
use \LwModel\ModelCommand as modelCommand;
use \LwModel\CommandBus as commandBus;

class Controller 
{
    public function __construct(lwResponse $response)
    {
        $this->response = $response;
        $this->commandBus = new commandBus();
        $this->idString = "id";
    }
    
    public function setIdString($id)
    {
        $this->idString = $id;
    }
    
    public function setSession($session)
    {
        $this->session = $session;
    }
    
    public function execute($cmd, lwRequest $HTTPRequest)
    {
        $this->generateModelCommandFromHTTPRequest($cmd, $HTTPRequest);

        if (method_exists($this, $this->modelCommand->getCommandName()) && is_callable(array($this, $this->modelCommand->getCommandName()))) {
            call_user_func(array($this, $this->modelCommand->getCommandName()));
        }
        return $this->response;
    }

    public function generateModelCommandFromHTTPRequest($cmd, $HTTPRequest)
    {
        $modelCommand = $cmd;
        if (!$modelCommand) {
            if ($this->defaultAction) {
                $modelCommand = $this->defaultAction;
            } 
            else {
                $modelCommand = "indexAction";
            }
        }
        else {
            $modelCommand = $modelCommand."Action";
        }   
    
        $id = $HTTPRequest->getInt($this->idString);
    
        $dataValueObject = new valueObject($HTTPRequest->getPostArray());
        $parameterValueObject = new valueObject($HTTPRequest->getGetArray());
        $this->modelCommand = new modelCommand($modelCommand, $dataValueObject, $parameterValueObject, $id);
        $this->modelCommand->setSession($this->session);
    }    
}
