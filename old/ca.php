<?
date_default_timezone_set("PRC");
    
    $time = getdate();
    $mday = $time["mday"];
    echo $mon = $time["mon"];
    $year = $time["year"];
    
    
    if($mon==4||$mon==6||$mon==9||$mon==11){
        $day = 30;
    }elseif($mon==2){
        if(($year%4==0&&$year%100!=0)||$year%400==0){
            $day = 29;
        }else{
            $day = 28;
        }
    }else{
        $day = 31;
    }
    
    $w = getdate(mktime(0,0,0,$mon,1,$year))["wday"];
    
    $date = function($day,$w){
        echo "<table border='1'>";
        echo "<tr><th>星期日</th><th>星期一</th><th>星期二</th><th>星期三</th><th>星期四</th><th>星期五</th><th>星期六</th></tr>";
        $arr = array();
        for($i=1;$i<=$day;$i++){
            array_push($arr,$i);
        }
        if($w>=1&&$w<=6){
            for($m=1;$m<=$w;$m++){
                array_unshift($arr,"");
            }
        }
        $n=0;
        for($j=1;$j<=count($arr);$j++){
            $n++;
            if($n==1) echo "<tr>";
            global $mday;
            if($mday==$arr[$j-1]){
                echo "<td width='80px' style='background-color: greenyellow;'>".$arr[$j-1]."</td>";
            }else{
                echo "<td width='80px'>".$arr[$j-1]."</td>";
            }
            
            if($n==7){
                echo "</tr>";
                $n=0;
            }
        }
        if($n!=7)echo "</tr>";
        
        echo "</table>";
    };
    $date($day,$w);