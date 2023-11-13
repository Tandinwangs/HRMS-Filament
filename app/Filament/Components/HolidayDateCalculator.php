<?php

// app/Filament/Components/HolidayDateCalculator.php

namespace App\Filament\Components;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;

class HolidayDateCalculator extends Component
{
    public $start_date;

    public $end_date;

    public $optradioholidayfrom;

    public $optradioholidaylto;

    public $number_of_days;

    public function setUp():void
    {
        $this->configureInteractions();
    }

    public function configureInteractions()
    {
        $this->configureDatePicker('start_date', 'Start');
        $this->configureDatePicker('end_date', 'End');
        $this->configureSelects();
    }

    public function configureDatePicker($property, $label)
    {
        $this->{$property} = DatePicker::make($property)
            ->label("{$label} Date")
            ->required()
            ->on('change', fn () => $this->updateNumberOfDays());
    }

    public function configureSelects()
    {
        $this->optradioholidayfrom = Select::make('optradioholidayfrom')
            ->label('Start Time')
            ->options([
                'First Half' => 'First Half',
                'Second Half' => 'Second Half',
            ])
            ->required()
            ->on('change', fn () => $this->updateNumberOfDays());

        $this->optradioholidaylto = Select::make('optradioholidaylto')
            ->label('End Time')
            ->options([
                'First Half' => 'First Half',
                'Second Half' => 'Second Half',
            ])
            ->required()
            ->on('change', fn () => $this->updateNumberOfDays());
    }

    public function updateNumberOfDays()
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

        $this->number_of_days = $numberOfDays;
    }
}
