<?php

namespace Webdevvie\PushoverBundle\Tests\Service;


use Mockery;
use Mockery\MockInterface;
use Webdevvie\PushoverBundle\Message\PushoverMessage;
use Webdevvie\PushoverBundle\Service\PushoverService;
use Webdevvie\PushoverBundle\Response\PushoverResponse;

/**
 * Class PushoverServiceTest
 * @package Webdevvie\PushoverBundle\Tests\Service
 */
class PushoverServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MockInterface
     */
    private $guzzleClient;

    /**
     * @var PushoverService
     */
    private $service;

    /**
     * @var array
     */
    private $params;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->guzzleClient = $guzzleClient = Mockery::mock('\Guzzle\Http\Client');
        $params = $this->params = [
            'token' => 'abcd'
        ];
        $this->service = new PushoverService($guzzleClient, $params);
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @return void
     */
    public function testSendMessageWithValidData()
    {
        $message = new PushoverMessage();
        $message->setSound(PushoverMessage::SOUND_ALIEN);
        $message->setUser('1234');
        $message->setTitle('hi there');
        $message->setDevice('device1');
        $message->setMessage('hello world');
        $responseMessage = $this->genericRequest($message, 'messageSuccess');
        $this->assertTrue($responseMessage->isSent());
        $this->assertEquals('ghjk', $responseMessage->getRequestId());
    }

    /**
     * @return void
     */
    public function testSendMessageWithInvalidData()
    {
        $message = new PushoverMessage();
        $message->setSound(PushoverMessage::SOUND_ALIEN);
        $message->setUser('12345');
        $message->setTitle('hi there');
        $message->setDevice('device1');
        $message->setMessage('hello world');
        $responseMessage = $this->genericRequest($message, 'messageError');
        $this->assertEquals(["user identifier is not a valid user, group, or subscribed user key"], $responseMessage->getErrors());
        $this->assertEquals("invalid", $responseMessage->getUser());
        $this->assertFalse($responseMessage->isSent());
        $this->assertEquals("123456", $responseMessage->getRequestId());
    }

    /**
     * @param PushoverMessage $message
     * @param string $messageResponse
     * @return PushoverResponse
     */
    public function genericRequest(PushoverMessage $message, $messageResponse)
    {
        $params = $this->params;
        $closure = new Mockery\Matcher\Closure(function ($messageData) use ($message, $params) {
            $this->assertEquals($message->getUser(), $messageData['user']);
            $this->assertEquals($params['token'], $messageData['token']);
            $this->assertEquals($message->getMessage(), $messageData['message']);
            $this->assertEquals($message->getTitle(), $messageData['title']);
            if ($message->getDevice() != '') {
                $this->assertEquals($message->getDevice(), $messageData['device']);
            }
            $this->assertEquals($message->getSound(), $messageData['sound']);
            return true;
        });
        $request = Mockery::mock('\Guzzle\Http\Message\Request');
        $response = Mockery::mock('\Guzzle\Http\Message\Response');

        $this->guzzleClient->shouldReceive('createRequest')->withArgs(['POST', '/1/messages.json', null, $closure])->andReturn($request);
        $request->shouldReceive('send')->andReturn($response);
        $responseBody = file_get_contents(__DIR__ . "/../Resources/" . $messageResponse . ".json");
        $response->shouldReceive('getBody')->with(true)->andReturn($responseBody);
        return $this->service->sendMessage($message);
    }
}
