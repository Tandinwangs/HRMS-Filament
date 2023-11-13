<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeavePlanResource\Pages;
use App\Filament\Resources\LeavePlanResource\RelationManagers;
use App\Models\LeavePlan;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeavePlanResource extends Resource
{
    protected static ?string $model = LeavePlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('policy_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('created_by')
                    ->required(),
                Forms\Components\TextInput::make('edited_by'),
                Forms\Components\Toggle::make('attachment_required')
                    ->required(),
                Forms\Components\TextInput::make('gender')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('leave_year')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('credit_frequency')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('credit')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('include_public_holidays')
                    ->required(),
                Forms\Components\Toggle::make('include_weekends')
                    ->required(),
                Forms\Components\Toggle::make('can_be_clubbed_with_el')
                    ->required(),
                Forms\Components\Toggle::make('can_be_clubbed_with_cl')
                    ->required(),
                Forms\Components\Toggle::make('can_be_half_day')
                    ->required(),
                Forms\Components\Toggle::make('probation_period')
                    ->required(),
                Forms\Components\Toggle::make('regular_period')
                    ->required(),
                Forms\Components\Toggle::make('contract_period')
                    ->required(),
                Forms\Components\Toggle::make('notice_period')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('policy_id'),
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('edited_by'),
                Tables\Columns\IconColumn::make('attachment_required')
                    ->boolean(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('leave_year'),
                Tables\Columns\TextColumn::make('credit_frequency'),
                Tables\Columns\TextColumn::make('credit'),
                Tables\Columns\IconColumn::make('include_public_holidays')
                    ->boolean(),
                Tables\Columns\IconColumn::make('include_weekends')
                    ->boolean(),
                Tables\Columns\IconColumn::make('can_be_clubbed_with_el')
                    ->boolean(),
                Tables\Columns\IconColumn::make('can_be_clubbed_with_cl')
                    ->boolean(),
                Tables\Columns\IconColumn::make('can_be_half_day')
                    ->boolean(),
                Tables\Columns\IconColumn::make('probation_period')
                    ->boolean(),
                Tables\Columns\IconColumn::make('regular_period')
                    ->boolean(),
                Tables\Columns\IconColumn::make('contract_period')
                    ->boolean(),
                Tables\Columns\IconColumn::make('notice_period')
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
            'index' => Pages\ListLeavePlans::route('/'),
            'create' => Pages\CreateLeavePlan::route('/create'),
            'edit' => Pages\EditLeavePlan::route('/{record}/edit'),
        ];
    }    
}
