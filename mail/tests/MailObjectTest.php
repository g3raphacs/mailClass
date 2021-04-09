<?php

namespace Mail;

use PHPUnit\Framework\TestCase;

class MailObjectTest extends TestCase
{
    public function hydrateFalse() 
    {
        $object = new MailObject();

        $this->assertFalse($object->hydrate(array(
            "hello" => "salut@gmail.com"
        )));
    }
    public function hydrateTrue() {
        $object = new MailObject();

        $this->assertTrue($object->hydrate(array(
        "from" => "salut@gmail.com"
        )));
    }

    // methode permettant d'invoquer les fonctions privées et protected
    public function invokeMethod(&$object, $methodName, array $parameters = array()){
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}


