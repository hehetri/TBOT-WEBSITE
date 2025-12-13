<?php
class LangController extends Controller
{
    public function actionIndex()
    {
        if (property_exists(App::$Post, 'lang'))
        {
            if (App::$Post->lang == 'ru' || App::$Post->lang == "en")
                $_SESSION['lang'] = App::$Post->lang;
        }
        header("Location: /");
    }
}