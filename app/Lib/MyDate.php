<?php

App::uses('CakeTime', 'Utility');

class MyDate{   

  public static function Show($data = null, $format = "d/m/Y", $linguage = array("today" => "Hoje", "yesterday" => "Ontem","this_week" => "Esta Semana"))
  {
     $result = CakeTime::format($format, $data);

     if(CakeTime::isToday($data))
     {
        $result = $linguage["today"];
     }
     elseif(CakeTime::wasYesterday($data))
     {
        $result = $linguage["yesterday"];
     }
     elseif(CakeTime::isThisWeek($data))
     {
        $result = $linguage["this_week"];
     }

     return $result; 
  }

}


?>