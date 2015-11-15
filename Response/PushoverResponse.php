<?php

namespace Webdevvie\PushoverBundle\Response;


/**
 * Class PushoverResponse
 * @package Webdevvie\PushoverBundle\Response
 */
class PushoverResponse
{
    /**
     * @var boolean
     */
    protected $sent = false;
    /**
     * @var string
     */
    protected $requestId = '';

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var string
     */
    protected $user;

    public function __construct($responseBody)
    {
        $response = json_decode($responseBody);


        if (isset($response->status) && $response->status == 1) {
            $this->sent = true;
        }
        if (isset($response->request) && $response->request != '') {
            $this->requestId = $response->request;
        }
        if (isset($response->user) && $response->user != '') {
            $this->user = $response->user;
        }
        if(isset($response->errors) && is_array($response->errors))
        {
            $this->errors = $response->errors;
        }

    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function isSent()
    {
        return $this->sent;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }
}
