<?php

namespace Mail;

use BadFunctionCallException;
use PHPUnit\Framework\TestCase;

class MailObjectTest extends TestCase
{
    // public function test(){
    //     $object = new MailObject();
    //     $methodName = "validateMails";

    //     $reflection = new \ReflectionClass(get_class($object));
    //     $method = $reflection->getMethod($methodName);
    //     $method->setAccessible(true);

    //     $objectReflection = $method->invokeArgs($object, array(["test@gmail.com","salutgmail.com"]));
    //     print_r($objectReflection);

    //     $this->assertCount(2, $objectReflection);

    // }
    
    public function test(){
        $object = new MailObject();
        $methodName = "validateMails";

        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        $objectReflection = $method->invokeArgs($object, array(["test@gmail.com","salutgmail.com"]));
        print_r($objectReflection);

        $this->assertCount(2, $objectReflection);

    }



}


