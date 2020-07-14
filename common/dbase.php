<?php

class Dbase {

	var $db_format;

	var $dbLink;

	var $USER_NAME = "";

	var $USER_PWD = "";

	var $DATABASE;

	var $MYSQL_HOST = ""; 

	var $db_selected;

	var $num;

	var $db_prefix="";

	//var $db_name = "";



	

		







	function Dbase($link=""){

	

	include'db.php';

	

	

	

		

		if(!empty($link)){

			$this->dbLink = $link;

		}

		else{

		

			$this->USER_NAME = $username_dbconn;

			

			$this->USER_PWD = $password_dbconn;

			

			if (!isset($db_name)) {

				$this->DATABASE = $rating_dbname;

			} else {

				$this->DATABASE = $db_nsme;

			}

			$this->MYSQL_HOST = $hostname_dbconn;

	

			if ($this->connectHost()) {

				if (!($this->connectDB())) {

					exit("There is some problem with Database Connections, please contact Development Team! <br>".$this->DATABASE."<br>".mysql_error());

				}

			} else {

				exit("There is some problem with Database Connections, please contact Development Team! <br>".$this->MYSQL_HOST."<br>".mysql_error());

			}

		}

	} //  end of Admin



	function connectHost() {

		$this->dbLink = mysql_connect($this->MYSQL_HOST, $this->USER_NAME, $this->USER_PWD, TRUE);

		if (!$this->dbLink) {

			return false;

		}

		return true;

	}



	function get_db_name() {

		return $this->DATABASE;

	}



	function set_db_name($db_name) {

		$this->DATABASE = $db_name;

	}

	

	function connectDB() {

		$this->db_selected = mysql_select_db($this->DATABASE,$this->dbLink);

		if (!$this->db_selected) {

			return false;

		}

		//echo $this->dbLink."<br>".$this->DATABASE; 

		return true;

	}



	function escape($str="")

	{

		return(mysql_real_escape_string($str)); 

	}

	//function insert(array $data, string table)

	function insert($data, $table) {

		if(!is_array($data))

			return(0);

		

		foreach($data as $key => $name) {

				$attribs[]	=	$key;

				$values[]	=	"'" . $this->escape(stripslashes($name)) . "'";

		}	

		$attribs=implode(",", $attribs);

		$values = implode(",", $values);

		$query = "insert into $table ($attribs) values ($values)";

	    $this->sql = $query;

		//$this->log();

		

		if (mysql_query($query, $this->dbLink)) {

			return mysql_insert_id();

		} else {

			$this->error_log();

			return false;

		}

	}



	function insert_ignoreDuplicates($data, $table) {

		if(!is_array($data))

			return(0);

		

		foreach($data as $key => $name) {

			$attribs[]	=	$key;

			$values[]	=	"'" . $this->escape(stripslashes($name)) . "'";

		}	

		$attribs=implode(",", $attribs);

		$values = implode(",", $values);

		$query = "insert into $table ($attribs) values ($values)";

		$this->sql = $query;

		//$this->log();

		@mysql_query($query, $this->dbLink);

	}



	function exec_query($query) {

		$this->sql = $query;

		if (mysql_query($query, $this->dbLink)) {

			return true;

		} else {

			return false;

		}

		

	

	}

	function execute_query($query) {

		$this->sql = $query;

		if ($r = mysql_query($query, $this->dbLink)) {

			return $r;

		} else {

			return false;

		}

		

	

	}

	function selectIndex($retField, $table, $index, $value) {

		$q = "select $retField as RET from $table Where $index=$value";

		$r = mysql_query($q, $this->dbLink);

		$this->sql = $q;

		if (!($r)) {

			$this->error_log();

		}

		$row=mysql_fetch_object($r);

		if (mysql_num_rows($r)>0) {

			return $row->RET;

		} else {

			return false;

		}

	}

	

	

	function select($retField, $table, $where="", $groupby="", $orderby="", $limit="") {

		$fields = implode(",", $retField);

		if ($where!="") {

			 $q = "select $fields from $table WHERE $where";

		} else {

			$q = "select $fields from $table";

		}

		if ($groupby!="") {

			$q .= " GROUP BY $groupby";

		}

		if ($orderby!="") {

			$q .= " ORDER BY $orderby";

		}

		if ($limit!="") {

			$q .= " LIMIT $limit";

		}

		//echo "$q";exit;

		$this->sql = $q;

		//$this->log();

		$r = mysql_query($q, $this->dbLink) or die(mysql_error());

		$num=mysql_num_rows($r);

		if (!($r)) {

			$this->error_log();

		}

		$this->num=mysql_num_rows($r);

		$i=1;

		while ($row=mysql_fetch_object($r)) {

			$cont[$i] = $row;

			$i++;

		}

		if (mysql_num_rows($r)>0) {

			

		//	echo print_r($cont);

		//	exit;

			

			return $cont;

		}

		

	}

	



