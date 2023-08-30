<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'داشبورد';
    protected static ?string $label = 'کاربر';
    protected static ?string $pluralLabel = 'کاربران';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            SpatieMediaLibraryFileUpload::make('avatar')->label('تصویر')
                ->image()
                ->imageEditor()
                ->imageEditorMode(2)
                ->imageEditorAspectRatios([
                    '16:9',
                    '4:3',
                    '1:1',
                ])
                ->minSize(512)
                ->directory('users/avatars')
                ->conversion('thumb')
                ->maxWidth(700)
                ->collection('avatars')
                ->responsiveImages(),
            Grid::make([
                'default' => 1,
                'sm' => 1,
                'md' => 3,
            ])->schema([
                TextInput::make('name')->label('نام و نام خانوادگی')->required(),
                TextInput::make('email')->label('ایمیل')->email()->default(''),
                TextInput::make('mobile')->label('موبایل')->required()->length(11),
                TextInput::make('password')->label('رمزعبور')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#'),
                SpatieMediaLibraryImageColumn::make('avatar')->label('آواتار')
                    ->conversion('thumb')
                    // ->collection('avatars')
                    ->defaultImageUrl(fn() => filament()->hasDarkMode() ? url('/assets/images/user-light.svg') : url('/assets/images/user-dark.svg'))
                    ->circular(),
                TextColumn::make('name')->label('نام و نام خانوادگی')->searchable()->sortable(),
                TextColumn::make('mobile')->label('موبایل')->searchable()->sortable(),
                TextColumn::make('verify_code')->label('کد تایید'),
                TextColumn::make('created_at')->label('ایجاد')->jalaliDate(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Impersonate::make('impersonate'),
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
