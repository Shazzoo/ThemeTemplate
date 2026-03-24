# Theme Template

This repository is a GitHub template for creating a theme for Shazzoo's CMS.

## Quick start

1. Click **Use this template** on GitHub and create your new repository.
2. Clone it into your CMS at `storage/app/themes/<theme-name>`.
3. Run the initializer command:

```bash
composer template:init -- --vendor=your-vendor --name="Your Theme"
```

Example:

```bash
composer template:init -- --vendor=shazzoo --name="Corporate Theme"
```

This updates placeholders like:

- `theme-name` -> `corporate-theme`
- `ThemeName` -> `CorporateTheme`
- `template-vendor/theme-name` -> `shazzoo/corporate-theme`
- `TemplateVendor\\ThemeName` -> `Shazzoo\\CorporateTheme`
- `x-template-vendor-theme-name::...` -> `x-shazzoo-corporate-theme::...`


## Command options

You can also run the script directly:

```bash
php bin/theme-init.php --vendor=your-vendor --name="Your Theme"
```

Useful flags:

- `--dry-run` shows what would change without writing files
- `--no-interaction` disables prompts (cleanup defaults to no)
- `--help` shows usage

After a successful run, the command asks if you want to remove the initializer (`bin/theme-init.php`) and its Composer scripts from the new repo.

## Manual setup (if you skip the command)

Replace all occurrences of:

- `theme-name`
- `ThemeName`
- `template-vendor/theme-name`
- `TemplateVendor\\ThemeName`
- `template-vendor-theme-name`
