# Freeagent PHP Library

[![Build Status](https://travis-ci.org/punkstar/phreeagent.png?branch=develop)](https://travis-ci.org/punkstar/phreeagent)
[![Coverage Status](https://coveralls.io/repos/punkstar/phreeagent/badge.png?branch=develop)](https://coveralls.io/r/punkstar/phreeagent?branch=develop)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/punkstar/phreeagent/badges/quality-score.png?s=09805340d3b322a0f649446c484d9134438b7bfd)](https://scrutinizer-ci.com/g/punkstar/phreeagent/)

This library is incomplete, but you may find it useful.

## Implemented Features

* Invoices
    * Fetch Single
    * Create
    * Send Email
    * Mark as sent
* Contacts
    * Create
    * Fetch Single
    * Fetch All
* User
    * Create
    * Fetch Single
* Project
    * Create
    * Fetch Single
* Task
    * Create
    * Fetch Single
* Timeslip
    * Create
    * Fetch Single

## Generating Credentials

The second version of the Freeagent API uses OAuth 2.0 for authentication.  To get started with this library you will
need three things:

* OAuth Identifier (`$client_id`)
* OAuth Secret (`$client_secret`)
* Refresh Token (`$refresh_token`)

You can get an OAuth Identifer and OAuth Secret from the [Freeagent Developer Dashboard](https://dev.freeagent.com/apps).

You'll then need to go through the motions with the [Google OAuth 2.0 Playground](https://dev.freeagent.com/docs/quick_start) to get your access token, and then generate your refresh token.

Once you have your refresh token, use it along with your Freeagent App to configure your `Phreeagent\Config` instance, and you're away.

## Using the library

I've decided to experiment a little on how to build an API client library.  The approach I've taken is focussing all of the configuration into a class and passing that around to all the resources that you may want to create.

Creating a project, as an example, will look like this:

    $config = new Phreeagent\Config($client_id, $client_secret, $refresh_token);
    
    $contact = new Phreeagent\Contact($config);
    $contact->load(123);
    
    $project = new Phreeagent\Project($config);
    $project->setContact($contact);
    $project->name = "Example Project";
    $project->currency = "GBP";
    $project->create();

## Example

    <?php
    require_once "vendor/autoload.php";

    $client_id = '123';
    $client_secret = '456';
    $refresh_token = '789';

    $customer_email = 'nick@nicksays.co.uk';
    $customer_firstname = 'Nick';
    $customer_lastname = 'Jones';
    $item_name  = 'Test';
    $invoice_total = 29.99;
    $included_vat = true;
    $order_id = 123;

    $config = new Phreeagent\Config($client_id, $client_secret, $refresh_token);

    /*
     * Find the customer, create if not available.
     */
    $contact = new \Phreeagent\Contact($config);
    $all_customers = $contact->all();

    if (isset($all_customers[$customer_email])) {
        $customer = $all_customers[$customer_email];
    } else {
        $customer = new \Phreeagent\Contact($config);
        $customer->first_name = $customer_firstname;
        $customer->last_name = $customer_lastname;
        $customer->email = $customer_email;
        $customer->create();
    }

    /*
     * Create the invoice
     */
    $invoice = new Phreeagent\Invoice($config);
    $invoice->setContact($customer);
    $invoice->setDatedOn(new DateTime('now'));
    $invoice->comments = 'This is the invoice comment';
    $invoice->payment_terms_in_days = 0;

    /*
     * Create the invoice item
     */
    $invoice_item = new \Phreeagent\Invoice\InvoiceItem();
    $invoice_item->description = $item_name;
    $invoice_item->item_type = 'Products';
    $invoice_item->quantity = 1;
    $invoice_item->price = ($included_vat) ? ($invoice_total/1.2) : $invoice_total;
    $invoice_item->sales_tax_rate = ($included_vat) ? 20 : null;

    /*
     * Add the item to the invoice and create
     */
    $invoice->addInvoiceItem($invoice_item);
    $invoice->create();

    /*
     * Email the invoice to the customer
     */
    $message_body = <<<TEXT
    $customer_firstname $customer_lastname,

    Thanks for purchasing from us!  We've attached your invoice for your reference.  Your invoice is paid, there's no further action required on your part.

    Regards,

    Test

    TEXT;

    $invoice->sendEmail($customer_email, 'test@test.com', 'Your Invoice for #' . $order_id, $message_body);

    /*
     * Mark the invoice as sent
     */
    $invoice->markAsSent();
