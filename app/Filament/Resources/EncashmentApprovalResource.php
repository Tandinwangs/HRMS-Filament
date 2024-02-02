<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EncashmentApprovalResource\Pages;
use App\Filament\Resources\EncashmentApprovalResource\RelationManagers;
use App\Models\EncashmentApproval;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EncashmentApprovalResource extends Resource
{
    protected static ?string $model = EncashmentApproval::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Encashment';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('applied_encashment_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('created_by')
                    ->required(),
                Forms\Components\TextInput::make('edited_by'),
                Forms\Components\TextInput::make('level1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('level2')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('level3')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('remark')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('applied_encashment_id'),
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('edited_by'),
                Tables\Columns\TextColumn::make('level1'),
                Tables\Columns\TextColumn::make('level2'),
                Tables\Columns\TextColumn::make('level3'),
                Tables\Columns\TextColumn::make('remark'),
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
            'index' => Pages\ListEncashmentApprovals::route('/'),
            'create' => Pages\CreateEncashmentApproval::route('/create'),
            'edit' => Pages\EditEncashmentApproval::route('/{record}/edit'),
        ];
    }    
}
