<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveYearendProcessResource\Pages;
use App\Filament\Resources\LeaveYearendProcessResource\RelationManagers;
use App\Models\LeaveYearendProcess;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveYearendProcessResource extends Resource
{
    protected static ?string $model = LeaveYearendProcess::class;

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
                Forms\Components\TextInput::make('created_by')
                    ->required(),
                Forms\Components\TextInput::make('edited_by'),
                Forms\Components\Toggle::make('allow_carryover')
                    ->required(),
                Forms\Components\TextInput::make('carryover_limit')
                    ->required(),
                Forms\Components\Toggle::make('payat_yearend')
                    ->required(),
                Forms\Components\TextInput::make('min_balance')
                    ->required(),
                Forms\Components\TextInput::make('max_balance')
                    ->required(),
                Forms\Components\Toggle::make('carryforward_toEL')
                    ->required(),
                Forms\Components\TextInput::make('carryforward_toEL_limit')
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
                Tables\Columns\IconColumn::make('allow_carryover')
                    ->boolean(),
                Tables\Columns\TextColumn::make('carryover_limit'),
                Tables\Columns\IconColumn::make('payat_yearend')
                    ->boolean(),
                Tables\Columns\TextColumn::make('min_balance'),
                Tables\Columns\TextColumn::make('max_balance'),
                Tables\Columns\IconColumn::make('carryforward_toEL')
                    ->boolean(),
                Tables\Columns\TextColumn::make('carryforward_toEL_limit'),
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
            'index' => Pages\ListLeaveYearendProcesses::route('/'),
            'create' => Pages\CreateLeaveYearendProcess::route('/create'),
            'edit' => Pages\EditLeaveYearendProcess::route('/{record}/edit'),
        ];
    }    
}
