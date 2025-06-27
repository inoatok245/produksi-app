<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LaporanProduksi extends Model
{
    use HasFactory;

    protected $table = 'laporan_produksi';

    protected $fillable = [
        'user_id',
        'tanggal',
        'jenis_pekerjaan',
        'jumlah',
        'keterangan',
        'foto',
        'status',
        'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk karyawan agar hanya melihat laporan miliknya
     */
    public function scopeKaryawan(Builder $query)
    {
        if (auth()->check() && !auth()->user()->isAdmin()) {
            return $query->where('user_id', auth()->id());
        }

        return $query;
    }
}
