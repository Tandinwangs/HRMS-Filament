<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeavePolicyResource\Pages;
use App\Filament\Resources\LeavePolicyResource\RelationManagers;
use App\Models\LeavePolicy;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\LeaveType;

class LeavePolicyResource extends Resource
{
    protected static ?string $model = LeavePolicy::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Leave';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('leave_id')
                ->options(
                    LeaveType::all()->pluck('name', 'id')->toArray()
                )
                ->required()
                ->unique(ignoreRecord: true)
                ->label('Leave Type'),
                Forms\Components\TextInput::make('policy_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('policy_description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date'),
                Forms\Components\Toggle::make('status')
                    ->required(),
                Forms\Components\Toggle::make('is_information_only')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('leavetype.name'),
                Tables\Columns\TextColumn::make('policy_name'),
                Tables\Columns\TextColumn::make('policy_description'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_information_only')
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
            RelationManagers\LeavePlanRelationManager::class,
            RelationManagers\LeaveRulesRelationManager::class,
            RelationManagers\YearEndProcessRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeavePolicies::route('/'),
            'create' => Pages\CreateLeavePolicy::route('/create'),
            'edit' => Pages\EditLeavePolicy::route('/{record}/edit'),
        ];
    }    
}
