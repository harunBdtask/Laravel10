<?php

namespace App\MailChannels;

use SkylarkSoft\GoRMG\SystemSettings\Models\MailGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSetting;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class MailDTO
{
    public static function getReceivers($mailType): array
    {
        $mailSettings = MailSetting::query()
            ->where('mail_type', $mailType)
            ->pluck('receiver_groups')
            ->flatten()
            ->toArray();

        $receivers = MailGroup::query()
            ->whereIn('id', $mailSettings)
            ->pluck('users')
            ->flatten()
            ->toArray();

        return User::query()
            ->whereIn('id', $receivers)
            ->pluck('email')
            ->toArray();
    }

    public static function getDailyCuttingUpdateAttachmentName(): string
    {
        return "attachments/cutting_update/" . date('Y-m-d') . "_daily_cutting_update.pdf";
    }

    public static function getDailyLineInputUpdateAttachmentName(): string
    {
        return "attachments/line_input_update/" . date('Y-m-d') . "_daily_line_input_update.pdf";
    }

    public static function getDailySewingProductionUpdateAttachmentName(): string
    {
        return "attachments/sewing_update/" . date('Y-m-d') . "_daily_sewing_update.pdf";
    }

    public static function getDailyFinishingUpdateAttachmentName(): string
    {
        return "attachments/finishing_update/" . date('Y-m-d') . "_daily_finishing_update.pdf";
    }

    public static function getDailyOrderPOUpdateAttachmentName(): string
    {
        return "attachments/order_po_update/" . date('Y-m-d') . "_daily_order_po_update.pdf";
    }

    public static function getDailyYarnReceiveStatementAttachmentName($store): string
    {
        return "attachments/yarn_receive_update/" . date('Y-m-d') . "_daily_yarn_receive_statement_${store}.pdf";
    }

    public static function getDailyYarnIssueUpdateAttachmentName(): string
    {
        return "attachments/yarn_issue_update/" . date('Y-m-d') . "_daily_yarn_issue_update.pdf";
    }

    public static function getDailyYarnReceiveUpdateAttachmentName(): string
    {
        return "attachments/yarn_receive_update/" . date('Y-m-d') . "_daily_yarn_receive_update.pdf";
    }

    public static function getDailyFinishFabricReceiveUpdateAttachmentName(): string
    {
        return "attachments/finish_fabric_receive/" . date('Y-m-d') . "_daily_finish_fabric_receive_update.pdf";
    }

    public static function getDailyFinishFabricIssueUpdateAttachmentName(): string
    {
        return "attachments/finish_fabric_issue/" . date('Y-m-d') . "_daily_finish_fabric_issue_update.pdf";
    }

    public static function getDailyCuttingUpdateV2AttachmentName(): string
    {
        return "attachments/cutting_update_v2/" . date('Y-m-d') . "_daily_cutting_update_v2.pdf";
    }

    public static function getDailyPrintEmbrAttachmentName(): string
    {
        return "attachments/print_embr_update/" . date('Y-m-d') . "_daily_print_embr_update.pdf";
    }

    public static function getDailySewingInputAttachmentName(): string
    {
        return "attachments/daily_sewing_input_update/" . date('Y-m-d') . "_daily_sewing_input_update.pdf";
    }

    public static function getDailyOutputAttachmentName(): string
    {
        return "attachments/daily_output_update/" . date('Y-m-d') . "_daily_output_update.pdf";
    }

    public static function getHourlyFinishingProductionAttachmentName(): string
    {
        return "attachments/hourly_finishing_production_update/" . date('Y-m-d') . "_hourly_finishing_production_update.pdf";
    }

    public static function getDailyFinishingUpdateV3AttachmentName(): string
    {
        return "attachments/finishing_update_v3/" . date('Y-m-d') . "_daily_finishing_update_v3.pdf";
    }
}
