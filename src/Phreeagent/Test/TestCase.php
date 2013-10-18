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


    /**
     * @param $filename
     * @param $status_code
     * @param array $headers
     * @return \Requests_Response
     * @throws \Exception
     */
    protected function loadMockResponse($filename, $status_code, $headers = array())
    {
        $full_filename = sprintf("%s/raw_response/%s", __DIR__, $filename);

        if (file_exists($full_filename)) {
            if (is_readable($full_filename)) {
                $file_contents = file_get_contents($full_filename);

                $response = new \Requests_Response();
                $response->body = $file_contents;
                $response->status_code = $status_code;
                $response->success = ($status_code >= 200 && $status_code < 300);

                foreach ($headers as $key => $value) {
                    $response->headers[$key] = $value;
                }

                return $response;
            } else {
                throw new \Exception("Fixture $full_filename is not readable");
            }
        } else {
            throw new \Exception("Fixture $full_filename does not exist");
        }
    }
}
