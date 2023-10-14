<?php

namespace App\Contracts;


interface AuditAbleContract
{
    public function moduleName(): string;
    public function path(): string;
}
