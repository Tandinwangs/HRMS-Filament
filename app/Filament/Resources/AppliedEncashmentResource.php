<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppliedEncashmentResource\Pages;
use App\Filament\Resources\AppliedEncashmentResource\RelationManagers;
use App\Models\AppliedEncashment;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppliedEncashmentResource extends Resource
{
    protected static ?string $model = AppliedEncashment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Encashment';

    public static ?string $label = 'Apply Encashment';

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        $encash_balance = LeaveBalance::where('employee_id', $user->id)->first();
        return $form     
            ->schema([
                Forms\Components\Hidden::make('employee_id')
                ->default($user->id),
                Forms\Components\TextInput::make('number_of_days')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('employee_id'),
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('edited_by'),
                Tables\Columns\TextColumn::make('number_of_days'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('status'),
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
            'index' => Pages\ListAppliedEncashments::route('/'),
            'create' => Pages\CreateAppliedEncashment::route('/create'),
            'edit' => Pages\EditAppliedEncashment::route('/{record}/edit'),
        ];
    }    
}
