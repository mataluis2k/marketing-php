<?php

namespace mataluis2k\marapost\InputTypes;

use mataluis2k\marapost\Abstractions\OperationResult;
use mataluis2k\marapost\ResultTypes\GetResult;

class OrderItemInput
{
    /**
     * @var string
     */
    public $item_id;
    /**
     * @var string
     */
    public $price;
    /**
     * @var string
     */
    public $quantity;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $adcode;
    /**
     * @var string
     */
    public $category;

    /**
     * OrderItemInput constructor.
     * @param string $itemId
     * @param string $price
     * @param string $quantity
     * @param string $description
     * @param string $adcode
     * @param string $category
     */
    public function __construct(string $itemId, string $price, string $quantity, string $description,
                                string $adcode, string $category)
    {
        $this->item_id = $itemId;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->description = $description;
        $this->adcode = $adcode;
        $this->category = $category;
    }
}