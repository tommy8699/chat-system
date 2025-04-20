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

    /**
     * Mass assignable atribúty.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Skryté atribúty pri serializácii.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Typovanie dátumov.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Vzťahy - Chaty, v ktorých je používateľ zapojený.
     */
    public function chats()
    {
        return $this->belongsToMany(Chat::class)->withTimestamps();
    }

    /**
     * Vzťahy - Správy, ktoré používateľ odoslal.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
