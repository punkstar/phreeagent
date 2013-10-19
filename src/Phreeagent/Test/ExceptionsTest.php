<?php
namespace Phreeagent\Test;

use Phreeagent\Exception\InvalidRequestResponseException;
use Phreeagent\Exception\MalformedResponseException;
use Phreeagent\Exception\UnsuccessfulResponseException;

class ExceptionsTest extends TestCase
{
    /**
     * @test
     */
    public function testUnsuccessfulResponseException()
    {
        $this->assertInstanceOf(
            '\Phreeagent\Exception\UnsuccessfulResponseException',
            UnsuccessfulResponseException::factory(new \Requests_Response())
        );
    }

    /**
     * @test
     */
    public function testMalformedResponseException()
    {
        $this->assertInstanceOf(
            '\Phreeagent\Exception\MalformedResponseException',
            MalformedResponseException::factory(new \Requests_Response())
        );
    }

    /**
     * @test
     */
    public function testInvalidRequestResponseException()
    {
        $this->assertInstanceOf(
            '\Phreeagent\Exception\InvalidRequestResponseException',
            InvalidRequestResponseException::factory(new \Requests_Response())
        );
    }
}
