<?php
namespace Phreeagent\Test;

use Phreeagent\Contact;

class ContactTest extends TestCase
{
    /**
     * @test
     */
    public function testLoadData()
    {
        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.freeagent.com/v2/contacts/3293')
            ->will($this->returnValue($this->loadMockResponse('contact/fetch_success.json', 200)));

        $configuration = $this->getConfigurationMock($transport);

        $contact = new Contact($configuration);
        $contact->load(3293);

        $this->assertEquals('Test', $contact->organisation_name);
        $this->assertEquals('Nick', $contact->first_name);
        $this->assertEquals('Jones', $contact->last_name);
        $this->assertEquals(true, $contact->contact_name_on_invoices);
        $this->assertEquals('United Kingdom', $contact->country);
        $this->assertEquals('Auto', $contact->charge_sales_tax);
        $this->assertEquals('en', $contact->locale);
        $this->assertEquals(6750000, $contact->account_balance);
        $this->assertEquals('Active', $contact->status);
        $this->assertEquals(false, $contact->uses_contact_invoice_sequence);
        // created_at
        // updated_at
    }

    /**
     * @test
     */
    public function testFetchNotFound()
    {
        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.freeagent.com/v2/contacts/3293')
            ->will($this->returnValue($this->loadMockResponse('contact/fetch_not_found.json', 404)));

        $configuration = $this->getConfigurationMock($transport);

        $invoice = new Contact($configuration);
        $invoice->load(3293);
    }
}
