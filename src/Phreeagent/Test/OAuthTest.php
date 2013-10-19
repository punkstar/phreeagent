<?php
namespace Phreeagent\Test;

use Phreeagent\OAuth;

class OAuthTest extends TestCase
{
    /**
     * @test
     */
    public function testGetAccessToken()
    {
        $client_id = 123;
        $client_secret = 456;
        $refresh_token = 'abc';

        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.freeagent.com/v2/token_endpoint', array(), array(
                'client_id'     => $client_id,
                'client_secret' => $client_secret,
                'refresh_token' => $refresh_token,
                'grant_type'    => 'refresh_token'
            ))
            ->will($this->returnValue($this->loadMockResponse('oauth/access_token_success.json', 200)));

        $configuration = $this->getMock('\Phreeagent\Config', array(), array(
            $client_id,
            $client_secret,
            $refresh_token,
            $transport
        ));

        $oauth = new OAuth($configuration);
        $oauth->getAccessToken();
    }

    /**
     * @test
     */
    public function testCacheAccessToken()
    {
        $client_id = 123;
        $client_secret = 456;
        $refresh_token = 'abc';

        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.freeagent.com/v2/token_endpoint', array(), array(
                'client_id'     => $client_id,
                'client_secret' => $client_secret,
                'refresh_token' => $refresh_token,
                'grant_type'    => 'refresh_token'
            ))
            ->will($this->returnValue($this->loadMockResponse('oauth/access_token_success.json', 200)));

        $configuration = $this->getMock('\Phreeagent\Config', array(), array(
            $client_id,
            $client_secret,
            $refresh_token,
            $transport
        ));

        $oauth = new OAuth($configuration);

        $oauth->getAccessToken();
        $oauth->getAccessToken();
        $oauth->getAccessToken();
        $oauth->getAccessToken();
    }

    /**
     * @test
     * @expectedException \Phreeagent\Exception\InvalidRequestResponseException
     */
    public function testBadRefreshToken()
    {
        $client_id = 123;
        $client_secret = 456;
        $refresh_token = 'abc';

        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.freeagent.com/v2/token_endpoint', array(), array(
                'client_id'     => $client_id,
                'client_secret' => $client_secret,
                'refresh_token' => $refresh_token,
                'grant_type'    => 'refresh_token'
            ))
            ->will($this->returnValue($this->loadMockResponse('oauth/access_token_bad_refresh_token.json', 400)));

        $configuration = $this->getMock('\Phreeagent\Config', array(), array(
            $client_id,
            $client_secret,
            $refresh_token,
            $transport
        ));

        $oauth = new OAuth($configuration);
        $oauth->getAccessToken();
    }
}
