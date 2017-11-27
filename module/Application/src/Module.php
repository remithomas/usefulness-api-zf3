<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Zend\Http\PhpEnvironment\Response;

class Module
{
    const VERSION = '1.0';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $events = $e->getTarget()->getEventManager();
        $events->attach(MvcEvent::EVENT_RENDER, [$this, 'registerJsonStrategy'], 100);
        $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError'], 200);
    }

    public function registerJsonStrategy(MvcEvent $e)
    {
        $app          = $e->getTarget();
        $locator      = $app->getServiceManager();
        $view         = $locator->get('Zend\View\View');
        $jsonStrategy = $locator->get('ViewJsonStrategy');

        // Attach strategy, which is a listener aggregate, at high priority
        $jsonStrategy->attach($view->getEventManager(), 100);
    }

    public function onDispatchError(MvcEvent $e)
    {
        $error = $e->getError();
        if (! $error) {
            // No error? nothing to do.
            return;
        }

        $request = $e->getRequest();
        $headers = $request->getHeaders();
        if (! $headers->has('Accept')) {
            // nothing to do; can't determine what we can accept
            return;
        }


        $accept = $headers->get('Accept');
        if (! $accept->match('application/json')) {
            // nothing to do; does not match JSON
            return;
        }

        $response = $e->getResponse();
        $model = new JsonModel([
            "httpStatus" => $response->getStatusCode()
        ]);

        // Add headers
        $typeHeader = Header\ContentType::fromString('Content-Type: application/json; charset=utf-8');
        $response->getHeaders()->addHeader($typeHeader);


        switch ($error) {
            case 'error-controller-cannot-dispatch':
                $model->detail = 'The requested controller was unable to dispatch the request.';
                break;
            case 'error-controller-not-found':
                $model->detail = 'The requested controller could not be mapped to an existing controller class.';
                break;
            case 'error-controller-invalid':
                $model->detail = 'The requested controller was not dispatchable.';
                break;
            case 'error-router-no-match':
                $response->setStatusCode(404);
                $model->httpStatus = Response::STATUS_CODE_404;
                $model->detail = 'The requested URL could not be matched by routing.';
                break;
            default:
                $model->detail = null;
                break;
        }

        $response->sendHeaders();

        $e->setResult($model);
        $e->setViewModel($model);
        $e->stopPropagation();

        return $model;
    }
}
