<?php

$content = <<<START
作业1.根据自己期末机试考试完成农产品商业网的录屏作业
期末考试机试成绩在75~85分将期末考试试卷加水印录1遍；
期末考试机试成绩在60~74分将期末考试试卷加水印录2遍；
期末考试机试成绩在40~59分将期末考试试卷加水印录5遍；
期末考试机试成绩在0~39分将期末考试试卷加水印录10遍；
注：水印格式为“日期 姓名 第几次录屏”，录屏不加水印则录屏无效
作业2.完成逸岛旅游网的页面布局作业3.请大家开始准备网页设计大赛	
1.确定主题；	
2.收集素材；	
3.开始网页的制作；
（页面控制在10~15个左右）
START;

echo base64_encode($content);