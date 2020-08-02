<?php
//返回当前的毫秒时间戳
list($msec, $sec) = explode(' ', microtime());
echo $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);