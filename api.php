<?

//Cloodie Keystroke Dynamics

class CKD {
	private $dbconn;

	public function _query($q) {
		$result=pg_query($q) or die('Query failed: ' . pg_last_error());
		return $result;
	}


	function __construct($apiKey) {
		//TODO: connect
		$this->dbconn = pg_connect("host=localhost dbname=keystroke user=ecalot password=djh6xi")
	    or die('Could not connect: ' . pg_last_error());
	}
	public function createKeystroke($string) {
		return new Keystroke($string,$this);
	}

	//On dimensions of the form f or e (fuzzy_integer or exclusive) new entities may be created
	public function getEntity($dimension,$name) {
		$result=$this->_query("select id_entity from entity where id_dimension='$dimension' and name='$name'");
		if ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
			return $line['id_entity'];
		pg_free_result($result);
		$result=$this->_query("select nextval('seq'::regclass) as nv");
		$line = pg_fetch_array($result, null, PGSQL_ASSOC);
		$id=$line['nv'];
		$this->_query("insert into entity (id_dimension,id_entity,name) values ('$dimension','$id','$name')");
		pg_free_result($result);
		return $id;
	}
	public function listEntities($dimension) {
		$result=$this->_query("select id_entity,name from entity where id_dimension='$dimension'");
		$out=array();
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
			$out[]=$line;
		pg_free_result($result);
		return $out;
	}
	public function getEntities($dimension) { /* only when access is granted */
		$result=$this->_query("select id_entity,k,sigmasq,mu from entity join training using (id_entity) where id_dimension='$dimension'");
		$out=array();
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
			$out[]=$line;
		pg_free_result($result);
		return $out;
	}
	public function listDimensions() {
		$result=$this->_query("select id_dimension,name,form from dimension");
		$out=array();
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
			$out[]=$line;
		pg_free_result($result);
		return $out;
	}

}


class Keystroke {
	private $data;
	private $ckd;
	function __construct($string,$ckd) {
		//$this->data=$this->decode($string);
		$this->data=$string;
		$this->ckd=$ckd;
	}

	private function event($k){
		return ($k>>1).($k&1?'d':'u');
	}

/*
	public function show() {
		foreach ($this->data as $k=>$v) {
			$n=$v['n'];
			$mu=$v['mu']/$n;
			$sigmasq=$v['musq']/$n-$mu*$mu;

			$k2=$this->event($k&1023);
			$k1=$this->event($k>>10);

			printf("Event %s->%s: mu=%6.2f sigma=%5.2f n=%d\n",$k1,$k2,$mu,sqrt($sigmasq),$n);
		}
	}

	private function decode($string) {
		$encoding="0124689qwertyuiopsdfgASDFGHJKLZX357ahjklzxcvbnmQWERTYUIOPCVBNM #";
		$i=0;
		$out=array();
		$aux=$n=0;
		for ($i=0;$i<strlen($string);$i++) {
			$c=strpos($encoding,$string[$i]);
			$aux|=($c & 31 )<<(5*($n++));
			if ($c & 32) {
				$out[]=$aux;
				$aux=$n=0;
			}
		}
		$out2=array();
		$aux=0;
		$aux_arr=array();
		$n=0;
		if ($out) foreach ($out as $val) {
			switch(($n++)%4) {
			case 0:
				$aux+=$val;
				break;
			case 1:
				$aux_arr['n']=$val;
				break;
			case 2:
				$aux_arr['musq']=$val;
				break;
			case 3:
				$aux_arr['mu']=$val;
				$out2[$aux]=$aux_arr;
				$aux_arr=array();
				break;
			}
		}
		return $out2;
	}

*/

	public function train($entity) {
		$result=$this->ckd->_query("select * from train('$entity','{$this->data}')");
		if ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
			return $line;
		return null;
	}	

	public function guess($dimension) {
		$result=$this->ckd->_query("select sqrt(avg((t.mu-s.mu)*(t.mu-s.mu)*s.sigmasq)) as weight,sum(t.n) as trained_data_size,count(*) as trained_keys_count,sum(s.sigmasq+t.sigmasq) as total_variance from getStroke(
'{$this->data}'
) s join training t on s.k=t.k join dimension d on d.id_entity=t.id_entity
group by id_entity
 where id_dimension='$dimension' order by weight desc limit 10");
		return pg_fetch_all($result);
	}	
	public function check($entity) {
		$result=$this->ckd->_query("select sqrt(avg((t.mu-s.mu)*(t.mu-s.mu)*s.sigmasq)) as weight,sum(t.n) as trained_data_size,count(*) as trained_keys_count,sum(s.sigmasq+t.sigmasq) as total_variance from getStroke(
'{$this->data}'
) s join training t on s.k=t.k where id_entity='$entity'");
		if ($line = pg_fetch_array($result, null, PGSQL_ASSOC))
			return $line;
		return null;
	}
	

}
