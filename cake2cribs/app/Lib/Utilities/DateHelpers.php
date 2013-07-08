<?php
    class DateHelpers{
        /*
        $dates is an array of date strings
        returns a two item array with the number of weekdays 
        */
        public static function getDayCounts($dates){
            $weekdays = 0;
            $weekends = 0;
            foreach ($dates as $key => $date) {
                $day = date("N", strtotime($date));
                if($day > 5){
                    $weekends++;
                }else{
                    $weekdays++;
                }
            }
            return array('weekdays'=>$weekdays, 'weekends'=>$weekends);
        }

    }
    

?>