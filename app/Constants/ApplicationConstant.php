<?php


namespace App\Constants;

class ApplicationConstant
{
    const PAY_MODES = [
        1 => 'Credit',
        2 => 'Import',
        3 => 'In House',
        4 => 'Within Group',
    ];

    const SOURCES = [
        1 => 'Abroad',
        2 => 'EPZ',
        3 => 'Non-EPZ',
    ];

    const WO_BASIC = [
        1 => 'REQ. Basis',
        2 => 'Style Basis',
        3 => 'PO Basis',
        4 => 'Independent Basis',
    ];

    const DBP_TYPE = [
        1 => 'LDBP',
        2 => 'FDBP'
    ];

    const STYLE_WISE = 1;
    const PO_WISE = 2;
    const DECIMAL_PLACE = "%.4f";

    /*Costings*/
    const EMBELLISHMENT_COST = 'embellishment_cost';
    const TRIMS_COST = 'trims_costing';
    const FABRIC_COST = 'fabric_costing';
    const WASH_COST = 'wash_cost';
    const COMMERCIAL_COST = 'commercial_cost';
    const COMMISSION_COST = 'commission_cost';

    /*Messages*/
    const S_CREATED = 'Successfully Created!';
    const S_UPDATED = 'Successfully Updated!';
    const S_DELETED = 'Successfully Deleted!';
    const S_STORED = 'Successfully Stored!';
    const SOMETHING_WENT_WRONG = 'Something Went Wrong!';

    const SEARCH_BY_LC = 1;
    const SEARCH_BY_SC = 2;
    const SEARCH_BY_PMC = 3;

    const WORK_ORDER_BASIS = 1;
    const INDEPENDENT_BASIS = 2;

    const SUPER_ADMIN = 'super-admin';
    const ADMIN = 'admin';
    const MERCHANDISER = 'merchandiser';
    const NAN = 'NaN';

    const DEFAULT_UOM = 7;

    const SUPER_ADMIN_ROLE_ID = 1;
    const ADMIN_ROLE_ID = 3;
    const USER_ROLE_ID = 3;
    const MERCHANDISER_ROLE_ID = 3;

    const SYMBOL_FILTER_REGEX = "/([^\w\d\s&'.\-\)\(\/])+/i";
}