	function countfields($retField, $table, $where="") {

		if(is_array($retField)){

			$fields = implode(",", $retField);

		}else{

			$fields=$retField;

		}

		if ($where!="") {

			$q = "select $fields from $table WHERE $where";

			$this->sql = $q;

			//$this->log();

			$r = mysql_query($q, $this->dbLink);

			return mysql_num_rows($r);

		}

		if ($where=="") {

			 $q = "select $fields from $table ";

			$this->sql = $q;

			//$this->log();

			$r = mysql_query($q, $this->dbLink);

			 $count	=	mysql_num_rows($r);

			return $count;

		}

	}





	function selectfeilds($retField, $table, $where="") {

		if(is_array($retField)){

			$fields = implode(",", $retField);

		}else{

			$fields=$retField;

		}

		if ($where!="") {

			$q = "select $fields from $table WHERE $where";



		$this->sql = $q;

		//$this->log();

		$r = mysql_query($q, $this->dbLink);

		if (!($r)) {

			$this->error_log();

		}

		$row=mysql_fetch_array($r);

		return $row;

		}

	}

	//** function select (array $retField, string $table, string $where)

	function selectAll($table, $where="") {

		$q="SHOW COLUMNS FROM $table";

		$r = mysql_query($q, $this->dbLink);

		while ($res=mysql_fetch_array($r)) { 

			//echo $res[1]."<br>"; 

			if (($res[1]=="timestamp14") || ($res[1]=="datetime")) {

				$retField[]="DATE_FORMAT($res[0], '%d %b %Y at %H:%i:%s') AS $res[0]";

			} else {

				$retField[]=$res[0];

			}

		}

		

		$fields = implode(",", $retField);

		$q = "select $fields from $table $where";

		$this->sql = $q;

		//$this->log();

		$r = mysql_query($q, $this->dbLink);

		$num=mysql_num_rows($r);

		$i=1;

		while ($row=mysql_fetch_object($r)) {

			$cont[$i] = $row;

			$i++;

		}

		if (mysql_num_rows($r)>0) {

			return $cont;

		}

	}



	function selectSRow($retField, $table, $where="", $groupby="", $orderby="", $limit="") {

		$fields = implode(",", $retField);

		if ($where!="") {

			$q = "select $fields from $table WHERE $where";

		} else {

			$q = "select $fields from $table";

		}

		if ($groupby!="") {

			$q .= " GROUP BY $groupby";

		}

		if ($orderby!="") {

			$q .= " ORDER BY $orderby";

		}

		if ($limit!="") {

			$q .= " LIMIT $limit";

		}

	 	 $this->sql = $q;

		

		//$this->log();

		//echo $this->DATABASE; exit;

		$r = mysql_query($q, $this->dbLink);

		$num=mysql_num_rows($r);

		if (!($r)) {

			$this->error_log();

		}

		$num=mysql_num_rows($r);

		$i=1;

		$cont=array();	

		$row=mysql_fetch_array($r); 

		$cont = $row;

		$i++;

		return $cont;

	}



	function lastID() {

		return mysql_insert_id();

	}



	function selectIfExist($retField, $table, $where, $groupby="", $orderby="", $limit="") {

		$fields = implode(",", $retField);

		if ($where!="") {

			$q = "select $fields from $table WHERE $where";

		} else {

			$q = "select $fields from $table";

		}

		if ($groupby!="") {

			$q .= " GROUP BY $groupby";

		}

		if ($orderby!="") {

			$q .= " ORDER BY $orderby";

		}

		if ($limit!="") {

			$q .= " LIMIT $limit";

		}

		//$q = "select $fields from $table $where";

		$this->sql = $q;

		//$this->log();

		$r = mysql_query($q, $this->dbLink);

		if (!($r)) {

			$this->error_log();

		}

		$num=mysql_num_rows($r);

		//echo "query $q result = ".$num."<br><br><br><br>";

		if ($num!=0) {

			return true;

		}

		return false;

	}



