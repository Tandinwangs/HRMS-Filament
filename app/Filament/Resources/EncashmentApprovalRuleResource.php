<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EncashmentApprovalRuleResource\Pages;
use App\Filament\Resources\EncashmentApprovalRuleResource\RelationManagers;
use App\Models\EncashmentApprovalRule;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Encashment;

class EncashmentApprovalRuleResource extends Resource
{
    protected static ?string $model = EncashmentApprovalRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Encashment';

    public static function form(Form $form): Form
    {
        $encashment = Encashment::first();
        return $form
            ->schema([
                Forms\Components\Hidden::make('type_id')
                    ->default($encashment->id),
                Forms\Components\TextInput::make('For')
                    ->required()
                    ->maxLength(255)
                    ->default($encashment->name)
                    ->disabled(),
                Forms\Components\TextInput::make('RuleName')
                    ->required()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('For'),
                Tables\Columns\TextColumn::make('RuleName'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date(),
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
            RelationManagers\EncashmentApprovalConditionRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEncashmentApprovalRules::route('/'),
            'create' => Pages\CreateEncashmentApprovalRule::route('/create'),
            'edit' => Pages\EditEncashmentApprovalRule::route('/{record}/edit'),
        ];
    }    
}
