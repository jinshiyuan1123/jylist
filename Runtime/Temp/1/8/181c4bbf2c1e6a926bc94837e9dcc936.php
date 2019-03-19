<?php
//000000000000s:294:"SELECT p.thumbfiles,p.title,p.uid,u.avatar,u.user_rank,p.photoid,p.timeline,p.idmd5 as pidmd5,u.idmd5,u.user_nicename,p.hits FROM lx_user_photo p INNER JOIN lx_users as u ON u.id=p.uid  WHERE (  1=1  and sex=2 or cityid=0 and p.flag =1 and p.phototype=0 ) ORDER BY p.timeline desc LIMIT 45,15  ";
?>