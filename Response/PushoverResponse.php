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
    protected $receipt;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var array
     */
    private $map = [
        "status" => "boolean:sent",
        "request" => "requestId",
        "user" => "user",
        "errors" => "array:errors",
        "receipt" => "receipt"
    ];

    /**
     * PushoverResponse constructor.
     * @param string $responseBody
     */
    public function __construct($responseBody)
    {

        $response = json_decode($responseBody);
        $this->parseProperties($response);
    }

    /**
     * @param object $response
     */
    private function parseProperties($response)
    {
        $vars = get_object_vars($response);

        foreach ($vars as $var => $value) {
            $mapParts = explode(":", $this->map[$var]);
            if (count($mapParts) > 1) {
                $mapped = $mapParts[1];
                $mthd = "mapProperty".ucfirst($mapParts[0]);
                $this->$mapped = $this->$mthd($value);
            } else {
                $mapped = $mapParts[0];
                $this->$mapped = $value;
            }

        }
    }

    /**
     * @param string $value
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     * @return boolean
     */
    private function mapPropertyBoolean($value)
    {
        return ($value==1);
    }

    /**
     * @param array $value
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     * @return array
     */
    private function mapPropertyArray(array $value)
    {
        return (is_array($value)?$value:[]);
    }

    /**
     * @return string
     */
    public function getReceipt()
    {
        return $this->receipt;
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
