<?php
namespace Phreeagent\Test;

use Phreeagent\Project;

class ProjectTest extends TestCase
{
    /**
     * @test
     */
    public function testFetchProject()
    {
        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.freeagent.com/v2/projects/470')
            ->will($this->returnValue($this->loadMockResponse('project/fetch_success.json', 200)));

        $configuration = $this->getConfigurationMock($transport);

        $project = new Project($configuration);
        $project->load(470);

        $this->assertEquals('Hello World', $project->name);
        $this->assertEquals('0', $project->budget);
        $this->assertEquals('Active', $project->status);
        $this->assertEquals('Hours', $project->budget_units);
        $this->assertEquals(0, $project->normal_billing_rate);
        $this->assertEquals(8, $project->hours_per_day);
        $this->assertEquals(false, $project->uses_project_invoice_sequence);
        $this->assertEquals('GBP', $project->currency);
        $this->assertEquals('hour', $project->billing_period);
    }
}
