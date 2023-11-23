<?php

namespace App\Filament\Resources\LeavePolicyResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class YearEndProcessRelationManager extends RelationManager
{
    protected static string $relationship = 'YearEndProcess';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Toggle::make('allow_carryover')
            ->required()
            ->reactive(),
        Forms\Components\TextInput::make('carryover_limit')
            ->visible(function (callable $get) {
                return in_array($get('allow_carryover'), [true]);
            })
            ->required(function(callable $get){
                return in_array($get('allow_carryover'), [true]);
                }),
        
            Forms\Components\Toggle::make('payat_yearend')
                ->required()
                ->reactive(),
            Forms\Components\TextInput::make('min_balance')
            ->visible(function (callable $get) {
                return in_array($get('payat_yearend'), [true]);
            })
            ->required(function(callable $get){
                return in_array($get('payat_yearend'), [true]);
                }),
            Forms\Components\TextInput::make('max_balance')
            ->visible(function (callable $get) {
                return in_array($get('payat_yearend'), [true]);
            })
            ->required(function(callable $get){
                return in_array($get('payat_yearend'), [true]);
                }),
            Forms\Components\Toggle::make('carryforward_toEL')
                ->required()
                ->reactive(),
                Forms\Components\TextInput::make('carryforward_toEL_limit')
            ->visible(function (callable $get) {
                return in_array($get('carryforward_toEL'), [true]);
            })
            ->required(function(callable $get){
                return in_array($get('carryforward_toEL'), [true]);
                }),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('policy.policy_name'),
            Tables\Columns\IconColumn::make('allow_carryover')
                ->boolean(),
            Tables\Columns\TextColumn::make('carryover_limit'),
            Tables\Columns\IconColumn::make('payat_yearend')
                ->boolean(),
            Tables\Columns\TextColumn::make('min_balance'),
            Tables\Columns\TextColumn::make('max_balance'),
            Tables\Columns\IconColumn::make('carryforward_toEL')
                ->boolean(),
            Tables\Columns\TextColumn::make('carryforward_toEL_limit'),
        ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
