<?php

namespace SpyApp\ThemeEdinburgh;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use SpyApp\ThemeEdinburgh\Console\InstallCommand;

class ThemeEdinburghServiceProvider extends PackageServiceProvider
{
    public static string $name = 'theme-edinburgh';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasCommand(commandClassName: InstallCommand::class);
    }

    public function packageBooted(): void
    {
        //
    }
}
