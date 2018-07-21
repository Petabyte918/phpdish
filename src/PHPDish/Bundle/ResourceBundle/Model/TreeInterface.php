<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\ResourceBundle\Model;

use Doctrine\Common\Collections\Collection;

interface TreeInterface
{
    /**
     * 设置父节点
     *
     * @param TreeInterface $parent
     * @return self
     */
    public function setParent($parent);

    /**
     * 获取父节点
     *
     * @return TreeInterface
     */
    public function getParent();

    /**
     * 获取跟节点
     *
     * @return TreeInterface
     */
    public function getRoot();

    /**
     * 获取当前节点层级
     *
     * @return int
     */
    public function getLevel();

    /**
     * 获取子节点
     * @return TreeInterface[]|Collection
     */
    public function getChildren();

    /**
     * 获取节点左边数据
     * @return int
     */
    public function getLeft();

    /**
     * 获取节点右边数据
     * @return int
     */
    public function getRight();
}