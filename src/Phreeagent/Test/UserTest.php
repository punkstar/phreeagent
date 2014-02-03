<?php
namespace Phreeagent\Test;

use Phreeagent\User;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function testFetchUser()
    {
        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.freeagent.com/v2/users/480')
            ->will($this->returnValue($this->loadMockResponse('user/fetch_success.json', 200)));

        $configuration = $this->getConfigurationMock($transport);

        $user = new User($configuration);
        $user->load(480);

        $this->assertEquals('Nick', $user->first_name);
        $this->assertEquals('Jones', $user->last_name);
        $this->assertEquals('nick@nicksays.co.uk', $user->email);
        $this->assertEquals('Director', $user->role);
        $this->assertEquals('8', $user->permission_level);
        $this->assertEquals('0.0', $user->opening_mileage);
    }
}
