@php($verification = $getLivewire()->recipientVerification)

<div
    class="space-y-4"
    x-data="{
        rows: @js($verification['rows'] ?? []),
        search: '',
        status: 'all',
        page: 1,
        perPage: 10,
        filtered() {
            const needle = this.search.toLowerCase();
            return this.rows.filter(row => (this.status === 'all' || row.status === this.status)
                && [row.id, row.spreadsheet_name, row.spreadsheet_email].join(' ').toLowerCase().includes(needle));
        },
        paged() { return this.filtered().slice((this.page - 1) * this.perPage, this.page * this.perPage); },
        pages() { return Math.max(1, Math.ceil(this.filtered().length / this.perPage)); },
    }"
>
    @if ($getLivewire()->verificationInvalidated)
        <div class="rounded-xl border border-warning-500/40 bg-warning-50 p-4 text-sm text-warning-700 dark:bg-warning-400/10 dark:text-warning-300">
            Data Event atau URL Spreadsheet telah berubah. Lakukan verifikasi penerima ulang sebelum mengirim notifikasi.
        </div>
    @endif

    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['Total Data', $verification['total'] ?? 0, 'gray'],
            ['Valid', $verification['valid'] ?? 0, 'success'],
            ['Gagal', $verification['failed'] ?? 0, 'danger'],
            ['Duplikat', $verification['duplicate'] ?? 0, 'warning'],
        ] as [$label, $value, $color])
            <div class="rounded-xl bg-gray-50 p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
                <p class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    @if (($verification['failed'] ?? 0) + ($verification['duplicate'] ?? 0) > 0)
        <div class="rounded-xl border border-danger-500/40 bg-danger-50 p-4 text-sm text-danger-700 dark:bg-danger-400/10 dark:text-danger-300">
            Pengiriman ditahan karena masih terdapat data gagal atau duplikat. Tidak ada email yang dimasukkan ke antrean.
        </div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row">
        <input x-model.debounce.300ms="search" x-on:input="page = 1" type="search" placeholder="Cari ID, nama, atau email..."
            class="block w-full rounded-lg border-0 bg-white px-3 py-2 text-sm text-gray-950 shadow-sm ring-1 ring-gray-950/10 focus:ring-2 focus:ring-primary-600 dark:bg-white/5 dark:text-white dark:ring-white/20" />
        <select x-model="status" x-on:change="page = 1"
            class="rounded-lg border-0 bg-white px-3 py-2 text-sm text-gray-950 shadow-sm ring-1 ring-gray-950/10 dark:bg-white/5 dark:text-white dark:ring-white/20">
            <option value="all">Semua</option>
            <option value="valid">Valid</option>
            <option value="failed">Gagal</option>
            <option value="duplicate">Duplikat</option>
        </select>
    </div>

    <div class="overflow-x-auto rounded-xl ring-1 ring-gray-950/10 dark:ring-white/10">
        <table class="w-full divide-y divide-gray-200 text-sm dark:divide-white/10">
            <thead class="bg-gray-50 text-left text-gray-600 dark:bg-white/5 dark:text-gray-300">
                <tr>
                    @foreach (['No', 'ID', 'Nama Spreadsheet', 'Nama Database', 'Email Spreadsheet', 'Email Database', 'Status', 'Keterangan'] as $heading)
                        <th class="whitespace-nowrap px-3 py-3 font-medium">{{ $heading }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-900">
                <template x-for="row in paged()" :key="row.number">
                    <tr>
                        <td class="px-3 py-3" x-text="row.number"></td>
                        <td class="px-3 py-3" x-text="row.id || '-' "></td>
                        <td class="px-3 py-3" x-text="row.spreadsheet_name || '-' "></td>
                        <td class="px-3 py-3" x-text="row.database_name || '-' "></td>
                        <td class="px-3 py-3" x-text="row.spreadsheet_email || '-' "></td>
                        <td class="px-3 py-3" x-text="row.database_email || '-' "></td>
                        <td class="px-3 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-medium"
                                :class="row.status === 'valid' ? 'bg-success-50 text-success-700 dark:bg-success-400/10 dark:text-success-300' : (row.status === 'duplicate' ? 'bg-warning-50 text-warning-700 dark:bg-warning-400/10 dark:text-warning-300' : 'bg-danger-50 text-danger-700 dark:bg-danger-400/10 dark:text-danger-300')"
                                x-text="row.status === 'valid' ? 'Valid' : (row.status === 'duplicate' ? 'Duplikat' : 'Gagal')"></span>
                        </td>
                        <td class="min-w-72 px-3 py-3" x-text="`Baris ${row.line}: ${row.message}`"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
        <span x-text="`Halaman ${page} dari ${pages()}`"></span>
        <div class="flex gap-2">
            <button type="button" x-on:click="page = Math.max(1, page - 1)" :disabled="page === 1"
                class="rounded-lg px-3 py-2 ring-1 ring-gray-950/10 disabled:opacity-40 dark:ring-white/20">Sebelumnya</button>
            <button type="button" x-on:click="page = Math.min(pages(), page + 1)" :disabled="page >= pages()"
                class="rounded-lg px-3 py-2 ring-1 ring-gray-950/10 disabled:opacity-40 dark:ring-white/20">Berikutnya</button>
        </div>
    </div>
</div>
