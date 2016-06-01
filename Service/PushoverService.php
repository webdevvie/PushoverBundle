<?php

namespace Webdevvie\PushoverBundle\Service;

use Guzzle\Http\Client;
use Webdevvie\PushoverBundle\Message\PushoverMessage;
use Webdevvie\PushoverBundle\Response\PushoverResponse;

/**
 * Class PushoverService
 * @package Webdevvie\PushoverBundle\Service
 */
class PushoverService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * PushoverService constructor.
     * @param Client $client
     * @param array $params
     */
    public function __construct(Client $client, array $params)
    {
        $this->client = $client;
        $this->params = $params;
    }

    /**
     * @param PushoverMessage $message
     * @return PushoverResponse
     */
    public function sendMessage(PushoverMessage $message, $token = '')
    {
        if ($token == '') {
            $token = $this->params['token'];
        }
        $body = [
            "token" => $token,
            'user' => $message->getUser(),
            'message' => $message->getMessage(),
            'sound' => $message->getSound()
        ];
        if ($message->getDevice() != '') {
            $body['device'] = $message->getDevice();
        }
        if ($message->getTitle() != '') {
            $body['title'] = $message->getTitle();
        }
        if ($message->getPriority() >= 2) {
            $body['priority'] = $message->getPriority();
            $body['expire'] = $message->getExpire();
            $body['retry'] = $message->getRetry();
            if ($message->getCallback() != '') {
                $body['callback'] = $message->getCallback();
            }

        }
        if (!is_null($message->getTime())) {
            $body['timestamp'] = $message->getTime();
        }
        if ($message->getUrl() != '') {
            $body['url'] = $message->getUrl();
        }
        if ($message->getUrlTitle() != '') {
            $body['url_title'] = $message->getUrlTitle();
        }
        $request = $this->client->createRequest('POST', '/1/messages.json', null, $body);

        try {
            $response = $request->send();
            $responseBody = $response->getBody(true);
            return new PushoverResponse($responseBody);

        } catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
            return new PushoverResponse($exception->getResponse()->getBody(true));
        }

    }

    /**
     * @param string $receipt
     * @return PushoverResponse
     */
    public function cancelReceipt($receipt, $token = '')
    {
        if ($token == '') {
            $token = $this->params['token'];
        }
        $body = ['token' => $token];
        $request = $this->client->createRequest("POST", "/1/receipts/" . $receipt . "/cancel.json", null, $body);
        try {
            $response = $request->send();
            $responseBody = $response->getBody(true);
            return new PushoverResponse($responseBody);
        } catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
            return new PushoverResponse($exception->getResponse()->getBody(true));
        }
    }
}
