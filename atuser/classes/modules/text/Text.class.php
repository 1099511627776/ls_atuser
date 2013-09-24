<?php

class PluginAtuser_ModuleText extends PluginAtuser_Inherit_ModuleText
{
    protected function processHashTag($match){
        $word = $match[1];
        return '<a class="inline-tag" href="'.Router::GetPath('tag').trim($word).'/">'.trim($word).'</a>';
    }

    protected function markAHash($match){
        return "<a".$match[1]."\"".$match[2]."&hash;".$match[3]."\"".$match[4].">";
    }

    protected function makeHashTag($sText){
        //mark special hashtags
        $sTmp = preg_replace_callback("/<a(.*href=)['\"](.*)#(.*)['\"](.*[^>])>/misuU",array($this,'markAHash'),$sText);
        ////////////////////////
        $sTmp = preg_replace_callback("/#([\p{L}\p{N}_-]+)/misu",array($this,"processHashTag"),$sTmp);
        //demark
        $sTmp = str_replace("&hash;","#",$sTmp);
        ////////
        return $sTmp;
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