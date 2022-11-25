<?php

namespace App\Models;

use App\Enums\Roles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = ['name','email','role','email_veryfied_at'];

    protected $casts = [
        'email_veryfied_at' => 'datetime',
        'role' => Roles::class
    ];
}
