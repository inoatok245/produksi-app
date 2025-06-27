<x-filament::page>
    @php
        $filter = request('filter', 'semua');
        $query = \App\Models\LaporanProduksi::query();

        if ($filter === 'hari') {
            $query->whereDate('tanggal', \Illuminate\Support\Carbon::today());
        } elseif ($filter === 'bulan') {
            $query->whereMonth('tanggal', \Illuminate\Support\Carbon::now()->month)
                  ->whereYear('tanggal', \Illuminate\Support\Carbon::now()->year);
        } elseif ($filter === 'tahun') {
            $query->whereYear('tanggal', \Illuminate\Support\Carbon::now()->year);
        }

        $total = $query->count();
        $disetujui = (clone $query)->where('status', 'disetujui')->count();
        $ditolak = (clone $query)->where('status', 'ditolak')->count();
        $riwayat = $query->latest()->take(5)->get();
    @endphp

    <div class="space-y-6">
        {{-- Filter Waktu --}}
        <form method="GET" class="max-w-xs">
            <x-filament::section>
                <label for="filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filter Waktu</label>
                <select name="filter" id="filter"
                        class="block w-full max-w-xs px-3 py-2 text-sm bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="semua" {{ $filter === 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="hari" {{ $filter === 'hari' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="bulan" {{ $filter === 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun" {{ $filter === 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
                <button type="submit"
                        class="mt-3 inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Terapkan
                </button>
            </x-filament::section>
        </form>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-filament::card class="bg-primary-100 dark:bg-primary-900 text-primary-900 dark:text-primary-100">
                <div class="text-sm font-medium">Total Laporan</div>
                <div class="mt-1 text-3xl font-bold">{{ $total }}</div>
            </x-filament::card>

            <x-filament::card class="bg-green-100 dark:bg-green-900 text-green-900 dark:text-green-100">
                <div class="text-sm font-medium">Disetujui</div>
                <div class="mt-1 text-3xl font-bold">{{ $disetujui }}</div>
            </x-filament::card>

            <x-filament::card class="bg-red-100 dark:bg-red-900 text-red-900 dark:text-red-100">
                <div class="text-sm font-medium">Ditolak</div>
                <div class="mt-1 text-3xl font-bold">{{ $ditolak }}</div>
            </x-filament::card>
        </div>

        {{-- Riwayat Terbaru --}}
        <x-filament::section heading="Riwayat Laporan Terbaru">
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pekerjaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($riwayat as $laporan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $laporan->jenis_pekerjaan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap capitalize">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $laporan->status === 'disetujui' ? 'bg-green-100 text-green-800' : 
                                           ($laporan->status === 'ditolak' ? 'bg-red-100 text-red-800' : 
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ $laporan->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-400">Tidak ada laporan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament::page>
