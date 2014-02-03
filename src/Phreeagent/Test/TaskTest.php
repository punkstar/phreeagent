<?php
namespace Phreeagent\Test;

use Phreeagent\Task;

class TaskTest extends TestCase
{
    /**
     * @test
     */
    public function testFetchTask()
    {
        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.freeagent.com/v2/tasks/164')
            ->will($this->returnValue($this->loadMockResponse('task/fetch_success.json', 200)));

        $configuration = $this->getConfigurationMock($transport);

        $task = new Task($configuration);
        $task->load(164);

        $this->assertEquals('Hello World', $task->name);
        $this->assertEquals(true, $task->is_billable);
        $this->assertEquals(0, $task->billing_rate);
        $this->assertEquals('hour', $task->billing_period);
        $this->assertEquals('Active', $task->status);
    }
}
