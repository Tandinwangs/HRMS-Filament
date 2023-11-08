<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StorelocationResource\Pages;
use App\Filament\Resources\StorelocationResource\RelationManagers;
use App\Models\Storelocation;
use App\Models\TimeZone;
use App\Models\Dzongkhag;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StorelocationResource extends Resource
{
    protected static ?string $model = Storelocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Work-Structure';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dzongkhag_id')
                ->options(
                    Dzongkhag::all()->pluck('name', 'id')->toArray()
                )
                ->required(),
                Forms\Components\Select::make('timezone_id')
                ->options(
                    TimeZone::all()->pluck('name', 'id')->toArray()
                )
                ->required(),
                Forms\Components\TextInput::make('name')
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
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('dzongkhag.country.name', 'Country'),
                Tables\Columns\TextColumn::make('dzongkhag.region.name', 'Region'),
                Tables\Columns\TextColumn::make('dzongkhag.name'),
                Tables\Columns\TextColumn::make('timezone.name'),  
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
            'index' => Pages\ListStorelocations::route('/'),
            'create' => Pages\CreateStorelocation::route('/create'),
            'edit' => Pages\EditStorelocation::route('/{record}/edit'),
        ];
    }    
}
