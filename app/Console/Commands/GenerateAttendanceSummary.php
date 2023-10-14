<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Console\Command;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Helpers\CalculateAttendanceSummaryForNightOt;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceRawData;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;

class GenerateAttendanceSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:attendance-summary-table-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Attendance Summary Table Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $this->info('Start execution');
            $date_from = Carbon::now()->startOfMonth();
            $date_to = Carbon::now()->endOfMonth();

            //$date_from = Carbon::parse('2020-03-01');
            // $date_to = Carbon::parse('2020-03-22');


            $this->info('Start General Attendance');
            $this->processAttendanceSummary($date_from, $date_to);
            $this->info('Start Night Attendance');
            $this->processNightOtData($date_from, $date_to);
            $this->info('End execution');
            DB::commit();
            $this->info('success');
        } catch (Exception $e) {
            DB::rollBack();
            $this->info($e->getMessage());
        }
    }

    /**
     * Process Attendance
     *
     * @param $date
     */
    public function processAttendanceSummary(Carbon $from_date, Carbon $to_date)
    {
        $from_date = $from_date->copy()->toDateString();
        $to_date = $to_date->copy()->toDateString();
        $officeEndTime = '17:00:00';
        $userids_of_shifting_duty = HrEmployeeOfficialInfo::where([
            'department_id' => 7, //Personnel
            'section_id' => 18, //Security
            'designation_id' => 48 //Security
        ])->pluck('unique_id')->toArray();

        return HrAttendanceRawData::selectRaw("userid,
                    attendance_date,
                    MIN(punch_time) AS new_intime,
                    MAX(punch_time) AS new_outtime")
            ->where('attendance_date', '>=', $from_date)
            ->where('attendance_date', '<=', $to_date)
            ->where('punch_time', '>=', '06:00:00')
            ->whereNotIn('userid', $userids_of_shifting_duty)
            ->groupBy('userid', 'attendance_date')
            ->orderBy('attendance_date')
            ->orderBy('userid')
            ->chunk(500, function ($attendance_raw_datas, $key) use ($officeEndTime) {
                $this->info('Date = ' . $attendance_raw_datas->first()->attendance_date);
                foreach ($attendance_raw_datas as $attendance_raw_data) {
                    $date = $attendance_raw_data->attendance_date;
                    $attendanceDetails = (new CalculateAttendanceSummary($attendance_raw_data, $officeEndTime, $date))->handle();

                    /*$attendanceDetailsDataFormatted = collect($attendanceDetails)->except([
                        'approvedOtHourStart',
                        'approvedOtHourEnd',
                        'regularOtHourStart',
                        'regularOtHourEnd',
                        'extraOtHourStart',
                        'extraOtHourEnd',
                        'unapprovedOtHourStart',
                        'unapprovedOtHourEnd',
                    ])->toArray();*/

                    HrAttendanceSummary::where([
                        'userid' => $attendanceDetails['userid'],
                        'date' => $attendanceDetails['date']
                    ])->forceDelete();
                    HrAttendanceSummary::create($attendanceDetails);
                }
            });
    }

    /**
     * Processing Night OT Data
     *
     * @param $from_date
     * @param $to_date
     * @return mixed
     */
    private function processNightOtData(Carbon $from_date, Carbon $to_date)
    {
        $from_date = $from_date->copy()->toDateString();
        $to_date = $to_date->copy()->toDateString();
        $nightOtEndTime = '06:59:00';
//        $userids_of_shifting_duty = HrEmployeeOfficialInfo::where([
//            'department_id' => 1, //HR, Admin & Compliance
//            'section_id' => 1, //Admin
//            'designation_id' => 29 //Security Guard
//        ])->pluck('unique_id')->toArray();

        $userids_of_employee = HrEmployeeOfficialInfo::query()
            ->pluck('punch_card_id')->toArray();

        return HrAttendanceRawData::selectRaw("userid,
                    attendance_date,
                    MIN(punch_time) AS night_intime,
                    MAX(punch_time) AS night_outtime")
            ->where('attendance_date', '>=', $from_date)
            ->where('attendance_date', '<=', $to_date)
            ->where('punch_time', '>=', '17:00:00')
            ->whereIn('userid', $userids_of_employee)
//            ->whereNotIn('userid', $userids_of_employee)
            ->groupBy('userid', 'attendance_date')
            ->orderBy('attendance_date')
            ->orderBy('userid')
            ->chunk(500, function ($attendance_raw_datas, $key) use ($nightOtEndTime) {
                $this->info('Date = ' . $attendance_raw_datas->first()->attendance_date);
                foreach ($attendance_raw_datas as $attendance_raw_data) {
                    $date = $attendance_raw_data->attendance_date;
                    $userid = $attendance_raw_data->userid;

                    $attendance_summary = HrAttendanceSummary::where([
                        'userid' => $userid,
                        'date' => $date
                    ])->first();

                    if ($attendance_summary) {
                        $attendanceDetailsForNightOt = (new CalculateAttendanceSummaryForNightOt($attendance_raw_data, $nightOtEndTime, $date))->handle();

                        HrAttendanceSummary::where([
                            'userid' => $userid,
                            'date' => $date
                        ])->update($attendanceDetailsForNightOt);
                    }
                }
            });
    }
}
