<?php
 
namespace Vendor\ThemeName;
 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
 
class Theme
{
    public static function slug(): string
    {
        $namespace = static::class; // "Vendor\CorporateTheme\Theme"
 
        $base = explode('\\', $namespace) ?? 'theme'; // "CorporateTheme"
 
        // STRIP THE LAST PART.
        array_pop($base);
 
        return strtolower(Str::snake(join('',$base), '-')); // "corporate-theme"
    }
 
    public static function provider(): string
    {
        return ThemeServiceProvider::class;
    }
}
