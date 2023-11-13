<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveRuleResource\Pages;
use App\Filament\Resources\LeaveRuleResource\RelationManagers;
use App\Models\LeaveRule;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveRuleResource extends Resource
{
    protected static ?string $model = LeaveRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('policy_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('grade_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('created_by')
                    ->required(),
                Forms\Components\TextInput::make('edited_by'),
                Forms\Components\TextInput::make('duration')
                    ->required(),
                Forms\Components\TextInput::make('uom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->required(),
                Forms\Components\Toggle::make('islossofpay')
                    ->required(),
                Forms\Components\TextInput::make('employee_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('policy_id'),
                Tables\Columns\TextColumn::make('grade_id'),
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('edited_by'),
                Tables\Columns\TextColumn::make('duration'),
                Tables\Columns\TextColumn::make('uom'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date(),
                Tables\Columns\IconColumn::make('islossofpay')
                    ->boolean(),
                Tables\Columns\TextColumn::make('employee_type'),
                Tables\Columns\IconColumn::make('status')
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
            'index' => Pages\ListLeaveRules::route('/'),
            'create' => Pages\CreateLeaveRule::route('/create'),
            'edit' => Pages\EditLeaveRule::route('/{record}/edit'),
        ];
    }    
}
