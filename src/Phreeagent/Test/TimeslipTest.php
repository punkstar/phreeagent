<?php
namespace Phreeagent\Test;

use Phreeagent\Timeslip;

class TimeslipTest extends TestCase
{
    /**
     * @test
     */
    public function testFetchTimeslip()
    {
        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.freeagent.com/v2/timeslips/540')
            ->will($this->returnValue($this->loadMockResponse('timeslip/fetch_success.json', 200)));

        $configuration = $this->getConfigurationMock($transport);

        $timeslip = new Timeslip($configuration);
        $timeslip->load(540);

        $this->assertEquals('2014-02-03T00:00:00+0000', $timeslip->getDatedOn());
        $this->assertEquals(16, $timeslip->hours);
        $this->assertEquals('Hello World', $timeslip->comment);
    }
}
