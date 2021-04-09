<?php

namespace Mail;

use BadFunctionCallException;
use PHPUnit\Framework\TestCase;

class MailObjectTest extends TestCase
{

    public function test(){

        $object = new MailObject();
        $this->assertCount(2, $this->invokeMethod($object, 'validateMails', array(["test@gmail.com","salut@gmail.com"])));
    }

    public function invokeMethod(&$object, $methodName, array $parameters = array()){
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}


