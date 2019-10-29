<?php

$cc = new ModComb();
echo $cc->comb(10, 3);


/**
 * フェルマーの小定理を利用した組み合わせ計算クラス
 * https://qiita.com/ofutonfuton/items/92b1a6f4a7775f00b6ae
 * $modで組み合わせ爆発を抑制している
 */
class ModComb
{
	// 事前計算テーブル
	private $f_table  = [1]; // $n! % $mod
	private $if_table = [1]; // $k!の($mod-2)乗 % $mod
	// 十分大きな素数にする
	private $mod = 0;

	public function __construct($max = 10000, $mod = 1000000007){
		$this->mod = $mod;
		// 事前計算
		$this->calcFactorial($max);
	}

	// 組み合わせ計算（nCm）
	public function comb($n, $m){
		if ($n === 0 && $m === 0) {
			return 1;
		}
		if ($n < $m || $n < 0) {
			return 0;
		}
		if (!isset($this->f_table[$n])) {
			$this->calcFactorial($n);
		}
		return ($this->if_table[$n - $m] * $this->if_table[$m] % $this->mod) * $this->f_table[$n] % $this->mod;
	}

	// 順列計算（nPm）
	public function perm($n, $m){
		if ($n < $m) {
			return 0;
		}
		if (!isset($this->f_table[$n])) {
			$this->calcFactorial($n);
		}
		return ($this->if_table[$n - $m] % $this->mod) * $this->f_table[$n] % $this->mod;
	}

	// 重複組合せ計算（nHm）
	public function dupc($n, $m){
		if ($n === 0 && $m === 0) {
			return 1;
		}
		if ($n < 0 || $m === 0) {
			return 0;
		}
		if (!isset($this->f_table[$n + $m - 1])) {
			$this->calcFactorial($n + $m - 1);
		}
		return ($this->if_table[$n - 1] * $this->if_table[$m] % $this->mod) * $this->f_table[$n + $m - 1] % $this->mod;
	}

	// 既存の事前計算結果テーブルの末尾から$maxまでの階乗までtableを拡張する。
	private function calcFactorial($max){
		$already = count($this->f_table);
		for($i = $already; $i <= $max; $i++){
			$this->f_table[$i] = $this->f_table[$i-1] * $i % $this->mod;
			$this->if_table[$i] = $this->if_table[$i-1] * $this->binPow($i, $this->mod-2) % $this->mod;
		}
	}

	// $xの$n乗を2進数で高速に計算する
	// これを単体で利用する場合、コンストラクタの$maxは低めで良い
	public function binPow($x, $n){
		$ans = 1;
		$bin = decbin($n);
		$cur_pos = strlen($bin) -1;
		while ($cur_pos >= 0) {
			if ($bin[$cur_pos] == '1') {
				$ans = ($ans * $x) % $this->mod;
			}
			$x = ($x * $x) % $this->mod;
			--$cur_pos;
		}
		return $ans;
	}
}