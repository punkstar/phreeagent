<?php
namespace Phreeagent;

/**
 * Class Contact
 *
 * @package Phreeagent
 */
class Contact extends Resource
{
    const CREATE_ENDPOINT = '/v2/contacts';
    const FETCH_ENDPOINT  = '/v2/contacts/%s';

    public $organisation_name;
    public $first_name;
    public $last_name;
    public $email;
    public $contact_name_on_invoices;
    public $country;
    public $charge_sales_tax;
    public $locale;
    public $account_balance;
    public $status;
    public $uses_contact_invoice_sequence;

    /**
     * Return all customers.  An array indexed by email.  Contact objects as values.
     *
     * @return array
     */
    public function all()
    {
        $customer_map = array();
        $customer_page = 1;

        do {
            $all_customers = $this->config->transport->get(
                sprintf("%s?per_page=100&page=%d", $this->getFullEndpoint(self::CREATE_ENDPOINT), $customer_page),
                $this->getAuthHeaders()
            );

            $all_customers_json = json_decode($all_customers->body);

            $page_customer_count = count($all_customers_json->contacts);

            foreach ($all_customers_json->contacts as $contact) {
                if (isset($contact->email)) {
                    $contact_data = new \stdClass();
                    $contact_data->contact = $contact;

                    $contact_obj = new Contact($this->config);
                    $contact_obj->loadData($contact_data);

                    $contact_obj->url = $this->getResourcePathFromUrl($contact->url);

                    $customer_map[$contact->email] = $contact_obj;
                }
            }

            $customer_page++;
        } while ($page_customer_count > 0);

        return $customer_map;
    }

    public function loadData(\stdClass $response_data)
    {
        $keys = array(
            'organisation_name', 'first_name', 'last_name', 'email', 'contact_name_on_invoices', 'country',
            'locale', 'account_balance', 'status', 'uses_contact_invoice_sequence', 'charge_sales_tax'
        );

        foreach ($keys as $key) {
            if (isset($response_data->contact->$key)) {
                $this->$key = $response_data->contact->$key;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $contact_data = array(
            'organisation_name' => $this->organisation_name,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'email'             => $this->email,
            'contact_name_on_invoices' => $this->contact_name_on_invoices,
            'country'           => $this->country,
            'locale'            => $this->locale,
            'account_balance'   => $this->account_balance,
            'status'            => $this->status,
            'charge_sales_tax'  => $this->charge_sales_tax,
            'uses_contact_invoice_sequence' => $this->uses_contact_invoice_sequence
        );

        $contact_data = array_filter($contact_data);

        return array(
            'contact' => $contact_data
        );
    }

    /**
     * @inheritdoc
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
