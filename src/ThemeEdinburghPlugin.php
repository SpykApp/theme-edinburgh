<?php

namespace SpyApp\ThemeEdinburgh;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ThemeEdinburghPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'spyapp-theme-edinburgh';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
