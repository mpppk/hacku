<?php
// ライブラリ読み込み
require_once(dirname(__FILE__) . "/sessionInit.php");
require_once(dirname(__FILE__). '/config.php');
require_once(dirname(__FILE__). '/UltimateOAuth.php');

$beforeURL = $_SESSION['beforeURL'];
$_SESSION['nextURL'] = $beforeURL;
// UltimateOAuthオブジェクトを新規作成してセッションに保存
$_SESSION['uo'] = new UltimateOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$uo = $_SESSION['uo'];
// リクエストトークンを取得
$res = $uo->post('oauth/request_token');
if (isset($res->errors)) {
    die(sprintf('Error[%d]: %s',
        $res->errors[0]->code,
        $res->errors[0]->message
    ));
}

// Authenticateで認証するなら
$url = $uo->getAuthenticateURL();
// Authorizeで認証するなら
// $url = $uo->getAuthorizeURL();

// Twitterのログインページに遷移
header('Location: '.$url);
exit();
?>
