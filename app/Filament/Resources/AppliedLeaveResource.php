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
use App\Models\MasEmployee;
use App\Filament\Actions\DownloadFileAction;
use Filament\Tables\Columns\LinkColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;

class AppliedLeaveResource extends Resource
{
    protected static ?string $model = AppliedLeave::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static ?string $label = 'Apply Leave';

    public static function form(Form $form): Form
    {
        $currentUser = Auth()->user();
        $userGender = auth()->user()->gender;
        $userId = $currentUser->id;
        // $userEmploymentType = User::where('id', $userId)->value('employment_type');

        // $leaveType = LeaveType::first();
       
        // $leavePolicy = $leaveType->LeavePolicy;
     
        // $leavePlan = $leavePolicy->LeavePlan;


        $leaveTypes = LeaveType::all();
        $options = $leaveTypes->filter(function ($leaveType) use ($userGender, $userId) {
            $LeavePlan = $leaveType->LeavePolicy->LeavePlan;
        
            // Check employment type conditions
            $userEmploymentType = MasEmployee::where('id', $userId)->value('employment_type');
        
            // Include the LeaveType if gender and employment type conditions match
            return ($LeavePlan->gender === 'A' || $LeavePlan->gender === $userGender)
                && (
                    ($LeavePlan->probation_period || $userEmploymentType !== 'probation_period') &&
                    ($LeavePlan->contract_period || $userEmploymentType !== 'contract_period') &&
                    ($LeavePlan->regular_period || $userEmploymentType !== 'regular_period') &&
                    ($LeavePlan->notice_period || $userEmploymentType !== 'notice_period')
                );
        })
        ->pluck('name', 'id')
        ->toArray();

        return $form
            ->schema([
                Forms\Components\Hidden::make('employee_id')
                    ->required()
                    ->default($currentUser->id)
                    ->disabled(),
                Forms\Components\Select::make('leave_id')
                    ->options(
                        $options
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

                        $attachment_required = LeavePlan::where('policy_id', $leavePolicyId)->value('attachment_required');
                        $set('attachment_required', $attachment_required);
   
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
                    $includePublicHolidays = $get('include_public_holidays');
                    $holidayDates = $get('holiday_dates');

                    $startDate = strtotime($get('start_date'));
                    $endDate = strtotime($state);

                    $dayTypeStart = $get('optradioholidayfrom');
                    $dayTypeEnd = $get('optradioholidaylto');
                    $numberOfDays = 0;

                    while ($startDate <= $endDate) {
                        $isSunday = date('w', $startDate) == 0;
                        $isSaturday = date('w', $startDate) == 6;
                        $isHoliday = in_array(date('Y-m-d', $startDate), $holidayDates);

                        if ($includeWeekends || (!$isSunday && !$isSaturday)) {
                            if ($includePublicHolidays || !$isHoliday) {
                                if (date('Y-m-d', $startDate) === date('Y-m-d', $endDate)) {
                                    if ($dayTypeStart === 'First Half' || $dayTypeStart === 'Second Half') {
                                        $numberOfDays += 0.5;
                                    } else {
                                        $numberOfDays += 1;
                                    }
                                } else {
                                    $numberOfDays += 1;
                                }
                            }
                        } elseif (!$includeWeekends && $isSaturday) {
                            $numberOfDays += 0.5;
                        }

                        $startDate += 86400; // Move to the next day (86400 seconds in a day)
                    }
                    
                    if (($dayTypeEnd === 'First Half' || $dayTypeEnd === 'Second Half') && $numberOfDays > 0) {
                        $numberOfDays -= 0.5;
                    }

                    $set('number_of_days', $numberOfDays);


                }),                
                

                Forms\Components\TextInput::make('number_of_days')
                    ->required()
                    ->disabled(),
                Forms\Components\FileUpload::make('file_path')
                ->required(function(callable $get){
                    if($get('attachment_required') == true){
                        return true;
                    }else{
                        return false;
                    }
                })
                ->acceptedFileTypes(['application/pdf'])
                ->storeFileNamesIn('attachment_file_names'),
                Forms\Components\TextArea::make('remark')
                    ->maxLength(63355),
          
            ]);
    }


    protected function save(array $data)
    {
        $attachmentRequired = $data['attachment_required'] ?? false;
    
        // Handle file upload if attachment is required
        if ($attachmentRequired && isset($data['file_path'])) {
            $file = $data['file_path'];
    
            // Logic to move the uploaded file to the desired location
            $filePath = $file->storeAs('uploads', $file->getClientOriginalName());
    
            // Get the URL for the file path
            $url = Storage::url($filePath);
    
            // Save the URL to the model
            $data['file_path'] = $url;
        }
    
        // Call the parent save method to save other fields to the database
        return parent::save($data);
    }
    


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('leavetype.name'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date(),
                Tables\Columns\TextColumn::make('number_of_days'),
                Tables\Columns\TextColumn::make('remark'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Download')
                ->action(fn (AppliedLeave $record) => AppliedLeaveResource::downloadFile($record)),
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

    public static function downloadFile($record)
    {
        // Use Storage::url to generate the proper URL for the file
        $filePath = 'public/' . $record->file_path; // assuming 'public' is the disk name

        // Check if the file exists in storage
        if (!Storage::exists($filePath)) {
            abort(404, 'File not found');
        }
    
        return Storage::download($filePath);
    }
    
}
