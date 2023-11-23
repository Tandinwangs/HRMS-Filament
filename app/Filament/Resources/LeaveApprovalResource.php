<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveApprovalResource\Pages;
use App\Filament\Resources\LeaveApprovalResource\RelationManagers;
use App\Models\LeaveApproval;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use App\Filament\Resource\Widgets\LatestApproval\LatestApproval;
use App\Models\MasDesignation;
use App\Models\AppliedLeave;
use App\Models\MasEmployee;
use App\Models\LeaveApprovalRule;
use App\Models\LeaveApprovalCondition;
use App\Models\LeaveBalance;
use App\Models\LeaveRule;
use App\Models\LeaveType;
use App\Models\LeavePolicy;
use Filament\Notifications\Notification;
use App\Mail\LeaveApprovedMail;
use App\Mail\LeaveApplicationMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Level;

class LeaveApprovalResource extends Resource
{
    protected static ?string $model = LeaveApproval::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('appliedLeave.leavetype.name')
                //     ->required()
                //     ->maxLength(36)
                //     ->label('Leave Type'),
                Forms\Components\TextInput::make('level1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('level2')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('level3')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('remark')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('AppliedLeave.employee.name'),
                Tables\Columns\TextColumn::make('AppliedLeave.leavetype.name'),
                Tables\Columns\TextColumn::make('AppliedLeave.start_date')
                ->label('From Date'),
                Tables\Columns\TextColumn::make('AppliedLeave.end_date')
                ->label('To Date'),
                Tables\Columns\TextColumn::make('AppliedLeave.number_of_days')
                ->label('no.of Days'),
                Tables\Columns\TextColumn::make('remark'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('Approve')
                ->action(fn (LeaveApproval $record) => LeaveApprovalResource::ApproveLeave($record))
                ->requiresConfirmation()
                ->modalHeading('Appprove Leave')
                ->modalSubheading('Are you sure you\'d like to approve this leave? This cannot be undone.')
                ->modalButton('Yes, approve now')
                ->color('success'),
                Action::make('Decline')
                ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public function widgets(): array
    {
        return [
            // Other widgets...
            LatestApproval::class,
        ];
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
            'index' => Pages\ListLeaveApprovals::route('/'),
            'create' => Pages\CreateLeaveApproval::route('/create'),
            
            // 'edit' => Pages\EditLeaveApproval::route('/{record}/edit'),
        ];
    } 
    
    public static function ApproveLeave($record) {
        $id = $record->applied_leave_id;
        $leaveApplication = AppliedLeave::findOrFail($id);
        $leave_id = $leaveApplication->leave_id;
        $userID = $leaveApplication->employee_id;
 
        $number_of_days = $leaveApplication->number_of_days;
        $user = MasEmployee::where('id', $userID)->first();
        $Approvalrecipient = $user->email;

        $approvalRuleId = LeaveApprovalRule::where('type_id', $leave_id)->value('id');
       
        $approvalType = LeaveApprovalCondition::where('approval_rule_id', $approvalRuleId)->first();

        $hierarchy_id = $approvalType->hierarchy_id;

        $leaveApplication = AppliedLeave::findOrFail($id);
        $departmentId = $user->department_id;
        $departmentHead = MasEmployee::where('department_id', $departmentId)
        ->where('is_departmentHead', true)
        ->first();

        if($approvalType->approval_type === "Hierarchy"){

            if ($leaveApplication->leaveApproval->level1 === 'pending' && $approvalType->MaxLevel === 'Level1') {
                $leaveApplication->leaveApproval->update([
                    'level1' => 'approved',
                ]);
        
                // Update the AppliedLeave model fields
                $leaveApplication->update([
                    'status' => 'approved',
                ]);
                // $this->fetchCasualLeaveBalance($leave_id,  $userID, $number_of_days);
                $instance = new self(); // Create an instance
                $instance->fetchCasualLeaveBalance($leave_id,  $userID, $number_of_days);
                $content = "The leave you have applied for has been approved.";
            
                Mail::to($Approvalrecipient)->send(new LeaveApprovedMail($user, $content));
            
                Notification::make() 
                ->title('Leave Approved successfully')
                ->success()
                ->send();
            
            }else if (
                $leaveApplication->leaveApproval->level1 === 'pending' &&
                $leaveApplication->leaveApproval->level2 === 'pending' &&
                ($approvalType->MaxLevel === 'Level2' || $approvalType->MaxLevel === 'Level3')
            ) { 
                $leaveApplication->leaveApproval->update([
                    'level1' => 'approved'
                ]);
                $levelRecord = Level::where('hierarchy_id', $hierarchy_id)
                ->where('level', 2)
                ->first();
    
                if ($levelRecord) {
                    // Access the 'value' field from the level record
                    $levelValue = $levelRecord->value;
    
                    // Determine the recipient based on the levelValue
                    $recipient = '';
    
                    // Check the levelValue and set the recipient accordingly
                    if ($levelValue === "DH") {
                        // Set the recipient to the section head's email address or user ID
                        $recipient = $departmentHead->email; // Replace with the actual field name
                    }
                    $approval = $departmentHead;
                    $currentUser = $user;
    
                    Mail::to($recipient)->send(new LeaveApplicationMail($approval, $currentUser));
                    Notification::make() 
                    ->title('Leave Approved successfully')
                    ->success()
                    ->send();
                
                }
    
            }else if($leaveApplication->leaveApproval->level1==='approved' && $approvalType->MaxLevel === 'Level2') {
                $leaveApplication->leaveApproval->update([
                    'level1' => 'approved',
                ]);
        
                // Update the AppliedLeave model fields
                $leaveApplication->update([
                    'status' => 'approved',
                ]);
                // $this->fetchCasualLeaveBalance($leave_id,  $userID, $number_of_days);
                $instance = new self(); // Create an instance
                $instance->fetchCasualLeaveBalance($leave_id,  $userID, $number_of_days);
    
                $content = "The leave you have applied for has been approved.";
                Mail::to($Approvalrecipient)->send(new LeaveApprovedMail($user, $content));
            
                Notification::make() 
                ->title('Leave Approved successfully')
                ->success()
                ->send();

            } else if($leaveApplication->leaveApproval->level1==='approved' &&$leaveApplication->leaveApproval->level2==='pending' && $approvalType->MaxLevel === 'Level3') {
                $leaveApplication->leaveApproval->update([
                    'level2' => 'approved'
                ]);
                $levelRecord = Level::where('hierarchy_id', $hierarchy_id)
                ->where('level', 3)
                ->first();
    
                if ($levelRecord) {
                    // Access the 'value' field from the level record
                    $levelValue = $levelRecord->value;
                    $userID = $levelRecord->employee_id;
                    $approval = MasEmployee::where('id', $userID)->first();
                    // Determine the recipient based on the levelValue
                    $recipient = $approval->email;
    
                    // Check the levelValue and set the recipient accordingly
                    if ($levelValue === "IS") {
                        // Set the recipient to the section head's email address or user ID
                         // Replace with the actual field name
                    }
      
                    $currentUser = $user;
    
                    Mail::to($recipient)->send(new LeaveApplicationMail($approval, $currentUser));
  
                }
            } else if($leaveApplication->level1==='approved' &&$leaveApplication->level2==='approved' && $approvalType->MaxLevel === 'Level3') {

                $leaveApplication->leaveApproval->update([
                    'level3' => 'approved',
                ]);
        
                // Update the AppliedLeave model fields
                $leaveApplication->update([
                    'status' => 'approved',
                ]);

                $content = "The leave you have applied for has been approved.";
                
                Mail::to($Approvalrecipient)->send(new LeaveApprovedMail($user, $content));
            
                // Redirect back with a success message
                return redirect()->back()->with('success', 'Leave application approved successfully.');
            } else {
                // Handle cases where the leave application cannot be approved (e.g., it's not at the expected level or already approved)
                return redirect()->back()->with('error', 'Leave application cannot be approved.');
            }
        }
       

    }

    public function fetchCasualLeaveBalance($leave_id,  $userID, $number_of_days)
    {
        $user = MasEmployee::where('id', $userID)->first();
        
        // $totalAppliedDays = applied_leave::where('user_id', $userID)
        // ->where('leave_id', $leave_id)
        // ->where('status', 'approved')
        // ->sum('number_of_days');
        $totalAppliedDays = $number_of_days;
        $leavetype = LeaveType::where('id', $leave_id)->first();

        $leavePolicyId = LeavePolicy::where('leave_id', $leave_id)->value('id');

        $leaveRule = LeaveRule::where('policy_id', $leavePolicyId)
        ->where('grade_step_id', $user->grade_step_id)
        ->first();

        if (!$leaveRule) {
            return redirect()->back()->with('error', 'Leave rule not found');
        }

        $leaveDuration = $leaveRule->duration;

        // 3. Calculate the leave balance by subtracting the applied days from the leave duration
      
        // $leaveBalanceNow = $leaveDuration - $totalAppliedDays;

        $leaveBalanceRecord = LeaveBalance::where('Employee_id', $userID)
        ->first();

        if ($leaveBalanceRecord) {
            if($leavetype->name === 'Casual Leave'){
                $leaveBalanceNow = ($leaveBalanceRecord->casual_leave_balance) - $totalAppliedDays;
                $leaveBalanceRecord->casual_leave_balance = $leaveBalanceNow;
                $leaveBalanceRecord->save();
            }
            // Update the existing leave balance record
        
        } else {
           
            // Create a new leave balance record if it doesn't exist
            LeaveBalance::create([
                'user_id' => $userID, 
                'casual_leave_balance' => $leaveBalanceNow,
            ]);
        }
    }
    
}
