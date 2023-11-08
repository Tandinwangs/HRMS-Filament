<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status'
    ];

    public function users()
    {
        // Define the relationship with the User model
        return $this->hasMany(User::class); // Assuming you have a 'users' table
    }

    public function sections()
    {
        // Define the relationship with the User model
        return $this->hasMany(Section::class); // Assuming you have a 'users' table
    }
}
