<?php
// ライブラリ読み込み
// echo "callback処理するで!<br>";
require_once(dirname(__FILE__) . '/UltimateOAuth.php');
require_once(dirname(__FILE__) . "/sessionInit.php");
require_once(dirname(__FILE__) . '/config.php');

// セッションタイムアウトチェック
if (!isset($_SESSION['uo'])) {
	echo "callback で　uo ないで<br>";
    die('Error[-1]: Session timeout.');
}
$uo = $_SESSION['uo'];
// oauth_verifierパラメータが存在するかチェック
if (!isset($_GET['oauth_verifier'])) {
    die('Error[-1]: No oauth_verifier');
}
var_dump($_SESSION['uo']);
// アクセストークン取得
$res = $uo->post('oauth/access_token', array(
    'oauth_verifier' => $_GET['oauth_verifier']
));

if (isset($res->errors)) {
	echo "アクセストークン取得失敗<br>";
    die(sprintf('Error[%d]: %s',
        $res->errors[0]->code,
        $res->errors[0]->message
    ));
}
$me = $uo->get('account/verify_credentials');
$_SESSION['me'] = $me;

// ユーザー情報を登録
$userID = User::add(
	$_SESSION['me']->id,
	$_SESSION['me']->screen_name,
	$res->oauth_token,
	$res->oauth_token_secret,
	$_SESSION['me']->screen_name
	);

// もともとアクセスする予定だったLyncへリダイレクト
$targetURL = $_SESSION['targetURL'];
unset($_SESSION['targetURL']);
header('Location: '. $targetURL);
// header('Location: ../index.php');
exit();

?>
