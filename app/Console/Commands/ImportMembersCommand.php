<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MemberImport;

class ImportMembersCommand extends Command
{
    protected $signature = 'import:members {file}';
    protected $description = 'Import anggota dari file CSV atau Excel';

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File tidak ditemukan: $file");
            return Command::FAILURE;
        }

        $import = new MemberImport();
        Excel::import($import, $file);

        if ($import->hasDuplicates) {
            $this->warn('Beberapa data tidak diimpor karena duplikat.');
        }

        $this->info('Import selesai.');
        return Command::SUCCESS;
    }
}
