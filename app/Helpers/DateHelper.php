<?php

namespace App\Helpers;

class DateHelper
{
    /**
     * تبدیل تاریخ میلادی به شمسی
     * 
     * @param string|int|null $date تاریخ میلادی (Y-m-d یا timestamp)
     * @param string $format فرمت خروجی (پیش‌فرض: Y/m/d)
     * @return string|null تاریخ شمسی
     */
    public static function toPersian($date, string $format = 'Y/m/d'): ?string
    {
        if (empty($date)) {
            return null;
        }

        // اگر timestamp است
        if (is_numeric($date)) {
            $timestamp = (int)$date;
        } else {
            $timestamp = strtotime($date);
        }

        if ($timestamp === false) {
            return null;
        }

        // تبدیل به تاریخ شمسی
        $jDate = self::gregorianToJalali(
            (int)date('Y', $timestamp),
            (int)date('m', $timestamp),
            (int)date('d', $timestamp)
        );

        // فرمت‌دهی
        $result = $format;
        $result = str_replace('Y', $jDate[0], $result);
        $result = str_replace('m', str_pad($jDate[1], 2, '0', STR_PAD_LEFT), $result);
        $result = str_replace('d', str_pad($jDate[2], 2, '0', STR_PAD_LEFT), $result);

        return $result;
    }

    /**
     * تبدیل تاریخ میلادی به شمسی (آرایه)
     * 
     * @param int $gy سال میلادی
     * @param int $gm ماه میلادی
     * @param int $gd روز میلادی
     * @return array [سال شمسی, ماه شمسی, روز شمسی]
     */
    private static function gregorianToJalali(int $gy, int $gm, int $gd): array
    {
        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $jy = ($gy <= 1600) ? 0 : 979;
        $gy -= ($gy <= 1600) ? 621 : 1600;
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = (365 * $gy) + ((int)(($gy2 + 3) / 4)) - ((int)(($gy2 + 99) / 100)) + ((int)(($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm - 1];
        $jy += 33 * ((int)($days / 12053));
        $days %= 12053;
        $jy += 4 * ((int)($days / 1461));
        $days %= 1461;
        $jy += (int)(($days - 1) / 365);
        if ($days > 365) $days = ($days - 1) % 365;
        $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
        $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
        return [$jy, $jm, $jd];
    }

    /**
     * تبدیل تاریخ شمسی به میلادی
     * 
     * @param int $jy سال شمسی
     * @param int $jm ماه شمسی
     * @param int $jd روز شمسی
     * @return array [سال میلادی, ماه میلادی, روز میلادی]
     */
    private static function jalaliToGregorian(int $jy, int $jm, int $jd): array
    {
        $jy += 1595;
        $days = -355668 + (365 * $jy) + ((int)($jy / 33)) * 8 + ((int)((($jy % 33) + 3) / 4)) + $jd + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
        $gy = 400 * ((int)($days / 146097));
        $days %= 146097;
        if ($days > 36524) {
            $gy += 100 * ((int)(--$days / 36524));
            $days %= 36524;
            if ($days >= 365) $days++;
        }
        $gy += 4 * ((int)($days / 1461));
        $days %= 1461;
        if ($days > 365) {
            $gy += (int)(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        $gd = $days + 1;
        $sal_a = [0, 31, (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        for ($gm = 0; $gm < 13 && $gd > $sal_a[$gm]; $gm++) $gd -= $sal_a[$gm];
        return [$gy, $gm, $gd];
    }

    /**
     * فرمت تاریخ شمسی با نام ماه
     * 
     * @param string|int|null $date تاریخ میلادی
     * @return string|null
     */
    public static function toPersianWithMonthName($date): ?string
    {
        $persianDate = self::toPersian($date, 'Y-m-d');
        if (!$persianDate) {
            return null;
        }

        $parts = explode('-', $persianDate);
        $year = $parts[0];
        $month = (int)$parts[1];
        $day = $parts[2];

        $monthNames = [
            1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد',
            4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور',
            7 => 'مهر', 8 => 'آبان', 9 => 'آذر',
            10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
        ];

        return $day . ' ' . $monthNames[$month] . ' ' . $year;
    }
}

