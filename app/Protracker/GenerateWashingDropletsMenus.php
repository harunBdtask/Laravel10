<?php

namespace App\Protracker;

class GenerateWashingDropletsMenus extends ProtrackerRelatedMenuCreation
{
    protected $moduleId, $factoryId;

    public function __construct($moduleId, $factoryId)
    {
        $this->factoryId = $factoryId;
        $this->moduleId = $moduleId;
    }

    public function handler()
    {
        $menuName = 'Washing Droplets';

        $subModuleId = $this->getSubModuleId($this->factoryId, $this->moduleId, $menuName, 9);

        $subMenus = ProtrackerRelatedMenus::washingDropletsMenus($this->factoryId, $this->moduleId, $subModuleId);
        
        $this->insertMenus($subMenus);

        return true;
    }
}