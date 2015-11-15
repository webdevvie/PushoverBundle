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
    public function sendMessage(PushoverMessage $message)
    {

        $body = [
            "token" => $this->params['token'],
            'user' => $message->getUser(),
            'title' => $message->getTitle(),
            'message' => $message->getMessage(),
            'sound' => $message->getSound()
        ];
        if ($message->getDevice() != '') {
            $body['device'] = $message->getDevice();
        }

        $request = $this->client->createRequest('POST', '/1/messages.json', null, $body);

        try
        {
            $response = $request->send();
            $responseBody = $response->getBody(true);
            return new PushoverResponse($responseBody);

        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $exception)
        {
            return new PushoverResponse($exception->getResponse()->getBody(true));
        }

    }

    /**
     * @param string $requestId
     */
    public function getReceiptStatus($requestId)
    {
        //TODO
    }

}