	function is_url( $url )	{

		 if ( !( $parts = @parse_url( $url ) ) )

			  return false;

		 else {

		 if ( $parts[scheme] != "http" && $parts[scheme] != "https" && $parts[scheme] != "ftp" && $parts[scheme] != "gopher" )

			  return false;

		 else if ( !eregi( "^[0-9a-z]([-.]?[0-9a-z])*\.[a-z]{2,3}$", $parts[host], $regs ) )

			  return false;

		 else if ( !eregi( "^([0-9a-z-]|[\_])*$", $parts[user], $regs ) )

			  return false;

		 else if ( !eregi( "^([0-9a-z-]|[\_])*$", $parts[pass], $regs ) )

			  return false;

		 else if ( !eregi( "^[0-9a-z/_\.@~\-]*$", $parts[path], $regs ) )

			  return false;

		 else if ( !eregi( "^[0-9a-z?&=#\,]*$", $parts[query], $regs ) )

			  return false;

		 }

		 return true;

	}

	

	function lib_getmicrotime() { 

		 list($usec, $sec) = explode(" ",microtime()); 

		 return ((float)$usec + (float)$sec); 

  	}



	function log() {

		/*$fp = fopen("sql.log", "a");

		if(flock($fp, LOCK_EX))

		{

			$sql = str_replace("\n", " ", $this->sql);

			fputs($fp, date("d-m-Y h:i:s")." --> $sql\n");

			flock($fp, LOCK_UN);

		}

		fclose($fp);*/

	}

	

	function error_log() {

		$fp = fopen("sql_error.log", "a");

		if(flock($fp, LOCK_EX))

		{

			$sql = str_replace("\n", " ", $this->sql);

			fputs($fp, date("d-m-Y h:i:s")." --> $sql\n");

			flock($fp, LOCK_UN);

		}

		fclose($fp);

		

		$strHTML = "<HTML><HEAD><TITLE>DEBUG CONSOLE</TITLE></HEAD><BODY>";

		$strHTML .= "<div id='mysql_error_div'><table width='70%' align='center' border='0' cellspacing='0' cellpadding='0'>";

		$strHTML .="<tr><td width='1%' align='center' bordercolor='#000000' bgcolor='#FF0000'>&nbsp;</td>";

		$strHTML .="<td width='98%' align='center' bordercolor='#000000' bgcolor='#FF0000'><font color=#FFFFFF face='verdana' size='+1'>DEBUG CONSOLE</font> </td>";

		$strHTML .="<td width='1%' align='center' bordercolor='#000000' bgcolor='#FF0000'>&nbsp;</td></tr>";

		$strHTML .="<tr><td bgcolor='#FF0000'>&nbsp;</td><td>&nbsp;</td><td bgcolor='#FF0000'>&nbsp;</td></tr>";

		$strHTML .="<tr><td bgcolor='#FF0000'>&nbsp;</td><td style='padding-left:10px'><strong>Query:</strong></td><td bgcolor='#FF0000'>&nbsp;</td></tr>";

		$strHTML .="<tr><td bgcolor='#FF0000'>&nbsp;</td><td style='padding-left:20px'>$sql</td><td bgcolor='#FF0000'>&nbsp;</td></tr>";

		$strHTML .="<tr><td bgcolor='#FF0000'>&nbsp;</td><td>&nbsp;</td><td bgcolor='#FF0000'>&nbsp;</td></tr>";

		$strHTML .="<tr><td bgcolor='#FF0000'>&nbsp;</td><td style='padding-left:10px'><strong>Mysql Response:</strong></td><td bgcolor='#FF0000'>&nbsp;</td></tr>";

		$strHTML .="<tr><td bgcolor='#FF0000'>&nbsp;</td><td style='padding-left:20px'>".mysql_error()."------".$_SERVER['PHP_SELF']."</td><td bgcolor='#FF0000'>&nbsp;</td></tr>";

		$strHTML .="<tr><td bgcolor='#FF0000'>&nbsp;</td><td>&nbsp;</td><td bgcolor='#FF0000'>&nbsp;</td></tr>";

		$strHTML .="<tr><td colspan='3' bgcolor='#FF0000' height='2'></td></tr></table>";

		$strHTML .= "</div></BODY></HTML>";



		echo $strHTML;

	 }



	 function update($table="", $key="",$val="", $arr=array()) {

		if(!is_array($arr))

			return(0);

		

		$sql = array();

		while(list($k,$v) = each($arr))

		{

			$sql[] = "$k='" . $this->escape(stripslashes($v)) . "'";

		}

		

		$query = "UPDATE $table SET " . implode(", ", $sql) . " WHERE $key='$val'";

		$this->sql = $query;

		//$this->log();

		return mysql_query($query, $this->dbLink);

	 }



