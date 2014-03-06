<?

$charset=array("M","m","m·","M,","m,","mM","Mm","M.");

if(isset($_GET['a']) && $_GET['a']=='e'){
	if(! isset($_POST['oculto']) )
	die("'hide' message");
	$oculto=$_POST['oculto'];
	
	if(! isset($_POST['txt']) )
	die("'txt' text * 3");
	$transporte=strtolower ($_POST['txt']);
	echo "<strong>Black_Message:</strong> ".$oculto."<br>";
	echo "<strong>Normal_Text:</strong> ".$transporte."<br>";

	$codificado="";
	$mapa="";

	//Limpiar transporte
	$transportel     = preg_replace('/[^[a-z]| ]/', '', $transporte);
	$transporte     = preg_replace('/[·,.]/', '', $transporte);
	 
	
	for($i=0;$i<strlen($oculto);$i++){
		$mapa .= str_pad(substr(decoct(ord($oculto[$i])),0,3), 3, "0", STR_PAD_LEFT);
	}

	//echo $mapa ."<br>";
	
	if(strlen($oculto)*3 <= strlen($transportel)){
		for($i=0, $j=0;$i<strlen($transporte);$i++){
			if(preg_match("/[a-z]/", $transporte[$i]) && $j<strlen($oculto)*3){
				$codificado .= transformar($transporte[$i],$charset[$mapa[$j]]);
				$j++;
			}else{
				$codificado .= $transporte[$i];
			}
		}
		echo "<strong>BlackMessage Text Encrypted:</strong> ".$codificado;
	}else{
		echo "Need ". ((strlen($oculto)*3)-strlen($transportel)) ." characters for a BlackMessage";
	}
	
}else if(isset($_GET['a']) && $_GET['a']=='d'){
	if(! isset($_POST['txt']) )
		die("'txt' BlackMessage");
	$codificado=$_POST['txt'];
	//echo $codificado."<br>";
	$cmp="";
	for($i=0;$i<strlen($codificado);$i++){
		$actual=$codificado[$i];
		$siguiente=$codificado[$i+1];
		$codigo=$actual.$siguiente;
		//echo $codigo."<br>";
		if($actual!=" "){
			if(strtoupper($actual) == strtoupper($siguiente)){
				$patron = array("/[a-z]/","/[A-Z]/");
				$sustitucion = array("m","M");
				$encontrado = preg_replace($patron, $sustitucion, $codigo);
				//echo array_search($encontrado, $charset)."=".$codigo."<br>";
				$cmp .= array_search($encontrado, $charset);
				$i++;
			}else{
				if($siguiente=='·' || $siguiente==',' || $siguiente=="," || $siguiente=="."){
					$patron = array("/[a-z]\·/","/[A-Z]\,/","/[a-z],/","/[A-Z]\./");
					$sustitucion = array("m·","M,","m,","M.");
					$encontrado = preg_replace($patron, $sustitucion, $codigo);
					//echo array_search($encontrado, $charset)."=".$codigo."<br>";
					$cmp .= array_search($encontrado, $charset);
					$i++;
				}else{
					$patron = array("/[a-z]/","/[A-Z]/");
					$sustitucion = array("m","M");
					$encontrado = preg_replace($patron, $sustitucion, $actual);
					//echo array_search($encontrado, $charset)."=".$actual."<br>";
					$cmp .= array_search($encontrado, $charset);
				}
			}
		}
	}
	echo "<strong>BlackMessage Is:</strong> ";
	for($i=0; $i<strlen($cmp);$i+=3){
		echo chr(octdec($cmp[$i].$cmp[$i+1].$cmp[$i+2]));
	}
}else{
?>
<form action="?a=e" method="post">
BlackMessage:<br/><textarea name="oculto" cols="30" rows="6">BlackMessage </textarea><br>
Text:<br/><textarea name="txt" cols="30" rows="6">This is a simple test to hide text: BlankMessage</textarea><br>
<input type="submit" value="BlackMessage"/>
</form>

<form action="?a=d" method="post">
Decrypt BlackMessage:<br/><textarea name="txt" cols="30" rows="6">tHi·s iIs, a s,imp,L,e tTE,st tTo h,iIdEe T,eXxT,: bl,ank,M.es,sSAg,E</textarea><br>
<input type="submit" value="Decrypt"/>
</form>
<?
}

function transformar($letra, $codigo){
	$final="";
	//echo $codigo."<br>";
	for($i=0;$i<strlen($codigo);$i++){
		$final .= ($codigo[$i]=='M')?strtoupper($letra):(($codigo[$i]=='m')?$letra:$codigo[$i]);
	}
	return $final;
}
?>
