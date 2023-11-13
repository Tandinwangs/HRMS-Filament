<?php

namespace App\Http\Livewire;

use Livewire\Component;

class HolidayDateCalculator extends Component
{
    public $startDate;
    public $endDate;
    public $selectedStartTime;
    public $selectedEndTime;
    public $numberOfDays;

    public function render()
    {
        return view('livewire.holiday-date-calculator');
    }

    public function calculateNumberOfDays()
    {
        $startTimestamp = strtotime($this->startDate);
        $endTimestamp = strtotime($this->endDate);

        // Calculate the number of days
        $numberOfDays = ceil(abs($endTimestamp - $startTimestamp) / 86400); // 86400 seconds in a day

        // Adjust based on the selected time intervals
        if ($this->selectedStartTime === 'First Half') {
            $numberOfDays += 0.5;
        }

        if ($this->selectedEndTime === 'First Half') {
            $numberOfDays -= 0.5;
        }

        $this->numberOfDays = $numberOfDays;
    }
}