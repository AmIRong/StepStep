<?php



if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class discuz_session {

	public $sid = null;
	public $var;
	public $isnew = false;
	private $newguest = array('sid' => 0, 'ip1' => 0, 'ip2' => 0, 'ip3' => 0, 'ip4' => 0,
		'uid' => 0, 'username' => '', 'groupid' => 7, 'invisible' => 0, 'action' => 0,
		'lastactivity' => 0, 'fid' => 0, 'tid' => 0, 'lastolupdate' => 0);

	private $old =  array('sid' =>  '', 'ip' =>  '', 'uid' =>  0);

	private $table;

	public function __construct($sid = '', $ip = '', $uid = 0) {
		$this->old = array('sid' =>  $sid, 'ip' =>  $ip, 'uid' =>  $uid);
		$this->var = $this->newguest;

		$this->table = C::t('common_session');

		if(!empty($ip)) {
			$this->init($sid, $ip, $uid);
		}
	}



	public function init($sid, $ip, $uid) {
		$this->old = array('sid' =>  $sid, 'ip' =>  $ip, 'uid' =>  $uid);
		$session = array();
		if($sid) {
			$session = $this->table->fetch($sid, $ip, $uid);
		}

		if(empty($session) || $session['uid'] != $uid) {
			$session = $this->create($ip, $uid);
		}

		$this->var = $session;
		$this->sid = $session['sid'];
	}

	public function create($ip, $uid) {
	
	    $this->isnew = true;
	    $this->var = $this->newguest;
	    $this->set('sid', random(6));
	    $this->set('uid', $uid);
	    $this->set('ip', $ip);
	    $uid && $this->set('invisible', getuserprofile('invisible'));
	    $this->set('lastactivity', time());
	    $this->sid = $this->var['sid'];
	
	    return $this->var;
	}
	public function set($key, $value) {
	    if(isset($this->newguest[$key])) {
	        $this->var[$key] = $value;
	    } elseif ($key == 'ip') {
	        $ips = explode('.', $value);
	        $this->set('ip1', $ips[0]);
	        $this->set('ip2', $ips[1]);
	        $this->set('ip3', $ips[2]);
	        $this->set('ip4', $ips[3]);
	    }
	}
}

?>