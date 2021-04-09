<?php

namespace Mail;

use PHPUnit\Framework\TestCase;

class MailObjectTest extends TestCase
{
 
    /** 
     * @test
    */
    public function hydrate_existing_key(){

        $object = new MailObject();
        $this->assertTrue($this->invokeMethod($object, 'hydrate', array(array(
            "from"=>"machin@gmail.com"
        ))));
    }
    /** 
     * @test
    */
    public function hydrate_non_existing_key(){

        $object = new MailObject();
        $this->expectException("Exception");
        $this->invokeMethod($object, 'hydrate', array(array(
            "blabla"=>"machin@gmail.com"
        )));
    }
    /** 
     * @test
    */
    public function validate_multiple_good_mails(){

        $object = new MailObject();
        $this->assertTrue($this->invokeMethod($object, 'validateMails', array(["test@gmail.com","salut@gmail.com"])));
    }
    /** 
     * @test
    */
    public function validate_one_bad_mail(){

        $object = new MailObject();
        $this->expectException("Exception");
        $this->invokeMethod($object, 'validateMails', array(["test@gmail.com","salut@gmailcom"]));
    }
    

    // methode permettant d'invoquer les fonctions privées et protected
    public function invokeMethod(&$object, $methodName, array $parameters = array()){
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}