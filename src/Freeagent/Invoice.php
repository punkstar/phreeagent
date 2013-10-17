<?php
namespace Freeagent;
use Freeagent\Invoice\InvoiceItem;

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
    protected $due_on;
    protected $written_off_date;
    protected $invoice_items = array();

    public $comments;
    public $payment_terms_in_days;
    public $discount_percent;
    public $reference;
    public $currency;
    public $exchange_rate;
    public $net_value;
    public $paid_value;
    public $due_value;
    public $status;
    public $omit_header;
    public $total_value;
    public $ec_status; // @TODO Add validation

    /**
     * @param \DateTime $date
     */
    public function setDatedOn(\DateTime $date)
    {
        $this->dated_on = $date->format(\DateTime::ISO8601);
    }

    /**
     * @param \DateTime $date
     */
    public function setDueOn(\DateTime $date)
    {
        $this->due_on = $date->format(\DateTime::ISO8601);
    }

    /**
     * @param \DateTime $date
     */
    public function setWrittenOffDate(\DateTime $date)
    {
        $this->written_off_date = $date->format(\DateTime::ISO8601);
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
        /*
         * Add the basic data
         */
        $keys = array(
            'dated_on', 'due_on', 'reference', 'currency', 'exchange_rate', 'net_value', 'total_value', 'paid_value',
            'due_value', 'status', 'omit_header', 'payment_terms_in_days'
        );

        foreach ($keys as $key) {
            $this->$key = $response_data->invoice->$key;
        }

        /*
         * Add the invoice items
         */
        $invoice_item_keys = array(
            'position', 'description', 'item_type', 'price', 'quantity', 'category'
        );
        foreach ($response_data->invoice->invoice_items as $invoice_item_data) {
            $invoice_item = new InvoiceItem();

            foreach ($invoice_item_keys as $invoice_item_key) {
                $invoice_item->$invoice_item_key = $invoice_item_data->$invoice_item_key;
            }

            if (isset($invoice_item_data->category)) {
                $invoice_item->category = $this->getResourcePathFromUrl($invoice_item_data->category);
            }

            $this->addInvoiceItem($invoice_item);
        }

        /*
         * Add the contact
         */
        $contact = new Contact($this->config);
        $contact->url = $this->getResourcePathFromUrl($response_data->invoice->contact);
    }

    /**
     * Send an email containing the invoice as a pdf attachment.
     *
     * @param $to_email
     * @param $from_email
     * @param $subject
     * @param $body
     */
    public function sendEmail($to_email, $from_email, $subject, $body)
    {
        $post_data = array(
            'invoice' => array(
                'email' => array(
                    'to' => $to_email,
                    'from' => $from_email,
                    'subject' => $subject,
                    'body'  => $body
                )
            )
        );

        Transport::post(
            sprintf(
                "%s/send_email",
                $this->getFullEndpoint($this->url)
            ),
            $this->getAuthHeaders(),
            json_encode($post_data)
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $invoice_items = array();

        foreach ($this->invoice_items as $invoice_item) {
            /** @var Invoice\InvoiceItem $invoice_item */
            $invoice_items[] = $invoice_item->toArray();
        }

        return array(
            'invoice' => array(
                'contact' => $this->contact->url,
                'project' => null, // @TODO Add projects
                'comments' => $this->comments,
                'discount_percent' => $this->discount_percent,
                'dated_on' => $this->dated_on,
                'due_on' => $this->due_on,
                'exchange_rate' => $this->exchange_rate,
                'payment_terms_in_days' => $this->payment_terms_in_days,
                'currency' => $this->currency,
                'ec_status' => $this->ec_status,
                'written_off_date' => $this->written_off_date,
                'invoice_items' => $invoice_items
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
