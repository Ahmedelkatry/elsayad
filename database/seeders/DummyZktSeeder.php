<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyZktSeeder extends Seeder
{
    public function run()
    {
        // استخدام الموظف أحمد رمضان (num = 1, id = 2) كمثال
        $numid = 1;
        $name = 'احمد رمضان';

        // 1. تحديث شيفت الموظف ليكون شيفت ليلي (7 مساءً إلى 1 صباحاً)
        DB::table('shifts')->where('id', 1)->update([
            'from' => '19:00:00',
            'to' => '01:00:00',
            'workh' => 6,
            'start_ot' => '01:00:00'
        ]);

        // 2. توليد 13 يوماً من البيانات الوهمية في جدول zkts
        // من 2026-06-20 إلى 2026-07-02
        $startDate = Carbon::create(2026, 6, 20);

        for ($i = 0; $i < 13; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $nextDate = $currentDate->copy()->addDay();

            // الدخول في اليوم الحالي الساعة 7 مساءً (تقريباً مع بعض الدقائق العشوائية)
            $checkInTime = sprintf('19:%02d:%02d', rand(0, 10), rand(0, 59));
            // الخروج في اليوم التالي الساعة 1 صباحاً (تقريباً)
            $checkOutTime = sprintf('01:%02d:%02d', rand(0, 15), rand(0, 59));

            // إدراج بصمة الدخول في يوم الدخول
            DB::table('zkts')->updateOrInsert(
                ['numid' => $numid, 'date' => $currentDate->format('Y-m-d')],
                [
                    'name' => $name,
                    'in' => $checkInTime,
                    'out' => null,
                    'absent' => 'حاضر',
                    'latitude_in' => '30.0444',
                    'longitude_in' => '31.2357',
                    'latitude_out' => null,
                    'longitude_out' => null,
                ]
            );

            // إدراج بصمة الخروج في يوم الخروج التالي (تحاكي البصمة الفردية التي سجلها الموظف في اليوم التالي)
            DB::table('zkts')->updateOrInsert(
                ['numid' => $numid, 'date' => $nextDate->format('Y-m-d')],
                [
                    'name' => $name,
                    'in' => null,
                    'out' => $checkOutTime,
                    'absent' => 'غائب',
                    'latitude_in' => null,
                    'longitude_in' => null,
                    'latitude_out' => '30.0444',
                    'longitude_out' => '31.2357',
                ]
            );
        }
    }
}
