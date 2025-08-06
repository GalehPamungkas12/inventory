<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = "peminjaman_barang";
    protected $primaryKey = "id";

    protected $fillable = [
        'peminjam',
        'name',
        'user_id',
        'kode_barang',
        'surat',
        'kondisi',
        'tersedia',
        'foto'
    ];

    /**
     * Relasi ke model User (setiap peminjaman dimiliki oleh satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Ambil semua data dan urutkan dari yang terbaru
     */
    public static function getAllData(): \Illuminate\Database\Eloquent\Collection
    {
        return self::latest()->get();
    }

    /**
     * Simpan data baru
     */
    public static function storeData(array $data): self
    {
        return self::create($data);
    }

    /**
     * Cari data berdasarkan ID
     */
    public static function findById(int $id): ?self
    {
        return self::find($id);
    }

    /**
     * Ambil semua data berdasarkan user_id tertentu
     */
    public static function getDataByUserId(int $id): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('user_id', $id)->get();
    }

    /**
     * Update data berdasarkan ID
     */
    public static function updateData(int $id, array $data): bool
    {
        return self::where('id', $id)->update($data);
    }

    /**
     * Hapus data berdasarkan ID
     */
    public static function deleteById(int $id): bool
    {
        return self::where('id', $id)->delete();
    }

    /**
     * Hitung total jumlah data peminjaman
     */
    public static function totalCount(): int
    {
        return self::count();
    }
}
