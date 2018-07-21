<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\UserBundle\Model;

use PHPDish\Bundle\ResourceBundle\Model\DateTimeInterface;
use PHPDish\Bundle\ResourceBundle\Model\IdentifiableInterface;

interface PointHistoryInterface extends IdentifiableInterface, DateTimeInterface, UserAwareInterface
{
    /**
     * 获取积分类型
     *
     * @return string
     */
    public function getType();

    /**
     * 获取积分数量
     *
     * @return int
     */
    public function getAmount();

    /**
     * 是否是收入
     * @return boolean
     */
    public function isIncome();
}