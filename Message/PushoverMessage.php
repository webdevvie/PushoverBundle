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
    public static $availableSounds = [
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
     * @return string
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * @param string $sound
     * @return PushoverMessage
     */
    public function setSound($sound)
    {
        if (!in_array($sound, self::$availableSounds)) {
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
