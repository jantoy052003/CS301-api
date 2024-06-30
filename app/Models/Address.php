<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;


class Address extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'street',
        'city',
        'state',
        'country',
        'zip',
        'phone'
    ];

}