	 function update2($table="", $key="",$val="") {

		$query = "UPDATE $table SET ".$key." WHERE ".$val."";

		$this->sql = $query;

		//$this->log();

		return mysql_query($query, $this->dbLink);

	 }





	 function updateCondition($arr=array(), $table="",$cond="" ) {



		if(!is_array($arr))

			return(0);

		

		$sql = array();

		while(list($k,$v) = each($arr))

		{

			$sql[] = "$k='" . $this->escape(stripslashes($v)) . "'";

		}

		

		 $query = "UPDATE $table SET " . implode(", ", $sql) . " WHERE $cond";

		//$this->tz = $this->lib_getmicrotime();

	     $this->sql = $query;

//echo $query

		//$this->log();

		return mysql_query($query, $this->dbLink);

	 }



	function delete( $condition="" , $table="") {

		 $query = "DELETE FROM $table WHERE $condition";

		$this->sql = $query;

		//$this->log();

		if (!(mysql_query($query, $this->dbLink))) {

			$this->error_log();

			return false;

		} else {

			return true;

		}

	 }



	function deleteAll($table="") {

		$query = "TRUNCATE $table";

		//$this->tz = $this->lib_getmicrotime();

		$this->sql = $query;

		//$this->log();

		if (!(mysql_query($query, $this->dbLink))) {

			$this->error_log();

			return false;

		} else {

			return true;

		}

	 }



	function selectRows($table, $where="") {

		$q="SHOW COLUMNS FROM $table";

		$r = mysql_query($q, $this->dbLink);

		while ($res=mysql_fetch_array($r)) { 

			//echo $res[1]."<br>"; 

			if (($res[1]=="timestamp14") || ($res[1]=="datetime")) {

				$retField[]="DATE_FORMAT($res[0], '%d %b %Y at %H:%i:%s') AS $res[0]";

			} else {

				$retField[]=$res[0];

			}

		}

		

		$fields = implode(",", $retField);

		$q = "select $fields from $table $where";

		$this->sql = $q;

		//$this->log();

		$r = mysql_query($q, $this->dbLink);

		$num=mysql_num_rows($r);

		$i=1;

		while ($row=mysql_fetch_array($r)) {

			$cont[$i] = $row;

			$i++;

		}

		if (mysql_num_rows($r)>0) {

			return $cont;

		}

	}

	

	function select_array($retField, $table, $where="", $groupby="", $orderby="", $limit="") {

		$fields = implode(",", $retField);

		if ($where!="") {

			$q = "select $fields from $table WHERE $where";

		} else {

			$q = "select $fields from $table";

		}

		if ($groupby!="") {

			$q .= " GROUP BY $groupby";

		}

		if ($orderby!="") {

			$q .= " ORDER BY $orderby";

		}

		if ($limit!="") {

			$q .= " LIMIT $limit";

		}

		//echo "$q";exit;

		$this->sql = $q;

		//$this->log();

		$r = mysql_query($q, $this->dbLink);

		if (!($r)) {

			$this->error_log();

		}

		$num=mysql_num_rows($r);

		$i=1;

		while ($row=mysql_fetch_array($r)) {

			$cont[$i] = $row;

			$i++;

		}

		if (mysql_num_rows($r)>0) {

			

		//	echo print_r($cont);

		//	exit;

			

			return $cont;

		}

	}

	

	function pagination($table,$where="",$target_p,$limit=10,$page_no,$adjacent=1)

