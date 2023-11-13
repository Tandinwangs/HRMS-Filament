<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HolidayResource\Pages;
use App\Filament\Resources\HolidayResource\RelationManagers;
use App\Models\Holiday;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\HolidayType;
use App\Models\Region;
use App\Http\Livewire\HolidayDateCalculator;
use Livewire\Livewire;


class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

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
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHoliday::route('/create'),
            'edit' => Pages\EditHoliday::route('/{record}/edit'),
        ];
    }  
    
}




