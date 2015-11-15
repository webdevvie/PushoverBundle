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
    public function testCancelReceipt()
    {

        $receipt = "abd4t5hbont";
        $params = $this->params;
        $closure = new Mockery\Matcher\Closure(function ($messageData) use ($receipt, $params) {
            $this->assertEquals($params['token'], $messageData['token']);

            return true;
        });
        $request = Mockery::mock('\Guzzle\Http\Message\Request');
        $response = Mockery::mock('\Guzzle\Http\Message\Response');

        $this->guzzleClient->shouldReceive('createRequest')->withArgs(['POST', "/1/receipts/" . $receipt . "/cancel.json", null, $closure])->andReturn($request);
        $request->shouldReceive('send')->andReturn($response);
        $responseBody = file_get_contents(__DIR__ . "/../Resources/" . "messageSuccess" . ".json");
        $response->shouldReceive('getBody')->with(true)->andReturn($responseBody);

        $response = $this->service->cancelReceipt($receipt);
        $this->assertTrue($response->isSent());

    }

    /**
     * @return void
     */
    public function testCancelReceiptError()
    {

        $receipt = "abd4t5hbont";
        $params = $this->params;
        $closure = new Mockery\Matcher\Closure(function ($messageData) use ($receipt, $params) {
            $this->assertEquals($params['token'], $messageData['token']);

            return true;
        });
        $request = Mockery::mock('\Guzzle\Http\Message\Request');
        $response = Mockery::mock('\Guzzle\Http\Message\Response');

        $this->guzzleClient->shouldReceive('createRequest')->withArgs(['POST', "/1/receipts/" . $receipt . "/cancel.json", null, $closure])->andReturn($request);

        $exceptionObject = Mockery::mock('\Guzzle\Http\Exception\ClientErrorResponseException');

        $request->shouldReceive('send')->andThrow($exceptionObject)->once();
        $responseBody = file_get_contents(__DIR__ . "/../Resources/" . "messageError" . ".json");
        $response->shouldReceive('getBody')->with(true)->andReturn($responseBody);
        $exceptionObject->shouldReceive('getResponse')->andReturn($response)->once();



        $response = $this->service->cancelReceipt($receipt);
        $this->assertFalse($response->isSent());

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
        $message->setSound('iaminvalid'); //set sound to invalid so it is defaulted to pushover
        $responseMessage = $this->genericRequest($message, 'messageSuccess');
        $this->assertTrue($responseMessage->isSent());
        $this->assertEquals('ghjk', $responseMessage->getRequestId());
    }

    /**
     * @return void
     */
    public function testSendMessageWithReceipt()
    {
        $message = new PushoverMessage();
        $message->setSound(PushoverMessage::SOUND_ALIEN);
        $message->setUser('1234');
        $message->setTitle('hi there');
        $message->setDevice('device1');
        $message->setMessage('hello world');
        $message->setUrl('http://johnbakker.name/');
        $message->setUrlTitle("John Bakkers site");
        $sounds = $message->getAvailableSounds();
        $message->setSound($sounds[0]);
        $message->setRetry(60);
        $message->setExpire(3600);//max retry for an hour
        $message->setCallback('http://johnbakker.name/pushoverCallback');
        $message->setTime(new \DateTime());

        $message->setPriority(PushoverMessage::PRIORITY_REQUIRE_ACK);
        $responseMessage = $this->genericRequest($message, 'messageReceipt');
        $this->assertTrue($responseMessage->isSent());
        $this->assertEquals('ghjk', $responseMessage->getRequestId());
        $this->assertEquals("rtyuiop", $responseMessage->getReceipt());
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

        $responseMessage = $this->genericRequest($message, 'messageError',true);
        $this->assertEquals(["user identifier is not a valid user, group, or subscribed user key"], $responseMessage->getErrors());
        $this->assertEquals("invalid", $responseMessage->getUser());
        $this->assertFalse($responseMessage->isSent());
        $this->assertEquals("123456", $responseMessage->getRequestId());

    }

    /**
     * @param PushoverMessage $message
     * @param string $messageResponse
     * @param boolean $exception
     * @return PushoverResponse
     */
    public function genericRequest(PushoverMessage $message, $messageResponse,$exception=false)
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
            if($message->getPriority())
            {

            }
            $this->assertEquals($message->getSound(), $messageData['sound']);
            return true;
        });
        $request = Mockery::mock('\Guzzle\Http\Message\Request');
        $response = Mockery::mock('\Guzzle\Http\Message\Response');

        $this->guzzleClient->shouldReceive('createRequest')->withArgs(['POST', '/1/messages.json', null, $closure])->andReturn($request)->once();

        if($exception)
        {
            $exceptionObject = Mockery::mock('\Guzzle\Http\Exception\ClientErrorResponseException');

            $request->shouldReceive('send')->andThrow($exceptionObject)->once();
            $responseBody = file_get_contents(__DIR__ . "/../Resources/" . $messageResponse . ".json");
            $response->shouldReceive('getBody')->with(true)->andReturn($responseBody);
            $exceptionObject->shouldReceive('getResponse')->andReturn($response)->once();

        }
        else
        {
            $request->shouldReceive('send')->andReturn($response)->once();
            $responseBody = file_get_contents(__DIR__ . "/../Resources/" . $messageResponse . ".json");
            $response->shouldReceive('getBody')->with(true)->andReturn($responseBody)->once();

        }
        return $this->service->sendMessage($message);
    }
}
