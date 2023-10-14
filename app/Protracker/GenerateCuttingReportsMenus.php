<?php

namespace App\Protracker;

class GenerateCuttingReportsMenus extends ProtrackerRelatedMenuCreation
{
    protected $moduleId, $factoryId;

    public function __construct($moduleId, $factoryId)
    {
        $this->factoryId = $factoryId;
        $this->moduleId = $moduleId;
    }

    public function handler()
    {
        $menuName = 'Cutting Reports';

        $subModuleId = $this->getSubModuleId($this->factoryId, $this->moduleId, $menuName, 2);

        $subMenus = ProtrackerRelatedMenus::cuttingReportMenus($this->factoryId, $this->moduleId, $subModuleId);
        
        $this->insertMenus($subMenus);

        return true;
    }
}