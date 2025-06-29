<?php

namespace App\Filament\Karyawan\Resources\LaporanProduksiResource\Pages;

use App\Filament\Karyawan\Resources\LaporanProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanProduksi extends EditRecord
{
    protected static string $resource = LaporanProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
