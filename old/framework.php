<?php

include_once "functions.inc";
include_once "button.inc";
include_once "orszagok.class";
include_once "$inc_dir/main.inc";

// A caption-t nem birja a fejlec valign=middle meg a cellpadding ott nem lehet 0-tól különbözõ
connectdb();
connectdb2();

class mainclass {
  public $params;
  public $trace = true;
  public $logged = false;
  public $Form = "0"; /* P: P=0; L=1 */
  public $FormDisp = "P";
  public $Size = 0;
  public $ChatSize = 0;
  public $ID = -1;
  public $IDMain = -1;
  public $ShowID = -1;
  public $EpisodeID = -1;
  public $ForduloID = -1;
  public $Nev = "";
  public $Race = -1;
  public $XP = 0;
  public $CreditAll = 0;
  public $CreditPrivate = 0;
  public $CreditPublic = 0;
  public $Penz = -1;
  public $Kaja = -1;
  public $Mana = -1;
  public $Fa = -1;
  public $Ko = -1;
  public $Fem = -1;
  public $Fold = -1;
  public $Szabadsag = -1;
  public $TurnCounter = -1;
  public $TurnCounterFV = -1;
  public $Position = -1;
  public $Number = -1;
  public $Luck = -1;
  public $Rang = -1;
  public $Klan = -1;
  public $NoKlan = -1;
  public $Feeling = -1;
  public $TAXRate = -1;
  public $Activated = 0;
  public $IntezoTurns = 0;
  public $IntezoText = "";
  public $RaceNev = "";
  public $LuckDisp = "";
  public $Language = "";
  public $MinForumKarma;
  public $NoSpeak;
  public $Moderator;
  public $ModeratorMain;
  public $signature;
  public $arr;
  public $IframeStart = "";
  public $LastBonusDay;
  public $LastReset;
  public $User_WantWin;
  public $UserID;
  public $BonusID;
  public $ido = 0;
  public $NewLog = 0;
  public $NewMess = 0;
  public $NewVox = 0;
  public $NewForum = 0;
  public $SzabiRaktar;
  public $Flags = 0;
  public $OldStyle = 1;
  public $OldStyleMain = 1;
  public $Skin = "stratega.css";
  public $PlayerSkin = 0;
  public $PlayerID = 0;
  public $IframeHeight = "60px;";
  public $IframeScroll = "20px;";
  public $IframeChange = false;
  public $WindowLeft;
  public $WindowTop;
  public $UnxTSTMP;
  public $ShowMode = false;
  public $ClanRight=0;
  public $HeroAmuletRight=0;
  public $ChatNumber=0;
  public $ChatAdmin=0;
  public $ArtifactRight=0;
  public $MaxEpisodeRound=0;
  public $MaxEpisodeXP=0;
  public $EpisodeState=-1;
  public $IsActived=0;
  private $isactivatelinksend=0;
  

  public function __construct($params, $trace=false){
    global $lockfile;
    if( $trace )
      echo 'mainclass 0 contruct.<br>';

    $this->params = $params;
    $this->trace  = $trace;
	

    if( $this->mustLogin() || $this->canLogin() ){
      # megnezzuk, hogy be van-e lepve. Ha nincs visszadobjuk a login-ba
      $id = getuserid(@$_COOKIE['signature']);
      if( $this->mustLogin() && $id == 0 ){
        header("Location:exit.html");
        die();
      }
      if( $this->mustLogin()  && file_exists($lockfile) && !$this->isTurnChange() && !$this->isChat()) {
		  if($_SERVER['PHP_SELF']!="/gyik.html" && $_SERVER['PHP_SELF']!="/sugo.html" && $_SERVER['PHP_SELF']!="/forum.html" && $_SERVER['PHP_SELF']!="/old_stratega.html" && $_SERVER['PHP_SELF']!="/news.html"){
			$_SESSION['lastpage'] = $_SERVER['PHP_SELF'];
			header("Location:wait.html");
			die();
		  }
      }

      if( $id > 0 ){
        $this->signature = $_COOKIE['signature'];
        $this->logged = true;
      }

    }
    $this->start();
	
  }

  public function start(){
    global $error_file;

	$eredf = query("SELECT count(c.orsz_ID) cnt, SUM(case WHEN admin>0 THEN 1 ELSE 0 END) ad FROM `chat_user` c inner join orszagok o on o.id=orsz_ID WHERE  `active`>0 and `visible`>0 AND room =1");
	if($rowf = mysqli_fetch_array($eredf)) { 
		$this->ChatNumber=$rowf['cnt'];
		$this->ChatAdmin=$rowf['ad'];}
		
    if( $this->checkParam() ){
      if( $this->logged )
        $this->selectMainInfo();
      InitPiac();

      $this->main();
      $this->header();
      if( $this->OldStyle == 1 ){
        $this->menuOldStyle();
        $this->body();
		$this->footer();
      }
      else if( $this->Form == 0 ){
        $this->body();
        $this->footer();
		//if($this->isChat()) {	$this->buttonbottom();}
      }
      else{
        $this->footer();
        $this->body();
      }
      $this->end();
    }
    else
      $this->emptyHTML();
  }

  public function main(){
  }

