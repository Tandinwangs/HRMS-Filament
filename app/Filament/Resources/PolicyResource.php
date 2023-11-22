<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PolicyResource\Pages;
use App\Filament\Resources\PolicyResource\RelationManagers;
use App\Filament\Resources\PolicyResource\RelationManagers\RateDefinitionsRelationManager;
use App\Filament\Resources\PolicyResource\RelationManagers\RateLimitsRelationManager;
use App\Filament\Resources\PolicyResource\RelationManagers\EnforcementOptionsRelationManager;
use App\Models\Policy;
use App\Models\RateDefinition;
use App\Models\ExpenseType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PolicyResource extends Resource
{
    protected static ?string $model = Policy::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Expense';

    protected static ?string $navigationLabel = 'Policy';


    protected static ?int $navigationSort = 2;




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('expense_type_id')
                ->options(
                    ExpenseType::all()->pluck('name', 'id')->toArray()
                )
                ->label("Expense type")
                ->required(),
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
                Forms\Components\Textarea::make('description')
                ->rows(2),
                Forms\Components\DateTimePicker::make('start_date')
                ->required(),
                Forms\Components\DateTimePicker::make('end_date')
                ->after('start_date'),
                Forms\Components\Toggle::make('status')->required(),
               
        ]);
    }
    public static function createRateDefinition(Policy $policy)
    {
        
    }

    public static function createRateLimit(RateDefinition $rateDefinition)
    {
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('expenseType.name'),

                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextColumn::make('description'),


                Tables\Columns\TextColumn::make('start_date')
                ->dateTime(),
                
                Tables\Columns\TextColumn::make('end_date')
                ->dateTime(),

                Tables\Columns\IconColumn::make('status')
                ->boolean(), 
                //
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
            RateDefinitionsRelationManager::class,
            RateLimitsRelationManager :: class,
            EnforcementOptionsRelationManager::class,

        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPolicies::route('/'),
            'create' => Pages\CreatePolicy::route('/create'),
            'edit' => Pages\EditPolicy::route('/{record}/edit'),
        ];
    }    
}
