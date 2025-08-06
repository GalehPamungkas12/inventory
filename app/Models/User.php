<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'kelas',
        'google_id',
        'facebook_id',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Ambil semua data user (ordered by latest)
     */
    public function getAllData()
    {
        return self::latest()->get();
    }

    /**
     * Cari user berdasarkan ID
     */
    public function findById($id)
    {
        return self::find($id);
    }

    /**
     * Update user berdasarkan ID
     */
    public function updateData($id, $data)
    {
        return self::where('id', $id)->update($data);
    }

    /**
     * Hapus user berdasarkan ID
     */
    public function deleteById($id)
    {
        return self::where('id', $id)->delete();
    }

    /**
     * Ambil satu user berdasarkan ID
     */
    public function getDataById($id)
    {
        return self::where('id', $id)->first();
    }
}
    