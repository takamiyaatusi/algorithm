<?php

/**
 * 
 */
class SimpleStack 
{
	protected $idx = 0;
	protected $data = [];

	public function push($data){
		$this->idx += 1;
		$this->data[$this->idx] = $data;
	}

	public function pop(){
		$d = null;
		if (!$this->isEmpty()) {
			$d = $this->data[$this->idx];
			$this->idx -= 1;
		}
		return $d;
	}

	public function isEmpty(){
		return ($this->idx === 0);
	}
}