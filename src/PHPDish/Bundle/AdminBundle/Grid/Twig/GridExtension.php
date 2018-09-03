<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\AdminBundle\Grid\Twig;

use PHPDish\Bundle\AdminBundle\Grid\GridInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TemplateWrapper;

class GridExtension extends AbstractExtension
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $baseTemplate;

    /**
     * @var TemplateWrapper[]
     */
    protected $templates = [];

    public function __construct(Environment $twig, $baseTemplate)
    {
        $this->twig = $twig;
        $this->baseTemplate = $baseTemplate;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('grid', [$this, 'grid'])
        ];
    }

    /**
     * 渲染一个grid
     *
     * @param GridInterface $grid
     * @param null $theme
     * @return string
     */
    public function grid(GridInterface $grid, $theme =  null)
    {
        $this->initializeTemplate($theme);
        $grid->initialize();
        return $this->renderBlock('grid', [
            'grid' => $grid
        ]);
    }

    /**
     * 是否有block
     *
     * @param string $name
     * @return bool
     */
    protected function hasBlock($name)
    {
        foreach ($this->templates as $template) {
            if ($template->hasBlock($name)) {
                return true;
            }
        }
        return false;
    }

    protected function renderBlock($name, $context = [])
    {
        foreach ($this->templates as $template) {
            if (!$template->hasBlock($name)) {
                continue;
            }
            return $template->renderBlock($name, $context);
        }
        throw new \InvalidArgumentException(sprintf('The block "%s" is not found', $name));
    }

    protected function initializeTemplate($theme)
    {
        if ($this->templates) {
            return;
        }
        if ($theme) {
            $themeTemplate = $this->twig->load($theme);
            $this->templates[] = $themeTemplate;
        }
        $this->templates[] = $this->twig->load($this->baseTemplate);
    }
}