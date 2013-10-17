<?php
namespace Freeagent\Invoice;

/**
 * Class InvoiceItem
 *
 * @package Freeagent\Invoice
 */
class InvoiceItem
{
    /**
     * @var string
     */
    public $item_type;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var float
     */
    public $price;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $sales_tax_rate;

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'item_type'      => $this->item_type,
            'quantity'       => $this->quantity,
            'price'          => $this->price,
            'description'    => $this->description,
            'sales_tax_rate' => $this->sales_tax_rate
        );
    }
}
