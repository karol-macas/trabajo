<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Método para verificar si el usuario es admin
   

    // Método para verificar si el usuario es empleado
    public function isEmpleado(): bool
    {
        return $this->role === 'empleado';
    }



    // Método para verificar un rol específico
    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }

    // Método para verificar si el rol es distinto
    public function isNotRole(string $role): bool
    {
        return $this->role !== $role;
    }

   

    public function isSupervisor()
    {
        return $this->empleado && $this->empleado->es_supervisor;
    }

    // Relación con el modelo Empleado
    public function empleado()
    {
        return $this->hasOne(Empleados::class, 'user_id');
    }

    public function isAdmin()

    {

        return $this->role === 'admin';

    }
}
