<?php

namespace App\Protracker;

class GenerateIeReportMenus extends ProtrackerRelatedMenuCreation
{
    protected $moduleId, $factoryId;

    public function __construct($moduleId, $factoryId)
    {
        $this->factoryId = $factoryId;
        $this->moduleId = $moduleId;
    }

    public function handler()
    {
        $menuName = 'IE Reports';

        $subModuleId = $this->getSubModuleId($this->factoryId, $this->moduleId, $menuName, 14);

        $subMenus = ProtrackerRelatedMenus::ieReportMenus($this->factoryId, $this->moduleId, $subModuleId);
        
        $this->insertMenus($subMenus);

        return true;
    }
}