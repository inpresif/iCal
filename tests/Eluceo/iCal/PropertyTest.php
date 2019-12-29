<?php

namespace Eluceo\iCal;

use Eluceo\iCal\Property\StringValue;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    public function testPropertyWithSingleValue()
    {
        $property = new Property('DTSTAMP', '20131020T153112');
        $this->assertEquals(
            'DTSTAMP:20131020T153112',
            $property->toLine()
        );
    }

    public function testPropertyWithValueAndParams()
    {
        $property = new Property('DTSTAMP', '20131020T153112', array('TZID' => 'Europe/Berlin'));
        $this->assertEquals(
            'DTSTAMP;TZID=Europe/Berlin:20131020T153112',
            $property->toLine()
        );
    }

    public function testPropertyWithEscapedSingleValue()
    {
        $property = new Property('SOMEPROP', 'Escape me!"');
        $this->assertEquals(
            'SOMEPROP:Escape me!\\"',
            $property->toLine()
        );
    }

    public function testPropertyWithEscapedValues()
    {
        $property = new Property('SOMEPROP', 'Escape me!"', array('TEST' => 'Lorem "test" ipsum'));
        $this->assertEquals(
            'SOMEPROP;TEST="Lorem \\"test\\" ipsum":Escape me!\\"',
            $property->toLine()
        );
    }

    public function testSetParam()
    {
        $property = new Property('DTSTAMP', '20131020T153112');
        $property->setParam('paramName', ['key' => 'value']);
        $this->assertSame(['key' => 'value'], $property->getParam('paramName'));
    }

    public function testSetValueOnArray()
    {
        $property = new Property('DTSTAMP', '20131020T153112');
        $property->setValue(['value1', 'value2']);
        $this->assertSame('value1,value2', $property->getValue()->getEscapedValue());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage The value must implement the ValueInterface.
     */
    public function testSetValueOnInvalidValue()
    {
        $property = new Property('DTSTAMP', '20131020T153112');
        $property->setValue(new \DateTimeZone('Asia/Taipei'));
    }

    public function testSetValueOnStringValue()
    {
        $property = new Property('DTSTAMP', '20131020T153112');
        $property->setValue(new StringValue(1000));
        $this->assertSame('1000', $property->getValue()->getEscapedValue());
    }
}