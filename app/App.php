<?php

class App
{
    /**
     * @var QueryBuilder[]
     */
    public static $DBs = array();
    /**
     * @var Configs
     */
    public static $Configs;

    /**
     * Шаблонизатор отбражения
     *
     * @var Smarty Шаблонизатор
     */
    public static $Smarty;

    /**
     * @var Post
     */
    public static $Post;

    public static $countDB;

    public static $NamesDB;

    public static $LANG;

    public static $FullServerOnline = 0;


    public function __construct()
    {
        include "configs/Configs.php";
        include "library/QueryBuilder/QueryBuilder.php";
        include "library/QueryBuilder/ResultQuery.php";
        static::$Configs = new Configs();

        Security::XssTest();

        static::$countDB = 0;

        foreach (static::$Configs->db as $db_info)
        {
            if ($db_info['host'] != "")
            {
                $db['default'] = array(
                    'hostname' => $db_info['host'],
                    'username' => $db_info['login'],
                    'password' => $db_info['pass'],
                    'database' => $db_info['db_name'],
                    'db_debug' => FALSE
                );
                static::$DBs[static::$countDB] = new QueryBuilder($db['default']);
                static::$DBs[static::$countDB]->column_names = $db_info['ColumnTablesInDb'];

                static::$DBs[static::$countDB]->initialize();

                static::$NamesDB[static::$countDB] = $db_info['serv_name'];
        
                static::$countDB++;
            }
        }

    }
    public function run()
    {
        $this->initSmarty();

        $this->Intro();

        static::$Post = new Post();
        $routes = $this->routes();

        if(!class_exists($routes['ControllerName']))
            $routes['ControllerName'] = "SiteController";

        $controller = new $routes['ControllerName'];

        if (!method_exists($controller, $routes['ActionName']))
        {
            $routes['ActionName'] = "actionIndex";
        }
        static::$Configs->LoadLang($routes);
        if ($routes['ControllerName'] != "AdminController")
            $this->InitWidgets();

        $reflectionMethod = new ReflectionMethod($routes['ControllerName'], $routes['ActionName']);

        $page = $reflectionMethod->invokeArgs($controller, $routes['Params']);

        static::$Smarty->assign('Messages', Messages::GetAllMessages());
        Messages::ClearAllMessages();

        static::$Smarty->assign('page', $page);
        static::$Smarty->display(static::$Configs->main['template'] . '/index.tpl');

        //Statistic site
        //$VisitorStats = new VisitorStatistics(); $VisitorStats->Run();

    }

    /*
     * Инициализируем шаблонизатор Smarty
     */
    private function initSmarty()
    {
        define('SMARTY_DIR', str_replace("\\", "/", getcwd()).'/library/Smarty/');

        require_once (SMARTY_DIR . "Smarty.class.php");
        static::$Smarty = new Smarty();
        static::$Smarty->template_dir = $_SERVER['DOCUMENT_ROOT'] ."/views/templates/";
        static::$Smarty->compile_dir =  SMARTY_DIR . 'tmp/templates_c/';
        static::$Smarty->config_dir = SMARTY_DIR . 'configs/';
        static::$Smarty->cache_dir = SMARTY_DIR . '/tmp/cache/';
        //static::$Smarty->debugging = true;
        static::$Smarty->caching = false;

        static::$Smarty->assign('Time', @date("F d, Y H:i:s"));
        static::$Smarty->assign('RF', static::$Configs->main['RF']);
        static::$Smarty->assign('CountDB', static::$countDB);
        static::$Smarty->assign('NamesDB', static::$NamesDB);
        static::$Smarty->assign('template', static::$Configs->main['template']);
        if (isset($_SESSION['in_login']) && $_SESSION['in_login'] == true)
        {
            static::$Smarty->assign('InLogin', true);
        }
        if (isset($_SESSION['active_char']) && $_SESSION['in_login'] != "")
        {
            static::$Smarty->assign('ActiveChar', $_SESSION['active_char']);
        }

    }
    private function routes()
    {
        $Uri = $_SERVER['REQUEST_URI'];
        $Uri = trim($Uri,"/\\");
        $Paths = explode("/",$Uri);

        if (isset($Paths[0]))
        {
            for ($i = 0; $i < count($Paths); $i++)
            {
                $Paths[$i] = App::Validate($Paths[$i]);
            }
        }
        if (isset($Paths[0]) && $Paths[0] != "" && $Paths[0][0] != "?"  )
            $ControllerName = $Paths[0];
        else
            $ControllerName = "site";

        if (isset($Paths[1]) && $Paths[1] != "" && $Paths[1][0] != "?" )
            $ActionName = $Paths[1];
        else
            $ActionName = "index";

        array_shift($Paths);
        array_shift($Paths);

        $ControllerName = ucfirst(strtolower($ControllerName)) . "Controller";
        $ActionName = "action" . ucfirst(strtolower($ActionName));

        return array(
            'ControllerName' => $ControllerName,
            'ActionName' => $ActionName,
            'Params' => $Paths
        );

    }
    public function InitWidgets()
    {
        for ($i= 0 ;$i < static::$countDB; $i++)
        {
            $ServerInfo[$i] = new ServerInfo($i);
            $ServerInfo[$i] = $ServerInfo[$i]->Main();
        }
        if (static::$FullServerOnline == 0) {
            static::$FullServerOnline = App::$Configs->GetCacheVariable('FullServerOnline');
        } else {
            App::$Configs->SetCacheVariable('FullServerOnline', static::$FullServerOnline);
        }
        App::$Smarty->assign('FullServerOnline', static::$FullServerOnline);
        App::$Smarty->assign('WidgetServerInfo', $ServerInfo);
        $forum = new Forum();

        App::$Smarty->assign('WidgetForum', $forum->Main());

        for ($i= 0 ;$i < static::$countDB; $i++)
        {
            $Strongest[$i] = new Strongest($i);
            $Strongest[$i] = $Strongest[$i]->Main();
        }
        App::$Smarty->assign('WidgetStrongest', $Strongest);

        for ($i= 0 ;$i < static::$countDB; $i++)
        {
            $TopGuildsWidget[$i] = new TopGuilds($i);
            $TopGuildsWidget[$i] = $TopGuildsWidget[$i]->Main();
        }
        App::$Smarty->assign('WidgetTopGuilds', $TopGuildsWidget);
        for ($i= 0 ;$i < static::$countDB; $i++)
        {
            $TopPKWidget[$i] = new TopPK($i);
            $TopPKWidget[$i] = $TopPKWidget[$i]->Main();
        }
        App::$Smarty->assign('WidgetTopPK', $TopPKWidget);

        for ($i= 0 ;$i < static::$countDB; $i++)
        {
            $TopSellerCharsWidget[$i] = new TopSellerChars($i);
            $TopSellerCharsWidget[$i] = $TopSellerCharsWidget[$i]->Main();
        }
        App::$Smarty->assign('WidgetTopSellerChars', $TopSellerCharsWidget);
    }
    public static function Validate($str)
    {
        $str = htmlspecialchars($str, ENT_QUOTES);
        $str = trim($str);
        $str = Security::FilterWorlds($str);
        return $str;
    }
    public function Intro()
    {
        if (App::$Configs->main['IntroEnable']) {
            if (!$_COOKIE['intro']) {
                setcookie('intro',true, time()+App::$Configs->main['IntroActionTime']);
                header('Location: /intro');
                die;
            }
        }
    }
}