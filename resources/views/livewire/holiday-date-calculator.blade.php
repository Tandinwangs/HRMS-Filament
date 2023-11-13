<!-- resources/views/livewire/holiday-date-calculator.blade.php -->

<div>
    <label for="start_date">Start Date</label>
    <input wire:model="startDate" type="date" id="start_date">

    <label for="end_date">End Date</label>
    <input wire:model="endDate" type="date" id="end_date">

    <label for="selected_start_time">Select Start Time</label>
    <select wire:model="selectedStartTime" id="selected_start_time">
        <option value="First Half">First Half</option>
        <option value="Second Half">Second Half</option>
    </select>

    <label for="selected_end_time">Select End Time</label>
    <select wire:model="selectedEndTime" id="selected_end_time">
        <option value="First Half">First Half</option>
        <option value="Second Half">Second Half</option>
    </select>

    <p>Number of Days: {{ $numberOfDays }}</p>
</div>

