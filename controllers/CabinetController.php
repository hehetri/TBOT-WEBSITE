<?php

class CabinetController
{
    private $Cabinet;
    public function __construct()
    {
        $this->Cabinet = new Cabinet();
    }
    protected function render($template, Array $Params = null, $OnePage = false,$Ajax = false)
    {
        if ($Params != null && is_array($Params))
        {
            foreach ($Params as $key => $par)
            {
                App::$Smarty->assign($key, $par);
            }
        }
        if ($OnePage) {

            App::$Smarty->assign('Messages', Messages::GetAllMessages());

            if (file_exists("views/templates/" . App::$Configs->main['template'] . "/" . $template))
                App::$Smarty->display(App::$Configs->main['template'] . "/" . $template);
            else
                App::$Smarty->display("default/" . $template);

            Messages::ClearAllMessages();
            die;
        } elseif($Ajax == true) {

            App::$Smarty->assign('Messages', Messages::GetAllMessages());

            If (file_exists("views/templates/" . App::$Configs->main['template'] . "/" . $template))
                App::$Smarty->display(App::$Configs->main['template'] . "/" . $template);
            else
                App::$Smarty->display("default/" . $template);
            Messages::ClearAllMessages();
            die;
        } else{
            App::$Smarty->assign('Messages', Messages::GetAllMessages());

            ob_start();

            If (file_exists("views/templates/" . App::$Configs->main['template'] . "/" . $template))
                App::$Smarty->display(App::$Configs->main['template'] . "/" . $template);
            else
                App::$Smarty->display("default/" . $template);

            $page = ob_get_contents();
            ob_clean();

            App::$Smarty->assign('CabinetPage', $page);

            $MainValutes = $this->Cabinet->GetAllValutes();

            App::$Smarty->assign('MainValutes', $MainValutes);

            if (file_exists("views/templates/" . App::$Configs->main['template'] . "/cabinet/main.tpl"))
                App::$Smarty->display(App::$Configs->main['template'] ."/cabinet/main.tpl");
            else
                App::$Smarty->display("default/cabinet/main.tpl");
           Messages::ClearAllMessages();
            die;
        }

    }

    public function actionIndex($Mess = false)
    {
        if ($this->Cabinet->isLogin()) {
            $PanelInfo = $this->Cabinet->PanelInfo();
            $this->render("cabinet/panel.tpl", $PanelInfo,false,$Mess);
        } else {
            header("Location: /cabinet/login");
        }
    }
    public function actionLogin($Mess = null)
    {
        if ($this->Cabinet->isLogin()) {
            header("Location: /cabinet");
        }
        if (property_exists(App::$Post, 'login') && property_exists(App::$Post, 'pass')) {
            //if (App::$Post->captcha == $_SESSION['captcha']) {
                $this->Cabinet->Login();
                header("Location: /cabinet");
    /*        } else {
                Messages::Add("captcha_incorrect");
            }*/
        }
        $this->render('cabinet/login.tpl', null ,true);

    }
    public function actionRegister($Mess = false)
    {
        if ($this->Cabinet->isLogin()) {
            header("Location: /cabinet");
        }
        if ((property_exists(App::$Post, 'login') && property_exists(App::$Post, 'pass'))||$Mess == 'ajax') {
            //if (App::$Post->captcha == $_SESSION['captcha']){
                $statusReg = $this->Cabinet->Register();  
     /*       } else {
                Messages::Add("captcha_incorrect");
            }*/

            if ($Mess == 'ajax' && isset($_SESSION['RegFile'])) {
                header('Content-type: application/octet-stream');
                header('Content-Disposition: attachment; filename="[Freya]Account.txt"');
                echo $_SESSION['RegFile'];
                //unset($_SESSION['RegFile']);
                die;
            }
            if (isset($_SESSION['RegFile'])) {
                App::$Smarty->assign('ActiveRegFile', true);
            }
            if ($statusReg) {
                $this->render('cabinet/successreg.tpl', null ,true);
            } else {
                $this->render('cabinet/register.tpl', null ,true);
            }
        } else {
        $this->render('cabinet/register.tpl', null ,true);
        }
    }

