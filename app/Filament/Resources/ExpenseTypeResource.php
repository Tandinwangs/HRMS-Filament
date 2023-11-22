<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseTypeResource\Pages;
use App\Filament\Resources\ExpenseTypeResource\RelationManagers;
use App\Models\ExpenseType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseTypeResource extends Resource
{
    protected static ?string $model = ExpenseType::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Expense';
    protected static ?int $navigationSort = 1;






    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord:true),
                Forms\Components\DatePicker::make('start_date')
                ->required(),
                Forms\Components\DatePicker::make('end_date')
                ->after('start_date'),
                Forms\Components\Toggle::make('status')->required(),

                
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('start_date')
            ->dateTime(),
            
            Tables\Columns\TextColumn::make('end_date')
            ->dateTime(),

            Tables\Columns\IconColumn::make('status')
            ->boolean(),        
            ])
        ->filters([
            //
        ])
        ->defaultSort('name') // Set your default sorting column here
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
        ])
        ->bulkActions([
            //Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListExpenseTypes::route('/'),
            'create' => Pages\CreateExpenseType::route('/create'),
            'edit' => Pages\EditExpenseType::route('/{record}/edit'),
        ];
    }    
}
