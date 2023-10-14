<?php

namespace App\Protracker;

class GenerateMisReportMenus extends ProtrackerRelatedMenuCreation
{
    protected $moduleId, $factoryId;

    public function __construct($moduleId, $factoryId)
    {
        $this->factoryId = $factoryId;
        $this->moduleId = $moduleId;
    }

    public function handler()
    {
        $menuName = 'MIS Reports';

        $subModuleId = $this->getSubModuleId($this->factoryId, $this->moduleId, $menuName, 15);

        $subMenus = ProtrackerRelatedMenus::misReportMenus($this->factoryId, $this->moduleId, $subModuleId);
        
        $this->insertMenus($subMenus);

        return true;
    }
}