<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "jenis_barang";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    // Ambil semua data dengan urutan terbaru
    public function getAllData()
    {
        return self::latest()->get();
    }

    // Simpan data baru
    public static function store(array $data)
    {
        return self::create($data);
    }

    // Cari data berdasarkan ID
    public static function findById($id)
    {
        return self::find($id);
    }

    // Perbarui data berdasarkan ID
    public static function updateData($id, array $data)
    {
        return self::where('id', $id)->update($data);
    }

    // Hapus data berdasarkan ID
    public static function deleteById($id)
    {
        return self::where('id', $id)->delete();
    }

    // Hitung jumlah data
    public static function totalCount()
    {
        return self::count();
    }
}
