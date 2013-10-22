<?php
require_once (dirname(__FILE__) . "/dbconfig.php");
require_once (dirname(__FILE__) . "/dbfuncs.php");

// 主キーが１つのもののみ
function getTableValue($tableName, $columnName, $primaryColumn, $primaryValue) {
	$dbh = connectDB();
	$sql = "SELECT * FROM `$tableName` WHERE $primaryColumn = '$primaryValue'";
	//var_dump($sql);
	$res = array();
	foreach ($dbh->query($sql) as $row) {
		array_push($res, $row[$columnName]);
	}
	return $res[0];
}

//==========================================================================================================
// User クラス
//
class User {
	private $_userID;	// ユーザID
	
	// 指定したユーザーのインスタンスを生成
	public function __construct($userID) {
		$this->_userID = $userID;
	}
	
	// 指定したカラムの値を返す
	public function getColumnValue($columnName) {
		return getTableValue('users', $columnName, 'user_id', $this->_userID);
	}
	
	// このユーザの情報を更新する
	// 変更が必要ないパラメータはnullを引数として与える
	public function update($screenName, $accessToken, $accessTokenSecret, $userName) {
		$dbh = connectDB();
		$sql = "UPDATE `users` SET ";
		if($screenName != null)			$sql.= "screen_name = '$screenName', ";
		if($accessToken != null)		$sql.= "access_token = '$accessToken', ";
		if($accessTokenSecret != null)	$sql.= "access_token_secret = '$accessTokenSecret', ";
		if($userName != null)			$sql.= "user_name = '$userName', ";
		$sql.= "modified = now() WHERE user_id = '$this->_userID'";
		$dbh->query($sql);
	}
	
	// このユーザを削除する
	public function remove() {
		$dbh = connectDB();
		$sql = "DELETE FROM `users` WHERE user_id = '$this->_userID'";
		$dbh->query($sql);
	}
	
	// このユーザーが参加しているスタンプラリーのIDをすべて返す関数
	public function getAllJoinedStamprallyID() {
		$dbh = connectDB();
		$sql = "SELECT * FROM `participants` WHERE user_id = '$this->_userID'";
		
		$res = array();
		foreach ($dbh->query($sql) as $row) {
			array_push($res, (int)$row['stamprally_id']);
		}
		return $res;
	}
	
	// このユーザーが指定したスタンプラリーのIDで回ったチェックポイントIDをすべて返す関数
	public function getAllCheckedCheckpointID($stamprallyID) {
		$dbh = connectDB();
		$sql = "SELECT * FROM (checked_checkpoints CC JOIN checkpoints CP ON CC.checkpoint_id = CP.checkpoint_id) ";
		$sql.= "WHERE CC.user_id = '$this->_userID' AND CP.stamprally_id = '$stamprallyID'";
		
		$res = array();
		foreach ($dbh->query($sql) as $row) {
			array_push($res, (int)$row['checkpoint_id']);
		}
		return $res;
	}
	
	// このユーザーが管理しているスタンプラリーIDを返す関数
	public function getAllManagedStamprallyID() {
		$dbh = connectDB();
		$sql = "SELECT * FROM `stamprallies` WHERE master_id = '$this->_userID'";
		
		$res = array();
		foreach ($dbh->query($sql) as $row) {
			array_push($res, (int)$row['stamprally_id']);
		}
		return $res;
	}
	
	// このユーザーが獲得済みかつ未使用のチケットIDを全て返す関数
	public function getGotTickets() {
		$dbh = connectDB();
		$sql = "SELECT * FROM `got_tickets` WHERE user_id = '$this->_userID' AND exchanged_ticket = FALSE";
		
		$res = array();
		foreach ($dbh->query($sql) as $row) {
			array_push($res, (int)$row['ticket_id']);
		}
		return $res;
	}
	
	// 指定したチケットを所持していれば使用済みにする
	public function useTicket($ticketID) {
		$dbh = connectDB();
		$sql = "UPDATE `got_tickets` SET exchanged_ticket = TRUE, modified = now() WHERE user_id = '$this->_userID' AND ticket_id = '$ticketID'";
		$dbh->query($sql);
	}
	
	// 指定したチェックポイントをチェック済みにする
	public function checkCheckpoint($checkpointID) {
		$dbh = connectDB();
		$sql = "INSERT INTO `checked_checkpoints` (user_id, checkpoint_id, checked_date, created, modified) ";
		$sql.= "VALUES ('$this->_userID', '$checkpointID', now(), now(), now())";
		$dbh->query($sql);
	}
	
	// 指定したチケットを獲得済みにする
	public function getTicket($ticketID) {
		$dbh = connectDB();
		$sql = "INSERT INTO `got_tickets` (user_id, ticket_id, exchanged_ticket, created, modified) ";
		$sql.= "VALUES ('$this->_userID', '$ticketID', FALSE, now(), now())";
		$dbh->query($sql);
	}
	
	// 指定したスタンプラリーに参加する
	public function joinStamprally($stamprallyID) {
		$dbh = connectDB();
		$sql = "INSERT INTO `participants` (user_id, stamprally_id, created, modified) ";
		$sql.= "VALUES ('$this->_userID', '$stamprallyID', now(), now())";
		$dbh->query($sql);
	}
	
