<?php

/**
 * 
 */
class SimpleQueue
{
	private $data = [];
	private $idx = 0;

	function enq($d){
		$this->data[] = $d;
	}

	function deq(){
		$this->idx += 1;
		return $this->data[$this->idx-1];
	}

	function isEmpty(){
		return (count($this->data) === $this->idx);
	}
}