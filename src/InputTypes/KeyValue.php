<?php

namespace mataluis2k\marapost\InputTypes;

use mataluis2k\marapost\Abstractions\OperationResult;
use mataluis2k\marapost\ResultTypes\GetResult;

class KeyValue
{
    /**
     * @var string
     */
    public $key;
    public $value;

    public function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}