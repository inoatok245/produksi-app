<?php

namespace App\Filament\Widgets;

use App\Models\LaporanProduksi;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class RingkasanProduksi extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.ringkasan-produksi';

    public ?string $periode = 'semua';
    public int $total = 0;
    public int $disetujui = 0;
    public int $ditolak = 0;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('periode')
                ->label('Filter Waktu')
                ->options([
                    'hari' => 'Hari Ini',
                    'bulan' => 'Bulan Ini',
                    'tahun' => 'Tahun Ini',
                    'semua' => 'Semua Waktu',
                ])
                ->default('semua')
                ->reactive()
                ->afterStateUpdated(fn () => $this->updateStats()),
        ];
    }

    protected function updateStats(): void
    {
        $query = LaporanProduksi::query();

        if ($this->periode === 'hari') {
            $query->whereDate('tanggal', Carbon::today());
        } elseif ($this->periode === 'bulan') {
            $query->whereMonth('tanggal', Carbon::now()->month)
                  ->whereYear('tanggal', Carbon::now()->year);
        } elseif ($this->periode === 'tahun') {
            $query->whereYear('tanggal', Carbon::now()->year);
        }

        $this->total = $query->count();
        $this->disetujui = (clone $query)->where('status', 'disetujui')->count();
        $this->ditolak = (clone $query)->where('status', 'ditolak')->count();
    }

    public function mount(): void
    {
        $this->updateStats();
    }
}
