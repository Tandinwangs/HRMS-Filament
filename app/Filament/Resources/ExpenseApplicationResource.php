<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseApplicationResource\Pages;
use App\Filament\Resources\ExpenseApplicationResource\RelationManagers;
use App\Models\ExpenseApplication;
use App\Models\ExpenseType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseApplicationResource extends Resource
{
    protected static ?string $model = ExpenseApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    
    protected static ?string $navigationGroup = 'Expense';

    public static function form(Form $form): Form
    {
        $currentUserId = Auth::id();
        $expenseTypes = ExpenseType::all()->pluck('name', 'id')->toArray();

        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                ->default($currentUserId)
                ->disabled()
                //->hidden()
                ->required(),
                Forms\Components\Select::make('expense_type_id')
                    ->options(
                        ExpenseType::all()->pluck('name', 'id')->toArray()
                    )
                    ->label('Expense Type')
                    ->required()
                    ->reactive(),
                    Forms\Components\TextInput::make('application_date')
                    ->type('date')
                    ->default(now()->toDateString())  // Set default value to current date
                    ->disabled()  // Make the field disabled
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\select::make('travel_type')
                ->options([
                    'Domestic' => 'Domestic',
                    'International'=>'International'
                ])
                ->visible(function ($get) use ($expenseTypes) {
                    $selectedExpenseTypeId = $get('expense_type_id');
                    if (isset($selectedExpenseTypeId) && array_key_exists($selectedExpenseTypeId, $expenseTypes) && $expenseTypes[$selectedExpenseTypeId] === "Conveyance Expense") {
                        return true;
                    }
                    return false;
                }),        
                Forms\Components\select::make('travel_mode')
                ->options([
                    'Car' => 'Car',
                    'Bike'=>'Bike',
                    'Plain'=>'Plain',
                    'Train'=>'Train'                    
                ])
                ->visible(function ($get) use ($expenseTypes) {
                    $selectedExpenseTypeId = $get('expense_type_id');
                    if (isset($selectedExpenseTypeId) && array_key_exists($selectedExpenseTypeId, $expenseTypes) && $expenseTypes[$selectedExpenseTypeId] === "Conveyance Expense") {
                        return true;
                    }
                    return false;
                }),
                Forms\Components\DatePicker::make('travel_from_date')
                ->visible(function ($get) use ($expenseTypes) {
                    $selectedExpenseTypeId = $get('expense_type_id');
                    if (isset($selectedExpenseTypeId) && array_key_exists($selectedExpenseTypeId, $expenseTypes) && $expenseTypes[$selectedExpenseTypeId] === "Conveyance Expense") {
                        return true;
                    }
                    return false;
                }),  
                    Forms\Components\DatePicker::make('travel_to_date')
                    ->after('travel_from_date')
                    ->visible(function ($get) use ($expenseTypes) {
                        $selectedExpenseTypeId = $get('expense_type_id');
                        if (isset($selectedExpenseTypeId) && array_key_exists($selectedExpenseTypeId, $expenseTypes) && $expenseTypes[$selectedExpenseTypeId] === "Conveyance Expense") {
                            return true;
                        }
                        return false;
                    }),                              
                    Forms\Components\TextInput::make('travel_from')
                    ->visible(function ($get) use ($expenseTypes) {
                        $selectedExpenseTypeId = $get('expense_type_id');
                        if (isset($selectedExpenseTypeId) && array_key_exists($selectedExpenseTypeId, $expenseTypes) && $expenseTypes[$selectedExpenseTypeId] === "Conveyance Expense") {
                            return true;
                        }
                        return false;
                    }),                
                    Forms\Components\TextInput::make('travel_to')
                    ->visible(function ($get) use ($expenseTypes) {
                        $selectedExpenseTypeId = $get('expense_type_id');
                        if (isset($selectedExpenseTypeId) && array_key_exists($selectedExpenseTypeId, $expenseTypes) && $expenseTypes[$selectedExpenseTypeId] === "Conveyance Expense") {
                            return true;
                        }
                        return false;
                    }),    
                    Forms\Components\FileUpload::make('attachment') 
                    ->preserveFilenames()                  
                ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('expenseType.name'),
                Tables\Columns\TextColumn::make('application_date')
                ->dateTime(),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('attachment'),   
                Tables\Columns\TextColumn::make('status'),

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
            'index' => Pages\ListExpenseApplications::route('/'),
            'create' => Pages\CreateExpenseApplication::route('/create'),
            'edit' => Pages\EditExpenseApplication::route('/{record}/edit'),
        ];
    }    
}
