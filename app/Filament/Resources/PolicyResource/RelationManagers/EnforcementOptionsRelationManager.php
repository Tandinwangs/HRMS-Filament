<?php

namespace App\Filament\Resources\PolicyResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use App\Models\Policy;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnforcementOptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'enforcementOptions';

    protected static ?string $recordTitleAttribute = 'policy_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('prevent_submission')
                ->required(),   
                Forms\Components\Toggle::make('display_warning')
                ->required(),    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('prevent_submission')
                ->boolean(),        
                Tables\Columns\IconColumn::make('display_warning')
                ->boolean(),             ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