	// 新しくユーザーを作成する関数
	public static function add($userID, $screenName, $accessToken, $accessTokenSecret, $userName, $startDate, $endDate) {
		$dbh = connectDB();
		$sql = "INSERT INTO `users` (user_id, screen_name, access_token, access_token_secret, user_name, created, modified) ";
		$sql.= "VALUES ('$userID', '$screenName', '$accessToken', '$accessTokenSecret', '$userName', now(), now())";
		$dbh->query($sql);
	}
	
}

//==========================================================================================================
// StampRally クラス
//
class StampRally {
	private $_stamprallyID;	// スタンプラリーID
	
	// 指定したスタンプラリーIDのインスタンスを生成
	public function __construct($stamprallyID) {
		$this->_stamprallyID = $stamprallyID;
	}
	
	// 指定したカラムの値を返す
	public function getColumnValue($columnName) {
		return getTableValue('stamprallies', $columnName, 'stamprally_id', $this->_stamprallyID);
	}
	
	// このスタンプラリーの情報を更新する
	// 変更が必要ないパラメータはnullを引数として与える
	public function update($stamprallyName, $masterID, $masterName, $place, $lat, $lon, $description, $startDate, $endDate) {
		$dbh = connectDB();
		$sql = "UPDATE `stamprallies` SET ";
		if($stamprallyName != null)	$sql.= "stamprally_name = '$stamprallyName', ";
		if($masterID != null)		$sql.= "master_id = '$masterID', ";
		if($masterName != null)		$sql.= "master_name = '$masterName', ";
		if($place != null)			$sql.= "place = '$place', ";
		if($lat != null)			$sql.= "lat = '$lat', ";
		if($lon != null)			$sql.= "lon = '$lon', ";
		if($description != null)	$sql.= "description = '$description', ";
		if($startDate != null)		$sql.= "start_date = '$startDate', ";
		if($endDate != null)		$sql.= "end_date = '$endDate', ";
		$sql.= "modified = now() WHERE stamprally_id = '$this->_stamprallyID'";
		$dbh->query($sql);
	}
	
	// このスタンプラリーを削除する
	public function remove() {
		$dbh = connectDB();
		$sql = "DELETE FROM `stamprallies` WHERE stamprally_id = '$this->_stamprallyID'";
		$dbh->query($sql);
	}
	
	// このスタンプラリーで獲得可能なチケットIDを全て返す
	public function getAllTicketID() {
		$dbh = connectDB();
		$sql = "SELECT * FROM `tickets` WHERE stamprally_id = '$this->_stamprallyID'";
		
		$res = array();
		foreach ($dbh->query($sql) as $row) {
			array_push($res, (int)$row['ticket_id']);
		}
		return $res;
	}
	
	// このスタンプラリーで獲得可能なチェックポイントIDを全て返す
	public function getAllCheckpointID() {
		$dbh = connectDB();
		$sql = "SELECT * FROM `checkpoints` WHERE stamprally_id = '$this->_stamprallyID'";
		
		$res = array();
		foreach ($dbh->query($sql) as $row) {
			array_push($res, (int)$row['checkpoint_id']);
		}
		return $res;
	}
	
	// このスタンプラリーに参加しているユーザーのIDをすべて返す関数
	public function getAllJoinedUserID() {
		$dbh = connectDB();
		$sql = "SELECT * FROM `participants` WHERE stamprally_id = '$this->_stamprallyID'";
		
		$res = array();
		foreach ($dbh->query($sql) as $row) {
			array_push($res, (int)$row['user_id']);
		}
		return $res;
	}
	
	// 新しくスタンプラリーを作成する関数
	public static function add($stamprallyName, $masterID, $place, $description, $startDate, $endDate) {
		$dbh = connectDB();
		$sql = "INSERT INTO `stamprallies` (stamprally_name, master_id, place, description, start_date, end_date, created, modified) ";
		$sql.= "VALUES ('$stamprallyName', '$masterID', '$place', '$description', '$startDate', '$endDate', now(), now())";
		$dbh->query($sql);
		return $dbh->lastInsertId();
	}
}

//==========================================================================================================
// Ticket クラス
//
class Ticket {
	private $_ticketID;	// チケットーID
	
	// 指定したチケットIDのインスタンスを生成
	public function __construct($ticketID) {
		$this->_ticketID = $ticketID;
	}
	
	// 指定したカラムの値を返す
	public function getColumnValue($columnName) {
		return getTableValue('tickets', $columnName, 'ticket_id', $this->_ticketID);
	}
	
