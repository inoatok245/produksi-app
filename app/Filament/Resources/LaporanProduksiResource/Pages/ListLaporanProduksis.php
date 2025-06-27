<?php

namespace App\Filament\Resources\LaporanProduksiResource\Pages;

use App\Filament\Resources\LaporanProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanProduksis extends ListRecords
{
    protected static string $resource = LaporanProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
