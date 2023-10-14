<?php

namespace App\Library\Services\DailyMailUpdates;

interface DailyMailUpdateContract
{
    public function getFolderName(): string;

    public function getStoragePath(): string;

    public function getViewPath(): string;

    public function getViewData(): array;

    public function getFactory();
}