	{

		/*

		Place code to connect to your DB here.

		*/

	

		$tbl_name=$table;		//your table name

		// How many adjacent pages should be shown on each side?

		$adjacents = $adjacent;

		

		/* 

		   First get total number of rows in data table. 

		   If you have a WHERE clause in your query, make sure you mirror it here.

		*/

		if($where!="")

		$query = "SELECT COUNT(*) as num FROM $tbl_name where $where";

		else

		$query = "SELECT COUNT(*) as num FROM $tbl_name";

		$total_pages = mysql_fetch_array(mysql_query($query));

		$total_pages = $total_pages["num"];

		

		/* Setup vars for query. */

		$targetpage = $target_p; 	//your file name  (the name of this file)

		$limit = $limit; 								//how many items to show per page

		$page = $page_no;

		if($page) 

			$start = ($page - 1) * $limit; 			//first item to display on this page

		else

			$start = 0;								//if no page var is given, set start to 0

		

		/* Get data. */

		/*$sql = "SELECT * FROM $tbl_name where status=0 order by mhid DESC LIMIT $start, $limit";

		

		$result = mysql_query($sql) or die(mysql_error());*/

		

		/* Setup page vars for display. */

		if ($page == 0) $page = 1;					//if no page var is given, default to 1.

		$prev = $page - 1;							//previous page is page - 1

		$next = $page + 1;							//next page is page + 1

		$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.

		$lpm1 = $lastpage - 1;						//last page minus 1

		

		/* 

			Now we apply our rules and draw the pagination object. 

			We're actually saving the code to a variable in case we want to draw it more than once.

		*/

		$pagination = "";

		if($lastpage > 1)

		{	

			$pagination .= "<div class=\"pagination\">";

			//previous button

			if ($page > 1) 

				$pagination.= "<a href=\"$targetpage&pg=$prev\">&laquo; previous</a>";

			else

				$pagination.= "<span class=\"disabled\">&laquo; previous</span>";	

			

			//pages	

			if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up

			{	

				for ($counter = 1; $counter <= $lastpage; $counter++)

				{

					if ($counter == $page)

						$pagination.= "<a class=\"number current\">$counter</a>";

					else

						$pagination.= "<a class=\"number\" href=\"$targetpage&pg=$counter\">$counter</a>";					

				}

			}

			elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some

			{

				//close to beginning; only hide later pages

				if($page < 1 + ($adjacents * 2))		

				{

					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)

					{

						if ($counter == $page)

							$pagination.= "<a class=\"number current\">$counter</a>";

						else

							$pagination.= "<a class=\"number\" href=\"$targetpage&pg=$counter\">$counter</a>";					

					}

					$pagination.= "...";

					$pagination.= "<a class=\"number\" href=\"$targetpage&pg=$lpm1\">$lpm1</a>";

					$pagination.= "<a class=\"number\" href=\"$targetpage&pg=$lastpage\">$lastpage</a>";		

				}

				//in middle; hide some front and some back

				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))

				{

					$pagination.= "<a class=\"number\" href=\"$targetpage&pg=1\">1</a>";

					$pagination.= "<a class=\"number\" href=\"$targetpage&pg=2\">2</a>";

					$pagination.= "...";

					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)

					{

						if ($counter == $page)

							$pagination.= "<a class=\"number current\">$counter</a>";

						else

							$pagination.= "<a class=\"number\" href=\"$targetpage&pg=$counter\">$counter</a>";					

					}

					$pagination.= "...";

					$pagination.= "<a class=\"number\" href=\"$targetpage&pg=$lpm1\">$lpm1</a>";

