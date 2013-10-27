<?php
    require_once(dirname(__FILE__) . "/php/sessionInit.php");
    require_once(dirname(__FILE__). "/php/config.php");
    require_once(dirname(__FILE__). "/php/getLoginInfo.php");
    // echo "session_id: ". session_id(). "<br>";
    $_SESSION['targetURL'] = "http://www6063ue.sakura.ne.jp/hacku/index.php";
    // echo session_id(). "<br>";
    // if(!isset($_SESSION['me'])){
    //     echo "in index session me timeout<br>";
    // }else{
    //     echo "you have session me<br>";
    // }

    // $nextURL = "a";
    // // よく分からんけど、twitterログイン後、index.php以外に飛ぶとエラーになるのでここで処理する
    // if (isset($_SESSION['nextURL'])) {
    //     $nextURL = $_SESSION['nextURL'];
    //     echo "session nextURL exist: ". $nextURL. "<br>";
    //     unset($_SESSION['beforeURL']);
    //     unset($_SESSION['nextURL']);
    //     // header('Location: '. $nextURL. "?". session_name(). "=". session_id());exit();
    // }else{
    //     echo "session nextURLはないで<br>";
    // }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyRally</title>
    <link rel="stylesheet" href="css/hacku.css">
</head>
<body>
<?php include (HEADER_NAME); ?> 
<div id="page">
<div id = "contents">
    <?php include (MENU_NAME); ?>
	<div id="main">
		<img src="imgs/easyrallytoha01.gif">
		<p>スタンプラリー/オリエンテーリング支援サービスです</p>
            <div id="main1_s">
            <h1>easyrallyとは</h1>
            <p>ユビキタス次世代ノマドを支えるオリエンテーリング・プロダクト"Easyrally"はWeb2.0をイノベーションするソーシャルエンジニアリングでアジャイルをアジェンダしたダイバーシティをコモディティ化したモダン・ベストエフォートです！ </p>
            </div>
            <div id="main2_s">
            <h1>easyrallyとは</h1>
            <p>rally rally rally rally rally rally rally rally </p>
            </div>
            <div id="main3_s">
            <h1>easyrallyとは</h1>
            <p>rally rally rally rally rally rally rally rally </p>
            </div>
            <div id="main4_s">
            <h1>easyrallyとは</h1>
            <p>rally rally rally rally rally rally rally rally </p>
            </div>
            <div id="main1_k">
            <h1>easyrallyとは</h1>
            <p>rally rally rally rally rally rally rally rally </p>
            </div>
            <div id="main2_k">
            <h1>easyrallyとは</h1>
            <p>rally rally rally rally rally rally rally rally </p>
            </div>
            <div id="main3_k">
            <h1>easyrallyとは</h1>
            <p>rally rally rally rally rally rally rally rally </p>
            </div>
            <div id="main4_k">
            <h1>easyrallyとは</h1>
            <p>rally rally rally rally rally rally rally rally </p>
            </div>
            
	</div>
</div>
</div>
<?php include (FOOTER_NAME); ?>

</body>
</html>


