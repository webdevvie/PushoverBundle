PushoverBundle
==============

This is a simple bundle for Symfony2 to send messages through Pushover. 
This library is still in development and its interface is subject to change.


Still to do:
------------
 - retrieve receipt status  


To install
----------


### Add it using composer

`composer require "webdevvie/pushover-bundle"`

### Add it to your appkernel registerbundles method

`new Webdevvie\PushoverBundle\WebdevviePushoverBundle(),`

### Add your token to the config

```yaml

webdevvie_pushover:
  token: %pushover_token%

```

### Add your token to the parameters yaml file 

```yaml
    pushover_token: "your_token_here"
    
```

To test this
------------

```console

app/console pushover:send <usertoken>

```
Follow the questions to send a test message


In your code
------------

 Within your application (in this case a controller) you can send a message to a user's pushover using the following code


```php

    # get the service from the container;
    $pushover = $this->get('pushover');
    
    $message = new PushoverMessage();
    $message->setUser("usercode_here");
    $message->setSound("pushover");
    $message->setTitle("A title here");
    $message->setMessage("Your message here");
    $response = $pushover->sendMessage($message);
    
    # Now you can check on the response object with $response->isSent();
    # any errors are stored in the $response->getErrors() as an array of strings (isSent will then return false)
    
```

 If you want to send a message using a higher priority you can set that property on the message object using the constants starting with PRIORITY_

Sounds
------

 All the sounds are available via the `->availableSounds()` method and as a constant starting with SOUND_ in the PushoverMessage class
 
