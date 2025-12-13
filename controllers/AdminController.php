<?php

class AdminController extends Controller
{
    private $isAdmin;
    private $NumDb;
    public function __construct()
    {
        

        if (isset($_SESSION['admin']))
        {
            $this->isAdmin = true;
        }
        else
        {
            $this->isAdmin = false;
        }
        if (isset($_SESSION['select_db_admin']) && $_SESSION['select_db_admin'] > 0 && $_SESSION['select_db_admin'] < App::$countDB)
        {
            $this->NumDb = $_SESSION['select_db_admin'];
        }
        else
        {
            $this->NumDb = 0;
        }
        App::$Smarty->assign('DbInAdmin', $this->NumDb);
    }
    protected function render($template, Array $Params = null)
    {
        ob_start();
        if ($Params != null && is_array($Params))
        {
            foreach ($Params as $key => $par)
            {
                App::$Smarty->assign($key, $par);
            }
        }

            App::$Smarty->display("default/admin/" . $template);


        $page = ob_get_contents();
        ob_clean();
        App::$Smarty->assign('AdminPage', $page);
        App::$Smarty->display("default/admin/main.tpl");
    }
    public function actionLogin()
    {
        if (property_exists(App::$Post, 'adm_login') && property_exists(App::$Post, 'adm_pass') && property_exists(App::$Post, 'adm_captcha'))
        {
            if (Admin::Login())
            {
                header("Location: /admin");
            }
        }
        App::$Smarty->display('default/admin/login.tpl');
        $this->CallEnd();
    }
    public function actionLogout()
    {
        Admin::Logout();
        header("Location: /admin/login");
    }
    private function IfNoLogin()
    {
        if ($this->isAdmin == false)
        {
            header("Location: /admin/login");
            die();
        }

    }
    public function actionIndex($param = null)
    {
        $this->IfNoLogin();
        $this->render('index.tpl', array('SiteSpace' => Admin::SiteSpace() ));

        $this->CallEnd();
    }
    public function actionGeneral($sub = null, $subsub = null)
    {
        $this->IfNoLogin();
        if ($sub == "databases") {
            $this->render('general/databases.tpl');
        }
        else if ($sub == "cache") {
            if (property_exists(App::$Post, 'cache_btn'))
                Admin::ConfigCache();
            $this->render('general/cache.tpl', array('Config' => json_decode(file_get_contents("configs/cache.json"), true)));
        }
        else if ($sub == "valutemanager") {
            if (property_exists(App::$Post, 'valute_btn'))
                Admin::ConfigValuteManager($this->NumDb);
            $this->render('general/valutemanager.tpl', array('Config' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true)));
        }
        else if ($sub == "lang") {
            if (property_exists(App::$Post, 'lang_ru_btn'))
                Admin::LangRuEdit();
            if (property_exists(App::$Post, 'lang_en_btn'))
                Admin::LangEnEdit();
            if (property_exists(App::$Post, 'messages_lang_ru_btn'))
                Admin::LangMessagesRuEdit();
            if (property_exists(App::$Post, 'messages_lang_en_btn'))
                Admin::LangMessagesEnEdit();
            
            if ($subsub == 'ru') {
                $LangConfig = json_decode(file_get_contents("configs/Lang/ru.json"), true);
                $UpdateValue = '';
                if  (property_exists(App::$Post, 'update_value')) {
                    $UpdateValue['Value'] = $LangConfig[App::$Post->update_value];
                    $UpdateValue['Key'] = App::$Post->update_value;
                }
                $this->render('general/langru.tpl', array('LangRu' => $LangConfig, 'UpdateValue' => $UpdateValue));
            } elseif ($subsub == 'en') {
                $LangConfig = json_decode(file_get_contents("configs/Lang/en.json"), true);
                $UpdateValue = '';
                if  (property_exists(App::$Post, 'update_value')) {
                    $UpdateValue['Value'] = $LangConfig[App::$Post->update_value];
                    $UpdateValue['Key'] = App::$Post->update_value;
                }
                $this->render('general/langen.tpl', array('LangEn' => $LangConfig, 'UpdateValue' => $UpdateValue));
            } else {
                $this->render('general/lang.tpl');
            }
            
            
        }
        else if ($sub == "novelty") {
            $read = json_decode(@file_get_contents("http://forum.ex-team.net/exteamweb/updater.json"),true);
            $updated = json_decode(@file_get_contents("configs/updated_files.json"),true);
            if (!empty($read))
            {
                if (property_exists(App::$Post, 'get_all')) {
                    foreach ($read as $file) {
                        if(Admin::UpdateFile($file['Path'] . $file['FileName'], @file_get_contents($file['Link']))) {
                            $updated[] = $file;
                        }
                    }
                    file_put_contents('configs/updated_files.json', json_encode($updated));
                }
                if (property_exists(App::$Post, 'get_one')) {
                    if(Admin::UpdateFile($read[App::$Post->get_one]['Path'] . $read[App::$Post->get_one]['FileName'], @file_get_contents($read[App::$Post->get_one]['Link']))) {
                        $updated[] = $read[App::$Post->get_one];
                        file_put_contents('configs/updated_files.json', json_encode($updated));
                    }
                }
            }
            $read = json_decode(@file_get_contents("updater.json"),true);
            $count_read = count($read);
            for ($i = 0; $i < $count_read; $i++){
                for ($j = 0; $j < count($updated); $j++) {
                    if (
                        $read[$i]['Name'] == $updated[$j]['Name'] &&
                        $read[$i]['Path'] == $updated[$j]['Path'] &&
                        $read[$i]['Description'] == $updated[$j]['Description'] &&
                        $read[$i]['Link'] == $updated[$j]['Link'] &&
                        $read[$i]['FileName'] == $updated[$j]['FileName']
                    ) {
                        unset($read[$i]);
                    }
                }
            }
            $this->render('general/novelty.tpl', array('Novelty' => $read));
        }
        else if($sub == "user"){
            if (property_exists(App::$Post, 'user_btn'))
                Admin::ConfigUser();
            $this->render('general/user.tpl', array('Config' => json_decode(file_get_contents("configs/user.json"), true)));
        }
        else if($sub == "logs"){
            $this->render('general/logs.tpl', array('MainLogs' => Logs::GetMainLogs(), 'ModulesLogs' => Logs::GetModulesLogs(), 'UserLogs' => Logs::GetUserLogs(), 'SecurityLogs' => Logs::GetSecurityLogs()));
        }
        else {
            if (property_exists(App::$Post, 'general_btn'))
                Admin::ConfigGenerals();
            $this->render('general/index.tpl', array('Config' => json_decode(file_get_contents("configs/main.json"), true)));
        }

