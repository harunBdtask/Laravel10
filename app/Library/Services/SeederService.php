<?php

namespace App\Library\Services;

use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Menu;
use SkylarkSoft\GoRMG\SystemSettings\Models\Module;

class SeederService
{

    public function storeModule($moduleName, $menus)
    {
        $factoryId = $this->getFactoryId();

        $module = new Module();
        $module->withoutGlobalScope('factoryId');
        $module->module_name = $moduleName;
        $module->sort = 1;
        $module->factory_id = $factoryId;
        $module->saveQuietly();

        $this->saveMenu($menus, $module->id);
    }

    private function getFactoryId()
    {
        return Factory::query()->whereNull('deleted_at')->orderBy('id')->first()->id;
    }

    private function saveMenu($menus, $moduleId, $subModuleId = null)
    {
        foreach ($menus as $menuData) {
            $menu = new Menu();
            $menu->withoutGlobalScope('factoryId');
            $menu->menu_name = $menuData['menu_name'];
            $menu->menu_url = $menuData['menu_url'];
            $menu->sort = $menuData['sort'];
            $menu->module_id = $moduleId;
            $menu->submodule_id = $subModuleId;
            $menu->factory_id = $this->getFactoryId();
            $menu->saveQuietly();

            if (array_key_exists('SUBMENUS', $menuData)) {
                $this->saveMenu($menuData['SUBMENUS'], $moduleId, $menu->id);
            }
        }
    }

}
