<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Client as HttpClient;

class RequestToAPIController extends AbstractActionController
{
    public function indexAction()
    {   
        $client = new HttpClient();
        $client->setAdapter('Zend\Http\Client\Adapter\Curl');
    
        $method = $this->params()->fromRoute('param', 'get-list');

        $client->setUri('http://localhost/album-rest');
        
        switch($method) {
            case 'get' :
                $id = $this->params()->fromRoute('id', 2);
                $client->setMethod('GET');
                $client->setParameterGET(array('id' => $id));
                break;
            case 'get-list' :
                $client->setMethod('GET');
                break;
            case 'create' :
                $client->setMethod('POST');
                $client->setParameterPOST(array(
                    'title' => 'Bob Marley LIVE',
                    'artist' => 'Bob Marley'
                ));
                break;
            case 'update' :
                $id = $this->params()->fromRoute('id', 2);
                $data = array(
                    'title' => 'Show 90 Anos Ao Vivo',
                    'artist' => 'Zeze di Camargo & Luciano'
                );
                $client->setMethod('PUT');
                $client->setParameterPOST($data);
                $client->setParameterGET(array('id' => $id));
                break;
            case 'delete' :
                $id = $this->params()->fromRoute('id', 2);
                $client->setMethod('DELETE');
                $client->setParameterGET(array('id' => $id));
                break;
        }
        
        //send request
        $response = $client->send();
        if (!$response->isSuccess()) {

            //error
            $message = $response->getStatusCode() . ': ' . $response->getReasonPhrase();
            
            $response = $this->getResponse();
            $response->setContent($message);
            return $response;
        }
        $body = $response->getBody();
        
        $response = $this->getResponse();
        $response->setContent($body);
        
        return $response;
    }
}
