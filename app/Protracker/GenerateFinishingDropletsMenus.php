<?php

namespace App\Protracker;

class GenerateFinishingDropletsMenus extends ProtrackerRelatedMenuCreation
{
    protected $moduleId, $factoryId;

    public function __construct($moduleId, $factoryId)
    {
        $this->factoryId = $factoryId;
        $this->moduleId = $moduleId;
    }

    public function handler()
    {
        $menuName = 'Finishing Droplets';

        $subModuleId = $this->getSubModuleId($this->factoryId, $this->moduleId, $menuName, 11);

        $subMenus = ProtrackerRelatedMenus::finishingDropletsMenus($this->factoryId, $this->moduleId, $subModuleId);
        
        $this->insertMenus($subMenus);

        return true;
    }
}