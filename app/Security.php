<?php
class Security
{
    public static function XssTest()
    {
        if (@$_SERVER['HTTP_REFERER'])
        {

            $refer = parse_url($_SERVER['HTTP_REFERER']);
            if($refer["host"]!=$_SERVER['HTTP_HOST'])
            {
                if ($_POST)
                {
                    Logs::WriteLog("XSS_ подозрение на xss атаку: '".$_SERVER['REMOTE_ADDR']."'","Security.log");
                    die("Maybe xss, sorry :)");
                }
            }
        }
    }
    public static function FilterWorlds($var){
        $badwords = array(";","'","delete","union","update","insert","drop","shutdown","<script>","</script>","script","%","$",",","`","system","/",'chr(', 'chr=', 'chr%20', '%20chr', 'wget%20', '%20wget', 'chr ', ' chr', 'wget ', ' wget' , 'wget(','cmd=', '%20cmd', 'cmd%20', ' cmd', 'cmd ', 'rush=', ' rush', 'rush ','union ', ' union', ' rush', 'rush ','union ', ' union', 'union(', 'union=', 'echr(', '%20echr', 'echr%20', ' echr', 'echr ', 'echr=','esystem(', 'esystem%20', 'cp%20', '%20cp', 'esystem ', 'cp ', ' cp', 'cp(', 'mdir%20', '%20mdir' , 'mdir ', ' mdir', 'mdir(','mcd%20', 'mrd%20', 'rm%20', '%20mcd', ' mrd', ' rm' ,'mcd ', 'mrd ', 'rm ', ' mcd', ' mrd', ' rm' ,'mcd(', 'mrd(', 'rm(', 'mcd=', 'mrd=', 'mv%20', 'rmdir%20', 'mv(', 'rmdir(','chmod(', 'chmod%20', '%20chmod', 'chmod(', 'chmod=', 'chown%20', 'chgrp%20', 'chown(', 'chgrp(','locate%20', 'grep%20', 'locate(', 'grep(', 'diff%20', 'kill%20', 'diff ', 'kill ' , 'kill(', 'killall','passwd%20', '%20passwd' ,'passwd ', ' passwd' ,'passwd ', ' passwd', 'passwd(', 'telnet%20' , 'telnet ', 'vi(', 'vi%20','insert%20into', 'select%20' , 'vi%20','insert into', 'select ', 'fopen', 'fwrite', '%20like', 'like%20' , ' like', 'like ','$_request', '$_get', '$request', '$get', '.system', 'HTTP_PHP', '&aim', '%20getenv', 'getenv%20' , ' getenv', 'getenv ','/etc/password','/etc/shadow', '/etc/groups', '/etc/gshadow','HTTP_USER_AGENT', 'HTTP_HOST', '/bin/ps', 'wget%20' , 'wget ', 'uname\x20-a', '/usr/bin/id','/bin/echo', '/bin/kill', '/bin/', '/chgrp', '/chown', '/usr/bin', 'g\+\+', 'bin/python','bin/tclsh', 'bin/nasm', 'perl%20', 'traceroute%20', 'ping%20', '.pl', '/usr/X11R6/bin/xterm', 'lsof%20', 'lsof ','/bin/mail', '.conf', 'motd%20' , 'motd', 'HTTP/1.', '.inc.php', 'config.php', 'cgi-', '.eml','file\://', 'window.open', 'javascript\://','img src', 'img%20src', 'img src','.jsp','ftp.exe','xp_enumdsn', 'xp_availablemedia', 'xp_filelist', 'xp_cmdshell', 'nc.exe', '.htpasswd','servlet', '/etc/passwd', 'wwwacl', '~root', '~ftp', '.js', '.jsp', '.history','bash_history', '.bash_history', '~nobody', 'server-info', 'server-status', 'reboot%20', 'halt%20','powerdown%20' , 'reboot ', 'halt ','powerdown ', '/home/ftp', '/home/www', 'secure_site, ok', 'chunked', 'org.apache', '/servlet/con','<script', '/robot.txt' ,'/perl' ,'mod_gzip_status', 'db_mysql.inc', '.inc', 'select%20from','select from', 'drop%20' , 'drop ', '.system', 'getenv', 'http_', '_php', 'php_', 'phpinfo()', 'DELETE%20FROM' , 'DELETE FROM', 'MEMB_INFO', 'Character','AccountCharacter', 'MEMB_CREDITS', 'VI_CURR_INFO', '.exe', '<?php', '?>', 'sql=','../','..\\','"','&lt','&gt');
        foreach($badwords as $word)
        {
            if(substr_count(strtolower($var), strtolower($word)) > 0)
            {	
            	var_dump($var);
                Logs::WriteLog("Inject запрещенные символы: ".htmlspecialchars($var));
                $var=0;
            }
            $var = trim(preg_replace("/[^a-z[A-Z]0-9_!.-]/","", $var));
        }
        return $var;
    }
}