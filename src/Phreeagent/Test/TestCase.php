<?php
namespace Phreeagent\Test;

class TestCase extends \PHPUnit_Framework_TestCase
{
    const EXAMPLE_ACCESS_TOKEN = '1231231231231231wklsdjfvcjdfsda';

    /**
     * @return \Phreeagent\Transport
     */
    protected function getTransportMock()
    {
        $transport = $this->getMock('\Phreeagent\Transport', array('request'));
        return $transport;
    }

    /**
     * @param $transport
     * @return \Phreeagent\Config
     */
    protected function getConfigurationMock($transport)
    {
        $configuration = $this->getMock('\Phreeagent\Config', array('getAccessToken'), array(1, 2, 3, $transport));

        $configuration->expects($this->any())
            ->method('getAccessToken')
            ->will($this->returnValue(self::EXAMPLE_ACCESS_TOKEN));

        return $configuration;
    }
}
