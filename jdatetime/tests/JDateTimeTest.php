<?php
class JDateTimeTest extends PHPUnit_Framework_TestCase
{
    /** @var JDateTime  */
    public $jDateTime;

    public function setUp()
    {
        $this->jDateTime = new JDateTime('now', new DateTimeZone('Asia/Tehran'));
    }

    public function testMakeFormat()
    {
        $this->jDateTime->setDate(1368, 10, 2)->setTime(0, 0, 0);

        $this->assertEquals($this->jDateTime->format("l w Y/m/d"), "شنبه 0 1368/10/02");
        $this->assertEquals($this->jDateTime->format("c"), "1368-10-02T00:00:00+03:30");
    }

    public function testMakeTimestamp()
    {
        $this->jDateTime->setDate(1368, 10, 2)->setTime(0, 0, 0);

        $this->assertEquals($this->jDateTime->getTimestamp(), 630361800);
    }

    public function testMakeISODate()
    {
        $this->jDateTime->setISODate(1394, 5, 4);

        $this->assertEquals($this->jDateTime->format("Y/m/d"), "1394/02/01");
    }

    public function testMakeFromFormat()
    {
        $dt = $this->jDateTime
                   ->createFromFormat('Y/m/d h:i:s a', '1394/02/01 12:40:15 ق.ظ')
                   ->format('Y/m/d H:i:s');

        $this->assertEquals($dt, "1394/02/01 00:40:15");

        $dt = $this->jDateTime
                   ->createFromFormat('', '')
                   ->format('Y/m/d H:i:s');

        $this->assertEquals($dt, $this->jDateTime->format('Y/m/d H:i:s'));
    }
}
