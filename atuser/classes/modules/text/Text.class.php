<?php

class PluginAtuser_ModuleText extends PluginAtuser_Inherit_ModuleText
{
    protected function processHashTag($match){
        $word = $match[1];
        return '<a class="inline-tag" href="'.Router::GetPath('tag').$word.'">'.$word.'</a>';
    }

    protected function makeHashTag($sText){
        return preg_replace_callback("/#(\S+[^\s])/misu",array($this,"processHashTag"),$sText);
    }

    protected function makeAtUser($sText){
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
        return $sRes;
    }
    
    public function Parser($sText) {
        $sResult = parent::Parser($sText);
        $sResult = $this->makeAtUser($sResult);
        $sResult = $this->makeHashtag($sResult);
        return $sResult;
    }
}

?>