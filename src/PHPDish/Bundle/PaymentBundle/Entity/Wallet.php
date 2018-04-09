<?php

namespace PHPDish\Bundle\PaymentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use PHPDish\Bundle\CoreBundle\Model\DateTimeTrait;
use PHPDish\Bundle\CoreBundle\Model\IdentifiableTrait;
use PHPDish\Bundle\PaymentBundle\Model\WalletHistoryInterface;
use PHPDish\Bundle\PaymentBundle\Model\WalletInterface;
use PHPDish\Bundle\UserBundle\Model\UserAwareTrait;

class Wallet implements WalletInterface
{
    use IdentifiableTrait, UserAwareTrait, DateTimeTrait;

    /**
     * 余额，单位分
     * @var int
     */
    protected $amount = 0;

    /**
     * 冻结余额，单位分
     * @var int
     */
    protected $freezeAmount = 0;

    /**
     * @var WalletHistoryInterface[]|Collection
     */
    protected $histories;

    public function __construct()
    {
        $this->histories = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return Wallet
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return int
     */
    public function getFreezeAmount()
    {
        return $this->freezeAmount;
    }

    /**
     * @param int $freezeAmount
     * @return Wallet
     */
    public function setFreezeAmount($freezeAmount)
    {
        $this->freezeAmount = $freezeAmount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHistories()
    {
        return $this->histories;
    }

    /**
     * {@inheritdoc}
     */
    public function setHistories($histories)
    {
        $this->histories = $histories;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addHistory(WalletHistoryInterface $history)
    {
        $history->setWallet($this);
        $this->histories[] = $history;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice()
    {
        return Money::CNY($this->amount);
    }

    /**
     * {@inheritdoc}
     */
    public function getFreezePrice()
    {
        return Money::CNY($this->freezeAmount);
    }

    /**
     * {@inheritdoc}
     */
    public function freeze($amount)
    {
        if ($this->amount < $amount) {
            throw new \LogicException('Not enough balance');
        }
        $this->amount -= $amount;
        $this->freezeAmount += $amount;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function release($amount)
    {
        if ($this->freezeAmount < $amount) {
            throw new \LogicException('Not enough frozen balance');
        }
        $this->amount += $amount;
        $this->freezeAmount -= $amount;
        return $this;
    }
}