        $this->CallEnd();
    }
    public function actionWidgets($sub = null)
    {
        $this->IfNoLogin();
        if ($sub == "forum") {
            if (property_exists(App::$Post, 'forum_btn'))
                Admin::ConfigWidgetsForum();
            $this->render('widgets/forum.tpl', array('Config' => json_decode(file_get_contents("configs/widgets/forum.json"), true)));
        }
        else if ($sub == "servinfo") {
            if (property_exists(App::$Post, 'serv_info_btn'))
                Admin::ConfigWidgetsServerInfo();
            $this->render('widgets/serverinfo.tpl', array('Config' => json_decode(file_get_contents("configs/widgets/serverinfo.json"), true)));
        }
        else if ($sub == "strongest") {
            if (property_exists(App::$Post, 'strongest_btn'))
                Admin::ConfigWidgetsStrongest();
            $this->render('widgets/strongest.tpl', array('Config' => json_decode(file_get_contents("configs/widgets/strongest.json"), true)));
        }
        else if ($sub == "topguilds") {
            if (property_exists(App::$Post, 'topguilds_btn'))
                Admin::ConfigWidgetsTopGuilds();
            $this->render('widgets/topguilds.tpl', array('Config' => json_decode(file_get_contents("configs/widgets/topguilds.json"), true)));
        }

        $this->CallEnd();
    }
    public function actionModules($sub = null)
    {
        $this->IfNoLogin();
        if ($sub == "addstats") {
            if (property_exists(App::$Post, 'addstats_btn'))
                Admin::ConfigModulesAddStats($this->NumDb);
            $this->render('modules/addstats.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/AddStats.json"), true), 
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "changeclass") {
            if (property_exists(App::$Post, 'changeclass_btn'))
                Admin::ConfigModulesChangeClass($this->NumDb);
            $this->render('modules/changeclass.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/ChangeClass.json"), true), 
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "reset") {
            if (property_exists(App::$Post, 'reset_btn'))
                Admin::ConfigModulesReset($this->NumDb);
            $this->render('modules/reset.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/Reset.json"), true), 
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "grandreset") {
            if (property_exists(App::$Post, 'grandreset_btn'))
                Admin::ConfigModulesGrandReset($this->NumDb);
            $this->render('modules/grandreset.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/GrandReset.json"), true), 
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "pkclear") {
            if (property_exists(App::$Post, 'pkclear_btn'))
                Admin::ConfigModulesPkClear($this->NumDb);
            $this->render('modules/pkclear.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/PkClear.json"), true), 
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "referral") {
            if (property_exists(App::$Post, 'referralsystem_btn'))
                Admin::ConfigModulesReferralSystem($this->NumDb);
            $this->render('modules/referralsystem.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/ReferralSystem.json"), true),
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "changename") {
            if (property_exists(App::$Post, 'change_name_btn'))
                Admin::ConfigModulesChangeName($this->NumDb);
            $this->render('modules/changename.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/ChangeName.json"), true),
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "resetstats") {
            if (property_exists(App::$Post, 'reset_stats_btn'))
                Admin::ConfigModulesResetStats($this->NumDb);
            $this->render('modules/resetstats.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/ResetStats.json"), true),
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "resetskilltree") {
            if (property_exists(App::$Post, 'reset_skill_tree_btn'))
                ResetMasterSkillTree::ConfigResetMasterSkillTree($this->NumDb);
            $this->render('modules/reset_skill_tree.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/ResetMasterSkillTree.json"), true),
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "charmarket") {
            if (property_exists(App::$Post, 'charmarket_btn'))
                CharacterMarket::ConfigModules($this->NumDb);
            $this->render('modules/charmarket.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/CharacterMarket.json"), true),
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "donate") {
            $Update = array();
            if (property_exists(App::$Post, 'delete_item')) {
                Donate::DeleteItem(App::$Post->delete_item, $this->NumDb);
            }
            if (property_exists(App::$Post, 'add_item')) {
                if (is_numeric(App::$Post->add_item)){
                    Donate::UpdateItem(App::$Post->add_item, $this->NumDb);
                }
                else{
                    Donate::AddItem($this->NumDb);
                }
            }
            if (property_exists(App::$Post, 'click_enable'))
                Donate::ChangeEnable($this->NumDb);
            $Config = json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/Donate.json"), true);

            if (property_exists(App::$Post, 'update_item')) {
                $Update = $Config[App::$Post->update_item];
                $Update['Key'] = App::$Post->update_item;
            }
            $Enable = $Config['Enable'];
            unset($Config['Enable']);

            $this->render('modules/donate.tpl', array('Config' => $Config,'Enable' => $Enable,'Update' => $Update,
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "shop") {
            if (property_exists(App::$Post, 'shop_save')) {
                Shop::MainConfig($this->NumDb, App::$Post->shop_save);
            }
            elseif (property_exists(App::$Post, 'delete_item')) {
                Shop::DeleteItem(App::$Post->delete_item, $this->NumDb);
            }
            elseif (property_exists(App::$Post, 'update_item')) {
                if (is_numeric(App::$Post->update_item)){
                    $Update = Shop::UpdateItem(App::$Post->update_item, $this->NumDb);
                }
            }
            elseif (property_exists(App::$Post, 'add_item')) {
                Shop::AddItem($this->NumDb, App::$Post->add_item);
            }
            $Config = json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/Shop.json"), true);
            $this->render('modules/shop.tpl', array('Config' => $Config,'Update' => $Update ,  'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "vote") {
            if (property_exists(App::$Post, 'mmotop_vote_btn'))
                Vote::ConfigMMOTop($this->NumDb);
            if (property_exists(App::$Post, 'qtop_vote_btn'))
                Vote::ConfigQTop($this->NumDb);

            $this->render('modules/vote.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/Shop.json"), true),
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "remotecontrol") {
           /* if (property_exists(App::$Post, 'mmotop_vote_btn'))
                Vote::ConfigMMOTop($this->NumDb);
            if (property_exists(App::$Post, 'qtop_vote_btn'))
                Vote::ConfigQTop($this->NumDb);*/

            $this->render('modules/remotecontrol.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/RemoteControl.json"), true),
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        else if ($sub == "hideinfo") {
            if (property_exists(App::$Post, 'hideinfo_btn'))
                Admin::ConfigModulesHideInfo($this->NumDb);
            $this->render('modules/hideinfo.tpl', array('Config' => json_decode(file_get_contents("configs/modules/DB_" . $this->NumDb . "/HideInfo.json"), true),
                'Valutes' => json_decode(file_get_contents("configs/DB_" . $this->NumDb . "/ValuteManager.json"), true) ));
        }
        $this->CallEnd();
    }
    public function actionNews()
    {
        $this->IfNoLogin();

        if (property_exists(App::$Post,'CountInPage'))
        {
            News::ConfigNews();
        }
        if (property_exists(App::$Post, 'news_btn')) {
            if (App::$Post->news_btn === "")
                News::AddNews();
            else
                News::UpdateNews(App::$Post->news_btn);
        }

        if (property_exists(App::$Post, 'delete_article'))
            News::DeleteNews(App::$Post->delete_article);

        $file = file("configs/news.dat");
        $Update = array();
        foreach ($file as $key => $article)
        {
            if (property_exists(App::$Post, 'update_article')){
                if ($key == App::$Post->update_article) {
                    $Update = explode('¦', $article);
                    $Update['Key'] = $key;
                    continue;
                }
            }
            $News[] = explode('¦', $article);
        }
        $this->render('newsmanager.tpl', array('News' => $News, 'Update' => $Update, 'Config' => json_decode(file_get_contents("configs/news.json"), true)));


        $this->CallEnd();
    }

    public function actionEditor($sub = null)
    {
        $this->IfNoLogin();
        if ($sub == "prisons") {
            if (property_exists(App::$Post, 'delete_prison'))
                Prison::DeletePrison($this->NumDb, App::$Post->delete_prison);
            if (property_exists(App::$Post, 'add_prison')) {
                if (App::$Post->Date == 0 && App::$Post->Time == 0) {
                    $Time = 1;
                }
                else{
                    $Time = strtotime(App::$Post->Date . " " . App::$Post->Time);
                }
                Prison::AddPrison($this->NumDb, App::$Post->Name, App::$Post->Reason, $Time);
            }
            $Prisons = Prison::GetAllPrisons($this->NumDb);
            $this->render('editor/prisons.tpl', array('Prisons' => $Prisons));
        }
        if ($sub == "items") {
            $ItemClass = new Item();
            if (property_exists(App::$Post, 'delete_prison')) {
                Prison::DeletePrison($this->NumDb, App::$Post->delete_prison);
            }
            if (property_exists(App::$Post, 'add_prison')) {
                Prison::AddPrison($this->NumDb, App::$Post->Name, App::$Post->Reason, $Time);
            }
            $Items = $ItemClass->GetAllItems();
            //var_dump($Items);
            $this->render('editor/items.tpl', array('Items' => $Items));
        }
        if ($sub = "accs"){
            $Acc = null;
            if (property_exists(App::$Post, 'search_for_ip') && App::$Post->search_for_ip != '') {
                $Accs = Admin::GetAccountForIp($this->NumDb, App::$Post->search_for_ip);
            }
            if (property_exists(App::$Post, 'search_for_acc') && App::$Post->search_for_acc != '') {
                $Accs = Admin::GetAccountForAcc($this->NumDb, App::$Post->search_for_acc);
            }
            if (property_exists(App::$Post, 'search_for_char') && App::$Post->search_for_char != '') {
                $Accs = Admin::GetAccountForChar($this->NumDb, App::$Post->search_for_char);
            }
            $this->render('editor/accs.tpl', array('Accs' => $Accs));
        }
        else{
            header("Location: /admin");
       }

        $this->CallEnd();
    }

    public function actionTemplate()
    {
        $this->IfNoLogin();
        if (property_exists(App::$Post, 'temp'))
            $Update = array();
        $Pages = scandir('views/templates/'.App::$Configs->main['template'].'/pages');
        if (property_exists(App::$Post, 'delete_page'))
        {
            if (file_exists('views/templates/'.App::$Configs->main['template'].'/pages/' . App::$Post->delete_page))
            {
                unlink('views/templates/'.App::$Configs->main['template'].'/pages/' . App::$Post->delete_page);
            }
        }
        if (property_exists(App::$Post, 'update_page'))
        {
            if (file_exists('views/templates/'.App::$Configs->main['template'].'/pages/' . App::$Post->update_page))
            {
                $Update['Title'] = current( explode('.',App::$Post->update_page));
                $Update['Content'] = file_get_contents('views/templates/'.App::$Configs->main['template'].'/pages/' . App::$Post->update_page);
            }
        }
        if (property_exists(App::$Post, 'page_btn'))
            {
                file_put_contents('views/templates/'.App::$Configs->main['template'].'/pages/' . App::$Post->new_page_title . '.tpl', $_POST['page_content']);
        }

        unset($Pages[0]);
        unset($Pages[1]);
        $this->render('templates.tpl', array('Pages' => $Pages, 'Update' => $Update));


        $this->CallEnd();
    }
    public function actionUsermessages($Dialog = null)
    {
        $this->IfNoLogin();
        if (property_exists(App::$Post,'send_to'))
        {
            WriteAdmin::AdminSendMessage();
        }
        if ($Dialog !== null) {
            $Account = $Dialog;
            $Dialog = WriteAdmin::GetDialog($Dialog);

            $this->render('feedback/dialog.tpl', array('Dialog' => $Dialog ,'Account' => $Account));
        } else {
            $Messages = WriteAdmin::GetListMessages();
            $this->render('feedback/index.tpl', array('Messages' => $Messages));
        }

        $this->CallEnd();
    }
    public function actionSelectdb()
    {
        $this->IfNoLogin();
        if (property_exists(App::$Post,'select_db_admin'))
        {
            $_SESSION['select_db_admin'] = App::$Post->select_db_admin;
        }
        header("Location: /admin");
        $this->CallEnd();
    }
    
    public function CallEnd()
    {
        die();
    }
}