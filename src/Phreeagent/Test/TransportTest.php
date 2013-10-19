<?php
namespace Phreeagent\Test;

use Phreeagent\Transport;

class TransportTest extends TestCase
{
    /**
     * @test
     * @expectedException \Exception
     */
    public function testInvalidHttpMethodOnRequest()
    {
        $transport = new Transport();
        $transport->request(
            'nick',
            'http://test.com',
            array('Content-type' => 'application/json')
        );
    }
}