					$pagination.= "<a class=\"number\" href=\"$targetpage&pg=$lastpage\">$lastpage</a>";		

				}

				//close to end; only hide early pages

				else

				{

					$pagination.= "<a class=\"number\" href=\"$targetpage&pg=1\">1</a>";

					$pagination.= "<a class=\"number\" href=\"$targetpage&pg=2\">2</a>";

					$pagination.= "...";

					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)

					{

						if ($counter == $page)

							$pagination.= "<a class=\"number current\">$counter</a>";

						else

							$pagination.= "<a class=\"number\" href=\"$targetpage&pg=$counter\">$counter</a>";					

					}

				}

			}

			

			//next button

			if ($page < $counter - 1) 

				$pagination.= "<a href=\"$targetpage&pg=$next\">next &raquo;</a>";

			else

				$pagination.= "<span class=\"disabled\">next &raquo;</span>";

			$pagination.= "</div>\n";		

		}

		

		return $pagination;

	}

	

	

	



	function redirect($page)

	{

		header("Location:$page");

		echo '<script type="text/javascript">';

		echo '<!--';

		echo 'window.location = "'.$page.'"';

		echo '//-->';

		echo '</script>';

		exit();

	}

	

	function createthumb($name,$filename,$new_w,$new_h)

	{

		$system=explode(".",$name);

		if (preg_match("/jpg|jpeg/",$system[1])){$src_img=imagecreatefromjpeg($name);}

		if (preg_match("/png/",$system[1])){$src_img=imagecreatefrompng($name);}

		if (preg_match("/gif/",$system[1])){$src_img=imagecreatefromgif($name);}

		$old_x=imageSX($src_img);

		$old_y=imageSY($src_img);

		if ($old_x > $old_y) 

		{

			$thumb_w=$new_w;

			$thumb_h=$old_y*($new_h/$old_x);

		}

		if ($old_x < $old_y) 

		{

			$thumb_w=$old_x*($new_w/$old_y);

			$thumb_h=$new_h;

		}

		if ($old_x == $old_y) 

		{

			$thumb_w=$new_w;

			$thumb_h=$new_h;

		}

		$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);

		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 

		if (preg_match("/png/",$system[1]))

		{

			imagepng($dst_img,$filename); 

		}

		else if (preg_match("/gif/",$system[1]))

		{

			imagegif($dst_img,$filename); 

		} else {

			imagejpeg($dst_img,$filename); 

		}

		imagedestroy($dst_img); 

		imagedestroy($src_img); 

	}

	

	 //calculate years of age (input string: YYYY-MM-DD)

	  function birthday ($birthday){

		list($day,$month,$year) = explode("/",$birthday);

		$year_diff  = date("Y") - $year;

		$month_diff = date("m") - $month;

		$day_diff   = date("d") - $day;

		if ($day_diff < 0 || $month_diff < 0)

		  $year_diff--;

		return $year_diff;

	  }

	

	/***********Pass date in dd/mm/yyyy it will return mm/dd/yyyy**********/

	

	function dateformat($descr){

		$explode_desc = explode("/",$descr);

		return $explode_desc[1]."/".$explode_desc[0]."/".$explode_desc[2];

		

	}

	

	/***********Pass a video complete URL it will return thumbnail**********/

	

	function yt_thumb($url){

	$explode_url = explode("v=",$url);

	return substr($explode_url[1],0,11);

	

	}

	

	/***********Pass a desction it will return description and extract video**********/

	

	function yt_description($descr){

	$explode_desc = explode("\n",$descr);

	$ex_des = $explode_desc[1];

	return  substr($ex_des,0,80)."....";

	}

	

	

	function SendMail($to,$subject,$message){

			

		$from_name      = "Bidhouse";

		$internal_email = "ramwinstars@gmail.com";

		$from 			= $from_name." <".$internal_email.">";

		$headers 		= "";

		$headers 		.= "MIME-Version: 1.0\r\n"; 

		$headers 		.= "Content-type: text/html;charset=iso-8859-1\r\n";

		$headers 		.= "From: ".$from."\r\n";

		$headers 		.= "Reply-To: ".$internal_email."\r\n";

		$headers 		.= "X-Priority: 1\r\n"; 

		$headers 		.= "X-MSMail-Priority: High\r\n"; 

		$headers 		.= "X-Mailer: Just My Server";

		mail($to, $subject, $message , $headers);

	

	}

	

	function Buttons($txt){

			

		return $btn = '

		<span style="float:left; margin:5px;"><table  border="0" cellspacing="0" cellpadding="0">

		  <tr>

			<td width="5"><img src="images/postproject-t_01.jpg" width="5" height="20"></td>

			<td background="images/postproject-t_03.jpg">

				<span class="button_text"> '.$txt.' </span>

			</td>

			<td width="7"><img src="images/postproject-t_05.jpg" width="7" height="20"></td>

		  </tr>

		</table></span>';

	

	}

	

	function dateDiff($start, $end) {

	  $start_ts = strtotime($start);

	  $end_ts = strtotime($end);

	  $diff = $end_ts - $start_ts;

	  return round($diff / 86400);

	}	

	

	function generatePassword($length=8, $strength=2) {

	$vowels = 'aeuy';

	$consonants = 'bdghjmnpqrstvz';

	if ($strength & 1) {

		$consonants .= 'BDGHJLMNPQRSTVWXZ';

	}

	if ($strength & 2) {

		$vowels .= "AEUY";

	}

	if ($strength & 4) {

		$consonants .= '23456789';

	}

	if ($strength & 8) {

		$consonants .= '@#$%';

	}

 

	$password = '';

	$alt = time() % 2;

	for ($i = 0; $i < $length; $i++) {

		if ($alt == 1) {

			$password .= $consonants[(rand() % strlen($consonants))];

			$alt = 0;

		} else {

			$password .= $vowels[(rand() % strlen($vowels))];

			$alt = 1;

		}

	}

	return $password;

}

	

	

	

}

if ($_SERVER['HTTP_HOST'] != 'localhost'){

	$path='http://demo.stardomtechnology.net/village4rent/';

} else {

	$path='http://demo.stardomtechnology.net/village4rent/';

}



?>