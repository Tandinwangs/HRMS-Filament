<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomUserResource\Pages;
use App\Filament\Resources\CustomUserResource\RelationManagers;
use App\Models\CustomUser;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Support\Facades\Hash;

class CustomUserResource extends Resource
{
    protected static ?string $model = CustomUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->dehydrateStateUsing(static function (null|string $state) {
                        return filled($state) ? Hash::make($state) : null;
                    })
                    ->required(static function ($livewire) {
                        // Check for the specific Page instance
                        return $livewire instanceof App\Filament\Resources\CustomUserResource\Pages\CreateCustomUser;
                    })
                    ->dehydrated(static function (null|string $state) {
                        return filled($state);
                    })
                    ->label(static function ($livewire) {
                        // Customize the label based on the specific Page instance
                        return $livewire instanceof App\Filament\Resources\CustomUserResource\Pages\EditCustomUser ? 'New Password' : 'Password';
                    })
                ,
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('expires_at'),
                Forms\Components\DateTimePicker::make('two_factor_expires_at'),
                Forms\Components\TextInput::make('two_factor_code')
                    ->maxLength(255),
                CheckboxList::make('roles')
                ->relationship('roles', 'name')
                ->columns(2)
                ->helperText('Only Choose One!')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('first_name'),
                Tables\Columns\TextColumn::make('last_name'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('two_factor_expires_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('two_factor_code'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCustomUsers::route('/'),
            'create' => Pages\CreateCustomUser::route('/create'),
            'edit' => Pages\EditCustomUser::route('/{record}/edit'),
        ];
    }    
}
