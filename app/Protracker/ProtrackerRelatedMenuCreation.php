<?php

namespace App\Protracker;

use Illuminate\Support\Facades\DB;

class ProtrackerRelatedMenuCreation
{
    public function insertMenus($data)
    {
        // loop through each menu
        foreach ($data as $menu) {
            $this->insertSingleMenu($menu);
        }
        return true;
    }

    public function insertSingleMenu($data)
    {
        $query = DB::table('menus')
            ->whereNull('deleted_at')
            ->where([
                'menu_name' => $data['menu_name'],
                'menu_url' => $data['menu_url'],
                'module_id' => $data['module_id'],
                'factory_id' => $data['factory_id'],
                'submodule_id' => $data['submodule_id'],
            ]);
        if ($query->count()) {
            $id = $query->first()->id;
            DB::table('menus')->where('id', $id)->update($data);
        } else {
            DB::table('menus')->insert($data);
        }
        return true;
    }

    public function getSubModuleId($factoryId, $moduleId, $menuName, $sort)
    {
        $data = [
            'menu_name' => $menuName,
            'menu_url' => '#',
            'sort' => $sort,
            'module_id' => $moduleId,
            'factory_id' => $factoryId,
            'submodule_id' => NULL,
            'left_menu' => 1,
            'deleted_at' => NULL,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $menu = DB::table('menus')
            ->whereNull('deleted_at')
            ->where([
                'menu_name' => $menuName,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
            ])
            ->first();
        if ($menu) {
            $subModuleId = $menu->id;
        } else {
            $subModuleId = DB::table('menus')->insertGetId($data);
        }

        return $subModuleId;
    }
}
