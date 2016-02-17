<?php
/**
 * ----------------------
 * ConfsettingController.php
 *
 * User: jian0307@icloud.com
 * Date: 2015/5/27
 * Time: 14:01
 * ----------------------
 */
namespace Lib;

class DateUtil
{
    /**
     * 根据指定日期获取所在周的起始时间和结束时间
     * @param $date
     * @return array
     */
    public static function getWeekInfoByDate($date)
    {
        $idx = strftime("%u", strtotime($date));
        $mon_idx = $idx - 1;
        $sun_idx = $idx - 7;
        return array(
            'week_start_day' => strftime('%Y-%m-%d', strtotime($date) - $mon_idx * 86400),
            'week_end_day' => strftime('%Y-%m-%d', strtotime($date) - $sun_idx * 86400),
        );
    }

    /**
     * 根据指定日期获取所在月的起始时间和结束时间
     * @param $date
     * @return array
     */
    public static function getMonthinfoByDate($date)
    {
        $ret = array();
        $timestamp = strtotime($date);
        $mdays = date('t', $timestamp);
        return array(
            'month_start_day' => date('Y-m-1', $timestamp),
            'month_end_day' => date('Y-m-'.$mdays, $timestamp)
        );
    }

    /**
     * 获取指定日期之间的各个周
     * @param $sdate
     * @param $edate
     * @return array
     */
    public static function getWeeks($sdate, $edate)
    {
        $range_arr = array();
        // 检查日期有效性
        self::checkDate(array($sdate, $edate));
        // 计算各个周的起始时间
        do {
            $weekinfo = self::getWeekInfoByDate($sdate);
            $end_day = $weekinfo['week_end_day'];

            $start = self::substrDate($weekinfo['week_start_day']);
            $end = self::substrDate($weekinfo['week_end_day']);
            $range = "{$start} ~ {$end}";
            $range_arr[] = $range;

            $sdate = date('Y-m-d', strtotime($sdate)+7*86400);
        } while ($end_day < $edate);
        return $range_arr;
    }

    /**
     * 获取指定日期之间的各个月
     * @param string $sdate 开始日期 格式: YYYY-mm-dd
     * @param string $edate 结束日期 格式: YYYY-mm-dd
     * @param bool $cut 是否去掉两端，即去除起始日期前和结束日期后的日期
     * @return array
     */
    public static function getMonths($sdate, $edate, $cut = false)
    {
        $sym = date('Ym', strtotime($sdate));
        $eym = date('Ym', strtotime($edate));
        $range_arr = array();
        do {
            //每个月的第一天
            $fdate = date('Y-m', strtotime($sdate)).'-01';
            $monthinfo = self::getMonthinfoByDate($fdate);
            $end_day = $monthinfo['month_end_day'];

            $dsym = date('Ym', strtotime($fdate));
            $deym = date('Ym', strtotime($end_day));

            if ($cut && $sym === $dsym) {
                $monthStartDay = $sdate;
            } else {
                $monthStartDay = $monthinfo['month_start_day'];
            }

            if ($cut && $eym === $deym) {
                $monthEndDay = $edate;
            } else {
                $monthEndDay = $monthinfo['month_end_day'];
            }

            $start = self::substrDate($monthStartDay);
            $end = self::substrDate($monthEndDay);
            $range = array($start,$end);
            $range_arr[] = $range;

            $sdate = date('Y-m-d', strtotime($fdate.'+1 month'));
        } while ($end_day < $edate);

        return $range_arr;
    }

    /**
     * 截取日期中的月份和日
     * @param string $date
     * @return string $date
     */
    public static function substrDate($date)
    {
        if (!$date) {
            return false;
        }
        return date('Y-m-d', strtotime($date));
    }

    /**
     * 检查日期的有效性 YYYY-mm-dd
     * @param array $date_arr
     * @return boolean
     */
    public static function checkDate($date_arr)
    {
        $invalid_date_arr = array();
        foreach ($date_arr as $row) {
            $timestamp = strtotime($row);
            $standard = date('Y-m-d', $timestamp);
            if ($standard != $row) {
                $invalid_date_arr[] = $row;
            }
        }
        if (!empty($invalid_date_arr)) {
            die("invalid date -> ".print_r($invalid_date_arr, true));
        }
    }

    /**
     * 求两个日期之间的天数
     * @param string $startDate 格式'YYYY-mm-dd'
     * @param string $endDate 格式'YYYY-mm-dd'
     * @return int
     */
    public static function diffDays($startDate, $endDate)
    {
        $startdate = strtotime($startDate);
        $enddate = strtotime($endDate);
        $days = round(($enddate-$startdate)/3600/24) ;
        return $days;
    }

    /**
     * 两个日期间的日期列表
     * @param string $start 格式 YYYY-mm-dd
     * @param string $end 格式 YYYY-mm-dd
     * @return array
     */
    public static function inDays($start, $end)
    {
        $dtStart = strtotime($start);
        $dtEnd = strtotime($end);
        $list = array();
        while ($dtStart <= $dtEnd) {
            array_push($list, date('Y-m-d', $dtStart));
            $dtStart = strtotime('+1 day', $dtStart);
        }
        return $list;
    }
}
