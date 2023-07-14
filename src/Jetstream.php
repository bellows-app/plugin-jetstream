<?php

namespace Bellows\Plugins;

use Bellows\PluginSdk\Contracts\Installable;
use Bellows\PluginSdk\Facades\Console;
use Bellows\PluginSdk\Plugin;
use Bellows\PluginSdk\PluginResults\CanBeInstalled;
use Bellows\PluginSdk\PluginResults\InstallationResult;

class Jetstream extends Plugin implements Installable
{
    use CanBeInstalled;

    public int $priority = 100;

    public function install(): ?InstallationResult
    {
        return InstallationResult::create()->installationCommand($this->getInstallationCommand());
    }

    public function requiredComposerPackages(): array
    {
        return [
            'laravel/jetstream',
        ];
    }

    protected function getInstallationCommand(): string
    {
        $stack = strtolower(
            Console::choice(
                'Which stack would you like to use for Jetstream?',
                ['Inertia', 'Livewire'],
            ),
        );

        if ($stack === 'inertia') {
            // TODO: Do we have to deal with this: https://github.com/inertiajs/server/issues/10
            $ssr = Console::confirm('Would you like to enable server-side rendering?', false);
        }

        $teams = Console::confirm('Would you like to enable teams?', false);

        $darkMode = Console::confirm('Would you like to enable dark mode support?', false);

        return 'jetstream:install ' . $stack
            . ($teams ? ' --teams' : '')
            . ($ssr ?? false ? ' --ssr' : '')
            . ($darkMode ? ' --dark' : '');
    }
}
