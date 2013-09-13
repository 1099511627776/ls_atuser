<?php

class PluginAtuser_HookAtuser extends Hook {

    /*
     * Регистрация событий на хуки
    */
    protected function processHashTag($match){
        $word = $match[1];
        return '<a class="inline-tag" href="'.Router::GetPath('tag').trim($word).'/">'.trim($word).'</a>';
    }

    protected function makeHashTag($sText){
        return preg_replace_callback("/#(\p{L}+)/misu",array($this,"processHashTag"),$sText);
    }

    protected function makeAtUser($sText,$template,$aAssign=array()){
        $match = array();
        preg_match_all('/@\w+/u',$sText,$match);
        $repls = array();
        foreach($match as $vals){
            if(count($vals) != 0) {
                foreach ($vals as $val){
                    $login = substr(trim($val),1);
                    if($oUser = $this->User_GetUserByLogin($login)) {
                        $repls[] = array('repl'=>$val,'ref'=>$oUser->getUserWebPath(),'login'=>$oUser->getLogin());
                    }
                }
            }
        }
        $sRes = $sText;
        foreach($repls as $repl) {
            $sRes = str_replace($repl['repl'],'<a href="'.$repl['ref'].'" class="ls-user">'.$repl['login'].'</a>',$sRes);
        }
        preg_match_all("/<a.*?class=[\"']ls-user[\"']\s*?>(.*?)<\/a>/u",$sText,$match);
        $matches = array_unique($match[1]);
        $sNotifyTitle = $this->Lang_Get('plugin.atuser.notify_title');
        foreach($matches as $val){
            $login = trim($val);
            if($oUser = $this->User_GetUserByLogin($login)) {
                if($template != ''){
                    $params=array('oUser'=>$oUser);
                    $params = array_merge($params,$aAssign);
                    $this->Notify_Send($oUser,$template,$sNotifyTitle,$params,'atuser');
                }
            }
        }
        return $sRes;
    }
    public function RegisterHook() {
        $this->AddHook('comment_add_before', 'correctComment',__CLASS__);
        $this->AddHook('comment_item_add_after', 'correctComment',__CLASS__);
    }
    
    public function correctComment($params){
        $oComment = $params['oCommentNew'];
        $oSender = $this->User_GetUserById($oComment->getUserId());
        $oTopic = $this->Topic_GetTopicById($oComment->getTargetId());
        $sRes = $this->makeAtUser($oComment->getText(),
            'notify.comment_mention.tpl',
            array('oComment'=>$oComment,'oSender'=>$oSender,'oTopic'=>$oTopic)
        );
        $oComment->setText($sRes);
        $oComment->setTextHash(md5($sRes));
    }

    public function correctTopic($params){
        $oTopic = $params['oTopic'];
        $sRes = $this->makeAtUser($oTopic->getText(),'');
        $oTopic->setText($sRes);
        $oTopic->setTextHash(md5($sRes));
    }
}
?>
