<?
include('api.php');

switch ($_GET['op']) {
	case 'train':
		$api=new CKD('apikey');
		$stroke=$api->createKeystroke($_POST['ks']);
		$id_entity=$api->getEntity($_POST['pk'],$_POST['value']);

		$stroke->train($id_entity);
	break;
	case 'src':
		$api=new CKD('apikey');
		$entities=$api->listEntities($_GET['pk']);
		$out=array();
		if ($entities) foreach ($entities as $ent) {
			$out[]=$ent['name'];
		}
		die(json_encode($out));

	case 'cache':
		$api=new CKD('apikey');
		$data=$api->getEntities($_POST['pk']);
		$out=array();
		if ($data) foreach ($data as $line) {
			@$out[$line['id_entity']][$line['k']]=array(round($line['mu'],1),round($line['sigmasq'],1));
		}
		die(json_encode($out));
	case 'experiment':
		$today=date('Y-m-d');
		$date = DateTime::createFromFormat('d/m/Y', $_POST['birth']);
		$out=explode(',',$_POST['data']);
		$r=array('today'=>$today,'birth'=>$date->format('Y-m-d'),'data'=>$out,'gender'=>$_POST['gender'],'dexterity'=>$_POST['dexterity'],'name'=>$_POST['name']);
		$fh = fopen('experiment.json', 'at');
		fwrite($fh, json_encode($r)."\n");
		fclose($fh);
		die();
	case 'savemail':
		$fh = fopen('mails.txt', 'at');
		fwrite($fh,sprintf("%s <%s>\n",$_POST['name'],$_POST['mail']));
		fclose($fh);
		die();
}




