<?php

namespace App\Protracker;

class GenerateCuttingDropletsMenus extends ProtrackerRelatedMenuCreation 
{
    protected $moduleId, $factoryId;

    public function __construct($moduleId, $factoryId)
    {
        $this->factoryId = $factoryId;
        $this->moduleId = $moduleId;
    }

    public function handler()
    {
        $menuName = 'Cutting Droplets';

        $subModuleId = $this->getSubModuleId($this->factoryId, $this->moduleId, $menuName, 1);

        $subMenus = ProtrackerRelatedMenus::cuttingDropletsMenus($this->factoryId, $this->moduleId, $subModuleId);

        $this->insertMenus($subMenus);

        return true;
    }
}