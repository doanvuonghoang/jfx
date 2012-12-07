<?php
	use jf\Context;
	use jf\route\HttpRequest;

	$context = Context::getContext();
	$request = $context->getService('request');
	
	$page = '';
	if(isset($_REQUEST['__p'])) $page = $_REQUEST['__p'];
	
	$configURL = $request->createURL(null, '__p=config');
	
	if($page != 'index' && $page != '') {
		require $page . '.php';
		
		return;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>C&#7845;u hình ứng dụng JF</title>


</head>
<body>
	<h1>Ứng dụng JF chưa được cấu hình</h1>
Để bắt đầu cấu hình JF đề nghị chọn vào <a href="<?php echo $configURL; ?>">đây</a>.</body>
</html>