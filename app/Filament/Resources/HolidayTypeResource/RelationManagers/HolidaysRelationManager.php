<?php

namespace App\Filament\Resources\HolidayTypeResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\HolidayType;
use App\Models\Region;

class HolidaysRelationManager extends RelationManager
{
    protected static string $relationship = 'holidays';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([    
                Forms\Components\Select::make('holidaytype_id')
                    ->options(
                        HolidayType::all()->pluck('name', 'id')->toArray()
                    )
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('year')
                    ->required(),
                Forms\Components\Select::make('optradioholidayfrom')
                    ->label('Select Half')
                    ->options([
                        'First Half' => 'First Half',
                        'Second Half' => 'Second Half',
                    ])->reactive(),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\Select::make('optradioholidaylto')
                    ->label('Select Half')
                    ->options([
                        'First Half' => 'First Half',
                        'Second Half' => 'Second Half',
                    ])->visible(function(callable $get){
                        if(!in_array((string)$get('optradioholidayfrom'),["First Half"])){
                            return true;
                        }else{
                            return false;
                        }
                    }),
                Forms\Components\DatePicker::make('end_date')
                ->visible(function(callable $get){
                    if(!in_array((string)$get('optradioholidayfrom'),["First Half"])){
                        return true;
                    }else{
                        return false;
                    }})
                ->required(function(callable $get){
                    if(!in_array((string)$get('optradioholidayfrom'),["First Half"])){
                        return true;
                    }else{
                        return false;
                        }
                    }),
                Forms\Components\TextInput::make('number_of_days')
                    ->disabled()
                    ->placeholder('Calculated automatically'),
                Forms\Components\Select::make('region_id')
                    ->options(
                        Region::all()->pluck('name', 'id')->toArray()
                    )
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                    ]);
    }


    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('holidaytype.name'),
            Tables\Columns\TextColumn::make('start_date')
                ->date(),
            Tables\Columns\TextColumn::make('end_date')
                ->date(),
            Tables\Columns\TextColumn::make('number_of_days'),
            Tables\Columns\TextColumn::make('description'),
        ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
