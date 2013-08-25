<?php

class ObjectTest extends \PHPUnit_Framework_TestCase {
    /**
     * @dataProvider testValidProvider
     * @param array $values
     */
    public function testValid(array $values) {
        $concrete = ConcreteObject::create($values);
        $this->assertInstanceOf('\Railtime\Object', $concrete);
        foreach ($values as $k => $v) {
            $this->assertAttributeEquals($v, $k, $concrete);
        }
    }

    /**
     * @return array
     */
    public function testValidProvider() {
        return array(
            array(array()),
            array(array("a" => 100)),
            array(array("a" => 100, "b" => "xyz")),
            array(array("b" => "xyz", "a" => 100)),
        );
    }

    /**
     * @expectedException \Railtime\Exception
     * @dataProvider testInvalidProvider
     * @param array $values
     */
    public function testInvalid(array $values) {
        $concrete = ConcreteObject::create($values);
        $this->assertNotInstanceOf('\Railtime\Object', $concrete);
    }

    /**
     * @return array
     */
    public function testInvalidProvider() {
        return array(
            array(array("c" => "xyz")),
            array(array("a" => 1, "b" => 1, "c" => "xyz")),
        );
    }
}
