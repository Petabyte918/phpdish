<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\WebBundle\Menu;

use Knp\Menu\FactoryInterface;

class NotificationMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMenu(array $options = [])
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('menu.inbox', [
            'route' => 'chat_inbox',
        ]);
        $menu->addChild('menu.sent', [
            'route' => 'chat_sent',
        ]);

        $menu->addChild('menu.notification', ['route' => 'notifications'])
            ->setAttribute('class', 'list-group-item if i-bell-o');

        return $menu;
    }
}
