<?php
/**
 * 動作確認：abc137_4
 */
$input = explode(' ', trim(fgets(STDIN)));

$n = (int)$input[0];
$m = (int)$input[1];

$tasks = [];
for ($i=0; $i < $n; $i++) { 
	$input = explode(' ', trim(fgets(STDIN)));
	$a = (int)$input[0];
	$b = (int)$input[1];
	if ($a > $m) {
		continue;
	}
	if (isset($tasks[$a])) {
		$tasks[$a][] = $b;
	}else{
		$tasks[$a] = [$b];
	}
}
ksort($tasks);
$pq = new PriorityQueue();

$sum = 0;
for ($i=0; $i < $m; $i++) { 
	$rem_day = $i+1;
	if (isset($tasks[$rem_day])) {
		foreach ($tasks[$rem_day] as $t) {
			$pq->insert($t);
		}
	}
	$max = $pq->pop();
	if ($max !== NULL) {
		$sum += $max;
	}
}

echo $sum;

/**
 * priority queue
 * 優先度設定を変更するときは、compareメソッドを変更する
 */
class PriorityQueue
{
	// 1-idxedな配列
	private $data = [];

	// データを追加する
	public function insert($d){
		$size = count($this->data);
		$this->increaseKey($size+1, $d);
	}

	// $this->data[$idx]に値$dを追加し、priorityを満たす位置まで上昇させる
	private function increaseKey($idx, $d){
		$this->data[$idx] = $d;
		$parent_idx = (int)floor($idx/2);
		while ($idx > 1 && $this->compare($this->data[$idx], $this->data[$parent_idx])) {
			$tmp = $this->data[$idx];
			$this->data[$idx] = $this->data[$parent_idx];
			$this->data[$parent_idx] = $tmp;
			$idx = $parent_idx;
			$parent_idx = (int)floor($idx/2);
		}
	}

	// priorityが最大のデータを取り出す
	public function pop(){
		$result = null;
		$size = count($this->data);
		if ($size > 0) {
			$result = $this->data[1];
			$this->data[1] = $this->data[$size];
			unset($this->data[$size]);
			if ($size !== 1) {
				$this->heapify(1);
			}
		}
		return $result;
	}

	// $this->data[$idx]を、priorityを満たす位置まで降下させる
	private function heapify($idx){
		$size = count($this->data);
		$l_idx = 2 * $idx;
		$r_idx = $l_idx + 1;
		$heighest_priority_idx = $idx;
		if ($l_idx <= $size && $this->compare($this->data[$l_idx], $this->data[$idx])) {
			$heighest_priority_idx = $l_idx;
		}
		if ($r_idx <= $size && $this->compare($this->data[$r_idx], $this->data[$heighest_priority_idx])) {
			$heighest_priority_idx = $r_idx;
		}

		if ($heighest_priority_idx !== $idx) {
			$tmp = $this->data[$heighest_priority_idx];
			$this->data[$heighest_priority_idx] = $this->data[$idx];
			$this->data[$idx] = $tmp;
			$this->heapify($heighest_priority_idx);
		}
	}

	// targetのpriorityがfromより真に高い場合にtrueを返す
	private function compare($target, $from){
		return ($target > $from);
	}

}