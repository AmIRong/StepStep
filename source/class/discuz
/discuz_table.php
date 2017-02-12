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
	protected function _init_extend() {
	}
	public function update($val, $data, $unbuffered = false, $low_priority = false) {
	    if(isset($val) && !empty($data) && is_array($data)) {
	        $this->checkpk();
	        $ret = DB::update($this->_table, $data, DB::field($this->_pk, $val), $unbuffered, $low_priority);
	        foreach((array)$val as $id) {
	            $this->update_cache($id, $data);
	        }
	        return $ret;
	    }
	    return !$unbuffered ? 0 : false;
	}
	public function update_cache($id, $data, $cache_ttl = null, $pre_cache_key = null) {
	    $ret = false;
	    if($this->_allowmem) {
	        if($pre_cache_key === null)	$pre_cache_key = $this->_pre_cache_key;
	        if($cache_ttl === null)	$cache_ttl = $this->_cache_ttl;
	        if(($_data = memory('get', $id, $pre_cache_key)) !== false) {
	            $ret = $this->store_cache($id, array_merge($_data, $data), $cache_ttl, $pre_cache_key);
	        }
	    }
	    return $ret;
	}
}

?>