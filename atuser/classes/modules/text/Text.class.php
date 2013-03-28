<?php

class PluginAtuser_ModuleText extends PluginAtuser_Inherit_ModuleText
{
	protected function makeCorrection($sText,$template,$aAssign=array()){
		$match = array();
		preg_match_all('/@\w+/u',$sText,$match);
		$repls = array();
		foreach($match as $vals){
			if(count($vals) != 0) {
				foreach ($vals as $val){
					$login = substr(trim($val),1);
					if($oUser = $this->User_GetUserByLogin($login)) {
						$repls[] = array('repl'=>$val,'ref'=>$oUser->getUserWebPath(),'login'=>$oUser->getLogin());
						if($template != ''){
							$params=array('oUser'=>$oUser);
							$params = array_merge($params,$aAssign);
							$sNotifyTitle = $this->Lang_Get('plugin.atuser.notify_title');
							$this->Notify_Send($oUser,$template,$sNotifyTitle,$params,'atuser');
						}
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
		$sResult = $this->makeCorrection($sResult,'');
		return $sResult;
	}
}

?>