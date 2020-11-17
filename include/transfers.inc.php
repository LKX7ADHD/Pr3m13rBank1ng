<?php

include 'accounts.inc.php';

/**
 * Represents monetary value
 */
class Currency
{
    /**
     * @var string amount of money
     */
    private $value;

    /**
     * @param $value string amount of money
     */
    public function __construct(string $value)
    {
        $this->value = trim($value);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param $currency Currency amount of money to add to the receiver
     */
    public function add(Currency $currency)
    {
        $this->value = bcadd($this->value, $currency->value, 2);
    }

    /**
     * @param Currency $currency amount of money to subtract from the receiver
     */
    public function subtract(Currency $currency)
    {
        $this->value = bcsub($this->value, $currency->value, 2);
    }

    public function equalTo(Currency $other)
    {
        return bccomp($this->value, $other->value, 2) == 0;
    }

    public function lessThan(Currency $other)
    {
        return bccomp($this->value, $other->value, 2) == -1;
    }

    public function greaterThan(Currency $other)
    {
        return $other->lessThan($this);
    }

    public function lessThanOrEqualTo(Currency $other)
    {
        return !$other->lessThan($this);
    }

    public function greaterThanOrEqualTo(Currency $other)
    {
        return !$this->lessThan($other);
    }
}

/**
 * Sends money from an account to another
 * @param Account $sender
 * @param Account $receiver
 * @param Currency $amount
 */
function performTransfer(Account $sender, Account $receiver, Currency $amount)
{

}

/**
 * Reverse a transfer
 * @param int $transferID
 */
function reverseTransfer(int $transferID)
{

}

?>