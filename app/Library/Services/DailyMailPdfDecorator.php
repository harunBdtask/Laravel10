<?php

namespace App\Library\Services;

use App\Library\Services\DailyMailUpdates\DailyMailUpdateContract;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PDF;

class DailyMailPdfDecorator
{
    private $dailyMailUpdates;

    private function __construct($dailyMailUpdates)
    {
        $this->dailyMailUpdates = $dailyMailUpdates;
    }

    public static function parse(array $dailyMailUpdates): self
    {
        return new static($dailyMailUpdates);
    }

    public static function make(DailyMailUpdateContract $dailyMailUpdates): self
    {
        return new static($dailyMailUpdates);
    }

    public function pdf()
    {
        if ($this->dailyMailUpdates instanceof DailyMailUpdateContract) {

            $this->cleanPreviousFiles($this->dailyMailUpdates->getFolderName());

            $this->individualPdf($this->dailyMailUpdates);
        } elseif (is_array($this->dailyMailUpdates)) {

            $this->cleanPreviousFiles($this->dailyMailUpdates[0]->getFolderName());

            $this->multiPdf();
        }
    }

    private function individualPdf($dailyMailUpdate)
    {
        $storagePath = $dailyMailUpdate->getStoragePath();
        $factory = $dailyMailUpdate->getFactory();

        $pdf = PDF::loadView($dailyMailUpdate->getViewPath(),
            $dailyMailUpdate->getViewData())
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'footer-html' => view('auto_mail.mail_footer', compact('factory')),
            ]);

        Storage::put($storagePath, $pdf->output());
    }

    private function multiPdf()
    {
        foreach ($this->dailyMailUpdates as $dailyMailUpdate) {
            $this->individualPdf($dailyMailUpdate);
        }
    }

    private function cleanPreviousFiles($folderName)
    {
        File::delete(File::glob(storage_path("app/public/attachments/" . $folderName . "/*.pdf")));
    }
}
