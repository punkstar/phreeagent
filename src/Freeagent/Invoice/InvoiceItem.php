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
     * @var int
     */
    public $position;

    /**
     * @var string
     */
    public $second_sales_tax_rate;

    /**
     * @var string
     */
    public $category;

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
            'sales_tax_rate' => $this->sales_tax_rate,
            'position'       => $this->position,
            'second_sales_tax_rate' => $this->second_sales_tax_rate,
            'category'       => $this->category
        );
    }
}
