<?php
namespace Freeagent;

/**
 * Class Invoice
 *
 * @package Freeagent
 */
class Invoice extends Resource
{
    const CREATE_ENDPOINT = '/v2/invoices';
    const FETCH_ENDPOINT  = '/v2/invoices/%s';

    protected $contact;
    protected $dated_on;
    protected $invoice_items = array();

    public $comments;
    public $payment_terms_in_days;

    /**
     * @param \DateTime $date
     */
    public function setDatedOn(\DateTime $date)
    {
        $this->dated_on = $date->format(\DateTime::ISO8601);
    }

    /**
     * @param Contact $contact
     */
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * @param Invoice\InvoiceItem $invoice_item
     */
    public function addInvoiceItem(Invoice\InvoiceItem $invoice_item)
    {
        $this->invoice_items[] = $invoice_item;
    }

    public function loadData(\stdClass $response_data)
    {
        // @TODO Implement.
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $invoice_items = array();

        foreach ($invoice_items as $invoice_item) {
            /** @var Invoice\InvoiceItem $invoice_item */
            $invoice_items[] = $invoice_item->toArray();
        }

        return array(
            'invoice' => array(
                'contact' => $this->contact->getUrn()
            )
        );
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
