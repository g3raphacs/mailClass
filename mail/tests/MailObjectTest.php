<?php

namespace Mail\Tests;
use Mail\MailObject;

use BadFunctionCallException;
use PHPUnit\Framework\TestCase;

class MailObjectTest extends TestCase
{
    // public function testReturnvoid()
    // {
    //     $test = new MailObject;
    //     $test->hydrate($data);
    //     $this->assertNull('foo');
    // }

    public function testSendMailtrue() :void
    {
        $sendMail = new MailObject;
        $sendMail->SendMail();
        $this->assertTrue(True->$body[, string $message = '']);
        
    }
    
}