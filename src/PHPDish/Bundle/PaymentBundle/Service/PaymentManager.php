<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\PaymentBundle\Service;

use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPDish\Bundle\PaymentBundle\Model\Payment;
use PHPDish\Bundle\PaymentBundle\Event\PaymentEvent;
use PHPDish\Bundle\PaymentBundle\Model\PaymentInterface;
use PHPDish\Bundle\ResourceBundle\Service\ServiceManagerInterface;
use PHPDish\Bundle\UserBundle\Model\UserInterface;
use Slince\YouzanPay\YouzanPay;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PaymentManager implements PaymentManagerInterface, ServiceManagerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var YouzanPay
     */
    protected $youzanPay;

    /**
     * @var WalletManagerInterface
     */
    protected $walletManager;

    protected $paymentEntity;

    public function __construct(
        $paymentEntity,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        WalletManagerInterface $walletManager,
        YouzanPay $youzanPay
    )
    {
        $this->paymentEntity = $paymentEntity;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->walletManager = $walletManager;
        $this->youzanPay = $youzanPay;
    }

    /**
     * {@inheritdoc}
     */
    public function createPayment(UserInterface $user = null)
    {
        $payment = new Payment();
        $now = Carbon::now();
        $payment->setUser($user)
            ->setStatus(PaymentInterface::STATUS_WAITING)
            ->setCreatedAt($now)
            ->setUpdatedAt($now);
        return $payment;
    }

    /**
     * {@inheritdoc}
     */
    public function savePayment(PaymentInterface $payment)
    {
        //如果没有钱包
        if (!$payment->getWallet()) {
            $payment->setWallet($this->walletManager->getUserWallet($payment->getUser()));
        }
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function charge(PaymentInterface $payment)
    {
        $qrCode = $this->youzanPay->charge([
            'name' => strip_tags($payment->getDescription()),
            'price' => $payment->getAmount(),
            'source' => $payment->getSerialNo()
        ]);
        $payment->setQrId($qrCode->getId());
        $this->savePayment($payment);
        return $qrCode;
    }

    /**
     * {@inheritdoc}
     */
    public function findPaymentByQrId($qrId)
    {
        return $this->getRepository()->findOneBy([
            'qrId' => $qrId
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function notifyPayment($qrId)
    {
        $payment = $this->findPaymentByQrId($qrId);
        if (!$payment) {
            return null;
        }
        //已经成功的不再通知
        if ($payment->getStatus() === PaymentInterface::STATUS_OK) {
            return null;
        }
        $payment->setStatus(PaymentInterface::STATUS_OK)
            ->setUpdatedAt(Carbon::now()); //交易状态

        $this->savePayment($payment);

        //触发交易完成事件
        $this->eventDispatcher->dispatch(PaymentEvent::PAYMENT_PAID, new PaymentEvent($payment));
        return $payment;
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository($this->paymentEntity);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEntities()
    {
        return [
            'paymentEntity' => PaymentInterface::class,
        ];
    }
}