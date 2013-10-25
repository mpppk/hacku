<?php
// ライブラリ読み込み
// echo "callback処理するで!<br>";
session_set_cookie_params( 1440 );
session_start();
require_once(dirname(__FILE__) . "/sessionInit.php");
require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/UltimateOAuth.php');

// セッションタイムアウトチェック
if (!isset($_SESSION['uo'])) {
	echo "callback で　uo ないで<br>";
    die('Error[-1]: Session timeout.');
}
$uo = $_SESSION['uo'];
// echo "checkやで<br>";
// oauth_verifierパラメータが存在するかチェック
if (!isset($_GET['oauth_verifier'])) {
    die('Error[-1]: No oauth_verifier');
}
var_dump($_SESSION['uo']);
echo "アクセストークン取得するで<br>";
// アクセストークン取得
$res = $uo->post('oauth/access_token', array(
    'oauth_verifier' => $_GET['oauth_verifier']
));
echo "アクセストークン取得エラーチェックするで<br>";

if (isset($res->errors)) {
	echo "アクセストークン取得失敗<br>";
    die(sprintf('Error[%d]: %s',
        $res->errors[0]->code,
        $res->errors[0]->message
    ));
}
$me = $uo->get('account/verify_credentials');
$_SESSION['me'] = $me;

// アプリケーションのメインページに遷移
if (!isset($_SESSION['nextURL'])) {
	$url = "http://www6063ue.sakura.ne.jp/hacku/";
}
session_regenerate_id(true);
// header('Location: '. $url. "?". session_name(). "=". session_id());
// header('Location: '. $_SESSION['nextURL']. "?". session_name(). "=". session_id());
header('Location: ../index.php');
exit();

?>
