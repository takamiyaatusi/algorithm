<?php

/**
 * DINIC法による最大流アルゴリズム
 * 計算量はV*V*E。辺の数が相対的に少ない疎グラフで高速。みつグラフの場合はエドモンドカープ！
 * 未完成！
 */
class DinicMaxFlow
{
	private $v_num;
	private $inf;
	private $q; // queue
	private $graph = [];
	private $level = [];
	// private $flow = 0;

	// node数を引数にとる
	function __construct($v_num = 0, $inf = 1000000007)
	{
		$this->v_num = $v_num;
		$this->inf   = $inf;
		$this->q = new SimpleQueue();
		for ($i=1; $i <= $this->v_num ; $i++) { 
			$this->graph[$i] = [];
		}
	}

	// $from から $to への容量$capacityの辺を追加する
	// 逆辺も追加する
	public function addEdge($from, $to, $capacity){
		$this->graph[$from][] = [
			'to' => $to,
			'cap' => $capacity,
			'rev' => count($this->graph[$from])
		];
		$this->graph[$to][] = [
			'to' => $from,
			'cap' => 0,
			'rev' => count($this->graph[$from])-1
		];
	}

	// 始点$sからの最短距離を幅優先探索。結果はlevelに記録する
	// capacity > 0 && level[$to] < 0（未到達）なパスだけを利用する
	private function bfs($s){
		for ($i=1; $i <= $this->v_num ; $i++) { 
			$this->level[$i] = -1;
		}
		$this->level[$s] = 0;
		$this->q->init();
		$this->q->enq($s);
		while (!$this->q->isEmpty()) {
			$v = $this->q->deq();
			foreach ($this->graph[$v] as $edge) {
				if ($edge['cap'] > 0 && $this->level[$edge['to']] < 0) {
					$this->level[$edge['to']] = $this->level[$v]+1;
					$this->q->enq($edge['to']);
				}
			}
		}
	}

	// 増加道を深さ優先探索
	private function dfs($s, $t, $fl){
		if ($s === $t) {
			return $fl;
		}
	}

	// $sから$tまでの最大流を計算ぢて返す
	public function getMaxFlow($s, $t){
		$flow = 0;
		while (true) {
			$this->bfs($s);
			// capacityを使い切って、bfsで到達不能とわかったら終了
			if ($this->level($t) < 0) {
				break;
			}
			$plus = $this->dfs($s, $t, $this->inf);
			while ($plus > 0) {
				$flow += $plus;
				$plus = $this->dfs($s, $t, $this->inf);
			}
		}
		return $flow;
	}
}




class SimpleQueue
{
	private $data = [];
	private $idx = 0;

	// 全部空にする
	function init(){
		$this->data = [];
		$this->idx = 0;
	}

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

	function getHead(){
		return $this->data[$this->idx];
	}
}