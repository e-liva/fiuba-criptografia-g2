<?php

define('END_OF_STREAM',-1);

class keylogger {

	public function __construct($string) {
		$this->string=$string;
		$this->len=strlen($this->string);
	}

	public function reset() {
		$this->start=0;
	}

	public function get() {
		if ($this->start>=$this->len) return END_OF_STREAM;
		return $this->string[$this->start++];
	}

	public function getn($n) {
		$start=$this->start;
		$this->start+=$n;
		return substr($this->string,$start,$n);
	}

	public function getint() {
		$encoding="0124689qwertyuiopsdfgASDFGHJKLZX35 ahjklzxcvbnmQWERTYUIOPCVBNM7#";
		$i=0;
		$aux=$n=0;
		while ($n<10) { //just in case of failure (streams with numbers bigger than 50 bits are not allowed)
			$c=strpos($encoding,$this->get());
			if ($c==END_OF_STREAM) return END_OF_STREAM;
			$aux|=($c & 31 )<<(5*($n++));
			if ($c & 32) return $aux;
		}
		return END_OF_STREAM;
	}

	public function toArray() {
		$this->reset();
		$l=$this->getint();
		$agent=$this->getn($l);

		$start_time=0;

		$aux=array();
		do {
			$v=$this->getint();
			if ($v!=END_OF_STREAM) {
				$start_time+=$v;
				$key=$this->getint();
				$aux[]=array($start_time,$key&1?'d':'u',($key>>1)&255,$key>>9);
			}
		} while ($v!=END_OF_STREAM);
		return array('agent'=>$agent,'stroke'=>$aux);
	}

	public function toJSON() {
		return json_encode($this->toArray());
	}
}



$test='y Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:34.0) Gecko/20100101 Firefox/34.0Kp86yhqhwa9hw Xh0 Zh0 Jhp XhzHh0 ZhW1 0 ejW0 F5thzwjw 1 zrhp 0 05uj0 yjp 4hwa2h';
$test='y Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:34.0) Gecko/20100101 Firefox/34.0LJ8Dyhsh0 php Xhp5ZhpaGh0aFhz4hp 2h0a115wOqhp 9hF50150aXhp ZhFaJhF Hhzqh0a9h0 XhF Zhwk4E0j2Ew5115pj1Ez015wh0E0cXS5F ZS5paAS5wagS5w5XS5p ZS5';

$kl=new keylogger($test);

print_r($kl->toJSON());





