<?php
namespace Phreeagent\Test\Invoice;

use Phreeagent\Invoice\InvoiceItem;
use Phreeagent\Test\TestCase;

class InvoiceItemTest extends TestCase
{
    /**
     * @test
     */
    public function testToArray()
    {
        $invoice_item = new InvoiceItem();

        $invoice_item->item_type = 'Products';
        $invoice_item->quantity = 1;
        $invoice_item->price = 99.99;
        $invoice_item->description = 'This is the description';
        $invoice_item->sales_tax_rate = 20;
        $invoice_item->position = 100;
        $invoice_item->second_sales_tax_rate = 50;
        $invoice_item->category = 'test';

        $this->assertEquals(array(
            'position' => 100,
            'item_type' => 'Products',
            'quantity'  => 1,
            'price'     => 99.99,
            'description' => 'This is the description',
            'sales_tax_rate' => 20,
            'second_sales_tax_rate' => 50,
            'category' => 'test'
        ), $invoice_item->toArray());
    }
}
