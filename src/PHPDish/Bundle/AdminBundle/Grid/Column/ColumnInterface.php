<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\AdminBundle\Grid\Column;

use PHPDish\Bundle\AdminBundle\Grid\Filter\FilterInterface;

interface ColumnInterface
{
    /**
     * 获取列名
     *
     * @return string
     */
    public function getName();

    /**
     * 获取类型
     *
     * @return string
     */
    public function getType();

    /**
     * @return bool
     */
    public function isSortable();

    /**
     * @return bool
     */
    public function isFilterable();

    /**
     * 获取筛选项
     *
     * @return FilterInterface[]
     */
    public function getFilters();

    /**
     * 根据operator获取filter
     *
     * @param string $operator
     * @return FilterInterface|null
     */
    public function getFilterByOperator($operator);

    /**
     * 添加筛选项
     *
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter);
}