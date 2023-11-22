<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddVehicleResource\Pages;
use App\Filament\Resources\AddVehicleResource\RelationManagers;
use App\Models\AddVehicle;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddVehicleResource extends Resource
{
    protected static ?string $model = AddVehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('vehicle_number')
                ->required()
                ->maxLength(255),
                Forms\Components\Select::make('vehicle_type')
                ->options([
                    'Bolero' => 'Bolero',
                    'COW' => 'COW',
                    'Creta' => 'Creta',
                    'Isuzu D Max' => 'Isuzu D Max',
                    'Isuzu S Cabin' => 'Isuzu S Cabin',
                    'I-20-Active' => 'I-20-Active',
                    'Maruti Ecco' => 'Maruti Ecco',
                    'MUX' => 'MUX',
                    'Motor Bikes' => 'Motor Bikes',
                    'TUV' => 'TUV',
                    'Van' => 'Van',
                ])                    
                ->required(),
                Forms\Components\TextInput::make('vehicle_mileage')
                    ->required(),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vehicle_type'),
                Tables\Columns\TextColumn::make('vehicle_number'),
                Tables\Columns\TextColumn::make('vehicle_mileage'),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
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
            'index' => Pages\ListAddVehicles::route('/'),
            'create' => Pages\CreateAddVehicle::route('/create'),
            'edit' => Pages\EditAddVehicle::route('/{record}/edit'),
        ];
    }    
}
