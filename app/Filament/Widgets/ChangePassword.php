<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\EditRecord;
use Filament\Widgets\Widget;

class ChangePassword extends Widget
{
    protected static ?int $sort = 1;

    protected static string $view = 'filament.widgets.change-password';

    public $password;

}
