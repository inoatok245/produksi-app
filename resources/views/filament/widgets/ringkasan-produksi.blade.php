<x-filament::widget>
    <x-filament::card>
        {{ $this->form }}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200 rounded-xl p-6 shadow">
                <div class="text-sm font-medium">Total Laporan</div>
                <div class="mt-1 text-3xl font-bold">{{ $total }}</div>
            </div>

            <div class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-xl p-6 shadow">
                <div class="text-sm font-medium">Disetujui</div>
                <div class="mt-1 text-3xl font-bold">{{ $disetujui }}</div>
            </div>

            <div class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-xl p-6 shadow">
                <div class="text-sm font-medium">Ditolak</div>
                <div class="mt-1 text-3xl font-bold">{{ $ditolak }}</div>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
