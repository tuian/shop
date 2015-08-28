<?php
defined('InShopNC') or exit('Access Invalid!');

$style_template = <<<EOT
<div style=" width:1200px; height:1907px; margin:10px auto 0 auto; position:relative; background:#313131">
			<div style="z-index:1;position:absolute;width:468px;height:390px;visibility:visible;top:0px;left:0px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r1_c1.jpg" width="468" height="390" /></a>
			</div>
			<div style="z-index:2;position:absolute;width:407px;height:390px;visibility:visible;top:0px;left:468px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r1_c3.jpg" width="407" height="390" /></a>
			</div>
			<div style="z-index:3;position:absolute;width:325px;height:390px;visibility:visible;top:0px;left:875px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r1_c5.jpg" width="325" height="390" /></a>
			</div>
			<div style="z-index:4;position:absolute;width:302px;height:509px;visibility:visible;top:390px;left:0px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r2_c1.jpg" width="302" height="509" /></a>
			</div>
			<div style="z-index:5;position:absolute;width:300px;height:509px;visibility:visible;top:390px;left:302px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r2_c2.jpg" width="300" height="509" /></a>
			</div>
			<div style="z-index:6;position:absolute;width:294px;height:509px;visibility:visible;top:390px;left:602px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r2_c4.jpg" width="294" height="509" /></a>
			</div>
			<div style="z-index:7;position:absolute;width:304px;height:509px;visibility:visible;top:390px;left:896px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r2_c6.jpg" width="304" height="509" /></a>
			</div>
			<div style="z-index:8;position:absolute;width:302px;height:498px;visibility:visible;top:899px;left:0px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r3_c1.jpg" width="302" height="498" /></a>
			</div>
			<div style="z-index:9;position:absolute;width:300px;height:498px;visibility:visible;top:899px;left:302px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r3_c2.jpg" width="300" height="498" /></a>
			</div>
			<div style="z-index:10;position:absolute;width:294px;height:498px;visibility:visible;top:899px;left:602px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r3_c4.jpg" width="294" height="498" /></a>
			</div>
			<div style="z-index:11;position:absolute;width:304px;height:498px;visibility:visible;top:899px;left:896px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r3_c6.jpg" width="304" height="498" /></a>
			</div>
			<div style="z-index:12;position:absolute;width:302px;height:510px;visibility:visible;top:1397px;left:0px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r4_c1.jpg" width="302" height="510" /></a>
			</div>
			<div style="z-index:13;position:absolute;width:300px;height:510px;visibility:visible;top:1397px;left:302px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r4_c2.jpg" width="300" height="510" /></a>
			</div>
			<div style="z-index:14;position:absolute;width:294px;height:510px;visibility:visible;top:1397px;left:602px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r4_c4.jpg" width="294" height="510" /></a>
			</div>
			<div style="z-index:15;position:absolute;width:304px;height:510px;visibility:visible;top:1397px;left:896px;">
				<a href="http://www.b0635.com" target="_blank"><img border="0" src="templates/default/store/style/super7/images/ganghua_r4_c6.jpg" width="304" height="510" /></a>
			</div>
		</div>


EOT;


$style_info = <<<EOT
	<img alt="" style="width:300px;float:left;margin:0px;" src="templates/{$tpl_name}/store/style/super7/images/style.jpg" border="0" />
  <div style=" float: right;margin: 0 auto;width:300px;  line-height:24px;">
    <p style="color:red"> <strong>可编辑区域的宽度为通栏100% <strong></p>
    <p> <strong>1</strong>号图片区域，宽1200px，也可以尝试其他宽度。为了设置居中效果，请给与样式margin:0 auto;</p>
    <input type="button" name="" value="恢复默认" onclick="insert_template();">
    <p> <span style="color:#999999;">编辑器使用技巧：</span> </p>
    <p style="margin-left:20px;"> <span style="color:#999999;">1可以用“HTML代码”按钮来查看编辑代码。</span> </p>
    <p style="margin-left:20px;"> <span style="color:#999999;">2如果一行要插入多个图片，可先用“表格”按钮分隔。</span> </p>
    <p style="margin-left:20px;"> <span style="color:#999999;">3默认模板中的链接地址为当前网站地址。</span> </p>
    <p style="margin-left:20px;"> <span style="color:#999999;">4“插入相册图片”的链接地址为当前店铺的地址。</span> </p>
    <p style="margin-left:20px;"> <span style="color:#999999;">5编辑器中图片的链接地址可以在鼠标右键的弹出菜单中选择修改。</span> </p>
    <p style="margin-left:20px;"> <span style="color:#999999;">6编辑器中所有内容均可替换修改。</span> <br />
    </p>
  </div>
EOT;

?>