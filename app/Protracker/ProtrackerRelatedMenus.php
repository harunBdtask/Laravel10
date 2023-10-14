<?php

namespace App\Protracker;

class ProtrackerRelatedMenus
{

    public static function cuttingDropletsMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'Bundle Card[Knit]',
                'menu_url' => 'bundle-card-generations',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Bundle Card[Manual]',
                'menu_url' => 'bundle-card-generation-manual',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Cutting Scan',
                'menu_url' => 'cutting-scan',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Challan Wise bundle',
                'menu_url' => 'challan-wise-bundle',
                'sort' => 4,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Bundle Card Replace',
                'menu_url' => 'replace-bundle-card',
                'sort' => 5,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Update Cutting Production',
                'menu_url' => 'update-cutting-production',
                'sort' => 6,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function cuttingReportMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'All PO\'s Report',
                'menu_url' => 'all-orders-cutting-report',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Buyer Wise Report',
                'menu_url' => 'buyer-wise-cutting-report',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Style, PO & Color Wise Report',
                'menu_url' => 'booking-no-po-and-color-report',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Excess Cutting Report',
                'menu_url' => 'excess-cutting-report',
                'sort' => 4,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Daily Cutting Report',
                'menu_url' => 'daily-cutting-report',
                'sort' => 5,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Date Wise Report',
                'menu_url' => 'date-wise-cutting-report',
                'sort' => 6,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Month Wise Report',
                'menu_url' => 'month-wise-cutting-report',
                'sort' => 7,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Monthly Table Wise Cutting Report',
                'menu_url' => 'monthly-table-wise-cutting-production-summary-report',
                'sort' => 8,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Cutting Wise Report',
                'menu_url' => 'cutting-no-wise-cutting-report',
                'sort' => 9,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Lot Wise Report',
                'menu_url' => 'lot-wise-cutting-report',
                'sort' => 10,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Consumption Report',
                'menu_url' => 'consumption-report',
                'sort' => 11,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Buyer Booking Wise Fabric Consumption',
                'menu_url' => 'buyer-style-wise-fabric-consumption-report',
                'sort' => 12,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Daily Fabric Consumption',
                'menu_url' => 'daily-fabric-consumption-report',
                'sort' => 13,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Monthly Fabric Consumption',
                'menu_url' => 'monthly-fabric-consumption-report',
                'sort' => 14,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Bundle Scan Check',
                'menu_url' => 'bundle-scan-check',
                'sort' => 15,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Daily Table Wise Cutting & Input Summary',
                'menu_url' => 'cutting-production-summary-report',
                'sort' => 16,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function printEmbrDropletsMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'Send To Print/Embr',
                'menu_url' => 'print-send-scan',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Received From Print & Embr.',
                'menu_url' => 'bundle-received-from-print',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Gatepass List',
                'menu_url' => 'gatepasses',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function printEmbrReportsMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'Buyer Wise Report',
                'menu_url' => 'buyer-wise-print-send-receive-report',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Style, PO & Color Wise Report',
                'menu_url' => 'booking-no-po-and-color-report',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Cutting Wise Report',
                'menu_url' => 'cutting-no-wise-color-print-send-receive-report',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Date Wise Report',
                'menu_url' => 'date-wise-print-send-report',
                'sort' => 4,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Bundle Scan Check',
                'menu_url' => 'bundle-scan-check',
                'sort' => 5,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function inputDropletsMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'Solid Input/Tag',
                'menu_url' => 'cutting-inventory-scan',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Challan List',
                'menu_url' => 'view-challan-list',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Tag List',
                'menu_url' => 'view-tag-list',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Gatepass List',
                'menu_url' => 'gatepasses',
                'sort' => 4,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Challan Wise Bundles',
                'menu_url' => 'challan-wise-bundles',
                'sort' => 5,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function inputReportMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'All PO\'s Inventory Summary',
                'menu_url' => 'order-wise-cutting-inventory-summary',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Cutting Wise Inventory Challan',
                'menu_url' => 'cutting-no-wise-inventory-challan',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Cutting Wise Report',
                'menu_url' => 'cutting-no-wise-cutting-report',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Challan Count',
                'menu_url' => 'inventory-challan-count',
                'sort' => 4,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'All PO\'s Input Summary',
                'menu_url' => 'order-sewing-line-input',
                'sort' => 5,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Buyer Wise Input',
                'menu_url' => 'buyer-sewing-line-input',
                'sort' => 6,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Style, PO & Color Wise Report',
                'menu_url' => 'booking-no-po-and-color-report',
                'sort' => 7,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Date Wise Input',
                'menu_url' => 'date-wise-sewing-input',
                'sort' => 8,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Month Wise Input',
                'menu_url' => 'date-range-or-month-wise-sewing-input',
                'sort' => 9,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Floor & Line Wise Report',
                'menu_url' => 'floor-line-wise-sewing-report',
                'sort' => 10,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Input Closing',
                'menu_url' => 'input-closing',
                'sort' => 11,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Bundle Scan Check',
                'menu_url' => 'bundle-scan-check',
                'sort' => 12,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function sewingDropletsMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'Sewing Output Scan',
                'menu_url' => 'sewing-output-scan',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Sewing Challan List',
                'menu_url' => 'sewingoutput-challan-list',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function sewingReportMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'All PO\'s Summary',
                'menu_url' => 'all-orders-sewing-output-summary',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Buyer Wise Output',
                'menu_url' => 'buyer-wise-sewing-output',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Style, PO & Color Wise Report',
                'menu_url' => 'booking-no-po-and-color-report',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Floor & Line Wise Report',
                'menu_url' => 'floor-line-wise-sewing-report',
                'sort' => 4,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Line Wise Hr Prod.',
                'menu_url' => 'line-wise-hourly-sewing-output',
                'sort' => 5,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Line & Date Wise Avg.',
                'menu_url' => 'date-wise-hourly-sewing-output',
                'sort' => 6,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Production on Graph',
                'menu_url' => 'production-dashboard',
                'sort' => 7,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Bundle Wise QC',
                'menu_url' => 'bundle-wise-qc',
                'sort' => 8,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Get Challans By Bundlecard',
                'menu_url' => 'get-challans-by-bundlecard',
                'sort' => 9,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Individual Bundle Scan Check',
                'menu_url' => 'individual-bundle-scan-check',
                'sort' => 10,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Style Balance Bundle Check',
                'menu_url' => 'booking-balance-bundle-scan-check',
                'sort' => 11,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Bundle Scan Check',
                'menu_url' => 'bundle-scan-check',
                'sort' => 12,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function washingDropletsMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'Send To Wash',
                'menu_url' => 'washing-scan',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Received From Washing',
                'menu_url' => 'received-bundle-from-wash',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Washing Challan List',
                'menu_url' => 'manual-washing-received-challan-list',
                'sort' => 4,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function washingReportMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'All Order\'s Summary',
                'menu_url' => 'order-wise-receievd-from-wash',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Buyer Wise Report',
                'menu_url' => 'buyer-wise-receievd-from-wash',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Date Wise Washing Report',
                'menu_url' => 'date-wise-washing-report',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function finishingDropletsMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'Packing List',
                'menu_url' => 'packing-list-generate',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Iron Poly Packings',
                'menu_url' => 'iron-poly-packings',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function finishingReportMenus($factoryId, $moduleId, $subModuleId): array
    {
        return [
            [
                'menu_name' => 'Order Wise Finishing V1',
                'menu_url' => 'finishing-receieved-report',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Order Wise Finishing V2',
                'menu_url' => 'order-wise-finishing-report',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Color Wise Finishing',
                'menu_url' => 'color-wise-finishing-report',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Finishing Summary Report',
                'menu_url' => 'finishing-summary-report',
                'sort' => 4,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Style Wise Finishing Summary Report',
                'menu_url' => 'style-wise-finishing-summary-report',
                'sort' => 5,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Date Wise Finishing',
                'menu_url' => 'date-wise-finishing-report',
                'sort' => 6,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'All Order\'s Poly & Cartoon',
                'menu_url' => 'all-orders-poly-cartoon-report',
                'sort' => 7,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Date Wise Iron Poly & Packing',
                'menu_url' => 'date-wise-iron-poly-packing-summary',
                'sort' => 8,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Finishing Production Status',
                'menu_url' => 'finishing-production-status',
                'sort' => 9,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'PO & Shipment Status',
                'menu_url' => 'po-shipment-status',
                'sort' => 10,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function ieDropletsMenus($factoryId, $moduleId, $subModuleId)
    {
        return [
            [
                'menu_name' => 'Operation Bulletins',
                'menu_url' => 'operation-bulletins',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Date Wise Cutting Targets',
                'menu_url' => 'date-wise-cutting-targets',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Sewing Line Target',
                'menu_url' => 'sewing-line-target',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Show SMV',
                'menu_url' => 'show-smv',
                'sort' => 4,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Shipment Date & Unit Price Update',
                'menu_url' => 'shipment-date-and-unit-price-update',
                'sort' => 5,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Shipments',
                'menu_url' => 'shipments',
                'sort' => 6,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function ieReportMenus($factoryId, $moduleId, $subModuleId)
    {
        return [
            [
                'menu_name' => 'Style, PO & Color Wise Report',
                'menu_url' => 'booking-no-po-and-color-report',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'All Order\'s Shipment Summary',
                'menu_url' => 'all-orders-shipment-summary',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Buyer Wise Shipment Summary',
                'menu_url' => 'buyer-wise-shipment-report',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Daily Shipment Report',
                'menu_url' => 'daily-shipment-report',
                'sort' => 4,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Overall Shipment Report',
                'menu_url' => 'overall-shipment-report',
                'sort' => 5,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }

    public static function misReportMenus($factoryId, $moduleId, $subModuleId)
    {
        return [
            [
                'menu_name' => 'Color Wise production Summary Report',
                'menu_url' => 'color-wise-production-summary-report',
                'sort' => 1,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Monthly Efficiency Summary Report',
                'menu_url' => 'monthly-efficiency-summary-report',
                'sort' => 2,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Factory Wise Cutting Report',
                'menu_url' => 'factory-wise-cutting-report',
                'sort' => 3,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Factory Wise Print Send Rcv Report',
                'menu_url' => 'factory-wise-print-sent-received-report',
                'sort' => 4,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'menu_name' => 'Factory Wise Input Output Report',
                'menu_url' => 'factory-wise-input-output-report',
                'sort' => 5,
                'module_id' => $moduleId,
                'factory_id' => $factoryId,
                'submodule_id' => $subModuleId,
                'left_menu' => 1,
                'deleted_at' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
    }
}
