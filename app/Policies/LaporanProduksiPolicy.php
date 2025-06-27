<?php

namespace App\Policies;

use App\Models\LaporanProduksi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LaporanProduksiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
{
    return true;
}

public function view(User $user, LaporanProduksi $laporan)
{
    return $user->id === $laporan->user_id || $user->isAdmin();
}


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
{
    // Hanya admin dan karyawan boleh membuat laporan
    return $user->isAdmin() || $user->role === 'karyawan';
}


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LaporanProduksi $laporan): bool
{
    return $user->isAdmin() || ($user->id === $laporan->user_id && $laporan->status === 'menunggu');
}

public function delete(User $user, LaporanProduksi $laporan): bool
{
    return $user->isAdmin();
}


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LaporanProduksi $laporanProduksi): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LaporanProduksi $laporanProduksi): bool
    {
        //
    }
}
