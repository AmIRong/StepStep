<?php



if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


class discuz_table extends discuz_base
{

	public $data = array();

	public $methods = array();

	protected $_table;
	protected $_pk;
	protected $_pre_cache_key;
	protected $_cache_ttl;
	protected $_allowmem;

	public function __construct($para = array()) {
		if(!empty($para)) {
			$this->_table = $para['table'];
			$this->_pk = $para['pk'];
		}
		if(isset($this->_pre_cache_key) && (($ttl = getglobal('setting/memory/'.$this->_table)) !== null || ($ttl = $this->_cache_ttl) !== null) && memory('check')) {
			$this->_cache_ttl = $ttl;
			$this->_allowmem = true;
		}
		$this->_init_extend();
		parent::__construct();
	}
}

?>