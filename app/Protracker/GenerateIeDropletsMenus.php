<?php

namespace App\Protracker;

class GenerateIeDropletsMenus extends ProtrackerRelatedMenuCreation
{
    protected $moduleId, $factoryId;

    public function __construct($moduleId, $factoryId)
    {
        $this->factoryId = $factoryId;
        $this->moduleId = $moduleId;
    }

    public function handler()
    {
        $menuName = 'IE Droplets';

        $subModuleId = $this->getSubModuleId($this->factoryId, $this->moduleId, $menuName, 13);

        $subMenus = ProtrackerRelatedMenus::ieDropletsMenus($this->factoryId, $this->moduleId, $subModuleId);
        
        $this->insertMenus($subMenus);

        return true;
    }
}