<?php
class JDateTimeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JDateTime
	 */
	var $jDateTime;

	public function setUp()
	{
		$this->jDateTime = new JDateTime(new DateTimeZone('Asia/Tehran'));
	}

	public function testMakeJalaliTime()
	{
		$this->jDateTime->setDate(1368, 10, 2)->setTime(0, 0, 0);

		$this->assertEquals($this->jDateTime->getTimestamp(), 630361800);
		$this->assertEquals($this->jDateTime->format("l Y/m/d"), "شنبه 1368/10/02");
		$this->assertEquals($this->jDateTime->format("c"), "1368-10-02T00:00:00+03:30");
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
	}
}