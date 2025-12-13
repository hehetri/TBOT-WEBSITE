<?php
abstract class Widget
{
    public $Config;
    public $ClassName;

    public function __construct($ClassName)
    {
        $this->ClassName = $ClassName;
        $ClassName = strtolower($ClassName);
        $this->Config = App::$Configs->{"wid_".$ClassName};
        

    }

    abstract protected function Main();
    protected function render($template, Array $Params = null)
    {
        if ($Params != null)
        {
                App::$Smarty->assign( "InWidget" . $this->ClassName, $Params);
        }
        if (file_exists("views/templates/" . App::$Configs->main['template'] . "/widgets/" . $template))
            $widget = App::$Smarty->fetch(App::$Configs->main['template'] . "/widgets/" . $template);
        else
            $widget = App::$Smarty->fetch("default/widgets/" . $template);
        return $widget;
    }

    protected function CheckCacheDb($method, $Db)
    {
        $method = "Widget" . $method;
        $Config = App::$Configs->cache;
        if (file_exists("cache/".$_SESSION['lang']."/widgets/DB_{$Db}/{$method}.cache"))
        {
            if (App::$DBs[$Db]->conn_id == false)
            {
                return true;
            }
            if( (time() - filemtime("cache/".$_SESSION['lang']."/widgets/DB_{$Db}/{$method}.cache")) < $Config[$method])
            {        
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    protected function GetCacheDb($method, $Db)
    {
        $method = "Widget" . $method;
        return file_get_contents("cache/".$_SESSION['lang']."/widgets/DB_{$Db}/{$method}.cache");
    }
    protected function WriteCacheDb($method, $Db, $text)
    {
        $method = "Widget" . $method;
        file_put_contents("cache/".$_SESSION['lang']."/widgets/DB_{$Db}/{$method}.cache", $text);
    }

    protected function CheckCache($method)
    {
        $method = "Widget" . $method;
        $Config = App::$Configs->cache;
        if (file_exists("cache/".$_SESSION['lang']."/widgets/{$method}.cache"))
        {

            if( (time() - filemtime("cache/".$_SESSION['lang']."/widgets/{$method}.cache")) < $Config[$method])
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    protected function GetCache($method)
    {
        $method = "Widget" . $method;
        return file_get_contents("cache/".$_SESSION['lang']."/widgets/{$method}.cache");
    }
    protected function WriteCache($method, $text)
    {
        $method = "Widget" . $method;
        file_put_contents("cache/".$_SESSION['lang']."/widgets/{$method}.cache", $text);
    }

}