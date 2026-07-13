<div class="space-y-4 text-sm">
    <div><span class="font-semibold">Nama:</span> {{ $record->user->name }}</div>
    <div><span class="font-semibold">Email:</span> {{ $record->user->email }}</div>
    <div><span class="font-semibold">Nomor telepon:</span> {{ $record->user->phone ?: '-' }}</div>
    <div><span class="font-semibold">Instansi:</span> {{ $record->user->profile?->instance ?: '-' }}</div>
    <div><span class="font-semibold">Fakultas/Divisi:</span> {{ $record->user->profile?->faculty ?: '-' }}</div>
    <div><span class="font-semibold">Program studi:</span> {{ $record->user->profile?->major ?: '-' }}</div>
</div>
