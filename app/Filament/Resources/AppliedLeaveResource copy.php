<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppliedLeaveResource\Pages;
use App\Filament\Resources\AppliedLeaveResource\RelationManagers;
use App\Models\AppliedLeave;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;
use App\Models\LeaveType; 
use App\Models\LeaveRule;
use App\Models\LeavePolicy;
use App\Models\LeavePlan;
use App\Models\Holiday;

class AppliedLeaveResource extends Resource
{
    protected static ?string $model = AppliedLeave::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        $curEmpId = Auth()->user()->id;
        return $form
            ->schema([
                Forms\Components\TextInput::make('employee_id')
                    ->required()
                    ->default($curEmpId)
                    ->hidden(),
                Forms\Components\Select::make('leave_id')
                    ->options(
                        LeaveType::all()->pluck('name', 'id')->toArray()
                    )
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state, Closure $set){
                        $empId = auth()->user()->id;

                        $currentYear = now()->year; // Get the current year

                        $totalAppliedDays = AppliedLeave::where('employee_id', $empId)
                            ->whereRaw("leave_id =?", [$state])
                            ->where('status', 'approved')
                            ->whereYear('created_at', $currentYear) // Filter by the current year
                            ->sum('number_of_days');

                        $leavePolicyId = LeavePolicy::where('leave_id', [$state])->value('id');

                        $leaveRule = LeaveRule::where('policy_id', $leavePolicyId)
                        ->where('grade_step_id', auth()->user()->grade_step_id)
                        ->first();

                        if($leaveRule) {
                            $leaveDuration = $leaveRule->duration;
                            $leave_balance = $leaveDuration - $totalAppliedDays;
                        }else{
                            $leave_balance = "Rule Not defined";
                        }
                        

                        // 3. Calculate the leave balance by subtracting the applied days from the leave duration
                       
                        $set('leave_balance', $leave_balance);  
                        
                        $includeWeekends = LeavePlan::where('policy_id', $leavePolicyId)->value('include_weekends');
                        $set('includeWeekends', $includeWeekends);

                        $can_be_half_day = LeavePlan::where('policy_id', $leavePolicyId)->value('can_be_half_day');
                        $set('can_be_half_day', $can_be_half_day);

                        $include_public_holidays = LeavePlan::where('policy_id', $leavePolicyId)->value('include_public_holidays');
                        $set('include_public_holidays', $include_public_holidays);
   
                            // Retrieve the currently logged-in user's region ID
                            $userRegionId = auth()->user()->region_id;
                    
                            // Retrieve the holiday dates from your database using the Holiday model
                            $holidays = Holiday::select('start_date', 'end_date')
                                ->whereHas('regions', function ($query) use ($userRegionId) {
                                    // Filter holidays by the user's region ID
                                    $query->where('regions.id', $userRegionId);
                                })
                                ->get();
                    
                            $allDates = [];
                    
                            // Iterate through the holidays and extract all the dates within each range
                            foreach ($holidays as $holiday) {
                                $startDate = new \DateTime($holiday->start_date);
                                $endDate = new \DateTime($holiday->end_date);
                    
                                // Create a DatePeriod object to iterate through the dates in the range
                                $dateInterval = new \DateInterval('P1D');
                                $dateRange = new \DatePeriod($startDate, $dateInterval, $endDate);
                    
                                // Add each date to the $allDates array
                                foreach ($dateRange as $date) {
                                    $allDates[] = $date->format('Y-m-d');
                                }
                    
                                // Add the end_date as well
                                $allDates[] = $endDate->format('Y-m-d');
                            }
                    
                            // Remove duplicates and sort the dates
                            $uniqueDates = array_unique($allDates);
                            sort($uniqueDates);
                    
                            $set('holiday_dates', $uniqueDates);

                    }),
                Forms\Components\TextInput::make('leave_balance')
                ->disabled(),
           
                Forms\Components\Select::make('optradioholidayfrom')
                ->label('Select Half')
                ->options([
                    'First Half' => 'First Half',
                    'Second Half' => 'Second Half',
                ])
                ->visible(function(callable $get){
                    if($get('can_be_half_day') == true){
                        return true;
                    }else{
                        return false;
                    }
                })->reactive(),

                Forms\Components\DatePicker::make('start_date')
                ->required()
                ->minDate(now())
                ->reactive()
               ,

                Forms\Components\Select::make('optradioholidaylto')
                ->label('Select Half')
                ->reactive()
                ->options([
                    'First Half' => 'First Half',
                    'Second Half' => 'Second Half',
                ])
                ->visible(function(callable $get){
                    if($get('can_be_half_day') == true){
                        return true;
                    }else{
                        return false;
                    }
                }),
                Forms\Components\DatePicker::make('end_date')
                ->required()
                 ->minDate(now())
                ->reactive()
                ->afterStateUpdated(function ($state, Closure $set, $get) {

                    $includeWeekends = $get('includeWeekends');
                    $startDate = strtotime($get('start_date'));
                    $endDate = strtotime($state);
                    $dayTypeStart = $get('optradioholidayfrom');
                    $dayTypeEnd = $get('optradioholidaylto');
                    $diff = $endDate - $startDate;
                    $numberOfDays = 0;

                    while ($startDate <= $endDate) {
                        $isSunday = date('w', $startDate) == 0;
                        $isSaturday = date('w', $startDate) == 6;

                        if ($includeWeekends || (!$isSunday && !$isSaturday)) {
                            if ($startDate == $endDate) {
                                if ($dayTypeStart === 'First Half' || $dayTypeStart === 'Second Half') {
                                    $numberOfDays += 0.5;
                                } else {
                                    $numberOfDays += 1;
                                }
                            } else {
                                $numberOfDays += 1;
                            }
                        } elseif (!$includeWeekends && $isSaturday) {
                            $numberOfDays += 0.5;
                        }

                        $startDate += 24 * 60 * 60; // Move to the next day
                    }

                    $set('number_of_days', $numberOfDays);



                }),                
                

                Forms\Components\TextInput::make('number_of_days')
                    ->required(),
                Forms\Components\TextInput::make('remark')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('file_path')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('employee_id'),
                Tables\Columns\TextColumn::make('leave_id'),
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('edited_by'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date(),
                Tables\Columns\TextColumn::make('number_of_days'),
                Tables\Columns\TextColumn::make('remark'),
                Tables\Columns\TextColumn::make('file_path'),
                Tables\Columns\TextColumn::make('level1'),
                Tables\Columns\TextColumn::make('level2'),
                Tables\Columns\TextColumn::make('level3'),
                Tables\Columns\TextColumn::make('status'),
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
            'index' => Pages\ListAppliedLeaves::route('/'),
            'create' => Pages\CreateAppliedLeave::route('/create'),
            'edit' => Pages\EditAppliedLeave::route('/{record}/edit'),
        ];
    }    
}