    public function actionForgot()
    {
        if ($this->Cabinet->isLogin()) {
            header("Location: /cabinet");
        }
        if (property_exists(App::$Post, 'login') && property_exists(App::$Post, 'email')) {
            //if (App::$Post->captcha == $_SESSION['captcha']){
                $this->Cabinet->Forgot();
       /*     } else {
                Messages::Add("captcha_incorrect");
            }*/
        }
        $this->render('cabinet/forgot.tpl', null ,true);
    }
    public function actionLogout()
    {
        $this->Cabinet->Logout();
        header("Location: /cabinet/login");
    }
    public function actionStatistic($Mess = false)
    {
        if ($this->Cabinet->isLogin()) {
            $StatisticInfo = $this->Cabinet->StatisticInfo();
            $this->render("cabinet/statistic.tpl", $StatisticInfo, false, $Mess);
        } else {
            header("Location: /cabinet/login");
        }
    }
    public function actionChar()
    {
        if ($this->Cabinet->isLogin()) {
            $CharInfo = $this->Cabinet->CharInfo(App::$Post->char);
            $this->render("cabinet/char.tpl", array('Player' => $CharInfo), false, true);
        } else {
            header("Location: /cabinet/login");
        }
    }
    public function actionClan()
    {
        if ($this->Cabinet->isLogin()) {
            $ClanInfo = $this->Cabinet->ClanInfo(App::$Post->clan);
            $this->render("cabinet/clan.tpl", array('Guild' => $ClanInfo), false, true);
        } else {
            header("Location: /cabinet/login");
        }
    }
    public function actionSettings($Mess = false)
    {
        if ($this->Cabinet->isLogin()) {
            if (property_exists(App::$Post, 'btn_new_pass'))
            {
                if (App::$Post->new_pass == App::$Post->r_new_pass) {
                    $this->Cabinet->ChangePass(App::$Post->old_pass, App::$Post->new_pass);
                }
                else
                    Messages::Add("Пароли не совпадают");
            }

            $ReturnArr['Chars'] = $this->Cabinet->GetListCharsInAccount();
            $hideInfo = new HideInfo();
            $ReturnArr['NeedToHide'] = $this->Cabinet->NeedToHide();

            $changeClass = new ChangeClass();
            $ReturnArr['NeedToChangeClass'] = $this->Cabinet->NeedToChangeClass();

            if (property_exists(App::$Post, 'btn_hideinfo'))
            {
                if($this->Cabinet->Hide(App::$Post->selected_char,App::$Post->count_hour_hideinfo)){
                    header("Location: /cabinet");
                }
            } elseif (property_exists(App::$Post, 'btn_new_class'))  {
                    $this->Cabinet->ChangeClass(App::$Post->selected_char, App::$Post->selected_class);
            }
            $ReturnArr['CharacterValutes'] = $this->Cabinet->GetAllValutes();
            $ReturnArr['Valutes'] =  json_decode(file_get_contents("configs/DB_" . $_SESSION['cabinet_db'] . "/ValuteManager.json"),true);
            $this->render("cabinet/settings.tpl",$ReturnArr, false, $Mess);
        } else {
            header("Location: /cabinet/login");
        }
    }
    public function actionBuyprem($Mess = false)
    {
        if ($this->Cabinet->isLogin()) {
            if (property_exists(App::$Post, 'btn_buyprem'))
            {
                if($this->Cabinet->BuyPrem(App::$Post->buyprem_level,App::$Post->count_hour_buyprem)){
                    header("Location: /cabinet");
                }
            }
            $this->render("cabinet/buyprem.tpl",$ReturnArr, false, $Mess);
        } else {
            header("Location: /cabinet/login");
        }
    }
    public static function genRanDig($length = 7) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function actionPay($Mess = false)
    {
        if ($this->Cabinet->isLogin()) {
        	if ($Mess == "success") {
        		Messages::Add("Оплата успешна");
                header("Location: /cabinet");

        	}
        	else if ($Mess == "fail") {
        		Messages::Add("Оплата не удалась");
        		header("Location: /cabinet");
        	} else {

	            $rand_invoc = static::genRanDig(); //Random Invoice
                $rand_pm_no = static::genRanDig(); //Random PM Nomber
                $conv = 'ID_'.$rand_pm_no.'';
                $date_now = date("Y-m-d H:i:s");
                App::$DBs[$_SESSION['cabinet_db']]->insert('interkassa', array('memb___id' => $_SESSION['cabinet_login'], 'amount' => $rand_invoc, 'payment_id' => $conv, 'time' => $date_now, 'is_paid' => 0));

	            $this->render("cabinet/pay.tpl",array('rand_invoc'=> $rand_invoc, 'rand_pm_no' => $rand_pm_no), false, $Mess);
	        }
        } else {
            header("Location: /cabinet/login");
        }
    }
    public function actionVote($Mess = false)
    {
        if ($this->Cabinet->isLogin()) {
        	$this->Cabinet->VoteConfig = json_decode(file_get_contents("configs/modules/DB_" . $_SESSION['cabinet_db'] . "/Vote.json"),true);
            //if (!$this->Cabinet->VoteConfig['MMOTop']['Enable'])
            //    header("Location: /cabinet");

            if (property_exists(App::$Post, 'mmotop_vote_id')) {
                $this->Cabinet->GetRewardMMOTop(App::$Post->mmotop_vote_id);
            }
            else if (property_exists(App::$Post, 'qtop_vote_id')) {
                $this->Cabinet->GetRewardQTop(App::$Post->qtop_vote_id);
            }
            $ListVote['MMOTop'] = $this->Cabinet->GetListMMOTop();
            $ListVote['QTop'] = $this->Cabinet->GetListQTop();
      
            $this->render("cabinet/vote.tpl",
				array('ListVote' => $ListVote,'Valutes' => json_decode(file_get_contents("configs/DB_" . $_SESSION['cabinet_db'] . "/ValuteManager.json"),true), 'VoteConfig' => $this->Cabinet->VoteConfig ), false, $Mess);
	        
        } else {
            header("Location: /cabinet/login");
        }
    }

    public function actionInformation($Mess = false)
    {
        if ($this->Cabinet->isLogin()) {

            $this->render("cabinet/information.tpl",null, false, $Mess);
        } else {
            header("Location: /cabinet/login");
        }
    }

    public function actionPrefix($Mess = false)
    {
        $s = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ", 2)), 0, 2);
        echo $s;
        die;
    }

}