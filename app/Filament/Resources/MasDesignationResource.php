<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasDesignationResource\Pages;
use App\Filament\Resources\MasDesignationResource\RelationManagers;
use App\Models\MasDesignation;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class MasDesignationResource extends Resource
{
    protected static ?string $model = MasDesignation::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Employee-Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(200),
                Forms\Components\Toggle::make('status')
                    ->required(),
                Forms\Components\TextInput::make('created_by')
                ->required()
                ->maxLength(36),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable() ,
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')
                ->options([
                    1 => 'Active',
                    0 => 'In-active',
                ])
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
            'index' => Pages\ListMasDesignations::route('/'),
            'create' => Pages\CreateMasDesignation::route('/create'),
            'edit' => Pages\EditMasDesignation::route('/{record}/edit'),
        ];
    }    
}
