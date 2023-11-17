<?php

namespace App\Filament\Resources\PolicyResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use App\Models\Policy;
use App\Models\RateDefinition;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RateDefinitionsRelationManager extends RelationManager
{
    protected static string $relationship = 'rateDefinitions';

    protected static ?string $recordTitleAttribute = 'travel_type';



    public static function form(Form $form): Form
    {

        return $form
        ->schema([
            Forms\Components\Toggle::make('attachment_required')
                ->required(),
            Forms\Components\Select::make('travel_type')
                ->options([
                    'Domestic' => 'Domestic',
                ])
                ->required(),
            Forms\Components\Select::make('type')
                ->options([
                    'Single Currency' => 'Single Currency',
                ])
                ->required(),
            Forms\Components\Select::make('name')
                ->options([
                    'Nu' => 'Nu',
                ])
                ->required(),
            Forms\Components\Select::make('rate_limit')
                ->options([
                    'daily' => 'Daily',
                    'monthly' => 'Monthly',
                    'yearly' => 'Yearly',
                ])
                ->required(),
    ]);

    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('Policy.name'),

            Tables\Columns\IconColumn::make('attachment_required')
            ->boolean(),        

            Tables\Columns\TextColumn::make('travel_type'),

            Tables\Columns\TextColumn::make('type'),
            
            Tables\Columns\TextColumn::make('name'),

            Tables\Columns\TextColumn::make('rate_limit'),
        ])
            ->filters([

                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->hidden(function ($record) {
                    // Check if $record is an instance of RateDefinition
                    if ($record instanceof \App\Models\RateDefinition) {
                        // Your logic for non-null $record
                        return true;
                    } else {
                        // Handle the case where $record is null
                        return false; // Or provide an appropriate default behavior
                    }
                }),
            
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
