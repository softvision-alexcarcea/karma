<?php

/**
 * /src/ThinFrame/Karma/Events/ActionResponseEvent.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Karma\Events;

use ThinFrame\Events\AbstractEvent;
use ThinFrame\Karma\Controller\BaseController;
use ThinFrame\Server\HttpRequest;
use ThinFrame\Server\HttpResponse;

/**
 * Class ActionResponseEvent
 *
 * @package ThinFrame\Karma\Events
 * @since   0.1
 */
class ActionResponseEvent extends AbstractEvent
{
    const EVENT_ID = 'thinframe.karma.action_response';

    /**
     * Constructor
     *
     * @param HttpRequest    $request
     * @param HttpResponse   $response
     * @param mixed          $actionResponse
     * @param BaseController $controller
     * @param string         $action
     */
    public function __construct(
        HttpRequest $request,
        HttpResponse $response,
        $actionResponse,
        BaseController $controller,
        $action
    ) {
        parent::__construct(
            self::EVENT_ID,
            [
                'request'        => $request,
                'response'       => $response,
                'actionResponse' => $actionResponse,
                'controller'     => $controller,
                'action'         => $action
            ]
        );
    }

    /**
     * Get request object
     *
     * @return HttpRequest
     */
    public function getRequest()
    {
        return $this->getPayload()->get('request')->get();
    }

    /**
     * Get response object
     *
     * @return HttpResponse
     */
    public function getResponse()
    {
        return $this->getPayload()->get('response')->get();
    }

    /**
     * Get action response
     *
     * @return array
     */
    public function getActionResponse()
    {
        return $this->getPayload()->get('actionResponse')->get();
    }

    /**
     * Replace action response
     *
     * @param $data
     */
    public function setActionResponse($data)
    {
        $this->getPayload()->set('actionResponse', $data);
    }

    /**
     * Get controller instance
     *
     * @return BaseController
     */
    public function getController()
    {
        return $this->getPayload()->get('controller')->get();
    }

    /**
     * Get request action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getPayload()->get('action')->get();
    }
}
