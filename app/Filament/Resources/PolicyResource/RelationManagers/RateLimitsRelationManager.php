<?php

namespace App\Filament\Resources\PolicyResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use App\Models\policy;
use App\Models\RateDefinition;
use App\Models\MasGrade;
use App\Models\region;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RateLimitsRelationManager extends RelationManager
{
    protected static string $relationship = 'rateLimits';

    protected static ?string $recordTitleAttribute = 'region';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('grade')
            ->options(
                MasGrade::all()->pluck('name', 'id')->toArray()
            )
            ->required(),
            Forms\Components\Select::make('region')
            ->options(
                region::all()->pluck('name', 'id')->toArray()
            )
            ->required(),
            Forms\Components\TextInput::make('limit_amount')
            ->required()
            ->numeric()
            ->minValue(0),
            Forms\Components\DatePicker::make('start_date')
            ->required(),
            Forms\Components\DatePicker::make('end_date')
            ->after('start_date'),
            Forms\Components\Toggle::make('status')->required(),


        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
    
                Tables\Columns\TextColumn::make('gradeName.name'),
    
                Tables\Columns\TextColumn::make('Regionname.name'),
    
                Tables\Columns\TextColumn::make('limit_amount'),
    
    
                Tables\Columns\TextColumn::make('start_date')
                ->dateTime(),
                
                Tables\Columns\TextColumn::make('end_date')
                ->dateTime(),
    
                Tables\Columns\IconColumn::make('status')
                ->boolean(),             
                ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
