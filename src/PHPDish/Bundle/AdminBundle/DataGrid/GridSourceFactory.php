<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\AdminBundle\DataGrid;

use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\EntityManagerInterface;
use PHPDish\Bundle\ResourceBundle\Metadata\ResourceRegistry;

class GridSourceFactory
{
    /**
     * @var ResourceRegistry
     */
    protected $resourceRegistry;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * [
     *     'PHPDish\Bundle\UserBundle\UserInterface' => 'phpdish_admin.source_factory.user'
     * ]
     * @var GridSourceFactoryInterface[]
     */
    protected $factory;

    /**
     * @var Grid
     */
    protected $grid;

    public function __construct(
        ResourceRegistry $resourceRegistry,
        EntityManagerInterface $entityManager,
        Grid $grid
    ) {
        $this->resourceRegistry = $resourceRegistry;
        $this->entityManager = $entityManager;
        $this->grid = $grid;
    }

    /**
     * 获取grid source
     *
     * @param string $sourceClass
     * @return Entity
     * @throws \ReflectionException
     */
    public function get($sourceClass)
    {
        if (isset($this->factory[$sourceClass])) {
            $factory = $this->factory[$sourceClass];
            $source = $factory->factory();
        } else {
            $reflection = new \ReflectionClass($sourceClass);
            if (!$reflection->isInstantiable()) {
                throw new \InvalidArgumentException(sprintf('The "%s" cannot be instantiated'));
            }
            $source = new Entity($sourceClass);
        }
        return $source;
    }

    /**
     * 获取 grid
     * @param string $sourceClass
     * @return Grid
     * @throws \ReflectionException
     */
    public function grid($sourceClass)
    {
        $source = $this->get($sourceClass);
        $this->grid->setSource($source);
        return $this->grid;
    }

    public function addFactory($sourceClass, $factory)
    {
        $this->factory[$sourceClass] = $factory;
    }
}