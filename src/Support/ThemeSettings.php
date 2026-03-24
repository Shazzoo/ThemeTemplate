<?php

namespace Vendor\ThemeName\Support;

use App\Models\Navigation;
use App\Models\Page;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use FinnWiel\ShazzooMedia\Components\Forms\ShazzooMediaPicker;

class ThemeSettings 
{
    public static function schema()
    {
        return Group::make()
            ->schema([
                Section::make('General Settings')
                    ->columns(2)
                    ->schema([
                        TextInput::make('company'),
                        TextInput::make('copyright'),
                        ShazzooMediaPicker::make('logo'),
                        Select::make('main_navigation')
                            ->label('Hoofd menu')
                            ->options(function () {
                                return Navigation::all()
                                    ->pluck('title', 'id');
                            })
                            ->required()
                            ->reactive(),
                        Repeater::make('nav_buttons')
                            ->collapsible(true)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->label('Titel'),
                                Select::make('page')
                                    ->label('Pagina')
                                    ->options(function () {
                                        return Page::all()
                                            ->pluck('title', 'id');
                                    })
                                    ->required()
                                    ->reactive(),
                            ])
                    ]),
                Section::make('Adres')
                    ->schema([
                        TextInput::make('address'),
                        TextInput::make('city'),
                        TextInput::make('zip'),
                    ])->columnSpan(1),
                Section::make('Contact')
                    ->schema([
                        TextInput::make('phone'),
                        TextInput::make('email'),
                        TextInput::make('linkedin_link'),
                    ])->columnSpan(1),
            ])
            ->columns(2);
    }
}
