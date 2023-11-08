<?php

namespace App\Filament\Resources\MasGradeResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GradeStepsRelationManager extends RelationManager
{
    protected static string $relationship = 'gradeSteps';

    protected static ?string $recordTitleAttribute = 'Steps';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(50),
            Forms\Components\Toggle::make('status')
                ->required(),
            Forms\Components\TextInput::make('starting_salary'),
            Forms\Components\TextInput::make('increment'),
            Forms\Components\TextInput::make('ending_salary'),
            Forms\Components\TextInput::make('pay_scale'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\IconColumn::make('status')
                ->boolean(),
            Tables\Columns\TextColumn::make('starting_salary'),
            Tables\Columns\TextColumn::make('increment'),
            Tables\Columns\TextColumn::make('ending_salary'),
            Tables\Columns\TextColumn::make('pay_scale'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime(),
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
