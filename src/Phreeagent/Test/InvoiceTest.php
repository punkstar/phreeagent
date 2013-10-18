<?php
namespace Phreeagent\Test;

use Phreeagent\Invoice;

class Invoicetest extends TestCase
{
    /**
     * @test
     * @expectedException \Phreeagent\Exception\UnsuccessfulResponseException
     */
    public function testInvalidJson()
    {
        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->will($this->returnValue($this->loadMockResponse('invoice/invalid_json.json', 400)));

        $configuration = $this->getConfigurationMock($transport);

        $invoice = new Invoice($configuration);
        $invoice->create();
    }

    /**
     * @test
     */
    public function testFetchInvoice()
    {
        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.freeagent.com/v2/invoices/2714')
            ->will($this->returnValue($this->loadMockResponse('invoice/fetch_success.json', 200)));

        $configuration = $this->getConfigurationMock($transport);

        $invoice = new Invoice($configuration);
        $invoice->load(2714);

        $this->assertEquals('001', $invoice->reference);
        $this->assertEquals('GBP', $invoice->currency);
        $this->assertEquals('1.0', $invoice->exchange_rate);
        $this->assertEquals('6750000', $invoice->net_value);
        $this->assertEquals('6750000', $invoice->total_value);
        $this->assertEquals('0', $invoice->paid_value);
        $this->assertEquals('6750000', $invoice->due_value);
        $this->assertEquals('Open', $invoice->status);
        $this->assertEquals(false, $invoice->omit_header);
        $this->assertEquals(30, $invoice->payment_terms_in_days);

        $invoice_items = $invoice->getInvoiceItems();
        $this->assertCount(1, $invoice_items);

        /** @var \Phreeagent\Invoice\InvoiceItem $invoice_item */
        $invoice_item = $invoice_items[0];
        $this->assertInstanceOf('\Phreeagent\Invoice\InvoiceItem', $invoice_item);

        $this->assertEquals(1, $invoice_item->position);
        $this->assertEquals('Death Star', $invoice_item->description);
        $this->assertEquals('Years', $invoice_item->item_type);
        $this->assertEquals('450000', $invoice_item->price);
        $this->assertEquals('15', $invoice_item->quantity);
    }

    /**
     * @test
     */
    public function testMarkAsSent()
    {
        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('put', 'https://api.freeagent.com/v2/invoices/2714/transitions/mark_as_sent')
            ->will($this->returnValue($this->loadMockResponse(null, 200)));

        $configuration = $this->getConfigurationMock($transport);

        $invoice = new Invoice($configuration);
        $invoice->setUrl('/v2/invoices/2714');

        $invoice->markAsSent();
    }

    /**
     * @test
     */
    public function testSendEmail()
    {
        $transport = $this->getMock('\Phreeagent\Transport', array('request'));

        $transport->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.freeagent.com/v2/invoices/2714/send_email')
            ->will($this->returnValue($this->loadMockResponse(null, 200)));

        $configuration = $this->getConfigurationMock($transport);

        $invoice = new Invoice($configuration);
        $invoice->setUrl('/v2/invoices/2714');

        $invoice->sendEmail('to@test.com', 'from@test.com', 'Subject', 'Message');
    }

    public function test
}
