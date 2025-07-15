<x-filament::page>
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="flex-1 bg-white rounded-xl shadow p-4 text-center">
            <h2 class="text-lg font-bold">Total Poin Belanja</h2>
            <p class="text-3xl mt-2">{{ $totalBelanja }}</p>
        </div>
        <div class="flex-1 bg-white rounded-xl shadow p-4 text-center">
            <h1 class="text-lg font-bold">Total Poin Aktivitas</h1>
            <p class="text-3xl mt-2">{{ $totalAktivitas }}</p>
        </div>
    </div>

    

    <div class="bg-white rounded-xl shadow p-4">
        <h2 class="text-lg font-bold mb-4">Riwayat Terbaru</h2>
        <div class="mb-4">
        <form method="GET">
            <select name="filterTipe" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm">
                <option value="semua" @selected($filterTipe === 'semua')>Semua</option>
                <option value="belanja" @selected($filterTipe === 'belanja')>Belanja</option>
                <option value="aktivitas" @selected($filterTipe === 'aktivitas')>Aktivitas</option>
            </select>
        </form>
    </div>
        <table class="w-full table-auto text-sm">
            <thead>
                <tr class="text-left font-semibold border-b">
                    <th class="py-2">Tanggal</th>
                    <th class="py-2">Tipe</th>
                    <th class="py-2">Keterangan</th>
                    <th class="py-2 text-right">Poin</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayat as $item)
                    <tr class="border-b">
                        <td class="py-2">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</td>
                        <td class="py-2 capitalize">{{ $item['tipe'] }}</td>
                        <td class="py-2">{{ $item['keterangan'] }}</td>
                        <td class="py-2 text-right">{{ $item['poin'] ?? '0' }}</td>


                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-2 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::page>
