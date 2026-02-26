<?php

namespace SpyApp\ThemeEdinburgh\Console;

use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\select;

class InstallCommand extends Command
{
    protected $signature = 'edinburgh:install';

    protected $description = 'Install the Edinburgh theme for a Filament panel';

    protected string $importPath = '../../../../vendor/spykapps/theme-edinburgh/resources/css/edinburgh.css';

    public function handle(): void
    {
        $this->components->info('');
        $this->components->info('🏰  Edinburgh Theme Installer');
        $this->components->info('   Royal stone & brass theme for Filament');
        $this->components->info('');

        $panels = Filament::getPanels();

        if (empty($panels)) {
            $this->components->error('No Filament panels found. Please create a panel first.');
            return;
        }

        if (count($panels) === 1) {
            $panelId = array_key_first($panels);
            $this->components->info("📋 Found panel: {$panelId}");
        } else {
            $panelId = select(
                label: 'Which panel should Edinburgh be installed for?',
                options: array_map(
                    fn (Panel $panel): string => $panel->getId(),
                    $panels,
                ),
            );
        }

        $themeCssPath = resource_path("css/filament/{$panelId}/theme.css");

        if (! File::exists($themeCssPath)) {
            $this->components->info("🎨 Creating theme CSS for '{$panelId}' panel...");
            $this->call('make:filament-theme', ['panel' => $panelId]);
        }

        if (! File::exists($themeCssPath)) {
            $this->components->error("Could not find or create theme CSS at: {$themeCssPath}");
            return;
        }

        $themeCss = File::get($themeCssPath);

        if (str_contains($themeCss, 'theme-edinburgh')) {
            $this->components->warn('⚠️  Edinburgh is already installed for this panel.');
            return;
        }

        $edinburghImport = "@import '{$this->importPath}';";
        $filamentImport = "@import '../../../../vendor/filament/filament/resources/css/theme.css';";

        if (str_contains($themeCss, $filamentImport)) {
            File::replaceInFile(
                $filamentImport,
                $filamentImport . "\n" . $edinburghImport,
                $themeCssPath,
            );
            $this->components->info('✅ Edinburgh stylesheet added after Filament base import.');
        } else {
            File::append($themeCssPath, "\n" . $edinburghImport . "\n");
            $this->components->warn('⚠️  Standard Filament import not found. Edinburgh import appended to end of file.');
            $this->components->warn('   Please verify the import order in: ' . $themeCssPath);
        }

        $this->components->info('');
        $this->components->info('🏰  Edinburgh installed successfully!');
        $this->components->info('');
        $this->components->info('   Next steps:');
        $this->components->info('');
        $this->components->info('   1. Register the plugin in your panel provider:');
        $this->components->info('');
        $this->components->info('      use SpyApp\ThemeEdinburgh\ThemeEdinburghPlugin;');
        $this->components->info('');
        $this->components->info('      ->plugin(ThemeEdinburghPlugin::make())');
        $this->components->info('');
        $this->components->info('   2. Compile your assets:');
        $this->components->info('');
        $this->components->info('      npm run build');
        $this->components->info('');
    }
}
