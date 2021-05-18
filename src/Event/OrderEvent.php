<?php

namespace App\Event;

use App\Entity\Order;

class OrderEvent
{

    private array $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function getArray(): array
    {
        return $this->array;
    }

}