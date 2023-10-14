<?php

namespace App\Protracker;

class GenerateWashingReportMenus extends ProtrackerRelatedMenuCreation
{
    protected $moduleId, $factoryId;

    public function __construct($moduleId, $factoryId)
    {
        $this->factoryId = $factoryId;
        $this->moduleId = $moduleId;
    }

    public function handler()
    {
        $menuName = 'Washing Reports';

        $subModuleId = $this->getSubModuleId($this->factoryId, $this->moduleId, $menuName, 10);

        $subMenus = ProtrackerRelatedMenus::washingReportMenus($this->factoryId, $this->moduleId, $subModuleId);
        
        $this->insertMenus($subMenus);

        return true;
    }
}