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


class MasEmployee extends FilamentUser implements Authenticatable
{
        use HasRoles;
        use AuthenticableTrait;

        protected $table = 'mas_employees';

        protected $primaryKey = 'emp_id';

        protected $password = 'password';

        protected $fillable = [
            "emp_id",
            "email",
            "first_name",
            "middle_name",
            "last_name",
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

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($employee) {
            $latestEmployee = static::orderBy('emp_id', 'desc')->first();
    
            if ($latestEmployee) {
                $lastEmpId = (int) $latestEmployee->emp_id;
                $nextEmpId = $lastEmpId + 1;
                $employee->emp_id = $nextEmpId;
            } else {
                $employee->emp_id = 1; // First employee
            }
        });
    }
    
}
