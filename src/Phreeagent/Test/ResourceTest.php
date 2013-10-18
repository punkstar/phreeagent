<?php
namespace Phreeagent\Test;

class ResourceTest extends TestCase
{
    /** @var \Phreeagent\Resource */
    protected $resource;

    public function setUp()
    {
        parent::setUp();

        $this->resource = $this->getResourceMock(
            $this->getConfigurationMock(
                $this->getTransportMock()
            )
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->resource = null;
    }

    /**
     * @param $configuration
     * @return \Phreeagent\Resource
     */
    protected function getResourceMock($configuration)
    {
        $resource = $this->getMockForAbstractClass('\Phreeagent\Resource', array($configuration));

        $resource->expects($this->any())
            ->method('loadData')
            ->will($this->returnValue(true));

        $resource->expects($this->any())
            ->method('toJson')
            ->will($this->returnValue('{}'));

        $resource->expects($this->any())
            ->method('toArray')
            ->will($this->returnValue(array()));

        return $resource;
    }

    /**
     * @test
     */
    public function testSetupCorrectly()
    {
        $this->assertInstanceOf('\Phreeagent\Resource', $this->resource);
    }

    /**
     * @test
     */
    public function testGetAuthHeaders()
    {
        $auth_headers = $this->resource->getAuthHeaders();

        $this->assertTrue(is_array($auth_headers));

        $this->assertArrayHasKey('Authorization', $auth_headers);
        $this->assertArrayHasKey('Accept', $auth_headers);
        $this->assertArrayHasKey('Content-Type', $auth_headers);

        $this->assertContains(self::EXAMPLE_ACCESS_TOKEN, $auth_headers['Authorization']);
    }

    /**
     * @test
     */
    public function testGetResourcePathFromUrl()
    {
        $this->assertEquals(
            '/v2/contacts/2093385',
            $this->resource->getResourcePathFromUrl('https://api.freeagent.com/v2/contacts/2093385')
        );
    }

    /**
     * @test
     */
    public function testGetFullEndpoint()
    {
        $this->assertEquals(
            'https://api.freeagent.com/v2/contacts/2093385',
            $this->resource->getFullEndpoint('/v2/contacts/2093385')
        );
    }
}
