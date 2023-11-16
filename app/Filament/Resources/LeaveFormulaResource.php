<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveFormulaResource\Pages;
use App\Filament\Resources\LeaveFormulaResource\RelationManagers;
use App\Models\LeaveFormula;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveFormulaResource extends Resource
{
    protected static ?string $model = LeaveFormula::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('employee_id'),
                Forms\Components\TextInput::make('condition_id')
                    ->maxLength(36),
                Forms\Components\Select::make('condition')
                ->options([
                    'AND' => 'AND',
                    'OR' => 'OR',
                    'NOT' => 'NOT',
                ]),
                Forms\Components\Select::make('field')
                ->options([
                    'User' => 'User',
                    'No. of Days' => 'No. of Days',
                    // Add other field options as needed
                ]),
                Forms\Components\Select::make('operator')
                ->options([
                    'Is' => 'Is',
                    'Is Not' => 'Is Not',
                    'Is Greater Than' => 'Is Greater Than',
                    'Is Less Than' => 'Is Less Than',
                    'Is Less Than or Equal To' => 'Is Less Than or Equal To',
                    'Is Greater Than or Equal To' => 'Is Greater Than or Equal To',
                    // Add other operator options as needed
                ]),
                Forms\Components\TextInput::make('value'),
                Form\Components\Button::make('add_formula')
                    ->text('Add Formula'),
            ])
            ->columns(1);
    }

    public function scripts()
    {
        return <<<SCRIPT
        document.addEventListener('DOMContentLoaded', function () {
            const addFormulaButton = document.getElementById('add_formula');
            const saveButton = document.getElementById('save');
            const formulaContainer = document.getElementById('formula_container');

            let formulaCount = 0;

            addFormulaButton.addEventListener('click', function () {
                // Get values from form components
                const condition = document.getElementById('condition').value;
                const field = document.getElementById('field').value;
                const operator = document.getElementById('operator').value;
                const value = document.getElementById('value').value;

                // Create a new formula line
                const formulaLine = document.createElement('div');
                formulaLine.textContent = `${condition} ${field} ${operator} ${value}`;

                // Append the formula line to the container
                formulaContainer.appendChild(formulaLine);

                // Clear form values
                document.getElementById('condition').value = '';
                document.getElementById('field').value = '';
                document.getElementById('operator').value = '';
                document.getElementById('value').value = '';
            });

            saveButton.addEventListener('click', function () {
                // Collect all formula lines
                const formulas = Array.from(formulaContainer.children).map(line => line.textContent);

                // Send an AJAX request to save the formulas to the database
                // Implement the logic to save formulas here

                // Optionally, you can redirect or show a success message
                alert('Formulas saved successfully!');
            });
        });
        SCRIPT;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('employee_id'),
                Tables\Columns\TextColumn::make('condition_id'),
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('edited_by'),
                Tables\Columns\TextColumn::make('condition'),
                Tables\Columns\TextColumn::make('field'),
                Tables\Columns\TextColumn::make('operator'),
                Tables\Columns\TextColumn::make('value'),
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
            'index' => Pages\ListLeaveFormulas::route('/'),
            'create' => Pages\CreateLeaveFormula::route('/create'),
            'edit' => Pages\EditLeaveFormula::route('/{record}/edit'),
        ];
    }    
}
