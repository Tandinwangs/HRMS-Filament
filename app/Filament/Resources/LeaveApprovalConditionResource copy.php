<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveApprovalConditionResource\Pages;
use App\Filament\Resources\LeaveApprovalConditionResource\RelationManagers;
use App\Models\LeaveApprovalCondition;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveApprovalConditionResource extends Resource
{
    protected static ?string $model = LeaveApprovalCondition::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Setting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('approval_rule_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('hierarchy_id')
                    ->maxLength(36),
                Forms\Components\TextInput::make('employee_id'),
                Forms\Components\TextInput::make('created_by')
                    ->required(),
                Forms\Components\TextInput::make('edited_by'),
                Forms\Components\TextInput::make('approval_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('MaxLevel')
                    ->maxLength(255),
                Forms\Components\Toggle::make('AutoApproval'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('approval_rule_id'),
                Tables\Columns\TextColumn::make('hierarchy_id'),
                Tables\Columns\TextColumn::make('employee_id'),
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('edited_by'),
                Tables\Columns\TextColumn::make('approval_type'),
                Tables\Columns\TextColumn::make('MaxLevel'),
                Tables\Columns\IconColumn::make('AutoApproval')
                    ->boolean(),
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
            'index' => Pages\ListLeaveApprovalConditions::route('/'),
            'create' => Pages\CreateLeaveApprovalCondition::route('/create'),
            'edit' => Pages\EditLeaveApprovalCondition::route('/{record}/edit'),
        ];
    }    
}
