<?php

namespace App\Protracker;

class GenerateSewingDropletsMenus extends ProtrackerRelatedMenuCreation
{
    protected $moduleId, $factoryId;

    public function __construct($moduleId, $factoryId)
    {
        $this->factoryId = $factoryId;
        $this->moduleId = $moduleId;
    }

    public function handler()
    {
        $menuName = 'Sewing Droplets';

        $subModuleId = $this->getSubModuleId($this->factoryId, $this->moduleId, $menuName, 7);

        $subMenus = ProtrackerRelatedMenus::sewingDropletsMenus($this->factoryId, $this->moduleId, $subModuleId);
        
        $this->insertMenus($subMenus);

        return true;
    }
}