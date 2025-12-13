<?php
class StatisticController extends Controller
{
    public function __construct()
    {
        App::$Smarty->assign('sidebar', 'off');
    }
    private function CheckCache($method, $Db)
    {
        $Config = App::$Configs->cache;
        if (file_exists("cache/".$_SESSION['lang']."/DB_{$Db}/{$method}.cache"))
        {
            if (App::$DBs[$Db]->conn_id == false)
            {
                return true;
            }
            if( (time() - filemtime("cache/".$_SESSION['lang']."/DB_{$Db}/{$method}.cache")) < $Config[$method]) {
                return true;
            }
            if (substr($method,0,13) == "actionPlayers" && (time() - filemtime("cache/".$_SESSION['lang']."/DB_{$Db}/{$method}.cache")) < $Config['actionPlayers']) {
                return true;
            }
            return false;

        }
        else
        {
            return false;
        }
    }
    private function GetCache($method, $Db)
    {
        return file_get_contents("cache/".$_SESSION['lang']."/DB_{$Db}/{$method}.cache");
    }
    private function WriteCache($method, $Db, $text)
    {
        file_put_contents("cache/".$_SESSION['lang']."/DB_{$Db}/{$method}.cache", $text);
    }

    public function actionIndex($Db = null)
    {
        if ($Db === null || $Db < 0 || $Db > App::$countDB)
        {
            $Db = 0;
        }
        
        if ($this->CheckCache(__FUNCTION__,$Db))
            return $this->GetCache(__FUNCTION__, $Db);
        $Castle = Statistic::Castle($Db);
        $Statistic = Statistic::ServerStatistic($Db);
        $content = $this->render("rankings/index.tpl", array('Statistic' => $Statistic, 'Castle' => $Castle, 'TopDb' => $Db));
        $this->WriteCache(__FUNCTION__, $Db, $content);
        return $content;
    }
    public function actionPlayers( $Db = null, $ClassPlayer = null)
    {
        if ($Db === null || $Db < 0 || $Db > App::$countDB)
        {
            $Db = 0;
        }
        if ($this->CheckCache(__FUNCTION__ . $ClassPlayer,$Db))
            return $this->GetCache(__FUNCTION__ . $ClassPlayer, $Db);
        $Players = Statistic::TopPlayers($ClassPlayer, $Db);

        $Pk = Statistic::TopPk($Db);
        $Guilds = Statistic::TopGuilds($Db);
        $Castle = Statistic::Castle($Db);
        $Voters = Statistic::TopVoters($Db);
        $BC = Statistic::TopBC($Db);
        $DS = Statistic::TopDS($Db);
        $CC = Statistic::TopCC($Db);
        
        $content = $this->render("rankings/players.tpl" , array('Players' => $Players, 'TopPK' => $Pk,'Guilds' => $Guilds,  'Castle' => $Castle,'Voters' => $Voters, 'TopDb' => $Db, 'BC' => $BC, 'DS' => $DS, 'CC' => $CC));
        $this->WriteCache(__FUNCTION__ . $ClassPlayer, $Db, $content);
        return $content;
    }
    public function actionPk($Db = null)
    {
        if ($Db === null || $Db < 0 || $Db > App::$countDB)
        {
            $Db = 0;
        }
        if ($this->CheckCache(__FUNCTION__,$Db))
            return $this->GetCache(__FUNCTION__, $Db);
        $Pk = Statistic::TopPk($Db);
        $content = $this->render("rankings/pk.tpl", array('TopPK' => $Pk, 'TopDb' => $Db));
        $this->WriteCache(__FUNCTION__, $Db, $content);
        return $content;
    }
    public function actionGuilds($Db = null)
    {
        if ($Db === null || $Db < 0 || $Db > App::$countDB)
        {
            $Db = 0;
        }
        if ($this->CheckCache(__FUNCTION__,$Db))
            return $this->GetCache(__FUNCTION__, $Db);
        $Guilds = Statistic::TopGuilds($Db);
        $content = $this->render("rankings/guilds.tpl", array('Guilds' => $Guilds, 'TopDb' => $Db));
        $this->WriteCache(__FUNCTION__, $Db, $content);
        return $content;
    }
    public function actionCastle($Db = null)
    {
        if ($Db === null || $Db < 0 || $Db > App::$countDB)
        {
            $Db = 0;
        }
        if ($this->CheckCache(__FUNCTION__,$Db))
            return $this->GetCache(__FUNCTION__, $Db);
        $Castle = Statistic::Castle($Db);
        $content = $this->render("rankings/castle.tpl", array('Castle' => $Castle, 'TopDb' => $Db));
        $this->WriteCache(__FUNCTION__, $Db, $content);
        return $content;
    }
    public function actionPrisons($Db = null)
    {
        if ($Db === null || $Db < 0 || $Db > App::$countDB)
        {
            $Db = 0;
        }
        if ($this->CheckCache(__FUNCTION__,$Db))
            return $this->GetCache(__FUNCTION__, $Db);
        $Prisons = Prison::GetAllPrisons($Db);
        $content = $this->render("rankings/prisons.tpl", array( 'Prisons' => $Prisons, 'TopDb' => $Db));
        $this->WriteCache(__FUNCTION__, $Db, $content);
        return $content;
    }
    public function actionPlayer($Nick, $Db = null)
    {
        App::$Smarty->assign('sidebar', 'on');
        if ($Db === null || $Db < 0 || $Db > App::$countDB)
        {
            $Db = 0;
        }
        $Nick = App::Validate($Nick);
        $PlayerInfo = Statistic::PlayerInfo($Nick, $Db);
        return $this->render('rankings/player.tpl', array('Player' => $PlayerInfo[0], 'TopDb' => $Db));
    }
    public function actionGuild($GuildName, $Db = null)
    {
        App::$Smarty->assign('sidebar', 'on');
        if ($Db === null || $Db < 0 || $Db > App::$countDB)
        {
            $Db = 0;
        }
        $GuildName = App::Validate($GuildName);
        $GuildInfo = Statistic::GuildInfo($GuildName, $Db);
        return $this->render('rankings/guild.tpl', array('Guild' => $GuildInfo, 'TopDb' => $Db));
    }
}