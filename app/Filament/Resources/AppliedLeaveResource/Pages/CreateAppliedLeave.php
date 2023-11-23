<?php

namespace App\Filament\Resources\AppliedLeaveResource\Pages;

use App\Filament\Resources\AppliedLeaveResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\LeaveApprovalRule;
use App\Models\Level;
use App\Mail\LeaveApplicationMail;
use Illuminate\Support\Facades\Mail;
use App\Models\MasEmployee;
use App\Models\LeaveApprovalCondition;

class CreateAppliedLeave extends CreateRecord
{
    protected static string $resource = AppliedLeaveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $currentUser = auth()->user();
    $sectionId = auth()->user()->section_id;
    $sectionHead = MasEmployee::where('section_id', $sectionId)
    ->where('is_sectionHead', true)->first();
   
    $leave_id = $data['leave_id'];
    $approvalRuleId = LeaveApprovalRule::where('type_id', $leave_id)->value('id');
    $approvalType = LeaveApprovalCondition::where('approval_rule_id', $approvalRuleId)->first();
    $hierarchy_id = $approvalType->hierarchy_id;

    if ($approvalType->approval_type == "Hierarchy") {
            // Fetch the record from the levels table based on the $hierarchy_id
            $levelRecord = Level::where('hierarchy_id', $hierarchy_id)->first();

            if ($levelRecord) {
                // Access the 'value' field from the level record
                $levelValue = $levelRecord->value;

                // Determine the recipient based on the levelValue
                $recipient = '';

                // Check the levelValue and set the recipient accordingly
                if ($levelValue === "SH") {
                    // Set the recipient to the section head's email address or user ID
                    $recipient = $sectionHead->email; // Replace with the actual field name
                }
                $approval = $sectionHead;

                Mail::to($recipient)->send(new LeaveApplicationMail($approval, $currentUser));
            }
        
    }
    return $data;
 
}
}
