<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RateLimitResource\Pages;
use App\Filament\Resources\RateLimitResource\RelationManagers;
use App\Models\RateLimit;
use App\Models\policy;
use App\Models\RateDefinition;
use App\Models\MasGrade;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RateLimitResource extends Resource
{
    protected static ?string $model = RateLimit::class;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('policy_id')
                ->options(
                    Policy::all()->pluck('name', 'id')->toArray()
                )
                ->required(),
                Forms\Components\Select::make('rate_definition_id')
                ->options(
                    RateDefinition::all()->pluck('travel_type', 'id')->toArray()
                )
                ->required(),
                Forms\Components\Select::make('grade')
                ->multiple()
                ->relationship('gradeName', 'id')
                ->required(),
                Forms\Components\Select::make('region')
                ->options([
                    'Thimphu' => 'Thimphu',
                ])
                ->required(),
                Forms\Components\TextInput::make('limit_amount')
                ->required()
                ->numeric()
                ->minValue(0),
                Forms\Components\TextInput::make('start_date')
                ->type('date')
                ->required(),
                Forms\Components\TextInput::make('end_date')
                ->type('date'),
                Forms\Components\Toggle::make('status')->required(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('policy_id'),

                Tables\Columns\TextColumn::make('rate_definition_id'),
    
                Tables\Columns\TextColumn::make('grade'),
    
                Tables\Columns\TextColumn::make('region'),
    
                Tables\Columns\TextColumn::make('limit_amount'),
    
    
                Tables\Columns\TextColumn::make('start_date')
                ->dateTime(),
                
                Tables\Columns\TextColumn::make('end_date')
                ->dateTime(),
    
                Tables\Columns\IconColumn::make('status')
                ->boolean(),             ])
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
           
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRateLimits::route('/'),
            'create' => Pages\CreateRateLimit::route('/create'),
            'edit' => Pages\EditRateLimit::route('/{record}/edit'),
        ];
    }    
}
