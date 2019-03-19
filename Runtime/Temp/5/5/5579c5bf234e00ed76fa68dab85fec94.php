<?php
//000000000000s:245:"SELECT p.thumbfiles,u.avatar,p.photoid,p.idmd5,u.user_nicename,p.hits FROM lx_user_photo p INNER JOIN lx_users as u ON u.id=p.uid  WHERE (  1=1  and sex=2 or cityid=0 and p.flag =1 and p.phototype=0 ) ORDER BY p.elite,p.photoid desc LIMIT 0,15  ";
?>