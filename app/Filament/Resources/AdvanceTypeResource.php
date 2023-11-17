<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvanceTypeResource\Pages;
use App\Filament\Resources\AdvanceTypeResource\RelationManagers;
use App\Models\AdvanceType;
use App\Models\ExpenseType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdvanceTypeResource extends Resource
{
    protected static ?string $model = AdvanceType::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Advance/Loan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord:true),
                    Forms\Components\Select::make('expense_type_id')
                    ->options(
                        ExpenseType::all()->pluck('name', 'id')->toArray()
                    )->label('Expense Type'),
                Forms\Components\DatePicker::make('start_date'),
                Forms\Components\DatePicker::make('end_date')
                ->after('start_date'),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('expenseType.name'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListAdvanceTypes::route('/'),
            'create' => Pages\CreateAdvanceType::route('/create'),
            'edit' => Pages\EditAdvanceType::route('/{record}/edit'),
        ];
    }    
}
