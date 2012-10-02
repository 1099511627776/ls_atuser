<?php
class PluginAtuser_ModuleText extends PluginAtuser_Inherit_ModuleText {             
        
        public function Parser($sText) {
                $match = array();
                preg_match_all('/@\S+/u',$sText,$match);
                $repls = array();
                foreach($match as $val){               	
                	if(count($val) != 0) {
                		$login = substr($val[0],1);
                		if($oUser = $this->User_GetUserByLogin($login)) {
                			$repls[] = array('repl'=>$val[0],'ref'=>$oUser->getUserWebPath(),'login'=>$oUser->getLogin());
                		}
                	}
                }
                $sResult = parent::Parser($sText);
                $sRes = $sResult;
                foreach($repls as $repl) {
                	$sRes = str_replace($repl['repl'],'<a href="'.$repl['ref'].'" class="ls-user">'.$repl['login'].'</a>',$sRes);
                }
                //$sResult = $sResult.' [from plugin Test]';
                return $sRes;
        }
}
?>