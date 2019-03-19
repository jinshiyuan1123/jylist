<?php
//000000000000s:304:"SELECT a.time,b.user_nicename,b.avatar,b.sex,b.age,b.jifen,b.user_rank,b.rank_time,b.provinceid,b.cityid,c.monolog,c.astro,b.idmd5 FROM lx_user_subscribe a LEFT JOIN lx_users b ON b.id=a.fromuid LEFT JOIN lx_user_profile c ON c.uid= a.fromuid  WHERE ( a.touid = '1069' ) ORDER BY a.time desc LIMIT 0,15  ";
?>