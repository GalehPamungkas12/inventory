<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = "data_barang";

    protected $primaryKey = "id";

    protected $fillable = [
        'name',
        'jenis_barang',
        'foto',
        'kondisi',
        'tersedia',
        'kode_barang'
    ];

    public $timestamps = false;

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class, 'jenis_barang', 'id');
    }

    // Ambil semua data terbaru
    public static function getAllData()
    {
        return self::latest()->get();
    }

    // Buat data baru
    public static function createItem($data)
    {
        return self::create($data);
    }

    // Cari berdasarkan ID
    public static function findById($id)
    {
        return self::find($id);
    }

    // Cari berdasarkan kode barang
    public static function getDataByCode($code)
    {
        return self::where('kode_barang', $code)->first();
    }

    // Update berdasarkan ID
    public static function updateData($id, $data)
    {
        return self::where('id', $id)->update($data);
    }

    // Update berdasarkan kode barang
    public static function updateDataByCode($code, $data)
    {
        return self::where('kode_barang', $code)->update($data);
    }

    // Hapus berdasarkan ID
    public static function deleteById($id)
    {
        return self::where('id', $id)->delete();
    }

    // Ambil data dengan relasi kategori
    public static function getWithCategory()
    {
        return self::with('category')->get();
    }

    // Barang kondisi baik
    public static function getGoodCondition()
    {
        return self::where('kondisi', 'baik')->get();
    }

    // Barang kondisi rusak
    public static function getBadCondition()
    {
        return self::where('kondisi', 'rusak')->get();
    }

    // Barang tersedia
    public static function getDataReady()
    {
        return self::where('tersedia', 'ya')->get();
    }

    // Hitung semua data
    public static function totalCount()
    {
        return self::count();
    }

    // Hitung barang baik
    public static function goodDataCount()
    {
        return self::where('kondisi', 'baik')->count();
    }

    // Hitung barang rusak
    public static function badDataCount()
    {
        return self::where('kondisi', 'rusak')->count();
    }
}
