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
use Closure;

class HolidaysRelationManager extends RelationManager
{
    protected static string $relationship = 'holidays';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([    
                // Forms\Components\Select::make('holidaytype_id')
                //     ->options(
                //         HolidayType::all()->pluck('name', 'id')->toArray()
                //     )
                //     ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('year')
                    ->required(),

                Forms\Components\DatePicker::make('start_date')
                ->required()
                ->reactive()
            ,
                Forms\Components\Select::make('optradioholidayfrom')
                    ->label('Select Half')
                    ->options([
                        'First Half' => 'First Half',
                        'Second Half' => 'Second Half',
                    ])->reactive()
                    ->afterStateUpdated(function ($state, Closure $set, $get) {
                        $sDate = $get('start_date');
                        $startDate = strtotime($sDate);

                        $dayTypeStart = $get('optradioholidayfrom');
                        $dayTypeEnd = $get('optradioholidaylto');

                        if ($dayTypeStart === "First Half") {
                            $set('number_of_days', 0.5);
                            $set('end_date', $sDate);
                        }
                       
                    })
                    ,
          
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
                        })  
                    ->reactive()
                    ->afterStateUpdated(function ($state, Closure $set, $get) {
                            $startDate = strtotime($get('start_date'));
                            $endDate = strtotime($state);
                      
                            $dayTypeStart = $get('optradioholidayfrom');
                         
                            $dayTypeEnd = $get('optradioholidaylto');
                            $diff = $endDate - $startDate;
                        
                            // Calculate the number of full days
                            $numberOfDays = floor($diff / (24 * 60 * 60)) + 1;
                        
                        if ($dayTypeStart === "First Half") {
                                $numberOfDays = 0.5;
                                $set('number_of_days', $numberOfDays);
                                // Set the "To Date" to be the same as "From Date"
                                $endDate == $startDate;
    
                            } else if ($dayTypeStart === "Second Half") {
                                // If "Second Half" is selected for "Start Date", calculate the number of days based on the date range
                                $numberOfDays -=  0.5;
                            } else {
                                
                            }
                
                            // If "First Half" is selected for "End Date"
                            if ($dayTypeEnd === "First Half") {
                                // Check if the second half in the "From Date" is selected
                                if ($dayTypeStart !== "Second Half") {
                                    $numberOfDays -= 0.5;
                                } else {
                                    $numberOfDays -= 1;  
                                }
                            } else if (!$dayTypeStart && !$dayTypeEnd) {
                                // Calculate the number of days as a whole day
                                $numberOfDays = floor($diff / (24 * 60 * 60)) + 1;
                               
                            }
                
                            $set('number_of_days', $numberOfDays);
                           
                        }),
                           
                Forms\Components\Select::make('optradioholidaylto')
                    ->label('Select Half')
                    ->reactive()
                    ->options([
                        'First Half' => 'First Half',
                    ])->visible(function(callable $get){
                        if(!in_array((string)$get('optradioholidayfrom'),["First Half"])){
                            return true;
                        }else{
                            return false;
                        }
                    })
                    ->afterStateUpdated(function ($state, Closure $set, $get) {
                        $startDate = strtotime($get('start_date'));
                        $endDate = strtotime($state);
                      
                        $dayTypeStart = $get('optradioholidayfrom');
                     
                        $dayTypeEnd = $get('optradioholidaylto');

                        // Calculate the number of full days
                        $numberOfDays = $get('number_of_days');
            
                        // If "First Half" is selected for "End Date"
                        if ($dayTypeEnd === "First Half") {
                            // Check if the second half in the "From Date" is selected
                            if ($dayTypeStart == "Second Half") {
                                $numberOfDays -= 0.5;
                            } else {
                                $numberOfDays += 1;  
                            }
                        } 
                        $set('number_of_days', $numberOfDays);
                       
                    })
                    ,

                  
                Forms\Components\TextInput::make('number_of_days')
                    ->disabled()
                    ,

                Forms\Components\Select::make('region_id')
                    ->options(
                        Region::all()->pluck('name', 'id')->toArray()
                    )->multiple()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                    ]);
    }

    protected function saving($record, array $data)
    {
        // Extract region IDs and sync the relationship
        $regionIds = $data['region_id'];
        $record->regions()->sync($regionIds);
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
                SelectFilter::make('Date')
                    ->options([
                        1 => 'Active',
                        0 => 'In-active',
                    ])
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
