<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RateDefinitionResource\Pages;
use App\Filament\Resources\RateDefinitionResource\RelationManagers;
use App\Models\RateDefinition;
use App\Models\policy;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RateDefinitionResource extends Resource
{
    protected static ?string $model = RateDefinition::class;




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('policy_id')
                ->options(
                    Policy::all()->pluck('name', 'id')->toArray()
                )
                ->required(),

                Forms\Components\Toggle::make('attachment_required')
                    ->required(),
                Forms\Components\Select::make('travel_type')
                    ->options([
                        'Domestic' => 'Domestic',
                    ])
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        'Single Currency' => 'Single Currency',
                    ])
                    ->required(),
                Forms\Components\Select::make('name')
                    ->options([
                        'Nu' => 'Nu',
                    ])
                    ->required(),
                Forms\Components\Select::make('rate_limit')
                    ->options([
                        'daily' => 'Daily',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ])
                    ->required(),
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Policy.name'),

                Tables\Columns\IconColumn::make('attachment_required')
                ->boolean(),        

                Tables\Columns\TextColumn::make('travel_type'),

                Tables\Columns\TextColumn::make('type'),
                
                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextColumn::make('rate_limit'),
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
            'index' => Pages\ListRateDefinitions::route('/'),
            'create' => Pages\CreateRateDefinition::route('/create'),
            'edit' => Pages\EditRateDefinition::route('/{record}/edit'),
        ];
    }    
}
