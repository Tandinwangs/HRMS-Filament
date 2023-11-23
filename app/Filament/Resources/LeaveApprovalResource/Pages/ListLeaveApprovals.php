<?php

namespace App\Filament\Resources\LeaveApprovalResource\Pages;

use App\Filament\Resources\LeaveApprovalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListLeaveApprovals extends ListRecords
{
    protected static string $resource = LeaveApprovalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {

    $query = parent::getTableQuery();
    $user = auth()->user();
   
    if ($user->is_sectionHead) {
        $sectionHeadId = $user->section_id;
        $query->whereHas('appliedLeave.user', function ($subQuery) use ($sectionHeadId) {
            $subQuery->where('section_id', $sectionHeadId);
        })->where('level1', 'pending');
    }
    

    // if ($user->is_sectionHead) {
    //     $sectionHeadId = $user->section_id;
    //     $query->whereHas('user.section', function ($subQuery) use ($sectionHeadId) {
    //         $subQuery->where('id', $sectionHeadId);
    //     })->where('level1', 'pending');
    // } 
    elseif ($user->is_departmentHead) {
        $departmentHeadId = $user->department_id;
        $query->whereHas('appliedLeave.user', function ($subQuery) use ($departmentHeadId) {
            $subQuery->where('department_id', $departmentHeadId);
        })->whereHas('appliedLeave', function($subQuery){
            $subQuery->where('status', 'pending');
        })->where('level1', 'approved');
    } else {
        // For other designations, you can customize the query as needed
        // For example, you might want to show all records for the "Management" designation
        $query->where('level3', 'pending')->where('status', 'pending');
    }

    return $query;
    }
    
  
}
