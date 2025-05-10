<?php

declare(strict_types=1);

namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator;
use SplFileInfo;

class MilkdownEditorPlugin extends Plugin
{
    public const LATEX_PLUGINS = ['mathjax'];

    public $features = [
        'blueprints' => 1000,
    ];

    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    public function onPluginsInitialized(): void
    {
        if (!$this->isAdmin()) {
            return;
        }

        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 999],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
        ]);
    }

    public function onTwigTemplatePaths(): void
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    public function onTwigSiteVariables(): void
    {
        if (!$this->isAdmin()) {
            return;
        }

        $this->grav['twig']->twig_vars['milkdown_config'] = $this->getMilkdownConfig();
        $this->grav['assets']->add('plugin://milkdown-editor/dist/milkdown.js');
    }

    private function getMilkdownConfig(): array
    {
        $config = [
            'latex' => false,
        ];

        $locator = $this->grav['locator'];
        assert($locator instanceof UniformResourceLocator);

        $iterator = $locator->getIterator('plugins://');
        foreach ($iterator as $directory) {
            if (!$directory instanceof SplFileInfo) {
                continue;
            }

            if (in_array($directory->getFilename(), static::LATEX_PLUGINS, true)) {
                $config['latex'] = true;
            }
        }

        return $config;
    }
}
