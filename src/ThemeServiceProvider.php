<?php

namespace TemplateVendor\ThemeName;

use App\Support\Settings\SettingsRegistry;
use App\Support\Theming\BlockRegistry;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;


class ThemeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/syphony.php', 'syphony');
    }

    public function boot(SettingsRegistry $settings, BlockRegistry $blocks): void
    {
        // To publish the theme assets, run the following command:

        // GET THE THEME SLUG.
        $slug = Theme::slug();

        // PUBLISH THE ASSETS.
        $assets = [
            __DIR__.'/../resources/public/images' => public_path("themes/{$slug}/images"),
            __DIR__.'/../resources/css' => public_path("themes/{$slug}/css"),
            __DIR__.'/../resources/js' => public_path("themes/{$slug}/js"),
        ];
        $this->publishes($assets, "theme-assets-{$slug}");
        $this->publishes($assets, 'theme-assets');

        // LOAD THE MIGRATIONS.
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // LOAD THE VIEWS.
        $this->loadViewsFrom(__DIR__.'/../resources/views', $slug);

        // LOAD THE TRANSLATIONS.
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', $slug);

        // COMPONENT NAMESPACE.
        Blade::componentNamespace(
            'TemplateVendor\\ThemeName\\View\\Components',
            $slug
        );

        // REGISTER THE THEME SETTINGS.
        $settings->register(\TemplateVendor\ThemeName\Support\ThemeSettings::class);

    }
}
