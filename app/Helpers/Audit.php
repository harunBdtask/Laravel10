<?php

namespace App\Helpers;

use App\Models\LogAudit;
use Illuminate\Support\Facades\Auth;

class Audit
{
    private $event;
    private $module;
    private $path;
    private $auditId;
    private $auditType;
    private $history;
    private $tableName;
    private $newValue = null;
    private $oldValue = null;


    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event): Audit
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param mixed $module
     */
    public function setModule($module): Audit
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $path
     * @return Audit
     */
    public function setPath($path): Audit
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuditId()
    {
        return $this->auditId;
    }

    /**
     * @param mixed $auditId
     */
    public function setAuditId($auditId): Audit
    {
        $this->auditId = $auditId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuditType()
    {
        return $this->auditType;
    }

    /**
     * @param mixed $auditType
     */
    public function setAuditType($auditType): Audit
    {
        $this->auditType = $auditType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @param mixed $history
     */
    public function setHistory($history): Audit
    {
        $this->history = $history;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param mixed $tableName
     */
    public function setTableName($tableName): Audit
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * @param mixed $newValue
     */
    public function setNewValue($newValue): Audit
    {
        $this->newValue = $newValue;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * @param mixed $oldValue
     */
    public function setOldValue($oldValue): Audit
    {
        $this->oldValue = $oldValue;
        return $this;
    }

    public function attach()
    {
        $audit = [
            'date' => date('y-m-d'),
            'user_id' => Auth::user()->id,
            'event' => $this->getEvent(),
            'module' => $this->getModule(),
            'auditable_id' => $this->getAuditId(),
            'auditable_type' => $this->getAuditType(),
            'history' => $this->getHistory(),
            'meta' => [
                'table' => $this->getTableName(),
                'path' => $this->getPath(),
                'old_values' => $this->getOldValue(),
                'new_values' => $this->getNewValue()
            ]
        ];
        LogAudit::query()->create($audit);
    }
}