  public function selectMainInfo(){
    global $OldStyle;
    global $turnshit;
    $turnmins = 20;

	
    $found = selectOrszagData($this->signature, $this);
	$eredf = query2("select turn_time from fordulok where episode_id=".$this->EpisodeID." and fordulo_id=".$this->ForduloID);
    if($rowf = mysqli_fetch_object($eredf)) {if($rowf->turn_time) {$turnmins=$rowf->turn_time;}}
    if( $found ){
		
		/*if(!strpos($_SERVER['PHP_SELF'],"tronterem.html") && $this->IsActived==0){//ha nincs aktivalva csak a trontermet nezheti
			header("Location:tronterem.html");
			die();
		}*///aktivalo link ujrakuldes
		if(isset($_POST['resend']) && isset($_POST['activreemail'])) if($_POST['resend'] == "1" && strlen($_POST['activreemail'])>6){ 
			$eredx=query2("SELECT PromotionalCode FROM player WHERE id='@1'", $this->ID);
			if( $rx=mysqli_fetch_object($eredx) ){
				//email egyediseg
				$eredx2 = query2("select ID from player where email='@1' and ID!='@2'",$_POST['activreemail'], $this->ID);
				if( mysqli_num_rows($eredx2)==0) {
					query2("UPDATE Player SET email='@1' WHERE ID='@2'",$_POST['activreemail'], $this->ID);
					sendActivateLink($_POST['activreemail'],$rx->PromotionalCode);
					$this->isactivatelinksend=1;
				}else{
					$this->isactivatelinksend=2;	
				}
			}
        }
			
      if( $this->isChat() ){
        $this->OldStyle = 1;
        $this->Size = $this->ChatSize;
        $this->Form = 0;
        $OldStyle = 1;
      }
      else {
        if( $this->Size == "" ) $this->Size = 0;
        if( $this->Form == "" ) $this->Form = 0;
      }
      if( $this->IntezoTurns > 0 )
        $this->IntezoText = " (".$this->IntezoTurns.")";

      if( $this->Form == 1 ) $this->FormDisp = "L"; else $this->FormDisp = "P";

      $this->ido = $this->UnxTSTMP; #time();
      $this->ido = ($turnshit*60)+($turnmins*60-($this->ido-((floor($this->ido/($turnmins*60)))*$turnmins*60))); # +($row->TimeShift);
      if( $this->ido > $turnmins*60 ) {
        $this->ido = $this->ido - $turnmins*60;
      }
      # ha nem volt internet a kv elotti 15 percben, akkor elhalasztjuk a kv-t
      $ered13 = query("SELECT UNIX_TIMESTAMP(LinkFail) AS LinkFail FROM beallitas");
      if( $row13 = mysqli_fetch_object($ered13) ) {
        if( $row13->LinkFail > (($this->UnxTSTMP + $this->ido)-15*60) ) {
          $this->ido = $this->ido + 30*60;
        }
      }

      if( $this->Activated == 1 ){
		if(isset($_REQUEST['vac_end'])) if($_REQUEST['vac_end'] == "1" && $this->Szabadsag == 1){ 
			$this->Szabadsag = 0;
			query("UPDATE Orszagok SET Szabadsag='0' WHERE ID='@1'", $this->ID);
        }
		if(isset($_REQUEST['prot_end'])) if($_REQUEST['prot_end'] == "1" && $this->Szabadsag < 1){ 
			$this->Szabadsag = 0;
			query("UPDATE Orszagok SET Szabadsag='0' WHERE ID='@1'", $this->ID);
        }
	  }
      $ered1 = query("SELECT COUNT(*) AS notread FROM log WHERE Orsz_ID='@1' AND Deleted=0 AND Olvasva=0 AND Seen=0", $this->ID);
      if( $row1 = mysqli_fetch_object($ered1) ) {
        $this->NewLog = $row1->notread;
      }
	  //ha van ide szolo globaluzenet azt bemasoljuk
	  $mq = query2("SELECT * FROM messages WHERE PlayerID='@1' AND EpisodeID=@2", $this->ID, $this->EpisodeID);
      while( $row1 = mysqli_fetch_object($mq) ) {
		query("INSERT INTO messages SET Orsz_ID='@1', ido=NOW(), szoveg='@2', Subject='@3', sender=0", $row1->PlayerID, $row1->Msg, $row1->Subject);
		query2("DELETE FROM messages WHERE ID=@1", $row1->ID);

	  }
      $ered1 = query("SELECT COUNT(*) AS notread FROM messages WHERE Orsz_ID='@1' AND Deleted=0 AND Olvasva=0 AND Seen=0", $this->ID);
      if( $row1 = mysqli_fetch_object($ered1) ) {
        $this->NewMess = $row1->notread;
      }	  
	  			
			
	/* szavazas ki 2021.11.12	
	if(strpos($_SERVER['PHP_SELF'],"szavazas.html")){
		 query("UPDATE Orszagok SET LastVoteDate=NOW() WHERE ID='@1'", $this->ID);
	  }else{
		  $ered=query("SELECT LastVoteDate FROM Orszagok WHERE ID='@1'", $this->ID);
			if($row = mysqli_fetch_object($ered)){
				$dstr=$row->LastVoteDate;
			}else{
				$dstr='0000-00-00 00:00:00';
			}
			$ered1 = query("SELECT COUNT(*) AS newvox FROM szavazasok s   WHERE s.Orsz_ID!='@1' AND (s.lejarat>NOW() OR s.lejarat='0000-00-00 00:00:00') AND s.Benyujtva>'$dstr' AND NOT EXISTS (SELECT Tamogat FROM szavazas_szavazat sz WHERE sz.Szavazas_ID=s.ID AND sz.Orsz_ID='@1' ) ", $this->ID);
		    if( $row1 = mysqli_fetch_object($ered1)) {
			    $this->NewVox = $row1->newvox;
		    }
	  }*/

      if(!strpos($_SERVER['PHP_SELF'],"forum.html")){
		  $LastID = 0;
		  $lang = $_SESSION['c_language'];
		  $ismod=0;
		  if( $lang == '' )
			 $lang = $this->Language;
		  $ered = query("SELECT f.Last_ID, Moderator FROM orszag_forum f JOIN orszagok o ON o.ID=f.Orsz_ID WHERE f.Orsz_ID='@1' ORDER BY f.Last_ID DESC LIMIT 1", $this->ID);
          if( $row = mysqli_fetch_object($ered) ){
			$LastID = $row->Last_ID;
			$ismod = $row->Moderator;
		  }
		  $ered = query("SELECT  f.LastID  FROM forum_themes f LEFT JOIN forum_themes_user u ON f.ID= u.theme AND u.Orsz_ID=$this->ID WHERE f.MinReadLevel<=$ismod AND f.MinReadXP<=$this->XP AND (f.LangCode='' OR f.LangCode='".$lang."') AND (f.UserRestrict=0 || u.ID IS NOT NULL) AND f.ID IN (9,24,5,29,28)  ORDER BY f.LastID DESC LIMIT 1");		  
		  while( $row = mysqli_fetch_object($ered) ) {
			   if( $row->LastID > $LastID ) {				   
				    $this->NewForum = 1;
			   }
		  }
					  
	  }	  
	   
     if( isset($_COOKIE['iframe_start']) ) {
        $this->IframeStart = $_COOKIE['iframe_start'];
        if( $this->IframeStart != "" ){
          $pieces = explode("&", $this->IframeStart);
          if( $pieces[0] == 'logid' ){
            if( $pieces[1] == '1' || $pieces[1] == '0' ) // késõbb a 0-t ki kellene venni, hogy kisebb legyen az ablak, de most 1 log-bejegyzés több mindent tartalmazhat
              $this->IframeHeight = "300px;";
              $this->IframeScroll = "260px;";
          }
        }
      }
      setcookie("iframe_start","",0,"/");
	  
	  //online frissitese
	  $isrefresh=true;
	  if(isset($_SERVER['HTTP_REFERER']) && isset($_SERVER['PHP_SELF'])){
		$ref_arr = explode("/", $_SERVER['HTTP_REFERER']);
		$ref_count = count($ref_arr);
		$ref_url1 = $ref_arr[$ref_count-1]; 
		$ref_arr = explode("/", $_SERVER['PHP_SELF']);
		$ref_count = count($ref_arr);
		$ref_url2 = $ref_arr[$ref_count-1]; 

		if($ref_url1==$ref_url2)//ha ugyanrrol az oldalrol hivjak akkor nem frissitjuk
			$isrefresh=false;
		}
	
		if($isrefresh){			
			
			$ered=query("select Online from Orszagok WHERE ID='@1'", $this->ID);
			$row = mysqli_fetch_object($ered);
			
			if($row->Online=='0000-00-00 00:00:00' && !$this->ShowMode ){//offline es nem moderator nezet
				
				$ered2=query2("SELECT id FROM Login_logs WHERE Success=1 AND (IsLogin=1 OR IsLogin=0) AND Orsz_ID='@1' and  episode_id=@2 and DATE_ADD(Ido, INTERVAL 24 HOUR)> now()", $this->ID,$this->EpisodeID);
				if( mysqli_num_rows($ered2)==0){//ha tobb mint 24 oraja nem lepett be kidobjuk
					header("Location:login.html");
					die();
				}	
							
				query2("UPDATE player SET ip='@1' WHERE ID='@2'",getenv("REMOTE_ADDR"), $this->ID);
				$ered2=query2("SELECT id FROM Login_logs WHERE Orsz_ID='@1' and  episode_id=@2 and DATE_ADD(Ido, INTERVAL 1 MINUTE)>now()", $this->ID,$this->EpisodeID);
				if( mysqli_num_rows($ered2)==0){					
					query2("INSERT INTO Login_logs SET Ido=NOW(),IP='@6',login='@5',Orszagnev='$this->Nev',Success=1,Orsz_ID=$this->ID, 
					  resolution='@1', colordepth='@2',platform='@3',broswer='@4', episode_id=@7, IsLogin=@8, IsAdmin=@9",(isset($_COOKIE['rs']) ? $_COOKIE['rs']:'0x0'),(isset($_COOKIE['cd']) ? $_COOKIE['cd']:'0'),
					  (isset($_COOKIE['an']) ? $_COOKIE['an']:''),(isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"]:''),(isset($_COOKIE['login']) ? $_COOKIE['login'] : $this->UserID),getenv("REMOTE_ADDR"), $this->EpisodeID, 2, (isset($_COOKIE['adm']) && intval($_COOKIE['adm'])==1)?1:0);
	 
				}
    
			}
			query("UPDATE Orszagok SET Online=now() WHERE ID='@1'", $this->ID);
		}
		logPageRefresh($this->EpisodeID);
    }
    else if( $this->canLogin() ){
      $this->logged = false;
    }
    else {
      header("Location:login.html");
      die();
    }
/*epizodkivetel 	2021.02.27
	if(($this->TurnCounter >= $this->MaxEpisodeRound || $this->XP >= $this->MaxEpisodeXP) && (strpos($_SERVER['PHP_SELF'],"episode.html")==false && strpos($_SERVER['PHP_SELF'],"forum.html")==false && strpos($_SERVER['PHP_SELF'],"kocsma.html")==false && strpos($_SERVER['PHP_SELF'],"log.html")==false && strpos($_SERVER['PHP_SELF'],"hirnev.html")==false && strpos($_SERVER['PHP_SELF'],"futar.html")==false && strpos($_SERVER['PHP_SELF'],"news.html")==false && strpos($_SERVER['PHP_SELF'],"statisztika.html")==false && strpos($_SERVER['PHP_SELF'],"gyik.html")==false && strpos($_SERVER['PHP_SELF'],"sugo.html")==false && strpos($_SERVER['PHP_SELF'],"old_stratega.html")==false)){//ha elerte a max korok szamat vagy max xp-t
		$ered = query2("SELECT COUNT(episode_id) as cc FROM episodes WHERE Condition_Episode=@1 AND State=0",$this->EpisodeID);
		if( $row = mysqli_fetch_object($ered) ) {//ha van nagyobb nyitott episod ahova belephetek akkor a tobbi oldalt tiltjuk
	      if($row->cc>0) {
			$kereses = query2("SELECT  EnableAllEpisode FROM Player WHERE ID='@1'",$this->ID);
			if( $rowx = mysqli_fetch_object($kereses)){
				if($rowx->EnableAllEpisode==0)//ha tilthato
					header("Location:episode.html");
			}
			
		  }
		}
	}
	
	if(  $this->EpisodeState==3 && strpos($_SERVER['PHP_SELF'],"episode.html")==false){//ha jelenkezheto az epizod
        header("Location:episode.html");
        die();
      }
	  */
  }

  public function checkParam(){
    foreach( $this->params as $param ) {
      if( $this->trace )
        echo "$param<br>";
    }
    $bad = array();
    foreach( $_POST as $key => $value ) {
      if( $this->trace )
        echo "$key=$value";
      $ok = false;
      foreach( $this->params as $param ) {
        if( $param == $key ){
          $ok = true;
          break;
        }
      }
/*
      if( !$ok ){
        $bad[] = "$key=$value";
        echo " - BAD<br>";
      }
      else
        echo " - OK<br>";
*/
    }
    return true;
  }

  public function mustLogin(){ return true; }
  public function canLogin(){ return false; }
  public function getResizeRow(){ return false; }
  public function getUsemap(){ return ""; }
  public function getImageClass(){ return "dtron"; }
  public function usemap(){ return ""; }
  public function isChat(){ return false; }
  public function isTurnChange(){ return false; }
  public function getTitle(){ return ""; }

  public function tooltip($img, $title1, $title2, $text){
    $title = "";
    if( $title1 != "" && $title2 != "" )
      $title = LocStr($title1) . ': ' . LocStr($title2);
    else if( $title1 != "" )
      $title = LocStr($title1);
    else if( $title2 != "" )
      $title = LocStr($title2);

    echo "class=\"tipz\" title=\"" . $img . "::" . $title . "::" . LocStr($text) . "\"";
  }

  public function submit($key){
  }

  public function emptyHTML(){
  }

  public function header(){
    global $lockfile;
    $image = $this->getImageClass();
    $usemap = $this->getUsemap();
    $desc = LocStr("DESCRIPTION");
	

?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "https://www.w3.org/TR/html4/frameset.dtd">
    <html>
      <head>
        <link href="skins/main.css" rel="stylesheet" type="text/css" >
<?php if( $this->OldStyle == 0 ){ ?>
        <link href="skins/stratarts.css" rel="stylesheet" type="text/css" >
        <link href="skins/background<?php echo $this->FormDisp.$this->Size;?>.css" rel="stylesheet" type="text/css" >
        <link href="uvumi-scrollbar.css" rel="stylesheet" type="text/css" media="screen">
<?php } else { ?>
        <link href="skins/<?php echo $this->Skin; ?>" rel="stylesheet" type="text/css" >
<?php } ?>

        <title><?php echo LocStr("TITLE").$this->getTitle(); ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" >
        <meta http-equiv="Pragma" CONTENT="no-cache">
	<?php  if(!strpos($_SERVER['PHP_SELF'],"register.html") ) { ?>
        <script type="text/javascript" src="js/mootools1.3/mootools-core-1.3-full-compat.js"></script>
        <script type="text/javascript" src="js/mootools1.3/mootools-more.js"></script>
        <script type="text/javascript" src="js/mootools1.3/UvumiScrollbar.js"></script>
	<?php } if( $this->mustLogin() || $this->canLogin() ) { ?>
        <style type="text/css">
          #pergamen       {Z-INDEX: 992; POSITION: absolute; TEXT-ALIGN: center; WIDTH: 100%; TOP: 0px; LEFT: 0px}
          #pergamen_image {Z-INDEX: 993; POSITION: relative; MARGIN: 0px auto; WIDTH: 700px; TOP: 260px; LEFT: 0px}
          #info_frame       {Z-INDEX: 994; POSITION: absolute; TEXT-ALIGN: center; WIDTH: 100%;  HEIGHT: 1px; TOP: 0px; LEFT: 0px; }
          #info_frame_main  {Z-INDEX: 995; POSITION: relative; MARGIN: 0px auto; WIDTH: 100px; HEIGHT: 500px; TOP: 80px; LEFT: 0px}
          #szabi       {Z-INDEX: 990; POSITION: absolute; TEXT-ALIGN: center; WIDTH: 100%; HEIGHT: 1px; TOP: 0px; LEFT: 0px;}
          #szabi_main  {Z-INDEX: 991; POSITION: relative; MARGIN: 0px auto; WIDTH: 800px; HEIGHT: <?php echo $this->IframeHeight;?> TOP: <?php if( $this->Szabadsag != 0 ) echo $this->WindowTop; else echo "100px";?>; LEFT: <?php echo $this->WindowLeft;?>}
          #yesno       {Z-INDEX: 998; POSITION: absolute; TEXT-ALIGN: center; WIDTH: 100%; HEIGHT: 1px; TOP: 0px; LEFT: 0px;}
          #yesno_main  {Z-INDEX: 999; POSITION: relative; MARGIN: 0px auto; WIDTH: 340px; HEIGHT: 120px; TOP: 200px; LEFT: 0px}
		  #eparch       {Z-INDEX: 990; POSITION: absolute; TEXT-ALIGN: center; WIDTH: 100%; HEIGHT: 1px; TOP: 0px; LEFT: 0px;}
          #eparch_main  {Z-INDEX: 991; POSITION: relative; MARGIN: auto;  TOP: 100px; LEFT: 40%;}
		   #activdiv       {Z-INDEX: 990; POSITION: absolute; TEXT-ALIGN: center; WIDTH: 100%; HEIGHT: 1px; TOP: 0px; LEFT: 0px;}
           #activdiv_main  {Z-INDEX: 991; POSITION: relative; TOP: 200px; LEFT: 30%; margin:auto;}
		   .divChat3 {min-height: 80vh;}
        </style>
<?php } ?>
        <script  type="text/javascript">  
		
		
		<?php if( $this->OldStyle == 0 ){ ?>
           

		onkeydown =  function(e) {	
		 e = e || event; // to deal with IE			 
		 if(e.ctrlKey &&  e.keyCode==37){
			 e.preventDefault();
			window.location.href = document.getElementById("prew");
		 }else  if(e.ctrlKey &&  e.keyCode==39){
			 e.preventDefault();
			window.location.href = document.getElementById("next");
		 }			
  
	
};

 <?php }  ?>
		
          var last_option=0;
          var ims = new Array();

          var tooltitle = [];
          var tooltext = [];
          tooltitle[0] = '<?php echo $desc . ": " . LocStr("Gold"); ?>';
          tooltitle[1] = '<?php echo $desc . ": " . LocStr("Food"); ?>';
          tooltitle[2] = '<?php echo $desc . ": " . LocStr("Wood"); ?>';
          tooltitle[3] = '<?php echo $desc . ": " . LocStr("Stone"); ?>';
          tooltitle[4] = '<?php echo $desc . ": " . LocStr("Metal"); ?>';
          tooltitle[5] = '<?php echo $desc . ": " . LocStr("Mana"); ?>';
          tooltitle[6] = '<?php echo $desc . ": " . LocStr("Land"); ?>';
          tooltitle[7] = '<?php echo $desc . ": " . LocStr("XP"); ?>';
          tooltitle[8] = '<?php echo $desc . ": " . LocStr("Trust"); ?>';
          tooltitle[9] = '<?php echo $desc . ": " . LocStr("Luck"); ?>';
          tooltitle[10]= '<?php echo $desc . ": " . LocStr("Elapsed_turns"); ?>';
          tooltitle[11]= '<?php echo $desc . ": " . LocStr("Seconds_left_from_turn"); ?>';
          tooltitle[12]= '<?php echo $desc . ": " . LocStr("HUMANSOUL"); ?>';
          tooltitle[13]= '<?php echo GetTextStr("MENU", 1); ?>';
          tooltitle[14]= '<?php echo GetTextStr("MENU", 3); ?>';
          tooltitle[15]= '<?php echo GetTextStr("MENU", 4); ?>';
          tooltitle[16]= '<?php echo GetTextStr("MENU", 7); ?>';
          tooltitle[17]= '<?php echo GetTextStr("MENU", 8); ?>';
          tooltitle[18]= '<?php echo GetTextStr("MENU", 9); ?>';
          tooltitle[19]= '<?php echo GetTextStr("MENU", 10); ?>';
          tooltitle[20]= '<?php echo GetTextStr("MENU", 11); ?>';
          tooltitle[21]= '<?php Display("NAVBOOK1");?>';
          tooltitle[22]= '<?php Display("NAVBOOK2");?>';
          tooltitle[23]= '<?php Display("NAVBOOK3");?>';
          tooltitle[24]= '<?php Display("NAVBOOK4");?>';
          tooltitle[25]= '<?php echo GetTextStr("MENU", 30); ?>';
          tooltitle[26]= '<?php echo GetTextStr("MENU", 31); ?>';
          tooltitle[27]= '<?php Display("EXIT");?>';
          tooltitle[28]= '<?php echo GetTextStr("MENU", 12); ?>';
          tooltitle[29]= '<?php echo GetTextStr("MENU", 13); ?>';
          tooltitle[30]= '<?php echo GetTextStr("MENU", 14); ?>';
          tooltitle[31]= '<?php echo GetTextStr("MENU", 16); ?>';
          tooltitle[32]= '<?php echo GetTextStr("MENU", 17); ?>';
          tooltitle[33]= '<?php echo GetTextStr("MENU", 2); ?>';
          tooltitle[34]= '<?php echo GetTextStr("MENU", 18); ?>';
          tooltitle[35]= '<?php echo GetTextStr("MENU", 19); ?>';
          tooltitle[36]= '<?php Display("NAVCHAT");?>';
          tooltitle[37]= '<?php echo GetTextStr("MENU", 20); ?>';
          tooltitle[38]= '<?php echo GetTextStr("MENU", 21); ?>';
          tooltitle[39]= '<?php echo GetTextStr("MENU", 22); ?>';
          tooltitle[40]= '<?php echo GetTextStr("MENU", 29); ?>';
          tooltitle[41]= '<?php echo GetTextStr("MENU", 23); ?>';
          tooltitle[42]= '<?php echo GetTextStr("MENU", 6); ?>';
          tooltitle[43]= '<?php echo GetTextStr("MENU", 5); ?>';
          tooltitle[44]= '<?php echo GetTextStr("MENU", 27); ?>';
          tooltitle[45]= '<?php echo GetTextStr("MENU", 24); ?>';
          tooltitle[46]= '<?php echo GetTextStr("MENU", 25); ?>';
          tooltitle[47]= '<?php echo GetTextStr("MENU", 26); ?>';
          tooltitle[48]= '<?php Display("NAVEMAIL");?>';
          tooltitle[49]= '<?php Display("ATTACK");?>';
          tooltitle[50]= '<?php Display("RANKING");?>';
          tooltitle[51]= '<?php Display("SPEELCAST");?>';
          tooltitle[52]= '<?php Display("disband");?>';
          tooltitle[53]= '<?php Display("revocation");?>';
          tooltitle[54]= '<?php Display("cancelQ");?>';
          tooltitle[55]= '<?php Display("demolishQ");?>';
          tooltitle[56]= '<?php Display("FORUMREPLYSUBJECT");?>';
          tooltitle[57]= '<?php Display("TRASH");?>';
          tooltitle[58]= '<?php Display("LOCKOUT");?>';
		  tooltitle[59]= '<?php echo $desc.": ".GetTextStr("episode", $this->EpisodeID); ?>';
		  tooltitle[61]= '<?php echo LocStr("NAVPRODUCTION"); ?>';
		  tooltitle[62]= '<?php echo GetTextStr("MENU", 28); ?>';

          tooltext[0] = '<?php echo LocStr("TIP_GOLD"); ?>';
          tooltext[1] = '<?php echo LocStr("TIP_FOOD"); ?>';
          tooltext[2] = '<?php echo LocStr("TIP_WOOD"); ?>';
          tooltext[3] = '<?php echo LocStr("TIP_STONE"); ?>';
          tooltext[4] = '<?php echo LocStr("TIP_METAL"); ?>';
          tooltext[5] = '<?php echo LocStr("TIP_MANA"); ?>';
          tooltext[6] = '<?php echo LocStr("TIP_LAND"); ?>';
          tooltext[7] = '<?php echo LocStr("TIP_XP"); ?>';
          tooltext[8] = '<?php echo LocStr("TIP_TRUST"); ?>';
          tooltext[9] = '<?php echo LocStr("TIP_LUCK"); ?>';
          tooltext[10]= '<?php echo LocStr("TIP_TURN", $this->TurnCounterFV); ?>';
          tooltext[11]= '<?php echo LocStr("TIP_TIME_LEFT"); ?>';
          tooltext[12]= '<?php echo LocStr("TIP_MAN"); ?>';
          tooltext[13]= '<?php echo LocStr("TIP_HALL"); ?>';
          tooltext[14]= '<?php echo LocStr("TIP_BUILDUNG"); ?>';
          tooltext[15]= '<?php echo LocStr("TIP_ARMIES"); ?>';
          tooltext[16]= '<?php echo LocStr("TIP_MARKET"); ?>';
          tooltext[17]= '<?php echo LocStr("TIP_CREDIT"); ?>';
          tooltext[18]= '<?php echo LocStr("TIP_BOARDROOM"); ?>';
          tooltext[19]= '<?php echo LocStr("TIP_CLANS"); ?>';
          tooltext[20]= '<?php echo LocStr("TIP_NAVCOUNTRYLIST"); ?>';
          tooltext[21]= '<?php echo LocStr("TIP_NAVBOOK1"); ?>';
          tooltext[22]= '<?php echo LocStr("TIP_NAVBOOK2"); ?>';
          tooltext[23]= '<?php echo LocStr("TIP_NAVBOOK3"); ?>';
          tooltext[24]= '<?php echo LocStr("TIP_NAVBOOK4"); ?>';
          tooltext[25]= '<?php echo LocStr("TIP_MESSENGER"); ?>';
          tooltext[26]= '<?php echo LocStr("TIP_DIARY"); ?>';
          tooltext[27]= '<?php echo LocStr("TIP_EXIT"); ?>';
          tooltext[28]= '<?php echo LocStr("TIP_NOTES"); ?>';
          tooltext[29]= '<?php echo LocStr("TIP_BONUS"); ?>';
          tooltext[30]= '<?php echo LocStr("TIP_SETUP"); ?>';
          tooltext[31]= '<?php echo LocStr("TIP_PRIORITY"); ?>';
          tooltext[32]= '<?php echo LocStr("TIP_HELPER"); ?>';
          tooltext[33]= '<?php echo LocStr("TIP_OVERVIEW"); ?>';
          tooltext[34]= '<?php echo LocStr("TIP_INN"); ?>';
          tooltext[35]= '<?php echo LocStr("TIP_FORUM"); ?>';
          tooltext[36]= '<?php echo LocStr("TIP_CHAT"); ?>';
          tooltext[37]= '<?php echo LocStr("TIP_VOTES"); ?>';
          tooltext[38]= '<?php echo LocStr("TIP_NEWS"); ?>';
          tooltext[39]= '<?php echo LocStr("TIP_HEROESTOUR"); ?>';
          tooltext[40]= '<?php echo LocStr("TIP_HALLOFFAME"); ?>';
          tooltext[41]= '<?php echo LocStr("TIP_STATS"); ?>';
          tooltext[42]= '<?php echo LocStr("TIP_AMULETS"); ?>';
          tooltext[43]= '<?php echo LocStr("TIP_SCROLLS"); ?>';
          tooltext[44]= '<?php echo LocStr("TIP_Magic_items"); ?>';
          tooltext[45]= '<?php echo LocStr("TIP_LINKLIST"); ?>';
          tooltext[46]= '<?php echo LocStr("TIP_HELP"); ?>';
          tooltext[47]= '<?php echo LocStr("TIP_FAQ"); ?>';
          tooltext[48]= '<?php echo LocStr("TIP_MAIL"); ?>';

          tooltext[49]= '<?php echo LocStr("TIP_TIME_BUILD"); ?>';
          tooltext[50]= '<?php echo LocStr("TIP_CLOSE"); ?>';
          tooltext[51]= '<?php echo LocStr("TIP_FORUMREPLY"); ?>';
          tooltext[52]= '<?php echo LocStr("TIP_TRASH"); ?>';		  
		  tooltext[59]= '<?php echo GetTextStr("episode_desc", $this->EpisodeID); ?>';
          tooltext[60]= '<?php echo LocStr("TIP_PERMISSION"); ?>';
		  tooltext[61]= '<?php echo LocStr("TIP_PRODUCTION"); ?>';
		  tooltext[62]= '<?php echo GetTextStr("MENU", 28); ?>';
          var tipz=null;
          var tipd=null;
          var elementsZ = [];
          var elementsD = [];
          function tipsplit(element) {
            var content = element.get('title').split('::');
            if( content[1] >= '0' && content[1] < tooltitle.length )
              content[1] = tooltitle[content[1]];
            if( content[2] >= '0' && content[2] < tooltext.length )
              content[2] = tooltext[content[2]];
            element.store('tip:class', content[0]);
            element.store('tip:title', content[1]);
            element.store('tip:text', content[2]);
            if( content.length > 3 )
              element.store('tip:img', content[3]);
          }
		  <?php  if(!strpos($_SERVER['PHP_SELF'],"register.html") ) { ?>
          function set_tooltips(){
            $$('.tipz').concat(elementsZ).each(function(element,index) {tipsplit(element);});
            $$('.tipd').concat(elementsD).each(function(element,index) {tipsplit(element);});

            //create the tooltips
            tipz = new Tips('.tipz',{
              className: 'tipdefault',
              fixed: false,
              hideDelay: 50,
              showDelay: 50
            });
            tipd = new Tips('.tipd',{
              className: 'tipdefault',
              fixed: false,
              hideDelay: 50,
              showDelay: 50
            });
<?php
/* Kiegészités: mootools.js
	elementEnter: function(event, element){
		this.container.empty();

		var mainclass = element.retrieve('tip:class');
		if( mainclass == '' ) mainclass = 'tipdefault';
		this.tip.className = mainclass;

	position: function(event){
		if (!this.tip) document.id(this);
                var element = event.target;
		var docmain = element.getDocument();
		if( element != null && docmain != document ){
			var url = docmain.URL.split("html")[0];
			var frms = document.getElementsByTagName('iframe');
			for( i = 0; i < frms.length; i++){
				var frm = frms[i];
				if( frm.src.split("html")[0] == url ){
					var pos = frm.getPosition();
					event.page['x'] += pos.x;
					event.page['y'] += pos.y;
					break;
				}
			}
		}
*/
?>
            tipz.attach(elementsZ);
            tipd.attach(elementsD);
          }
	<?php } ?>

          function tipattach(element, opt){
            if( typeof opt == 'undefined' ) opt = 0;

            if( element.className == "tipz" ){
              if( tipz == null && opt == 1 ) return;
              if( tipz == null )
                elementsZ[elementsZ.length] = element;
              else{
                tipsplit(element);
                tipz.attach(element);
              }
            }
            else if( element.className == "tipd" ){
              if( tipd == null && opt == 1 ) return;
              if( tipd == null )
                elementsD[elements.length] = element;
              else{
                tipsplit(element);
                tipd.attach(element);
              }
            }
          }

          function tiphide(element){
            if( tipz != null ) tipz.hide(element);
          }

<?php if( $this->mustLogin() || $this->canLogin() ) { ?>
          function trace(text, append){
            var pre = '';
            if( append == 1 )
              pre = document.getElementById('trace').innerHTML;
            document.getElementById('trace').innerHTML = pre + text;
          }

<?php     if( $this->getResizeRow() ){ ?>
          function resizeY(){
            var divA = document.getElementById("divA");
            var divB = document.getElementById("divB");
            var div1 = document.getElementById("div1");
            var div2 = document.getElementById("div2");
            var tab1 = document.getElementById("tab1");
            var tab2 = document.getElementById("tab2");
            var fix1 = document.getElementById("fix1");
            var fix2 = document.getElementById("fix2");

            var max = 440 - fix1.offsetHeight - fix2.offsetHeight;
            var h1 = tab1.offsetHeight;
            var h2 = tab2.offsetHeight;
            if( h1 + h2 > max ){
              if( h1 < max / 2 ){
                h2 = max - h1;
              }
              else if( h2 < max / 2 ){
                h1 = max - h2;
              }
              else{
                h1 = max / 2;
                h2 = max / 2;
              }
            }
            divA.style.height = h1 + 'px';
            div1.style.height = h1 + 'px';
            divB.style.height = h2 + 'px';
            div2.style.height = h2 + 'px';
            divA.style.display = '';
            var myScrollbar1 = new UvumiScrollbar('div1');
            var myScrollbar2 = new UvumiScrollbar('div2');
          }
<?php     } ?>

          function setMessageToRead() {
<?php if( !$this->ShowMode ) { ?>
            var image = $('messimg');
            if( image != null )
              image.src = 'images/menu/futar.png';
<?php } ?>
          }

          function setLogToRead() {
<?php if( !$this->ShowMode ) { ?>
            var image = $('logimg');
            if( image != null )
              image.src = 'images/menu/uzenetek.png';
<?php } ?>
          }

          function putIntoField(field, value) {
            var element = document.getElementById(field);
            if( element == null ){
              var elements = document.getElementsByName(field);
              if( elements != null && elements.length > 0 )
                element = elements[0];
            }

            if( element != null ){
              element.value = value;
            }
          }

          function show_option(num) {
            if( last_option > -1 )
              document.getElementById('row_' + last_option).style.display = 'none';
            document.getElementById('row_' + num).style.display = '';
            last_option = num;
          }

          function show_menu(num) {
            var item = $('menu' + num);
            new Fx.Tween(item, {duration:100, transition:Fx.Transitions.Sine.easeIn}).start('opacity', 0, 1);
          }

          function hide_all() {
            hide_menu(1);
            hide_menu(2);
            hide_menu(3);
            hide_menu(4);
//            hide_iframe();
          }

          function hide_menu(num) {
            var objectDiv = $('menu' + num);
            if( objectDiv == null ) return;
            if( objectDiv.getStyle('visibility') == 'hidden') return;
            new Fx.Tween(objectDiv, {duration:10, transition:Fx.Transitions.Sine.easeIn}).start('opacity', 1, 0);
          }

          var frameon = 0;
          var kvon = 0;

          function show_iframe(html, param, width, height) {
<?php if( $this->OldStyle == 1 || $this->OldStyleMain == 1 ){ ?>
            NewWin('ifr_'+html+'.html?' + param, 'title', width, height);
<?php } else { ?>
            var objectDiv  = $('info_frame');
            var objectIn   = $('info_frame_in');
            var objectImg  = $('info_frame_img');
            var objectTop  = $('info_frame_top');
            var objectMain = $('info_frame_main');
            if( objectIn.firstChild != null )
              objectIn.removeChild(objectIn.firstChild);
            var img = new Element('iframe', {'src': 'ifr_'+html+'.html?' + param, 'background-color': 'transparent', 'allowtransparency': 'true', 'frameborder': '0'});
            img.inject(objectIn);
            var width9 = width *0.9;
            var width05 = width *0.05;
            var height1 = height *0.1;
            var height9 = height *0.9;

            objectIn.firstChild.width = width *0.9;
            objectIn.firstChild.height = height-35;
            objectMain.style.width = width + 'px';
            objectMain.style.height = height + 'px';
            objectImg.style.width = width + 'px';
            objectImg.style.height = height + 'px';
            objectTop.style.width = width + 'px';
            objectTop.style.height = height1 + 'px';
            objectIn.style.width = width9 + 'px';
            objectIn.style.height = height9 + 'px';
            objectIn.style.top = height1 + 'px';
            objectIn.style.left = width05 + 'px';
            frameon = 1;

            new Fx.Tween(objectDiv, {duration:100, transition:Fx.Transitions.Sine.easeIn}).start('opacity', 0, 1);
<?php } ?>
          }
		  function show_upload(num) { show_iframe('upload','parid=' + num, 750, 550); }
		  function show_report(num,p) { show_iframe('report','parid=' + num+'&ch='+p, 750, 500); }
          function show_egyseg(num) { show_iframe('unit','parid=' + num, 750, 550); }
          function show_egyseg4(num) { show_iframe('unit4','parid=' + num, 550, 450); }
          function show_hero(num,p) { show_iframe('hero','parid=' + num+'&ch='+p, 550, 500); }
          function show_vari(num) { show_iframe('spell','parid=' + num, 450, 400); }
          function show_vari4(num) { show_iframe('spell4','parid=' + num, 550, 450); }
          function show_epulet(num) { show_iframe('building','parid=' + num, 800, 450); }
          function show_race(num) { show_iframe('race','parid=' + num, 800, 570); }
          function show_biglog(num,klan) { show_iframe('log','parid=' + num + '&klan=' + klan, 900, 500); }
          function show_bigmess(num) { show_iframe('bigmess','parid=' + num, 900, 450); }
		  function show_episodeinfo(num) { show_iframe('episodeinfo','parid=' + num, 600, 500); }
          function action_bontas(num) { show_iframe('demolish','parid=' + num, 400, 150); }
          function action_leszereles(num,ellen,kor) { show_iframe('disband','delete=1&parid=' + num + '&ellenfel=' + ellen + '&kor=' + kor, 400, 150); }
          function action_visszahivas(num,ellen,kor) { show_iframe('disband','delete=0&parid=' + num + '&ellenfel=' + ellen + '&kor=' + kor, 400, 150); }
		  function action_withdraw(num) { show_iframe('withdraw','parid=' + num , 400, 150); }
		  function action_herotur(num) { show_iframe('herotur','parid=' + num , 700, 400); }
          function action_torles(num) { show_iframe('delete','parid=' + num, 400, 150); }
          function action_futar(target, msgid) { show_iframe('message','parid=' + target + '&msgid=' + msgid, 600, 400); }
		  function action_log(target, logid) { show_iframe('message','parid=' + target + '&logid=' + logid, 600, 400); }
		  function action_bulklog(target, logids) {show_iframe('message','parid=' + target +'&logids=' + logids, 600, 400); }
          function action_note(noteid, orszid, clanid) { show_iframe('note','parid=' + noteid + '&orszid=' + orszid + '&clanid=' + clanid, 600, 500); }
		  function action_attack(target) { show_iframe('attack','parid=' + target, 600, 500); }
          function action_kimond(target, variid) { show_iframe('cast','chat=0&parid=' + target + '&variid=' + variid, 600, 400); }
          function action_ckimond(target, chatid, room) { show_iframe('cast','chat=1&parid=' + target + '&chatid=' + chatid + '&room=' + room, 600, 400); }
          function action_szavaz(id) { show_iframe('vote', 'parid='+id, 600, 500); }
          function action_klan() { show_iframe('clan', 'parid=0', 600, 600); }
          function action_forum(num, theme) { show_iframe('forum','parid=' + num + '&theme=' + theme, 400, 400); }
          function action_chat(nr) { show_iframe('chat','parid=' + nr, 600, 600); }
          function action_plan(room,nr) { show_iframe('plan','spellask=0&parid=' + nr + '&room='+room, 600, 600); }
          function action_planinfo(room,nr,info) { show_iframe('info','parid=' + nr + '&room='+room + '&infoid='+info, 600, 600); }
          function action_spellask(nr) { show_iframe('plan','spellask=1&parid=' + nr, 500, 200); }
		  function action_forumedit(num, wtype) { show_iframe('forumedit','parid=' + num + '&wtype=' + wtype, 400, 400); }
		  function action_multi() { show_iframe('multi','parid=0', 600, 500); }
		  
          function show_yesno(text, link1, link2, ans1, ans2, ans3) {
            if( typeof link2 == 'undefined' ) link2 = '';
            if( typeof ans1 == 'undefined' ) ans1 = '<?php Display("YES");?>';
            if( typeof ans2 == 'undefined' ) ans2 = '<?php Display("YES");?>';
            if( typeof ans3 == 'undefined' ) ans3 = '<?php Display("NO");?>';
            var objDiv = $('yesno');
            var objText = $('yesno_question');
            var objLink1 = $('yesno_yes1');
            var objLink2 = $('yesno_yes2');
            objText.innerHTML = text;
            objLink1.href = link1;
            if( link2 == '' )
              $('yesno-2').style.display = 'none';
            else{
              $('yesno-2').style.display = '';
              objLink2.href = link2;
            }
            $('yesno_yes1_m').innerHTML = ans1;
            $('yesno_yes2_m').innerHTML = ans2;
            $('yesno_no_m').innerHTML = ans3;

            new Fx.Tween(objDiv, {duration:100, transition:Fx.Transitions.Sine.easeIn}).start('opacity', 0, 1);
          }

          function hide_yesno() {
            var objDiv = $('yesno');
            if( objDiv.getStyle('visibility') == 'hidden') return;
            new Fx.Tween(objDiv, {duration:10, transition:Fx.Transitions.Sine.easeIn}).start('opacity', 1, 0);
          }

          function checkall(elem, pref) {
            var i = 0;
            while( true ){
              i++;
              var name = pref + i;
              var check = document.getElementById(name);
              if( check == null ) {
                break;
              }
              check.checked = elem.checked;
            }
          }

          function hide_iframe() {
            tiphide();
            var objectDiv = $('info_frame');
            if( objectDiv == null || objectDiv.getStyle('visibility') == 'hidden') return;
            new Fx.Tween(objectDiv, {duration:10, transition:Fx.Transitions.Sine.easeIn}).start('opacity', 1, 0);
            frameon = 0;
          }

          function show_pergamen(num) {
            var objectDiv = $('pergamen');
            if( objectDiv.getStyle('visibility') == 'hidden' )
              show_object(num);
            else
              hide_object(num);
          }

          function show_object(num) {
            var objectDiv = $('pergamen');
            new Fx.Tween(objectDiv, {duration:100, transition:Fx.Transitions.Sine.easeIn}).start('opacity', 0, 1);
          }

          function hide_object() {
            var objectImage = $('pergamen_image');
            if( objectImage.firstChild == null ) return;
            objectImage.firstChild.width = 400;
            window.setTimeout('remove_object()', 10);
            var objectDiv = $('pergamen');
            new Fx.Tween(objectDiv, {duration:10, transition:Fx.Transitions.Sine.easeIn}).start('opacity', 1, 0);
          }

          function remove_object() {
<?php /*
            var objectImage = $('pergamen_image');
            objectImage.removeChild(objectImage.firstChild);
*/ ?>
          }

          function hover_toggle_css(el, to_css, from_css) {
            $(el).toggleClass(to_css);
            if(from_css){
              $(el).toggleClass(from_css);
            }
          }

          var tick;
          var tick2;
          var s=<?php echo $this->ido; ?>;
          var s2 = s;
          var s3 = s;
          var nyit=0;

          function stop() {
            clearTimeout(tick);
<?php if( $this->isChat() ) {?>
            closeChat();
<?php }?>
          }

          function setTime(newtime) {
            s = newtime;
            s2 = s;
            s3 = s;
          }
          function usnotime() {
		  <?php if($this->EpisodeState==0 || $this->EpisodeState==1){ ?>
            tick=setTimeout("usnotime()",1000);
            s = s-1;
            s2 = s2-1;
            if( s <= 0 ) {
              s = 0;
<?php if( $this->isChat() ) {?>
              refreshTime();
<?php } else { ?>
              if( s2 < -30 && frameon == 0 ) {
                clearTimeout(tick);
                location.href = location.href;
              }
<?php } ?>
            }
            if( document.rclock != null ) {
              if( (s%60) < 10 )
                document.rclock.rtime.value=Math.floor(s/60)+":0"+(s%60);
              else
                document.rclock.rtime.value=Math.floor(s/60)+":"+(s%60);
            }
			<?php }?>
          }
          function NewWin(url, nev, width, height) {
		  if (typeof(ablak) != 'undefined' && ablak != null)
		  if(ablak.opener && !ablak.opener.closed)
		    ablak.resizeTo(width, height); 
            ablak=window.open(url,nev,"scrollbars=1,status=1,location=0,toolbar=0,menubar=0,resizable=1,height="+height+",width="+width);
            ablak.focus();
          }
<?php } else {/* MustLogin*/ ?>
          function hide_all() {}
		  function stop() {}
<?php } ?>

          function StartFirst(){
<?php     if( $this->getResizeRow() ){ ?> resizeY(); <?php } ?>
<?php     if( $this->logged ){ ?>
            usnotime();
<?php     } ?>
<?php     if( $this->IframeStart != "" ){ ?>
//            show_iframe('<?php echo $this->IframeStart;?>', 800, 60);
//            tick2=setTimeout("hide_iframe()", 5000);
            tick2=setTimeout("document.getElementById('szabi').style.display='none'", 5000);
<?php     } ?>
<?php  if(!strpos($_SERVER['PHP_SELF'],"register.html")) { ?>
            set_tooltips();
<?php } ?>
          }
          function stopShow(){
            clearTimeout(tick2);
            document.getElementById('szabi').style.display='none';
          }
	/*	  <?php if($this->logged ) {?>///inaktiv kepernyo esemenyei
		  var inactivtime=0;
		  var inactivtimer;
		  function handleEvent(oEvent) {
			inactivtime=0;
            //   document.getElementById("log").innerHTML+= "\n" + oEvent.type;
          }
		  function inactivcounter(){
			inactivtime++;
			//document.getElementById("log").innerHTML= inactivtime;
			if(inactivtime>=60){
				clearTimeout(inactivtimer); stop();//window.location='exit.html';
				document.getElementById("inactivpane").style.display='block';
			}
			else
				inactivtimer=setTimeout("inactivcounter()", 1000);
		  }
		  inactivtimer=setTimeout("inactivcounter()", 1000);
			<?php }?> */
			
/*
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-64367716-1', 'auto');
  ga('send', 'pageview');
*/

        </script>
<?php   $this->headerscript(); ?>
      </head>
<?php if( $this->OldStyle == 0 ) { ?>
      <body onload="StartFirst()" onunload="stop()" onclick="hide_all()" style="BORDER-BOTTOM: 0px; TEXT-ALIGN: center; BORDER-LEFT: 0px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; BACKGROUND: url(images/UresAlap<?php echo $this->Size;?>.jpg) #000 no-repeat center top; HEIGHT: 100%; VERTICAL-ALIGN: bottom; BORDER-TOP: 0px; BORDER-RIGHT: 0px; PADDING-TOP: 0px; overflow:auto;" >
<?php } else { ?>
      <body onload="StartFirst()" onunload="stop()" leftmargin="0" marginheight="0" marginwidth="0" topmargin="0">
<?php } ?>
	<!--  <?php if($this->logged) {?>//inaktiv kepernyo 
		<div id="inactivpane" style="display:none;position:absolute;cursor:pointer;z-index:1000;background-color: black; height : 100%; width : 100%; " onclick="window.location=location.href;">
		<div style=" width: 400px; height:100px;margin:auto;"><font style="color:white;font-size:30px;">Alvó mód ...</font><br><font style="color:white;font-size:18px;">Katt az ébredéshez.</font></div></div>
	  <?php }?> -->
      <div class="<?php echo $image; ?>"  >
	  <?php //inaktiv kepernyo if($this->logged ) echo 'onmouseover="handleEvent(event)" onmouseout="handleEvent(event)" onmousedown="handleEvent(event)" onmouseup="handleEvent(event)" onclick="handleEvent(event)" ondblclick="handleEvent(event)"'; ?> 
	  <?php //inaktiv kepernyo if($this->logged ) echo '<span id="log"></span>'; ?>
<?php /*  if( $usemap != "" ) $this->usemap(); */ ?>
        <div class="dmain">
          <div class="dtop">
            <table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
              <tr style="height:10px;" valign="top" id="tr1">
                <td></td>
              </tr>
<?php   if( $this->logged && !$this->isChat() && !$this->isTurnChange() ){ ?>
              <tr>
                <td align="left" width="40%" >
                  <table cellspacing="0" cellpadding="0" class="tabres" border="0">
                    <tr valign="center" align="right">
                      <td width="20" nowrap></td>
                      <td><b><?php echo $this->Nev . " (ID: " . $this->ShowID . ", #" . $this->Number . ")"; ?></b></td>
                      <td width="25" nowrap></td>
                      <td><b><?php echo GetTextStr("race", $this->Race); ?></b></td>
					  <td width="25" nowrap></td>
					  <?php /*epizodkivetel2021.02.27 <td BACKGROUND="images/baberk2.png" class="tipz" title="tipdefault::59::59" style="cursor:pointer;width:30px;height:30px;" align="center" onclick="location.href='episode.html'"><b><?php echo $this->EpisodeID; ?></b></td>*/?>
                    </tr>
                  </table>
                </td>
                <td align="<?php echo ($this->OldStyle == 0 ? 'right' : 'left');?>" width="60%" >
                  <table cellspacing="0" cellpadding="0" class="tabres" border="0" width="100%" nowrap>
                    <tr valign="center" align="left">
                      <td><div class="tipz" title="tipgold::0::0" align="center"><img alt="" src="images/resources/res_gold.png"/><br><?php echo formatNum($this->Penz); ?></div></td>
                      <td width="10" nowrap></td>
                      <td><div  class="tipz" title="tipfood::1::1" align="center"><img alt="" src="images/resources/res_food.png"/><br><?php echo formatNum($this->Kaja); ?></div></td>
                      <td width="10" nowrap></td>
                      <td><div  class="tipz" title="tipwood::2::2" align="center"><img alt="" src="images/resources/res_wood.png"/><br><?php echo formatNum($this->Fa); ?></div></td>
                      <td width="10" nowrap></td>
                      <td><div  class="tipz" title="tipstone::3::3" align="center"><img alt="" src="images/resources/res_stone.png"/><br><?php echo formatNum($this->Ko); ?></div></td>
                      <td width="10" nowrap></td>
                      <td><div  class="tipz" title="tipmetal::4::4" align="center"><img alt="" src="images/resources/res_metal.png"/><br><?php echo formatNum($this->Fem); ?></div></td>
                      <td width="10" nowrap></td>
                      <td><div  class="tipz" title="tipmana::5::5" align="center"><img alt="" src="images/resources/res_mana.png"/><br><?php echo formatNum($this->Mana); ?></div></td>
                      <td width="10" nowrap></td>
                      <td><div  class="tipz" title="tipland::6::6" align="center"><img alt="" src="images/resources/res_land.png"/><br><?php echo formatNum($this->Fold); ?></div></td>
                      <td width="10" nowrap></td>
                      <td><div  class="tipz" title="tipxp::7::7" align="center"><img alt="" src="images/resources/res_XP.png"/><br><?php echo formatNum($this->XP); ?></div></td>
                      <td width="10" nowrap></td>
                      <td><div  class="tipz" title="tiptrust::8::8" align="center"><img alt="" src="images/resources/res_trust.png"/><br><?php echo formatNum(GetFeeling($this->ID)); ?></div></td>
                      <td width="10" nowrap></td>
                      <td><div  class="tipz" title="tipluck::9::9" align="center"><img alt="" src="images/resources/res_luck.png"/><br><?php echo DisplayMoral(1,$this->Luck, 1); ?></div></td>
                      <td width="10" nowrap></td>
                      <td><div  class="tipz" title="tipturn::10::10" align="center"><img alt="" src="images/resources/elapsed_turns.png"/><br><?php echo formatNum($this->TurnCounter); ?></div></td>
                      <td width="10" nowrap></td>
                      <td><div class="tipz" title="tiptime::11::11" align="center"><img alt="" src="images/resources/time_left.png"/><br>
                        <form name="rclock">~<input class="time" type="text" name="rtime" readonly size="5" noshade></form>
                      </div></td>
                      <td width="20" nowrap></td>
                    </tr>
                  </table>
                </td>
              </tr>
<?php
			if($this->OldStyle == 1 && $this->EpisodeState==2){//lezart epizod figyelmeztetes
			echo '<tr><td colspan="2"><div align="center"><font size="+2" color="red">'.LocStr('EPISODEARCHIVE').'</font></div></td></tr>';
		  }
		  if($this->OldStyle == 1 && $this->EpisodeState==3){//lezart epizod figyelmeztetes
			echo '<tr><td colspan="2"><div align="center"><font size="+2" color="red">'.GetTextStr('EPISODERESERV',1).'</font></div></td></tr>';
		  }
		 /* szavazas ki 2021.12.11
		 if($this->OldStyle == 1 ){//kotelezo szavazas emlekezteto
			 $eredx=query("SELECT if(szsz.orsz_id is NULL, 0,1) as voted FROM Szavazasok sz left join szavazas_szavazat szsz on szsz.szavazas_id=sz.id and szsz.orsz_id=@1 where sz.mandatory=1  and sz.`lejarat`>now()",$this->ID);
			  if($rowx=mysqli_fetch_object($eredx))
				if($rowx->voted==0)
					echo '<tr><td colspan="2"><div align="center"><font size="+2" color="red">'.LocStr('MUSTANSVER').'</font></div></td></tr>';
		  }*/
		  
          if( $this->OldStyle == 1 && $this->Szabadsag != 0 ){
            if( $this->Szabadsag > 0 )
              $reason = LocStr('ON_VACATION');
            else
              $reason = LocStr('UNDER_PROTECTION');
            $turn = abs($this->Szabadsag) - ($this->Szabadsag > 0);
			if($this->Szabadsag==1 && $this->Activated==0){
				$eredx=query("SELECT TIMESTAMPDIFF(SECOND, now(), EndDate)  as dd FROM multi_reward_log WHERE orsz_id=@1 and EndDate>now() limit 1",$this->ID);
				$rowx=mysqli_fetch_object($eredx);
				$text=GetTextStr("MULTI",1).' <span id="ddtime">'.gmdate("H:i:s", $rowx->dd).'</span><script type="text/javascript"> var stime='.$rowx->dd.'; var t;
		function startTime(){
			stime--;
			showTime();
			if(stime==0)
				clearTimeout(t);
			else
				t=setTimeout("startTime();",1000);
				
		}
		function showTime(){
			var h,m,s;
			if(stime>3600) h=Math.floor(stime/3600);
			else h=0;
			m=Math.floor((stime-(h*3600))/60);
			s=stime-(h*3600)-(m*60);
			document.getElementById("ddtime").innerHTML=checkTime(h)+":"+checkTime(m)+":"+checkTime(s);
		}
		function checkTime(i){
			if (i<10){  i="0" + i;}
			return i;
		}startTime();
		</script >';
				}
			else
				$text = LocStr('YOUR_COUNTRY_IS_^1_FOR_^2_TURNS', $reason, $turn);
?>
              <tr>
                <td colspan="2"><div align="center"><font size="+2" color="red"><?php echo $text;?>
<?php       if( $this->Szabadsag < 0 ) { ?>
                  <br><a href="javascript:void(0)" onclick="if( confirm('<?php Display("SUREENDPROT");?>') ) location.href='<?php echo $_SERVER['PHP_SELF']; ?>?prot_end=1';"><?php Display("ENDPROT");?></a>
<?php       } else if( $this->Szabadsag == 1) {if( $this->Activated!=0) { ?>
                  <br><a href="<?php echo $_SERVER['PHP_SELF'];?>?vac_end=1"><?php Display("PROTEND");?></a>
<?php			}else{ $eredm = query( "SELECT count(ID) as cc FROM multi_appeals WHERE Orsz_ID='@1' AND Approved=0", $this->ID );
				  if( $rowm = mysqli_fetch_object($eredm) )if($rowm->cc==0){?>
						<a href="javascript:action_multi();"><?php Display("MULTI");?></a>
<?php       } }}?>
                </font></div></td>
              </tr>
<?php
          }
		  
        } else {
          $this->buttontop();
        }
?>
            </table>
<?php if( $this->OldStyle == 1 ){ ?>
              <hr noshade size="2">
<?php } ?>
          </div>
<?php if( $this->OldStyle == 0 && $this->Form == 0 ){ ?>
          <div class="dcenter">
<?php
    }
  }

  public function body(){
    echo "parent::body<br>";
  }
   public function getNavigationArray(){
	   
	    $menuArray = array();
	    $ered = query2("SELECT Menu_ID FROM menu_order  WHERE Orsz_ID=$this->ID AND IsActive=1 ORDER BY Priority ASC");	  
	  
	    if( mysqli_num_rows($ered)==0){
			$ered = query2("SELECT Menu_ID FROM menu_order  WHERE Orsz_ID=0 AND IsActive=1 ORDER BY Priority ASC");	  
		}
			$i=0;
			 while( $row = mysqli_fetch_object($ered) ) {
	            $menuArray[$i]=$row->Menu_ID;
			 $i++;
			}
		
		// if(count($menuArray) == 0)
			 //$menuArray[0]=1;
		return $menuArray;
   }
  
  public function getCurrentPage(){
	    $currentPage="tronterem";
		//error_log("$".$_SERVER['PHP_SELF']."$",0);
	   switch( $_SERVER['PHP_SELF']){
		   case "/tronterem.html":
		   $currentPage=1;
		   break;		  
		    case "/osszesites.html":
			 $currentPage=2;
		   break;
		    case "/epulet.html":
			 $currentPage=3;
		   break;
		    case "/egysegek.html":
			 $currentPage=4;
		   break;
		    case "/varazslatok.html":
			 $currentPage=5;
		   break;
		    case "/amulettek.html":
			 $currentPage=6;
		   break;
		    case "/piac.html":
			 $currentPage=7;
		   break;
		    case "/kredit.html":
			 $currentPage=8;
		   break;
		    case "/tanacsterem.html":
			 $currentPage=9;
		   break;
		    case "/klan.html":
			 $currentPage=10;
		   break;
		    case "/orszagok.html":
			 $currentPage=11;
		   break;
		    case "/jegyzet.html":
			 $currentPage=12;
		   break;
		    case "/bonusz.html":
			 $currentPage=13;
		   break;
		    case "/beallitas.html":
			 $currentPage=14;
		   break;
		    case "/spellaccept.html":
			 $currentPage=15;
		   break;		   
		    case "/prior.html":
			 $currentPage=16;
		   break;
		    case "/intezo.html":
			 $currentPage=17;
		   break;
		    case "/kocsma.html":
			 $currentPage=18;
		   break;
		 /*   case "/forum.html":
			 $currentPage=19;
		   break;*/
		 /*   case "/szavazas.html":
			 $currentPage=20;
		   break;*/
		    case "/news.html":
			 $currentPage=21;
		   break;
		    case "/tournament.html":
			 $currentPage=22;
		   break;
		    case "/statisztika.html":
			 $currentPage=23;
		   break;
		  /*  case "/linkek.html":
			 $currentPage=24;
		   break;*/
		    case "/sugo.html":
			 $currentPage=25;
		   break;
		    case "/gyik.html":
			 $currentPage=26;
		   break;
		    case "/varazstargy.html":
			 $currentPage=27;
		   break;
		/*    case "/old_stratega.html":
			 $currentPage=28;
		   break;*/
		    case "/hirnev.html":
			 $currentPage=29;
		   break;
		    case "/futar.html":
			 $currentPage=30;
		   break;		   
		    case "/log.html":
			 $currentPage=31;
		   break;
		    case "/episode.html":
			 $currentPage=2;//32; epizodkivetel 2021.02.27
		   break;
		   
		    default: 
			 $currentPage=1;
		   break;
		   
	   }
	   
	   return  $currentPage;
  }
  
   public function getPageByID($id){
         $Page="tronterem";	
		 
		 switch($id){
		   case 1:
		   $Page="tronterem";
		   break;
		  
		    case 2:
			 $Page="osszesites";
		   break;
		    case 3:
			 $Page="epulet";
		   break;
		    case 4:
			 $Page="egysegek";
		   break;
		    case 5:
			 $Page="varazslatok";
		   break;
		    case 6:
			 $Page="amulettek";
		   break;
		    case 7:
			 $Page="piac";
		   break;
		    case 8:
			 $Page="kredit";
		   break;
		    case 9:
			 $Page="tanacsterem";
		   break;
		    case 10:
			 $Page="klan";
		   break;
		    case 11:
			 $Page="orszagok";
		   break;
		    case 12:
			 $Page="jegyzet";
		   break;
		    case 13:
			 $Page="bonusz";
		   break;
		    case 14:
			 $Page="beallitas";
		   break;
		    case 15:
			 $Page="spellaccept";
		   break;
		    case 16:
			 $Page="prior";
		   break;
		    case 17:
			 $Page="intezo";
		   break;
		    case 18:
			 $Page="kocsma";
		   break;
		    case 19:
			 $Page="forum";		   		    
		   break;
		  /*  case 20:
			 $Page="szavazas";
		   break;*/
		    case 21:
			 $Page="news";
		   break;
		    case 22:
			 $Page="tournament";
		   break;
		    case 23:
			 $Page="statisztika";
		   break;
		   /* case 24:
			 $Page="linkek";
		   break;*/
		    case 25:
			 $Page="sugo";
		   break;
		    case 26:
			 $Page="gyik";
		   break;
		    case 27:
			 $Page="varazstargy";
		   break;
		 /*   case 28:
			 $Page="old_stratega";
		   break;*/
		    case 29:
			 $Page="hirnev";
		   break;
		    case 30:
			 $Page="futar";
		   break;
		    case 31:
			 $Page="log";
		   break;
		    case 32:
			 $Page="episode";
		   break;
		   
		    default: 
			 $Page="tronterem";
		   break;
		   
	   }
	  
	   return  $Page;
  }
  
   public function getTooltipByPageID($id){
         $Page="tipdefault::13::13";	
		 
		 switch($id){
		   case 1:
		   $Page="tipdefault::13::13";
		   break;
		  
		    case 2:
			 $Page="tipdefault::33::33";
		   break;
		    case 3:
			 $Page="tipdefault::14::14";
		   break;
		    case 4:
			 $Page="tipdefault::15::15";
		   break;
		    case 5:
			 $Page="tipdefault::43::43";
		   break;
		    case 6:
			 $Page="tipdefault::42::42";
		   break;
		    case 7:
			 $Page="tipdefault::16::16";
		   break;
		    case 8:
			 $Page="tipdefault::17::17";
		   break;
		    case 9:
			 $Page="tipdefault::18::18";
		   break;
		    case 10:
			 $Page="tipdefault::19::19";
		   break;
		    case 11:
			 $Page="tipdefault::20::20";
		   break;
		    case 12:
			 $Page="tipdefault::28::28";
		   break;
		    case 13:
			 $Page="tipdefault::29::29";
		   break;
		    case 14:
			 $Page="tipdefault::30::30";
		   break;
		    case 15:
			 $Page="tipdefault::43::60";
		   break;
		    case 16:
			 $Page="tipdefault::31::31";
		   break;
		    case 17:
			 $Page="tipdefault::32::32";
		   break;
		    case 18:
			 $Page="tipdefault::34::34";
		   break;
		    case 19:
			 $Page="tipdefault::35::35";
		   break;		   
		    case 20:
			 $Page="tipdefault::37::37";
		   break;
		    case 21:
			 $Page="tipdefault::38::38";
		   break;
		    case 22:
			 $Page="tipdefault::39::39";
		   break;
		    case 23:
			 $Page="tipdefault::41::41";
		   break;
		    case 24:
			 $Page="tipdefault::45::45";
		   break;
		    case 25:
			 $Page="tipdefault::46::46";
		   break;
		    case 26:
			 $Page="tipdefault::47::47";
		   break;
		    case 27:
			 $Page="tipdefault::44::44";
		   break;
		    case 28:
			 $Page="tipdefault::47::47";
		   break;
		    case 29:
			 $Page="tipdefault::40::40";
		   break;
		    case 30:
			 $Page="tipdefault::25::25";
		   break;
		    case 31:
			 $Page="tipdefault::26::26";
		   break;
		    case 32:
			 $Page="tipdefault::59::59";
		   break;
		   
		    default: 
			 $Page="tipdefault::13::13";
		   break;
		   
	   }
	  
	   return  $Page;
  }
  

  public function footer(){
/*	  
	  if($this->OldStyle == 0){
		$prew=0;
		$next=0;
		$NavArr=$this->getNavigationArray();
		//error_log(count($NavArr),0);
			$key = array_search($this->getCurrentPage(), $NavArr);
			//error_log($key,0);
			 if($key==0){
				$prew=count($NavArr)-1;
				if(count($NavArr)==1){				
					$next=0;
				}else{				
					$next=1;
				}
			}else if($key==count($NavArr)-1){
				$prew=$key-1;
				$next=0;
			}else{
				$prew=$key-1;
				$next=$key+1;
			}
			
			
			
				$i=0;
				while(($NavArr[$prew]==10 && $this->ClanRight==0)||(($NavArr[$prew]==22 || $NavArr[$prew]==6) && $this->HeroAmuletRight==0)|| ($NavArr[$prew]==27 && $this->ArtifactRight==0)){
					$prew--;
					if($prew<0)
						$prew=count($NavArr)-1;
					if($i==count($NavArr)-1)
						break;
					$i++;
					
				}
				$i=0;
				while(($NavArr[$next]==10 && $this->ClanRight==0)||(($NavArr[$next]==22 || $NavArr[$next]==6) && $this->HeroAmuletRight==0)|| ($NavArr[$next]==27 && $this->ArtifactRight==0)){
					$next++;
					if($next>count($NavArr)-1)
						$next=0;
					if($i==count($NavArr)-1)
						break;
					$i++;
					
				}
	  }
    if( $this->OldStyle == 0 && $this->Form == 0 ){?>
          </div>
<?php } ?>
          <div class="dbuttons">
<?php if( $this->Form == 0 ) { ?>
            <table cellspacing="0" cellpadding="0" width="95%" class="tabla" align="center">
              <tbody>
                <tr style="height:5px;" valign="top" id="tr6">
                  <td></td>
                </tr>
                <tr>
<?php   if( $this->logged && !$this->isChat() ){ ?>
                  <td width="50px"><td>
				   <td><A class="toplink" id="prew" href="<?php echo $this->getPageByID($NavArr[$prew]); ?>.html"><img alt="" class="tipz" title="<?php echo $this->getTooltipByPageID($NavArr[$prew]); ?>" src="images/csuszkabal.png" border="0"></A></td>
				    <td><A class="toplink" id="next" href="<?php echo $this->getPageByID($NavArr[$next]); ?>.html"><img alt="" class="tipz" title="<?php echo $this->getTooltipByPageID($NavArr[$next]); ?>" src="images/csuszkajobb.png" border="0"></A></td>
                  <td><A class="toplink" href="tronterem.html"><img alt="" class="tipz" title="tiphall::13::13" src="images/menu/tronterem.png" border="0"></A></td>
                  <td><A class="toplink" href="osszesites.html"><img alt="" class="tipz" title="tipover::33::33" src="images/menu/osszesites.png" border="0"></A><td>
				  <td><A class="toplink" href="javascript:show_menu(3)"><img alt="" class="tipz" title="tipbook3::61::61" src="images/menu/gyartas.png" border="0"></A></td>				  		  
                  <td><A class="toplink" href="piac.html"><img alt="" class="tipz" title="tipmarket::16::16" src="images/menu/piac.png" border="0"></A></td>
                  <td><A class="toplink" href="kredit.html"><img alt="" class="tipz" title="tipcredit::17::17" src="images/menu/kredit.png" border="0"></A></td>
                  <td><A class="toplink" href="tanacsterem.html"><img alt="" class="tipz" title="tipboard::18::18" src="images/menu/tanacsterem.png" border="0"></A></td>
                  <?php if($this->ClanRight>0){ ?><td><A class="toplink" href="klan.html"><img alt="" class="tipz" title="tipclan::19::19" src="images/menu/klanok.png" border="0"></A></td><?php } ?>
                  <td><A class="toplink" href="orszagok.html"><img alt="" class="tipz" title="tipcountry::20::20" src="images/menu/orszaglista.png" border="0"></A></td>
                  <td><A class="toplink" href="javascript:show_menu(1)"><img alt="" class="tipz" title="tipbook1::21::21" src="images/menu/book_set.png" border="0"></A></td>
                  <td><A class="toplink" href="javascript:show_menu(2)"><img alt="" class="tipz" title="tipbook2::22::22" src="images/menu/<?php echo ($this->NewVox > 0 || $this->NewForum > 0 ? 'book_all.gif' : 'book_all.png');?>" border="0"></A></td>                                    
                  <td><A class="toplink" href="javascript:show_menu(4)"><img alt="" class="tipz" title="tipbook4::24::24" src="images/menu/book_info.png" border="0"></A></td>
				  <td><A class="toplink" href="hirnev.html"><img alt="" class="tipz" title="tiprank::40::40" src="images/menu/hirnev.png" border="0"></A></td>	
                  <td><A class="toplink" href="futar.html"><img alt="" id="messimg" class="tipz" title="tipmessage::25::25" src="images/menu/<?php echo ($this->NewMess > 0 ? 'futar.gif' : 'futar.png');?>" border="0"></A></td>
                  <td><A class="toplink" href="log.html"><img alt="" id="logimg" class="tipz" title="tiplog::26::26" src="images/menu/<?php echo ($this->NewLog > 0 ? 'uzenetek.gif' : 'uzenetek.png');?>" border="0"></A></td>
                  <td ><A class="toplink" style="vertical-align:middle" href="chat.html" target="_blank"><img alt="" style="vertical-align:middle" class="tipz" title="tipchat::36::36" src="images/menu/chat.png" border="0"/>
				  <?php  if($this->ChatAdmin) echo '<font style="color:red;">('.$this->ChatNumber.')</font>'; else echo '('.$this->ChatNumber.')'; ?> </A><td>                  <td width="50px"><td>
                  <!--td><A class="toplink" href="login.html" title="<?php Display("EXIT");?>"><img alt="" class="tipz" title="tipexit::27::27" src="images/kilepes.png" border="0"></A></td-->
                  <td><A class="toplink" href="exit.html" title="<?php Display("EXIT");?>"><img alt="" class="tipz" title="tipexit::27::27" src="images/kilepes.png" border="0"></A></td>
<?php
        } else {
          $this->buttonbottom();
        }
?>
                </tr>
              </tbody>
            </table>
<?php } else { ?>
            <table cellspacing="0" cellpadding="0" width="100%" class="tabmenu" align="left">
              <tbody align="left">
                <tr style="height:5px;" valign="top" id="tr6">
                  <td></td>
                </tr>
<?php   if( $this->logged && !$this->isChat() ){ ?>
				 <tr>
				 <td>&nbsp;&nbsp;<A id="prew" class="toplink" href="<?php echo $this->getPageByID($NavArr[$prew]); ?>.html"><img alt="" class="tipz" title="<?php echo $this->getTooltipByPageID($NavArr[$prew]); ?>" src="images/csuszkabal.png" border="0"></A>&nbsp;&nbsp;&nbsp;&nbsp;<A class="toplink" id="next" href="<?php echo $this->getPageByID($NavArr[$next]); ?>.html"><img alt="" class="tipz" title="<?php echo $this->getTooltipByPageID($NavArr[$next]); ?>" src="images/csuszkajobb.png" border="0"></A></td>
				 <td></td></tr>
                <tr><td><A class="toplink" href="tronterem.html"><img alt="" width="16" height="16" class="tipz" title="tiphall::13::13" src="images/menu/tronterem.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 1); ?></A></td></tr>
                <tr><td><A class="toplink" href="osszesites.html"><img alt="" width="16" height="16" class="tipz" title="tipover::33::33" src="images/menu/osszesites.png" border="0"/>&nbsp;<?php  echo GetTextStr("MENU", 2); ?></A><td></tr>
				<tr><td><A class="toplink" href="javascript:show_menu(3)"><img alt="" width="16" height="16" class="tipz" title="tipbook3::61::61" src="images/menu/gyartas.png" border="0"/>&nbsp;<?php Display("NAVPRODUCTION");?>...</A></td></tr>    				
                <tr><td><A class="toplink" href="piac.html"><img alt="" width="16" height="16" class="tipz" title="tipmarket::16::16" src="images/menu/piac.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 7); ?></A></td></tr>
                <tr><td><A class="toplink" href="kredit.html"><img alt="" width="16" height="16" class="tipz" title="tipcredit::17::17" src="images/menu/kredit.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 8); ?></A></td></tr>
                <tr><td><A class="toplink" href="tanacsterem.html"><img alt="" width="16" height="16" class="tipz" title="tipboard::18::18" src="images/menu/tanacsterem.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 9); ?></A></td></tr>
                <?php if($this->ClanRight>0) {?><tr><td><A class="toplink" href="klan.html"><img alt="" width="16" height="16" class="tipz" title="tipclan::19::19" src="images/menu/klanok.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 10); ?></A></td></tr><?php }  ?>
                <tr><td><A class="toplink" href="orszagok.html"><img alt="" width="16" height="16" class="tipz" title="tipcountry::20::20" src="images/menu/orszaglista.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 11); ?></A></td></tr>
                <tr><td><A class="toplink" href="javascript:show_menu(1)"><img alt="" width="16" height="16" class="tipz" title="tipbook1::21::21" src="images/menu/book_set.png" border="0"/>&nbsp;<?php Display("NAVBOOK1");?></A></td></tr>
                <tr><td><A class="toplink" href="javascript:show_menu(2)"><img alt="" width="16" height="16" class="tipz" title="tipbook2::22::22" src="images/menu/<?php echo ($this->NewVox > 0 || $this->NewForum > 0  ? 'book_all.gif' : 'book_all.png');?>" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 19); ?>...</A></td></tr>				            
                <tr><td><A class="toplink" href="javascript:show_menu(4)"><img alt="" width="16" height="16" class="tipz" title="tipbook4::24::24" src="images/menu/book_info.png" border="0"/>&nbsp;<?php Display("NAVBOOK4");?></A></td></tr>
				<tr><td><A class="toplink" href="hirnev.html"><img alt="" width="16" height="16" class="tipz" title="tiprank::40::40" src="images/menu/hirnev.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 29); ?></A></td></tr>
                <tr><td><A class="toplink" href="futar.html"><img alt="" id="messimg" width="16" height="16" class="tipz" title="tipmessage::25::25" src="images/menu/<?php echo ($this->NewMess > 0 ? 'futar.gif' : 'futar.png');?>" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 30); ?></A></td></tr>
                <tr><td><A class="toplink" href="log.html"><img alt="" id="logimg" width="16" height="16" class="tipz" title="tiplog::26::26" src="images/menu/<?php echo ($this->NewLog > 0 ? 'uzenetek.gif' : 'uzenetek.png');?>" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 31); ?></A></td></tr>
                <tr><td><A class="toplink" href="chat.html" target="_blank"><img alt="" width="16" height="16" class="tipz" title="tipchat::36::36" src="images/menu/chat.png" border="0"/>&nbsp;<?php Display("NAVCHAT"); if($this->ChatAdmin) echo ' <font style="color:red;">('.$this->ChatNumber.')</font>'; else echo ' ('.$this->ChatNumber.')'; ?></A><td></tr>
                <tr><td>&nbsp;<td></tr>
                <tr><td><A class="toplink" href="exit.html" title="<?php Display("EXIT");?>"><img alt="" width="16" height="16" class="tipz" title="tipexit::27::27" src="images/kilepes.png" border="0"/>&nbsp;<?php Display("EXIT");?></A></td></tr>
<?php
        } else {
          $this->buttonbottom();
        }
?>
              </tbody>
            </table>
<?php } ?>
          </div>
          <font color="white" id="trace"></font>
          <DIV style="VISIBILITY: hidden" id="menu1" allowtransparency="true" onclick="hide_menu(1);">
            <DIV id="menu1_image" allowtransparency="true">
              <img alt="" src="images/tekercs1.png"  width="100%" height="100%">
              <DIV class="menudiv">
                <A class="tipz" title="tipbook1::28::28" href="jegyzet.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 12); ?></span></A><br>
                <A class="tipz" title="tipbook1::29::29" href="bonusz.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 13); ?></span></A><br>
                <A class="tipz" title="tipbook1::30::30" href="beallitas.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 14); ?></span></A><br>
				<A class="tipz" title="tipbook3::43::60" href="spellaccept.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 15); ?></span></A><br>
                <A class="tipz" title="tipbook1::31::31" href="prior.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 16); ?></span></A><br>
                <A class="tipz" title="tipbook1::32::32" href="intezo.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 17).$this->IntezoText;?></span></A><br>
				
              </DIV>
            </DIV>
          </DIV>
          <DIV style="VISIBILITY: hidden" id="menu2" allowtransparency="true" onclick="hide_menu(2);">
            <DIV id="menu2_image" allowtransparency="true">
              <img alt="" src="images/tekercs1.png"  width="100%" height="100%">
              <DIV class="menudiv">
                <!--A class="tipz" title="tipbook2::33::33" href="osszesites.html" target="_top"><?php echo GetTextStr("MENU", 2); ?></A><br-->
                <A class="tipz" title="tip18::34::34" href="kocsma.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 18); ?></span> <img alt="" src="images/18.png" border="0" width="12" height="12"></A><br>
                <A class="tipz" title="tip18::35::35" href="forum.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 19); ?></span> <img alt="" src="images/<?php if($this->NewForum > 0) echo 'menu/book_all.gif'; else echo '18.png'; ?>" border="0" width="12" height="12"></A><br>
                <A class="tipz" title="tipchat::36::36" href="chat.html" target="_blank"><span class="menutext"><?php Display("NAVCHAT");  if($this->ChatAdmin) echo ' <font style="color:red;">('.$this->ChatNumber.')</font>'; else echo ' ('.$this->ChatNumber.')'; ?></span> <img alt="" src="images/18.png" border="0" width="12" height="12"></A><br>
                <A class="tipz" title="tipbook2::37::37" href="szavazas.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 20); ?></span><?php if($this->NewVox > 0){ ?><img alt="" src="images/menu/book_all.gif" border="0" width="12" height="12"><?php } ?></A><br>
                <A class="tipz" title="tipbook2::38::38" href="news.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 21); ?></span></A><br>
                <?php if($this->HeroAmuletRight>0) {?><A class="tipz" title="tipbook2::39::39" href="tournament.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 22); ?></span></A><br>  <?php }?>             
                <A class="tipz" title="tipbook2::41::41" href="statisztika.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 23); ?></span></A><br>
              </DIV>
            </DIV>
          </DIV>
          <DIV style="VISIBILITY: hidden" id="menu3" allowtransparency="true" onclick="hide_menu(3);">
            <DIV id="menu3_image" allowtransparency="true">
              <img alt="" src="images/tekercs1.png"  width="100%" height="100%">
              <DIV class="menudiv">
			  <A class="tipz" title="tipbuild::14::14"  href="epulet.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 3); ?></span></A><br>
			  <A class="tipz" title="tipunits::15::15"  href="egysegek.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 4); ?></span></A><br>
			  <A class="tipz" title="tipbook3::43::43" href="varazslatok.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 5); ?></span></A><br>
              <?php if($this->HeroAmuletRight>0) {?><A class="tipz" title="tipbook3::42::42" href="amulettek.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 6); ?></span></A><br><?php }?>
             							
								  
              </DIV>
            </DIV>
          </DIV>
          <DIV style="VISIBILITY: hidden" id="menu4" allowtransparency="true" onclick="hide_menu(4);">
            <DIV id="menu4_image" allowtransparency="true">
              <img alt="" src="images/tekercs1.png"  width="100%" height="100%">
              <DIV class="menudiv">
                <A class="tipz" title="tipbook4::45::45" href="linkek.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 24); ?></span></A><br>
                <A class="tipz" title="tipbook4::46::46" href="sugo.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 25); ?></span></A><br>
                <A class="tipz" title="tipbook4::47::47" href="gyik.html" target="_top"><span class="menutext"><?php  echo GetTextStr("MENU", 26); ?></span></A><br>
				 <?php if($this->ArtifactRight>0) {?><A class="tipz" title="tipbook3::44::44" href="varazstargy.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 27); ?></span></A><br><?php }?>	
				 <A class="tipz" title="tipbook4::62::62" href="old_stratega.html" target="_top"><span class="menutext"><?php echo GetTextStr("MENU", 28); ?></span></A><br>
                <A class="tipz" title="tipbook4::48::48" href="mailto:stratega.contact@gmail.com" target="_top"><span class="menutext"><?php Display("NAVEMAIL");?></span></A><br>
              </DIV>
            </DIV>
          </DIV>
<?php */?>


          <DIV style="VISIBILITY: hidden;" id="info_frame" allowtransparency="true">
            <DIV id="info_frame_main" style="cursorX: move;">
              <img alt="" id="info_frame_img" src="images/tekercs1.png"  width="100%" height="100%">
              <DIV id="info_frame_top" style="position: absolute; top: 0px; cursor: move; background-image:url(images/semmi.png); background-repeat: repeat;width:100%" ></DIV>
              <DIV id="info_frame_in" style="position: absolute; top: 35px;"></DIV>
            </DIV>
          </DIV>
          <DIV style="VISIBILITY: hidden;" id="yesno" allowtransparency="true">
            <DIV id="yesno_main" allowtransparency="true" style="cursor: move;">
              <img alt="" src="images/pergamenYN.png" width="100%" height="100%">
              <DIV style="position: absolute; top: 25px; left:0px; width:100%; height:70%; text-align:center;">
                <table align="center" style="width:90%;height:100%;"><tr valign="middle"><td>
                  <table align="center" cellspacing="3" width="100%">
                    <tr>
                      <td align="center"><span id="yesno_question" class="szoveg"> </span></td>
                    </tr>
                    <tr><td><table align="center" style="border-spacing: 10px 0;"><tr>
                      <td id="yesno-1"><div align="right"><?php Button("yesno_yes1", /*"<img alt="" src='images/pipajel.png' border='0' width=12 height=12 />".LocStr("YES")*/ "YES", false, "", "", "", "X"); ?></div></td>
                      <td id="yesno-2"><div align="right"><?php Button("yesno_yes2", /*"<img alt="" src='images/pipajel.png' border='0' width=12 height=12 />".LocStr("YES")*/ "YES", false, "", "", "", "X"); ?></div></td>
                      <td id="yesno-3"><?php Button("yesno_no", /*"<img alt="" src='images/kilepes.png' border='0' width=12 height=12 />".LocStr("NO")*/ "NO", false, "", "javascript:hide_yesno()"); ?></td>
                    </tr></table></td></tr>
                  </table></td></tr>
                </table>
              </DIV>
            </DIV>
          </DIV>
<?php 

if( $this->logged && !$this->isChat() && $this->IsActived==0){//ha nincs aktivalva
			$eredx = query2("SELECT email FROM Player WHERE ID=@1", $this->ID);
			if( $rowx = mysqli_fetch_object($eredx) ) $email=$rowx->email; else $email='';
			echo '<DIV id="activdiv" allowtransparency="true">
            <DIV id="activdiv_main">
              
              <DIV id="activdiv_body" style="position: absolute; top: 15px; left:0px; background-color: red; text-align:center;padding:0px 30px;">
			  <form name="activateform" id="activateform" method="post">
                <table align="center" cellspacing="5" style="margin:0;">
                  <tr>
                    <td class="szovegBig" style="color:white">'.LocStr('ACTIVATE_ALERT').'</td>
					</tr><tr>
					<td ><input  type="text" id="activreemail" name="activreemail" value="'.$email.'"/>';
					switch($this->isactivatelinksend){
						case 0: Button("resendbt", "ACTIVATE_RESEND", true, "activateform", "=1", "resend"); break;
						case 1: echo '<span class="error" style="margin-left:5px;">'.LocStr('ACTIVATE_SENT').'</span>'; break;
						case 2: echo '<span class="error" style="margin-left:5px;">'.LocStr('WRONGMAIL').'</span>';  Button("resendbt", "ACTIVATE_RESEND", true, "activateform", "=1", "resend"); break;
					}				
					echo '</td>
                  </tr>
                </table>
				</form>
              </DIV>
            </DIV>
          </DIV>';
		  }
		  /*
if( $this->logged && !$this->isChat() && $this->EpisodeState==2){//lezart epizod figyelmeztetes
			echo '<DIV id="eparch" allowtransparency="true">
            <DIV id="eparch_main" allowtransparency="true" style="cursor: move;">
              <img alt="" src="images/tekercs3.png" width="100%" height="100%">
              <DIV id="eparch_body" style="position: absolute; top: 15px; left:0px; width:100%; text-align:center">
                <table align="center" cellspacing="5">
                  <tr>
                    <td class="szovegBig">'.LocStr('EPISODEARCHIVE').'</td>
                  </tr>
                </table>
              </DIV>
            </DIV>
          </DIV>';
		  }
if( $this->logged && !$this->isChat() && $this->EpisodeState==3){//jelenkezheto epizod figyelmeztetes
			echo '<DIV id="eparch" allowtransparency="true">
            <DIV id="eparch_main" allowtransparency="true" style="cursor: move;">
              <img alt="" src="images/tekercs3.png" width="100%" height="100%">
              <DIV id="eparch_body" style="position: absolute; top: 15px; left:0px; width:100%; text-align:center">
                <table align="center" cellspacing="5">
                  <tr>
                    <td class="szovegBig">'.GetTextStr('EPISODERESERV',1).'</td>
                  </tr>
                </table>
              </DIV>
            </DIV>
          </DIV>';
		  }*/
		/*  if( $this->logged && !$this->isChat() ){//kotelezo szavazas emlekezteto
			 $eredx=query("SELECT if(szsz.orsz_id is NULL, 0,1) as voted FROM Szavazasok sz left join szavazas_szavazat szsz on szsz.szavazas_id=sz.id and szsz.orsz_id=@1 where sz.mandatory=1  and sz.`lejarat`>now()",$this->ID);
			 if($rowx=mysqli_fetch_object($eredx))
				if($rowx->voted==0)
					echo '<DIV id="eparch" allowtransparency="true">
            <DIV id="eparch_main" allowtransparency="true" style="cursor: move;">
				<DIV id="eparch_body" style="position: absolute; top: 15px; left:0px; background-color: red; text-align:center;padding:0px 30px;">
                <table align="center" cellspacing="5">
                  <tr>
                    <td class="szovegBig">'.LocStr('MUSTANSVER').'</td>
                  </tr>
                </table>
              </DIV>
            </DIV>
          </DIV>';
		  }*/
        if( $this->logged && (($this->Szabadsag != 0 && !$this->isChat()) || $this->IframeStart != "") ) {
			
		  
          $textclass = "szovegBig";
          $imgBack = "tekercs3.png";
          $posit = "15px";
          $align = "center";
          if( $this->Szabadsag != 0 ){
            if( $this->Szabadsag > 0 )
              $reason = LocStr('ON_VACATION');
            else
              $reason = LocStr('UNDER_PROTECTION');
            $turn = abs($this->Szabadsag) - ($this->Szabadsag > 0);  
			if($this->Szabadsag==1 && $this->Activated==0){
				$eredx=query("SELECT   TIMESTAMPDIFF(SECOND, now(), EndDate)  as dd FROM multi_reward_log WHERE orsz_id=@1 and EndDate>now() limit 1",$this->ID);
				$rowx=mysqli_fetch_object($eredx);
				$text=GetTextStr("MULTI",1).' <span id="ddtime">'.gmdate("H:i:s", $rowx->dd).'</span>
				<script type="text/javascript"> var stime='.$rowx->dd.'; var t;
		function startTime(){
			stime--;
			showTime();
			if(stime==0)
				clearTimeout(t);
			else
				t=setTimeout("startTime();",1000);
		}
		function showTime(){
			var h,m,s;
			if(stime>3600) h=Math.floor(stime/3600);
			else h=0;
			m=Math.floor((stime-(h*3600))/60);
			s=stime-(h*3600)-(m*60);
			document.getElementById("ddtime").innerHTML=checkTime(h)+":"+checkTime(m)+":"+checkTime(s);
		}
		function checkTime(i){
			if (i<10){  i="0" + i;}
			return i;
		}startTime();
		</script >';
				}
			else
				$text = LocStr('YOUR_COUNTRY_IS_^1_FOR_^2_TURNS', $reason, $turn);
          }
          else {
            $pieces = explode("&", $this->IframeStart);
            if( $pieces[0] == 'attack' ){
              $ered1 = query( "SELECT o.Nev, oe.Cel, ROUND(SUM(darab*TE)) AS TE FROM Orszagok o, Orszag_Egyseg oe, Egysegek e WHERE oe.Orsz_ID='@1' AND o.ShowID='@2' AND o.ID=oe.Ellenfel AND e.ID=oe.Egyseg_ID AND oe.kikepzes_kor=0 AND tamadas_kor='@3' AND Cel>0 GROUP BY o.Nev, oe.tamadas_kor, oe.Cel", $this->ID, $pieces[1], $pieces[2] );
              if( $row1 = mysqli_fetch_object($ered1) ) {
                $against = LocStr('Against_^1', "<b><u>" . $row1->Nev . " (" . LocStr('ID') . ":" . $pieces[1] . ")</u></b>");
                $text = "";
                switch ($row1->Cel) {
                  case (1) : $img = "resources/res_gold"; $text = LocStr('Gold'); break;
                  case (4) : $img = "resources/res_food"; $text = LocStr('Food'); break;
                  case (3) : $img = "resources/res_wood"; $text = LocStr('Resource1'); break;
                  case (6) : $img = "varazstekercs"; $text = LocStr('Scroll'); break;
                  case (2) : $img = "resources/res_land"; $text = LocStr('Land'); break;
                  case (5) : $img = "kilepes"; $text = LocStr('Destroy'); break;
                  case (7) : $img = "varazstekercs"; $text = LocStr('Magic_item'); break;
                  case (101) : $img = "resources/res_gold"; $text = LocStr('Capture_gold'); break;
                  case (104) : $img = "resources/res_food"; $text = LocStr('Capture_food'); break;
                  case (103) : $img = "resources/res_wood"; $text = LocStr('Capture_resource'); break;
                  case (106) : $img = "varazstekercs"; $text = LocStr('Capture_scroll'); break;
                  case (201) : $img = "resources/res_gold"; $text = LocStr('StealMoney'); break;
                  case (204) : $img = "resources/res_food"; $text = LocStr('StealFood'); break;
                  case (203) : $img = "resources/res_wood"; $text = LocStr('StealResource'); break;
                  case (205) : $img = "kilepes"; $text = LocStr('BuildingSabotage'); break;
                  case (206) : $img = "varazstekercs"; $text = LocStr('StealScroll'); break;
                }
                // xx TE. Még yy kör. Cél zz
                $turn = $row1->TE . " " . LocStr('AF') . ". ".LocStr('^1_turn(s)_left', (-($pieces[2])-1)) . ". ".LocStr('Aim') . ": " . $text;
                $text = $against . ": " . $turn;
              }
            }
            else if( $pieces[0] == 'log' ){
              if( count($pieces) < 3 ) $pieces[2] = "";
              if( count($pieces) < 4 ) $pieces[3] = "";
              $text = LocStr($pieces[1], $pieces[2], $pieces[3]);
            }
            else if( $pieces[0] == 'logid' ){
				if($pieces[2]==0){//ha nincs loguzenet
					$text=LocStr($pieces[3]);
				}else{
				  $ered1 = query( "SELECT Szoveg FROM log WHERE Orsz_ID='@1' AND ID='@2'", $this->ID, $pieces[2] );
				  if( $row1 = mysqli_fetch_object($ered1) )
					$text = DinLoc($row1->Szoveg);
				}
              $pieces[1] = 1; // ideiglenesen, késõbb kivenni
              if( $pieces[1] == 1 ){
                $textclass = "szoveg";
                $imgBack = "tekercs1.png";
                $align = "center";
                $posit = "60px";
                $this->IframeChange = true;
              }
            }
            else
              $text = "semmi: " . $this->IframeStart . ": " . $pieces[1] . "-" . $pieces[2];
          }
/*?>
          <DIV id="szabi" allowtransparency="true">
            <DIV id="szabi_main" allowtransparency="true" style="cursor: move;">
              <img alt="" src="images/<?php echo $imgBack; ?>" width="100%" height="100%">
              <DIV id="szabi_body" style="position: absolute; top: <?php echo $posit;?>; left:0px; width:100%; text-align:center">
                <table align="<?php echo $align;?>" cellspacing="5">
                  <tr>
                    <td class="<?php echo $textclass; ?>"><?php echo $text; ?></td>
<?php     if( $this->Szabadsag < 0 ) { ?>
                    <td><?php Button("Mehet", "ENDPROT", false, "", "javascript:show_yesno('" . LocStr("SUREENDPROT") . "','" . $_SERVER['PHP_SELF'] . "?prot_end=1')"); ?></td>
<?php     } else if( $this->Szabadsag == 1) {if( $this->Activated!=0) { ?>
                    <td><?php Button("Mehet", "PROTEND", false, "", "", "", $_SERVER['PHP_SELF'] . "?vac_end=1"); ?></td>
<?php     			}else{ $eredm = query( "SELECT count(ID) as cc FROM multi_appeals WHERE Orsz_ID='@1' AND Approved=0", $this->ID );
				  if( $rowm = mysqli_fetch_object($eredm) )if($rowm->cc==0){ ?>
					<td><?php Button("Mehet", "MULTI", false, "", "action_multi()", "", "javascript:void(0)"); ?></td>
<?php	}}} else if( $this->Szabadsag == 0 ) { ?>
                    <td width="20" valign="top"><A class="tipz" href="javascript:void(0);" onclick="javascript:stopShow();" title="tipexit::27::50"><img alt="" src="images/kilepes.png" width="12" height="12" border="0"></A></td>
<?php     } ?>
                  </tr>
                </table>
              </DIV>
            </DIV>
          </DIV>


<?php  */ } ?>


<?php if( $this->Form != 0 ){ ?>
          <div class="dcenter">
<?php
    }
  }

  public function menuOldStyle(){
?>
      <table cellspacing="0" cellpadding="0" width="100%" class="table1" align="left">
        <tr valign="top">
          <td nowrap>
            <table cellspacing="0" cellpadding="0" width="100%" class="tabmenu" align="left">
              <tbody align="left">
                <tr style="height:5px;" valign="top" id="tr6">
                  <td></td>
                </tr>
<?php   if( $this->logged && !$this->isChat() ){ ?>
                <tr><td><A class="toplink" href="osszesites.html"><img alt="" width="16" height="16" class="tipz" title="tiphall::13::13" src="images/menu/tronterem.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 1); ?></A></td></tr>
                <tr><td><A class="toplink" href="epulet.html"><img alt="" width="16" height="16" class="tipz" title="tipbuild::14::14" src="images/menu/epitkezes.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 3); ?></A></td></tr>
                <?php if($this->HeroAmuletRight>0) {?><tr><td><A class="toplink" href="amulettek.html"><img alt="" width="16" height="16" class="tipz" title="tipbook3::42::42" src="images/menu/book_mana.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 6); ?></A></td></tr><?php }?>
                <tr><td><A class="toplink" href="varazslatok.html"><img alt="" width="16" height="16" class="tipz" title="tipbook3::43::43" src="images/menu/book_mana.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 5); ?></A></td></tr>
				  <tr><td><A class="toplink" href="spellaccept.html"><img alt="" width="16" height="16" class="tipz" title="tipbook3::43::60" src="images/menu/book_mana.png" border="0"/>&nbsp;<?php  echo GetTextStr("MENU", 15); ?></A></td></tr>
			
				<?php if($this->ArtifactRight>0) {?><tr><td><A class="toplink" href="varazstargy.html"><img alt="" width="16" height="16" class="tipz" title="tipbook3::44::44" src="images/menu/book_mana.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 27); ?></A></td></tr><?php }?>
                <tr><td><A class="toplink" href="egysegek.html"><img alt="" width="16" height="16" class="tipz" title="tipunits::15::15" src="images/menu/seregek.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 4); ?></A></td></tr>
				<tr><td><A class="toplink" href="javascript:action_attack();"><img alt="" width="16" height="16" class="tipz" title="tipattack::49::49" src="images/attack.png" border="0"/>&nbsp;<?php echo GetTextStr("ATTACK"); ?></A></td></tr>
                <tr><td><A class="toplink" href="piac.html"><img alt="" width="16" height="16" class="tipz" title="tipmarket::16::16" src="images/menu/piac.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 7); ?></A></td></tr>
                <tr><td><A class="toplink" href="kocsma.html"><img alt="" width="16" height="16" class="tipz" title="tip18::34::34" src="images/18.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 18); ?></A></td></tr>
                <?php if($this->HeroAmuletRight>0) {?><tr><td><A class="toplink" href="tournament.html"><img alt="" width="16" height="16" class="tipz" title="tipbook2::39::39" src="images/menu/book_all.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 22); ?></A></td></tr><?php }?>
                <tr><td><hr></td></tr>
                <tr><td><A class="toplink" href="tanacsterem.html"><img alt="" width="16" height="16" class="tipz" title="tipboard::18::18" src="images/menu/tanacsterem.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 9); ?></A></td></tr>
                <tr><td><A class="toplink" href="prior.html"><img alt="" width="16" height="16" class="tipz" title="tipbook1::31::31" src="images/menu/book_set.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 16); ?></A></td></tr>
                <tr><td><A class="toplink" href="orszagok.html"><img alt="" width="16" height="16" class="tipz" title="tipcountry::20::20" src="images/menu/orszaglista.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 11); ?></A></td></tr>
                <tr><td><A class="toplink" href="beallitas.html"><img alt="" width="16" height="16" class="tipz" title="tipbook1::30::30" src="images/menu/book_set.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 14); ?></A></td></tr>
                <?php if($this->ClanRight>0){ ?><tr><td><A class="toplink" href="klan.html"><img alt="" width="16" height="16" class="tipz" title="tipclan::19::19" src="images/menu/klanok.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 10); ?></A></td></tr><?php } ?>
                <tr><td><A class="toplink" href="intezo.html"><img alt="" width="16" height="16" class="tipz" title="tipbook1::32::32" src="images/menu/book_set.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 17).$this->IntezoText;?></A></td></tr>
                <tr><td><hr></td></tr>
             <?php /*  <tr><td><A class="toplink" href="forum.html"><img alt="" width="16" height="16" class="tipz" title="tip18::35::35" src="images/<?php if($this->NewForum > 0) echo 'menu/book_all.gif'; else echo '18.png'; ?>" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 19); ?></A></td></tr>*/ ?>
                <tr><td><A class="toplink" href="kredit.html"><img alt="" width="16" height="16" class="tipz" title="tipcredit::17::17" src="images/menu/kredit.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 8); ?></A></td></tr>
             <?php /* <tr><td><A class="toplink" href="szavazas.html"><img alt="" width="16" height="16" class="tipz" title="tipbook2::37::37" src="images/menu/<?php echo ($this->NewVox > 0 ? 'book_all.gif' : 'book_all.png');?>" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 20); ?></A></td></tr> */ ?>
                <tr><td><A class="toplink" href="futar.html"><img alt="" id="messimg" width="16" height="16" class="tipz" title="tipmessage::25::25" src="images/menu/<?php echo ($this->NewMess > 0 ? 'futar.gif' : 'futar.png');?>" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 30); ?></A></td></tr>
                <tr><td><A class="toplink" href="log.html"><img alt="" id="logimg" width="16" height="16" class="tipz" title="tiplog::26::26" src="images/menu/<?php echo ($this->NewLog > 0 ? 'uzenetek.gif' : 'uzenetek.png');?>" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 31); ?></A></td></tr>
                <tr><td><A class="toplink" href="statisztika.html"><img alt="" width="16" height="16" class="tipz" title="tipbook2::41::41" src="images/menu/book_all.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 23); ?></A></td></tr>
                <tr><td><A class="toplink" href="jegyzet.html"><img alt="" width="16" height="16" class="tipz" title="tipbook1::28::28" src="images/menu/book_set.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 12); ?></A></td></tr>
               <?php /*   <tr><td><A class="toplink" href="chat.html" target="_blank"><img alt="" width="16" height="16" class="tipz" title="tipchat::36::36" src="images/menu/chat.png" border="0"/>&nbsp;<?php Display("NAVCHAT"); if($this->ChatAdmin) echo ' <font style="color:red;">('.$this->ChatNumber.')</font>'; else echo ' ('.$this->ChatNumber.')'; ?></A></td></tr>*/ ?>
                <tr><td><A class="toplink" href="bonusz.html"><img alt="" width="16" height="16" class="tipz" title="tipbook1::29::29" src="images/menu/book_set.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 13); ?></A></td></tr>
                <tr><td><A class="toplink" href="hirnev.html"><img alt="" width="16" height="16" class="tipz" title="tiprank::40::40" src="images/menu/hirnev.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 29); ?></A></td></tr>
                <?php /*   <tr><td><A class="toplink" href="linkek.html"><img alt="" width="16" height="16" class="tipz" title="tipbook4::45::45" src="images/menu/book_info.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 24); ?></A></td></tr>*/ ?>
                <tr><td><A class="toplink" href="news.html"><img alt="" width="16" height="16" class="tipz" title="tip18::35::35" src="images/menu/book_all.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 21); ?></A></td></tr>

                <tr><td><A class="toplink" href="sugo.html"><img alt="" width="16" height="16" class="tipz" title="tipbook4::46::46" src="images/menu/book_info.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 25); ?></A></td></tr>
                <tr><td><A class="toplink" href="gyik.html"><img alt="" width="16" height="16" class="tipz" title="tipbook4::47::47" src="images/menu/book_info.png" border="0"/>&nbsp;<?php  echo GetTextStr("MENU", 26); ?></A></td></tr>
				<?php /* ?><tr><td><A class="toplink" href="old_stratega.html"><img alt="" width="16" height="16" class="tipz" title="tipbook4::62::62" src="images/menu/book_info.png" border="0"/>&nbsp;<?php echo GetTextStr("MENU", 28); ?></A></td></tr><?php */ ?>
                <?php /* <tr><td><A class="toplink" href="mailto:stratega.contact@gmail.com"><img alt="" width="16" height="16" class="tipz" title="tipbook4::48::48" src="images/menu/book_info.png" border="0"/>&nbsp;<?php Display("NAVEMAIL");?></A></td></tr> */ ?>
				<tr><td><A class="toplink" href="adatvedelem.html"><img alt="" width="16" height="16" class="tipz" title="tipbook4::47::47" src="images/menu/book_info.png" border="0"/>&nbsp;Adatv&eacute;delem</A></td></tr>
				<tr><td><A class="toplink" href="aszf.html"><img alt="" width="16" height="16" class="tipz" title="tipbook4::47::47" src="images/menu/book_info.png" border="0"/>&nbsp;&#193;SZF</A></td></tr>
				<tr><td><A class="toplink" href="fmsz.html"><img alt="" width="16" height="16" class="tipz" title="tipbook4::47::47" src="images/menu/book_info.png" border="0"/>&nbsp;FMSZ</A></td></tr>
				<tr><td><A class="toplink" href="jatekszabaly.html"><img alt="" width="16" height="16" class="tipz" title="tipbook4::47::47" src="images/menu/book_info.png" border="0"/><?php Display('RULES_GAME') ?></A></td></tr>
                <tr><td><hr></td></tr>
                <tr><td><A class="toplink" href="exit.html" title="<?php Display("EXIT");?>"><img alt="" width="16" height="16" class="tipz" title="tipexit::27::27" src="images/kilepes.png" border="0"/>&nbsp;<?php Display("EXIT");?></A></td></tr>
<?php
        } else {		
          $this->buttonbottom();
        }

?>
              </tbody>
            </table>
          </td>
          <td width="100%">
<?php
  }

  public function end(){
    $bSzabi = $this->logged && $this->Szabadsag != 0 && !$this->isChat();
    $bDrag =  $bSzabi || $this->IframeStart != "";
	
    if( $this->OldStyle == 1 ){
?>
          </td>
        </tr>
      </table>
<?php
     } else if( $this->Form != 0 ){
?>
          </div>
<?php } ?>
          <script  type="text/javascript">
      //      document.getElementById('DivMain').style.display = '';
<?php if( $bDrag ) { ?>
            var myDrag1 = new Drag('szabi_main', {
<?php if( $bSzabi ) { ?>
              onComplete: function(el){
                var req = new Request({
                  url:'send_data.html',
                  method:'post',
                  autoCancel:true,
                  data:'type=setpos&left=' + el.style.left + '&top=' + el.style.top
                }).send();
              }
<?php } ?>
            });
<?php if( !$bSzabi && $this->IframeChange ) { ?>
            obj1 = document.getElementById('szabi_main');
            obj2 = document.getElementById('szabi_body');
            iHeight = parseInt(obj2.scrollHeight);
            iTop = parseInt(obj2.style.top);
            if( iHeight > 400 ){
              iTop = Math.floor(iHeight*3/20);
              obj2.style.top = iTop +"px";
              obj1.style.top = "0px";
            }
            obj1.style.height=(iHeight + iTop + 40) +"px";
<?php } ?>
<?php } ?>

    /*        var dragHandle = $('info_frame_top');
//            var myDrag2 = new Drag('info_frame_main');
            var myDrag2 = new Drag('info_frame_main', {handle: dragHandle});
            var myDrag3 = new Drag('yesno_main');
			var myDrag4 = new Drag('eparch');
			var myDrag5 = new Drag('activdiv');	
*/


          </script>
        </div>
      </div>

      </body>
    </html>
<?php
  }

  public function buttontop(){}

  public function headerscript(){}

  public function buttonbottom(){
	  ?>
	  
	  
                <td style="text-align:center;">
				<a href="index.html"><img src="images/logo2-shadow.png" alt="stratega"></a></td>
				</td>
              
	  
<?php	  /*
?>
    <td>
      <table cellspacing="0" cellpadding="0" class="tabla" border="0" align="left">
        <tr>
          <td width="50px"><td>
          <td width="50px"><A href="login.html"><img alt="" class="tipz" title="tiphall::13::13" src="images/menu/tronterem.png" border="0"></A></td>
          <td width="50px" align="right"><A href="register.html"><img alt="" class="tipz" title="tiplog::26::<?php Display("DOREGISTER");?>" src="images/menu/uzenetek.png" border="0"></A></td>
          <td align="left"><A class="tipz" title="tiplog::26::<?php Display("DOREGISTER");?>" href="register.html"><?php Display("DOREGISTER");?></A></td>
        </tr>
      </table>
    </td>
<?php*/
  }

  public function teszt(){
    echo "teszt<br>";
    $HttpHost = $_SERVER['HTTP_HOST'];
    $pieces = explode(".", $HttpHost);

    $p_nev  = "^[a-zA-Z\-\.]{2,30}$";
    $p_text = "^[a-zA-Z\-\.\,]{2,255}$";
    $p_int  = "^[0-9\-]{1,5}$";
    $p_num  = "^[0-9\.\-]{1,5}$";
    $p_pos_int  = "^[0-9]{1,5}$";
    $p_pos_num  = "^[0-9\.]{1,5}$";
    $p_id   = "^[0-9]{1,8}$";

    $proba = "542";
    if( preg_match('/^[0-9]{2,3}[a-z]{1,3}[0-9]{2,3}$/', $proba) )
      echo "true<br>";
    else
      echo "false<br>";
    $proba = "x___";
    if( preg_match('/^x_{0,3}$/', $proba) )
      echo "true<br>";
    else
      echo "false<br>";
    if (preg_match("/php/i", "PHP is the web scripting language of choice.")) {
        echo "A match was found.<br>";
    } else {
        echo "A match was not found.<br>";
    }
  }
}

?>