	// このチケット情報を更新する
	public function update($stamprallyID, $ticketName, $description, $limitDate, $requiredCheckpointNum, $limitTicketNum, $type) {
		$dbh = connectDB();
		$sql = "UPDATE `tickets` SET ";
		if($stamprallyID != null)			$sql.= "stamprally_id = '$stamprallyID', ";
		if($ticketName != null)				$sql.= "ticket_name = '$ticketName', ";
		if($description != null)			$sql.= "description = '$description', ";
		if($limitDate != null)				$sql.= "limit_date = '$limitDate', ";
		if($requiredCheckpointNum != null)	$sql.= "required_checkpoint_num = '$requiredCheckpointNum', ";
		if($limitTicketNum != null)			$sql.= "limit_ticket_num = '$limitTicketNum', ";
		if($type != null)					$sql.= "type = '$type', ";
		$sql.= "modified = now() WHERE ticket_id = '$this->_ticketID'";
		$dbh->query($sql);
	}
	
	// このチケットを削除する
	public function remove() {
		$dbh = connectDB();
		$sql = "DELETE FROM `tickets` WHERE ticket_id = '$this->_ticketID'";
		$dbh->query($sql);
	}
	
	// 新しいチケットを作成する
	public static function add($stamprallyID, $ticketName, $limitDate, $requiredCheckpointNum, $limitTicketNum) {
		$dbh = connectDB();
		$sql = "INSERT INTO `tickets` (stamprally_id, ticket_name, limit_date, required_checkpoint_num, limit_ticket_num, created, modified) ";
		$sql.= "VALUES ('$stamprallyID', '$ticketName', '$limitDate', '$requiredCheckpointNum', '$limitTicketNum', now(), now())";
		$dbh->query($sql);
		return $dbh->lastInsertId();
	}
}

//==========================================================================================================
// Checkpoint クラス
//
class Checkpoint {
	private $_checkpointID;	// チェックポイントID
	
	// チェックポイントIDのインスタンスを生成
	public function __construct($checkpointID) {
		$this->_checkpointID = $checkpointID;
	}
	
	// 指定したカラムの値を返す
	public function getColumnValue($columnName) {
		return getTableValue('checkpoints', $columnName, 'checkpoint_id', $this->_checkpointID);
	}
	
	// このチェックポイント情報を更新する
	public function update($checkpointName, $publicDescription, $privateDescription, $stamprallyID, $url) {
		$dbh = connectDB();
		$sql = "UPDATE `checkpoints` SET ";
		if($checkpointName != null)			$sql.= "checkpoint_name = '$checkpointName', ";
		if($publicDescription != null)		$sql.= "public_description = '$publicDescription', ";
		if($privateDescription != null)		$sql.= "private_description = '$privateDescription', ";
		if($stamprallyID != null)			$sql.= "stamprally_id = '$stamprallyID', ";
		if($url != null)					$sql.= "url = '$url', ";
		$sql.= "modified = now() WHERE checkpoint_id = '$this->_checkpointID'";
		$dbh->query($sql);
	}
	
	// このチェックポイントを削除する
	public function remove() {
		$dbh = connectDB();
		$sql = "DELETE FROM `checkpoints` WHERE checkpoint_id = '$this->_checkpointID'";
		$dbh->query($sql);
	}
	
	// このチェックポイントを指定したユーザーが通過済みにする
	public function check($userID) {
		$dbh = connectDB();
		$sql = "INSERT INTO `checked_checkpoints` (user_id, checkpoint_id, checked_date, created, modified) ";
		$sql.= "VALUES ('$userID', '$this->_checkpointID', now(), now(), now())";
		$dbh->query($sql);
	}
	
	// 新しいチェックポイントを作成する
	public static function add($checkpointName, $publicDescription, $privateDescription, $stamprallyID, $url) {
		$dbh = connectDB();
		$sql = "INSERT INTO `checkpoints` (checkpoint_name, public_description, private_description, stamprally_id, url, created, modified) ";
		$sql.= "VALUES ('$checkpointName', '$publicDescription', '$privateDescription', '$stamprallyID', '$url', now(), now())";
		$dbh->query($sql);
		return $dbh->lastInsertId();
	}
}

//$u = new User(127982310); 
//var_dump( $u->getAllJoinedStamprallyID() );
//var_dump( $u->getAllCheckedCheckpointID(2) );
//var_dump( $u->getAllManagedStamprallyID() );
//var_dump( $u->getGotTickets() );
//$u->useTicket(3);
//var_dump( $u->getColumnValue('screen_name') );

//$sr = new StampRally(1);
//$sr->update('わかやまウォーク', null, null, null, null, null, null, null, null);	// わかやまウォーク
//$sr->update(null, 127982310, null, null, null, null, null, null, null);	// 127982310
//$sr->update(null, null, null, '和歌山城公園', null, null, null, null, null);	// 和歌山城公園
//var_dump( $sr->getAllJoinedUserID() );
//var_dump( $sr->getAllTicketID() );

//StampRally::add('すたんぽぷろじぇくと', 1234, 'ブリティッシュコロンビア大学', "たのしいだいがく", '2013-10-10 00:00:00', '2013-10-15 12:30:00');
//$s = new StampRally(3);
//$s->remove();

//var_dump( Ticket::add(2, 'わだいロボ', '2013-10-10 00:00:00', 5, 128) );
//$t = new Ticket(9);
//$t->remove();

//$t1 = new Ticket(1);
//$t1->update(null, 'ぶらくり丁福引券', null, null, null); // ぶらくり丁福引券


