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
public function getId(): int
{
    return $this->id;
}

public function getType(): string
{
    return $this->type;
}

public function getAmount(): float
{
    return $this->amount;
}
    public function process(float &$balance): bool
    {
        return match ($this->type) {
            'deposit' => $this->processDeposit($balance),
            'withdrawal' => $this->processWithdrawal($balance),
            default => false,
        };
    }

    private function processDeposit(float &$balance): bool
    {
        $balance += $this->amount;

        return true;
    }

    private function processWithdrawal(float &$balance): bool
    {
        if ($this->amount > $balance) {
            return false;
        }

        $balance -= $this->amount;

        return true;
    }
}