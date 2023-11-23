<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DzongkhagResource\Pages;
use App\Filament\Resources\DzongkhagResource\RelationManagers;
use App\Models\Dzongkhag;
use App\Models\Region;
use App\Models\Country;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DzongkhagResource extends Resource
{
    protected static ?string $model = Dzongkhag::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Work-Structure';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                ->options(
                    Country::all()->pluck('name', 'id')->toArray()
                )
                ->required()
                ->label('Country'),
                Forms\Components\Select::make('region_id')
                ->options(
                    Region::all()->pluck('name', 'id')->toArray()
                )
                ->required()
                ->label('Region'),
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255),       
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('region.name'),
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
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
            'index' => Pages\ListDzongkhags::route('/'),
            'create' => Pages\CreateDzongkhag::route('/create'),
            'edit' => Pages\EditDzongkhag::route('/{record}/edit'),
        ];
    }    
}
