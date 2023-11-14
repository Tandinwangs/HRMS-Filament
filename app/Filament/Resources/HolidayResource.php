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
use App\Filament\Components\HolidayDateCalculator;

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
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\Select::make('optradioholidaylto')
                    ->label('Select Half')
                    ->options([
                        'First Half' => 'First Half',
                        'Second Half' => 'Second Half',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->required(),
                Forms\Components\TextInput::make('number_of_days')
                ->disabled() // Disable user input, as it will be calculated automatically
                ->placeholder('Calculated automatically'),
                Forms\Components\Select::make('region_id')
                    ->options(
                        Region::all()->pluck('name', 'id')->toArray()
                    )
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                    ]);

            $form->configure(function () {
                $this->configureSelects();

                $this->beforeSave(function ($record) {
                    $startDate = $record->start_date;
                    $endDate = $record->end_date;
                    $dayTypeStart = $record->optradioholidayfrom;
                    $dayTypeEnd = $record->optradioholidaylto;
    
                    // Log the function entry
                    Log::info('Entering beforeSave');
    
                    $numberOfDays = $this->calculateNumberOfDays($startDate, $endDate, $dayTypeStart, $dayTypeEnd);
    
                    // Log the calculated number of days
                    Log::info("Calculated Number of Days: $numberOfDays");
    
                    $record->number_of_days = $numberOfDays;
    
                    // Log the function exit
                    Log::info('Exiting beforeSave');
                });
                
                
            });
    }

    private function configureSelects()
{
    $this->start_date = DatePicker::make('start_date')
        ->required()
        ->on('change', fn () => $this->updateNumberOfDays());

    $this->end_date = DatePicker::make('end_date')
        ->required()
        ->on('change', fn () => $this->updateNumberOfDays());

    $this->optradioholidayfrom = Select::make('optradioholidayfrom')
        ->label('Select Half')
        ->options([
            'First Half' => 'First Half',
            'Second Half' => 'Second Half',
        ])
        ->required()
        ->on('change', fn () => $this->updateNumberOfDays());

    $this->optradioholidaylto = Select::make('optradioholidaylto')
        ->label('Select Half')
        ->options([
            'First Half' => 'First Half',
            'Second Half' => 'Second Half',
        ])
        ->required()
        ->on('change', fn () => $this->updateNumberOfDays());
}

    private function updateNumberOfDays()
    {
        $startTimestamp = strtotime($this->start_date);
        $endTimestamp = strtotime($this->end_date);

        // Calculate the number of days
        $numberOfDays = ceil(abs($endTimestamp - $startTimestamp) / 86400); // 86400 seconds in a day

        // Adjust based on the selected time intervals
        if ($this->optradioholidayfrom === 'First Half') {
            $numberOfDays += 0.5;
        }

        if ($this->optradioholidaylto === 'First Half') {
            $numberOfDays -= 0.5;
        }

        // Log the value to the console
        echo "<script>console.log('Number of Days:', $numberOfDays);</script>";

        $this->number_of_days = $numberOfDays;
    }


    private function calculateNumberOfDays($startDate, $endDate, $dayTypeStart, $dayTypeEnd)
    {
        // Log the function entry
        echo "<script>console.log('Entering calculateNumberOfDays');</script>";
    
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
    
        // Calculate the number of days
        $numberOfDays = ceil(abs($endTimestamp - $startTimestamp) / 86400); // 86400 seconds in a day
    
        // Adjust based on the selected time intervals
        if ($dayTypeStart === 'First Half') {
            $numberOfDays += 0.5;
        }
    
        if ($dayTypeEnd === 'First Half') {
            $numberOfDays -= 0.5;
        }
    
        // Log the value to the console
        echo "<script>console.log('Calculated Number of Days:', $numberOfDays);</script>";
    
        // Log the function exit
        echo "<script>console.log('Exiting calculateNumberOfDays');</script>";
    
        return $numberOfDays;
    }
    
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('year'),
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('edited_by'),
                Tables\Columns\TextColumn::make('optradioholidayfrom'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('optradioholidaylto'),
                Tables\Columns\TextColumn::make('end_date')
                    ->date(),
                Tables\Columns\TextColumn::make('number_of_days'),
                Tables\Columns\TextColumn::make('description'),
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
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHoliday::route('/create'),
            'edit' => Pages\EditHoliday::route('/{record}/edit'),
        ];
    }    
}

