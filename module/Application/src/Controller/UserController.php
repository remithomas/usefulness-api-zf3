<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class UserController extends AbstractActionController
{
    public function meAction()
    {
        return new JsonModel([
            'success' => true
        ]);
    }
}
