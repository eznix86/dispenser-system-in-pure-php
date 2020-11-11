<?php

namespace App\Bank\Money;

class Money
{
    /**
     * @var int
     */
    private $amount;
    /**
     * @var string
     */
    private $currency = 'MUR';


    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @param string $amount
     * @return Money
     */
    public function setAmountWithDecimal(string $amount)
    {
        $this->amount = (int) $amount * 100;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function add(Money $money)
    {
        $this->amount += $money->amount;
        return $this;
    }

    public function substract(Money $money)
    {
        $this->amount -= $money->amount;
        return $this;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function __toString()
    {
        return sprintf("%.2f %s", $this->amount / 100, $this->getCurrency());
    }
}