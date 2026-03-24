<?php

declare(strict_types=1);

const SKIP_DIRECTORIES = ['.git', 'vendor', 'node_modules'];
const SKIP_FILES = ['theme-init.php'];
const BINARY_EXTENSIONS = [
    'png',
    'jpg',
    'jpeg',
    'gif',
    'webp',
    'ico',
    'pdf',
    'zip',
    'gz',
    'tar',
    'woff',
    'woff2',
    'ttf',
    'eot',
    'otf',
    'mp4',
    'mp3',
    'mov',
    'avi',
];

$arguments = parseArguments(array_slice($_SERVER['argv'], 1));

if (isset($arguments['help'])) {
    usage(0);
}

$vendorInput = $arguments['vendor'] ?? $arguments['positional'][0] ?? null;
$themeInput = $arguments['name'] ?? $arguments['positional'][1] ?? null;
$dryRun = isset($arguments['dry-run']);

if (! is_string($vendorInput) || trim($vendorInput) === '' || ! is_string($themeInput) || trim($themeInput) === '') {
    usage(1);
}

$vendorSlug = slugify($vendorInput);
$vendorNamespace = studly($vendorInput);
$themeSlug = slugify($themeInput);
$themeClass = studly($themeInput);

if ($vendorSlug === '' || $vendorNamespace === '' || $themeSlug === '' || $themeClass === '') {
    fwrite(STDERR, "Unable to derive valid names. Use alphanumeric input.\n");
    exit(1);
}

$replacements = [
    'Vendor\\ThemeName' => $vendorNamespace.'\\'.$themeClass,
    'vendor/theme-name' => $vendorSlug.'/'.$themeSlug,
    'vendor-theme-name' => $vendorSlug.'-'.$themeSlug,
    'ThemeName' => $themeClass,
    'theme-name' => $themeSlug,
    'Shazzoo\\ThemeName' => $vendorNamespace.'\\'.$themeClass,
    'shazzoo/theme-name' => $vendorSlug.'/'.$themeSlug,
    'shazzoo-theme-name' => $vendorSlug.'-'.$themeSlug,
];

$rootPath = realpath(__DIR__.'/..');

if ($rootPath === false) {
    fwrite(STDERR, "Unable to resolve project root path.\n");
    exit(1);
}

[$updatedFiles, $totalReplacements] = applyTemplateReplacements($rootPath, $replacements, $dryRun);

if ($dryRun) {
    fwrite(STDOUT, "Dry run complete. {$totalReplacements} replacement(s) in ".count($updatedFiles)." file(s).\n");
} else {
    fwrite(STDOUT, "Theme initialized. {$totalReplacements} replacement(s) in ".count($updatedFiles)." file(s).\n");
}

if ($updatedFiles !== []) {
    foreach ($updatedFiles as $file) {
        fwrite(STDOUT, "- {$file}\n");
    }
}

exit(0);

function parseArguments(array $argv): array
{
    $parsed = ['positional' => []];

    for ($index = 0; $index < count($argv); $index++) {
        $arg = $argv[$index];

        if ($arg === '--help' || $arg === '-h') {
            $parsed['help'] = true;
            continue;
        }

        if ($arg === '--dry-run') {
            $parsed['dry-run'] = true;
            continue;
        }

        if (str_starts_with($arg, '--vendor=')) {
            $parsed['vendor'] = substr($arg, 9);
            continue;
        }

        if ($arg === '--vendor') {
            $next = $argv[$index + 1] ?? null;
            if (is_string($next) && $next !== '') {
                $parsed['vendor'] = $next;
                $index++;
            }
            continue;
        }

        if (str_starts_with($arg, '--name=')) {
            $parsed['name'] = substr($arg, 7);
            continue;
        }

        if ($arg === '--name') {
            $next = $argv[$index + 1] ?? null;
            if (is_string($next) && $next !== '') {
                $parsed['name'] = $next;
                $index++;
            }
            continue;
        }

        $parsed['positional'][] = $arg;
    }

    return $parsed;
}

function usage(int $exitCode): void
{
    $output = <<<TXT
Usage:
  composer template:init -- --vendor=your-vendor --name="Your Theme"
  php bin/theme-init.php --vendor=your-vendor --name="Your Theme"

Options:
  --vendor   Package vendor, for example "acme"
  --name     Theme name, for example "Corporate Theme"
  --dry-run  Show files that would be changed
  --help     Show this help message

TXT;

    fwrite($exitCode === 0 ? STDOUT : STDERR, $output);
    exit($exitCode);
}

function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';

    return trim($value, '-');
}

function studly(string $value): string
{
    $value = preg_replace('/[^a-zA-Z0-9]+/', ' ', $value) ?? '';
    $value = ucwords(strtolower(trim($value)));

    return str_replace(' ', '', $value);
}

function applyTemplateReplacements(string $rootPath, array $replacements, bool $dryRun): array
{
    $updatedFiles = [];
    $totalReplacements = 0;

    $directory = new RecursiveDirectoryIterator($rootPath, FilesystemIterator::SKIP_DOTS);
    $filtered = new RecursiveCallbackFilterIterator(
        $directory,
        static function (SplFileInfo $entry): bool {
            if ($entry->isDir()) {
                return ! in_array($entry->getFilename(), SKIP_DIRECTORIES, true);
            }

            if (in_array($entry->getFilename(), SKIP_FILES, true)) {
                return false;
            }

            $extension = strtolower(pathinfo($entry->getFilename(), PATHINFO_EXTENSION));
            if ($extension !== '' && in_array($extension, BINARY_EXTENSIONS, true)) {
                return false;
            }

            return true;
        }
    );

    $iterator = new RecursiveIteratorIterator($filtered);

    foreach ($iterator as $entry) {
        if (! $entry instanceof SplFileInfo || ! $entry->isFile()) {
            continue;
        }

        $path = $entry->getPathname();
        $contents = file_get_contents($path);

        if (! is_string($contents) || str_contains($contents, "\0")) {
            continue;
        }

        $updated = str_replace(array_keys($replacements), array_values($replacements), $contents, $count);

        if ($count < 1 || ! is_string($updated)) {
            continue;
        }

        $totalReplacements += $count;
        $relative = ltrim(str_replace($rootPath, '', $path), DIRECTORY_SEPARATOR);
        $updatedFiles[] = $relative;

        if (! $dryRun) {
            file_put_contents($path, $updated);
        }
    }

    sort($updatedFiles);

    return [$updatedFiles, $totalReplacements];
}
