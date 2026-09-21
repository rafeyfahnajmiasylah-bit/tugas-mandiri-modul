<?php

declare(strict_types=1);

class Transaction
{
    public function __construct(
        private int $id,
        private string $type,
        private float $amount
    ) {
    }
}