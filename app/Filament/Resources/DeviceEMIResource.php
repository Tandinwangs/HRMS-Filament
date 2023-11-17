<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceEMIResource\Pages;
use App\Filament\Resources\DeviceEMIResource\RelationManagers;
use App\Models\DeviceEMI;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeviceEMIResource extends Resource
{
    protected static ?string $model = DeviceEMI::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Advance/Loan';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                ->required()
                ->numeric()
                ->minValue(0),            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('amount'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListDeviceEMIS::route('/'),
            'create' => Pages\CreateDeviceEMI::route('/create'),
            'edit' => Pages\EditDeviceEMI::route('/{record}/edit'),
        ];
    }    
}
