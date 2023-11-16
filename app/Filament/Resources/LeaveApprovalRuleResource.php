<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveApprovalRuleResource\Pages;
use App\Filament\Resources\LeaveApprovalRuleResource\RelationManagers;
use App\Models\LeaveApprovalRule;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\LeaveType;

class LeaveApprovalRuleResource extends Resource
{
    protected static ?string $model = LeaveApprovalRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Setting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('For')
                    ->required()
                    ->maxLength(255)
                    ->default("Leave")
                    ->disabled(),
                Forms\Components\TextInput::make('RuleName')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type_id')
                ->options(
                    LeaveType::all()->pluck('name', 'id')->toArray()
                )
                ->required(),
                Forms\Components\DatePicker::make('start_date')
                ->required(),
                Forms\Components\DatePicker::make('end_date'),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type.name'),
                Tables\Columns\TextColumn::make('RuleName'),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ApprovalConditionsRelationManager::class,
            RelationManagers\LeaveFormulasRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveApprovalRules::route('/'),
            'create' => Pages\CreateLeaveApprovalRule::route('/create'),
            'edit' => Pages\EditLeaveApprovalRule::route('/{record}/edit'),
        ];
    }    
}
