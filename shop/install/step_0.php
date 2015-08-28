<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $html_title;?></title>
<link href="css/install.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../data/resource/js/jquery.js"></script>
<link href="../../data/resource/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../data/resource/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="../../data/resource/js/jquery.mousewheel.js"></script>
</head>

<body>
<?php defined('InShopNC') or exit('Access Invalid!');?>
<?php echo $html_header;?>
<div class="main">
  <div class="text-box" id="text-box">
    <div class="license">

    </div>
  </div>
  <div class="btn-box"><a href="index.php?step=1" class="btn btn-primary">同意协议进入安装</a><a href="javascript:window.close()" class="btn">不同意</a></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	//自定义滚定条
	$('#text-box').perfectScrollbar();
});
</script>
</body>
</html>
