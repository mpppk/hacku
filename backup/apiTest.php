<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
	<h1>地図なんだが！？！？！？！？！？！</h1>
	<script src="http://i.yimg.jp/images/yjdn/js/bakusoku-jsonp-v1-min.js"
	  data-url="http://shopping.yahooapis.jp/ShoppingWebService/V1/json/itemSearch"
	  data-p-appid="dj0zaiZpPVphNFdrbTRqOHMzWSZzPWNvbnN1bWVyc2VjcmV0Jng9NmU-"
	  data-p-query="讃岐うどん"
	>
	{{#ResultSet.0.Result}}
	 {{#0}}
	 <a href="{{Url}}"><img src="{{Image.Medium}}" alt="{{Name}}"></a>
	 {{/0}}
	 {{#1}}
	 <a href="{{Url}}"><img src="{{Image.Medium}}" alt="{{Name}}"></a>
	 {{/1}}
	 {{#2}}
	 <a href="{{Url}}"><img src="{{Image.Medium}}" alt="{{Name}}"></a>
	 {{/2}}
	{{/ResultSet.0.Result}}
	</script>
</body>
</html>