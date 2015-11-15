<?php

namespace Webdevvie\PushoverBundle\Message;

/**
 * Class PushoverMessage
 * @package Webdevvie\PushoverBundle\Message
 */
class PushoverMessage
{
    const SOUND_PUSHOVER = 'pushover';
    const SOUND_BIKE = 'bike';
    const SOUND_BUGLE = 'bugle';
    const SOUND_CASHREGISTER = 'cashregister';
    const SOUND_CLASSICAL = 'classical';
    const SOUND_COSMIC = 'cosmic';
    const SOUND_FALLING = 'falling';
    const SOUND_GAMELAN = 'gamelan';
    const SOUND_INCOMING = 'incoming';
    const SOUND_INTERMISSION = 'intermission';
    const SOUND_MAGIC = 'magic';
    const SOUND_MECHANICAL = 'mechanical';
    const SOUND_PIANOBAR = 'pianobar';
    const SOUND_SIREN = 'siren';
    const SOUND_SPACEALARM = 'spacealarm';
    const SOUND_TUGBOAT = 'tugboat';
    const SOUND_ALIEN = 'alien';
    const SOUND_CLIMB = 'climb';
    const SOUND_PERSISTENT = 'persistent';
    const SOUND_ECHO = 'echo';
    const SOUND_UPDOWN = 'updown';
    const SOUND_NONE = 'none';

    /**
     * A list of available sound for use withing a command or to display in your interface
     *
     * @var array
     */
    private $availableSounds = [
        'pushover',
        'bike',
        'bugle',
        'cashregister',
        'classical',
        'cosmic',
        'falling',
        'gamelan',
        'incoming',
        'intermission',
        'magic',
        'mechanical',
        'pianobar',
        'siren',
        'spacealarm',
        'tugboat',
        'alien',
        'climb',
        'persistent',
        'echo',
        'updown',
        'none'
    ];

    /**
     * @var string
     */
    private $sound;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $device;

    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $message;

    /**
     * @var integer
     */
    private $priority =0;
    /**
     * @var integer
     */
    private $expire=3600;

    /**
     * @var integer
     */
    private $retry=60;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $urlTitle;

    /**
     * @var integer
     */
    private $time;

    /**
     * @var string
     */
    private $callback;

    const PRIORITY_NOALERT = -2;
    const PRIORITY_QUIET = -1;
    const PRIORITY_NONE = 0;
    const PRIORITY_HIGH = 1;
    const PRIORITY_REQUIRE_ACK = 2;

    /**
     * @return string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param string $callback
     * @return PushoverMessage
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @return integer
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @param integer $expire
     * @return PushoverMessage
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;
        return $this;
    }

    /**
     * @return integer
     */
    public function getRetry()
    {
        return $this->retry;
    }

    /**
     * @param integer $retry
     * @return PushoverMessage
     */
    public function setRetry($retry)
    {
        $this->retry = $retry;
        return $this;
    }


    /**
     * @return integer
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     * @return PushoverMessage
     */
    public function setTime(\DateTime $time)
    {
        $this->time = $time->getTimestamp();
        return $this;
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return PushoverMessage
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrlTitle()
    {
        return $this->urlTitle;
    }

    /**
     * @param string $urlTitle
     * @return PushoverMessage
     */
    public function setUrlTitle($urlTitle)
    {
        $this->urlTitle = $urlTitle;
        return $this;
    }

    /**
     * @return array
     */
    public function getAvailableSounds()
    {
        return $this->availableSounds;
    }

    /**
     * @return string
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param integer $priority
     * @return PushoverMessage
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * @param string $sound
     * @return PushoverMessage
     */
    public function setSound($sound)
    {
        if (!in_array($sound, $this->availableSounds)) {
            $this->sound = self::SOUND_NONE;
        } else {
            $this->sound = $sound;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @return PushoverMessage
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param string $device
     * @return PushoverMessage
     */
    public function setDevice($device)
    {
        $this->device = $device;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return PushoverMessage
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return PushoverMessage
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}
