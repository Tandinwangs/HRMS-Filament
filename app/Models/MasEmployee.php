<?php

namespace App\Models;

use Chiiya\FilamentAccessControl\Models\FilamentUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use App\Models\LeaveType;

class MasEmployee extends FilamentUser
{

        protected $table = 'mas_employees';

        protected $password = 'password';

        protected $fillable = [
            "id",
            "email",
            "first_name",
            "middle_name",
            "last_name",
            "emp_id",
            "grade_id",
            "grade_step_id",
            "created_by",
            "edited_by",
            'department_id',
            'section_id',
            "designation_id",
            "date_of_appointment",
            'gender',
            'employment_type',
            'region_id',
            'is_sectionHead',
            'is_departmentHead',
            'password',
        ];


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }
    public function grade():BelongsTo{
        return $this->belongsTo(MasGrade::class,'grade_id');
    }
    public function gradeStep():BelongsTo{
        return $this->belongsTo(MasGradeStep::class,'grade_step_id');
    }
    public function designation():BelongsTo{
        return $this->belongsTo(MasDesignation::class,'designation_id');
    }


    public function assignUserRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $this->assignRole($role);
        }
    }

    public function appliedLeaves()
    {
        return $this->hasMany(AppliedLeave::class);
    }

    public function leaveBalance()
    {
        return $this->hasOne(LeaveBalance::class, 'employee_id');
    }


    public function leaveRules()
    {
        return $this->hasMany(LeaveRule::class, 'grade_step_id');
    }

    
    protected static function boot()
    {               
        parent::boot();


        static::created(function ($employee) {
            $casualLeaveType = LeaveType::where('name', 'Casual Leave')->first();
            // Set the casual_leave_balance based on the matched LeaveRule
            $employee->leaveBalance()->create([
                'employee_id' => $employee->id,
                'casual_leave_balance' =>$casualLeaveType? $casualLeaveType->LeavePolicy->LeaveRules()
                    ->whereHas('gradeStep', function ($query) use ($employee) {
                        $query->where('id', $employee->grade_step_id);
                    })
                    ->first()->duration ?? 0.0
                : 0.0,
                'earned_leave_balance' => 0.0  
            ]);

        static::created(function ($masEmployee) {
            // Get the selected role IDs from the form data
            $selectedRoles = request('roles');

            // Find the roles in the database
            $roles = Role::find($selectedRoles);

            // Assign the roles to the new MasEmployee
            $masEmployee->roles()->attach($roles);
        });
        });
    }

    // protected static function boot()
    // {
    //     parent::boot();
    
    //     static::creating(function ($employee) {
    //         $latestEmployee = static::orderBy('emp_id', 'desc')->first();
    
    //         if ($latestEmployee) {
    //             $lastEmpId = (int) $latestEmployee->emp_id;
    //             $nextEmpId = $lastEmpId + 1;
    //             $employee->emp_id = $nextEmpId;
    //         } else {
    //             $employee->emp_id = 1; // First employee
    //         }
    //     });
    // }
    
}
