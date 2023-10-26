<?php
namespace App\Helper;
use App\Models\Table\Incentive\Incentive;
use App\Models\Table\Incentive\Customer;
use App\Models\Table\Incentive\CustType;
use DateTime;
class Helper
{
    public static function CekCust($id) {
        $customer = Customer::where('id', $id)->first();
        $custtype = CustType::select('percentage')->where('id',isset($customer['id']) ? 2 : 1)->first();
        return isset($custtype['percentage']) ? $custtype['percentage'] : 0;

    }
    public static function Total($grading,$aging,$gpm,$target,$inc_ef) {
        $total = round((($grading/100)*($aging/100)*($gpm/100)*($target)*$inc_ef)/100,2);
        return isset($total) ? $total : 0;

    }
    public static function CekInv($id)
    {
        $cekinv = Incentive::where('inv', $id)->first();
        return $cekinv;
    }
    public static function Datediff($date1,$date2)
    {
        $start = strtotime($date1);
        $end = strtotime($date2);

        $count = 0;

        if(date('Y-m-d', $start) < date('Y-m-d', $end)){while(date('Y-m-d', $start) < date('Y-m-d', $end)){
            $count += date('N', $start) < 6 ? 1 : 0;
            $start = strtotime("+1 day", $start);
            }
            return $count;
        }else{
            while(date('Y-m-d', $start) > date('Y-m-d', $end)){
            $count -= date('N', $start) < 6 ? 1 : 0;
            $start = strtotime("-1 day", $start);
            }
            return $count;
        }
    }
    public function TimeInterval($date1,$date2)
    {
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        $interval = abs($time1-$time2);
        return $interval;
    }
    public static function timeElapsedStringBefore($datetime) 
    {
        $full = false;
        date_default_timezone_set('Asia/Jakarta');
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) 
        {
            if ($diff->$k) 
            {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } 
            else 
            {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return  $string ? implode(', ', $string) . ' later' : 'just now';
    }

    public static function timeElapsedString($datetime) 
    {
        $full = false;
        date_default_timezone_set('Asia/Jakarta');
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) 
        {
            if ($diff->$k) 
            {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } 
            else 
            {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}