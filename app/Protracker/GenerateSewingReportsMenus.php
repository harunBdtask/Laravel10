<?php

namespace App\Protracker;

class GenerateSewingReportsMenus extends ProtrackerRelatedMenuCreation
{
    protected $moduleId, $factoryId;

    public function __construct($moduleId, $factoryId)
    {
        $this->factoryId = $factoryId;
        $this->moduleId = $moduleId;
    }

    public function handler()
    {
        $menuName = 'Sewing Reports';

        $subModuleId = $this->getSubModuleId($this->factoryId, $this->moduleId, $menuName, 8);

        $subMenus = ProtrackerRelatedMenus::sewingReportMenus($this->factoryId, $this->moduleId, $subModuleId);
        
        $this->insertMenus($subMenus);

        return true;
    }
}