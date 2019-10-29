<?php

// 最大公約数（ユークリッドの互除法）
function gcd($large, $small){
	while($small !== 0){
		$tmp_n = $small;
		$small = $large % $small;
		$large = $tmp_n;
	}
	return $large;
}
// 最小公倍数（gcd依存）
function lcm($m,$n){
	$large = max($m, $n);
	$small = min($m, $n);
	return ($m * $n) / gcd($large, $small);
}
// 素因数分解
function primefactors($n){
	// １は確実に1回
	$result = [1 => 1];
	// 偶数なら2を素因数に追加
	while ($n % 2 === 0) {
		$n = (int)floor($n/2);
		if (isset($result[2])) {
			$result[2] += 1;
		}else{
			$result[2] = 1;
		}
	}
	// ３以上の奇数について素因数と乗数を計算（割残し$nの平方根にあたる数まで確認すればOK）
	for ($i=3; $i*$i <= $n; $i+=2) { 
		while ($n % $i === 0) {
			$n = (int)floor($n/$i);
			if (isset($result[$i])) {
				$result[$i] += 1;
			}else{
				$result[$i] = 1;
			}
		}
	}
	// 3以上の割残しがある場合、その数は素数なので素因数として追加する（例：26の素因数13とか）
	if ($n > 2) {
		$result[$n] = 1;
	}
	return $result;
}

// $n以下の素数表作成（エラトステネスのふるい。nが大きいとメモリが足りないので注意！）
function sieve($n){
	// ここでメモリ足りなくなる
	$list = range(2, $n);
	$result = [1];
	for ($i=1; $i < $n; $i++) { 
		// echo implode(' ', $list) , PHP_EOL;
		$tmp_list = [];
		$target = array_shift($list);
		if (empty($target)) {
			break;
		}
		$result[] = $target;
		foreach ($list as $v) {
			if ($v % $target !== 0) {
				$tmp_list[] = $v;
			}
		}
		$list = $tmp_list;
	}
	return $result;
}
