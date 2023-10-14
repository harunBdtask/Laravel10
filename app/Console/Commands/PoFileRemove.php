<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Merchandising\Models\POFileModel;

class PoFileRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'po-file:remove {po-id}';

    const PO_FILES = "po_files/";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Po File';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $po_file = POFileModel::query()->where("id", $this->argument('po-id'))->first();
            if (isset($po_file->file)) {
                $file_name_to_delete = $po_file->file;
                $text_file_path = self::PO_FILES . str_replace(".pdf", ".txt", $po_file->file);
                if (Storage::disk('public')->exists(self::PO_FILES . $file_name_to_delete)
                    && $file_name_to_delete) {
                    Storage::delete('po_files/' . $file_name_to_delete);
                }
                if (Storage::disk('public')->exists('/' . $text_file_path)
                    && $text_file_path) {
                    Storage::delete($text_file_path);
                }
            }
            $po_file->delete();
            $this->info("Po File Remove Successfully");
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
