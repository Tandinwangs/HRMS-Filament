<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnfrocementOptionResource\Pages;
use App\Filament\Resources\EnfrocementOptionResource\RelationManagers;
use App\Models\EnforcementOption;
use App\Models\policy;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnfrocementOptionResource extends Resource
{
    protected static ?string $model = EnforcementOption::class;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('policy_id')
                ->options(
                    Policy::all()->pluck('name', 'id')->toArray()
                )
                ->required(),
                
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
            'index' => Pages\ListEnfrocementOptions::route('/'),
            'create' => Pages\CreateEnfrocementOption::route('/create'),
            'edit' => Pages\EditEnfrocementOption::route('/{record}/edit'),
        ];
    }    
}
