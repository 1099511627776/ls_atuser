<?php

class PluginAtuser_HookAtuser extends Hook {

    /*
     * ����������� ������� �� ����
	*/

	protected function makeCorrection($sText,$template,$aAssign=array()){
		$match = array();
		preg_match_all('/@\S+/u',$sText,$match);
		$repls = array();
		foreach($match as $val){               	
			if(count($val) != 0) {
				$login = substr($val[0],1);
				if($oUser = $this->User_GetUserByLogin($login)) {
					$repls[] = array('repl'=>$val[0],'ref'=>$oUser->getUserWebPath(),'login'=>$oUser->getLogin());
					$params=array('oUser'=>$oUser);
					$params = array_merge($params,$aAssign);
					$this->Notify_Send($oUser,$template,'Mention Notify',$params,'atuser');
				}
			}
		}
		$sRes = $sText;
		foreach($repls as $repl) {
			$sRes = str_replace($repl['repl'],'<a href="'.$repl['ref'].'" class="ls-user">'.$repl['login'].'</a>',$sRes);
		}
		return $sRes;
	}
    public function RegisterHook() {
        $this->AddHook('comment_add_before', 'correctComment',__CLASS__);
        $this->AddHook('topic_add_before', 'correctTopic',__CLASS__);
    }
	
	public function correctComment($params){
		$oComment = $params['oCommentNew'];
		$sRes = $this->makeCorrection($oComment->getText(),
			'notify.comment_mention.tpl',
			array('oComment'=>$oComment)
		);
		$oComment->setText($sRes);
		$oComment->setTextHash(md5($sRes));
	}

	public function correctTopic($params){
		$oTopic = $params['oTopic'];
		$sRes = $this->makeCorrection($oTopic->getText(),'');
		$oTopic->setText($sRes);
		$oTopic->setTextHash(md5($sRes));
	}
}
?>