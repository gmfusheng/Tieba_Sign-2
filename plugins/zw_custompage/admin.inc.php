<?php
if(!defined('IN_KKFRAME')) exit('Access Denied!');
?>
<h2>著微自定义页面插件 - 管理面板</h2>
<p style="color: #757575; font-size: 12px">当前插件版本：1.2.3 | 更新日期：2015-12-30 | Designed By <a href="http://jerrys.me" target="_blank">@JerryLocke</a> | Modify <a href="http://tieba.baidu.com/home/main?un=%D1%A7%D4%B0%BF%C6%BC%BC" target="_blank">@学园科技</a></p>

<h2>Tips</h2>
<p>1、本页面只有管理员才可以看到<br/>
2、在此页面 <b><u>修改设置后需要刷新浏览器才能看到效果</u></b>（建议Ctrl+F5强制刷新）</p>
<br/><br/>

<h2>页面管理</h2>
<p>可以添加任意数量的页面，支持HTML代码</p>
<table>
	<thead>
		<tr>
			<td style="width: 5%">#</td>
			<td>页面名称</td>
			<td>页面内容</td>
			<td>页面开关</td>
			<td style="width: 20%">操作</td>
		</tr>
	</thead>
	<tbody id="zw_custompage_list"></tbody>
</table><p>
<a class="btn" href="javascript:;" id="zw_custompage_add">添加页面</a> 
<a class="btn" href="javascript:;" id="zw_custompage_del_all" style="margin-left: 5px">全部删除</a>&nbsp;
<a class="btn" href="javascript:;" id="zw_custompage_all_able" style="margin-left: 5px">全部启用</a>&nbsp;
<a class="btn" href="javascript:;" id="zw_custompage_all_unable" style="margin-left: 5px">全部关闭</a>&nbsp;
<a class="btn" href="javascript:;" id="zw_custompage_turnedtoother" style="margin-left: 5px">反向开关</a>
</p><br/><br/>

<h2>全局设置</h2>
<form method="post" action="plugin.php?id=zw_custompage&action=savesetting"	id="settings" onsubmit="return post_win(this.action, this.id,zw_custompage_load_set)"><p>
<input type="checkbox" id="page_switch" name="page_switch" value="1" checked />启用自定义页面&nbsp;&nbsp;
<input type="checkbox" id="footer_text_switch" name="footer_text_switch" value="1" checked />启用页底文字&nbsp;&nbsp;
<input type="checkbox" id="footer_js_switch" name="footer_js_switch" value="1" checked />启用页底代码&nbsp;&nbsp;
<input type="checkbox" id="bg_switch" name="bg_switch" value="1" checked />启用自定义背景图
</p><br/><br/>

<h2>页底文字</h2><p>可以添加文字、超链接等</p>
<p><textarea id="page_footer_text" name="page_footer_text" style="width: 100%"></textarea></p><br/><br/>

<h2>页底代码</h2><p>可以添加统计代码、分享代码等</p>
<p><textarea id="page_footer_js" name="page_footer_js" style="width: 100%"></textarea></p><br/><br/>

<h2>自定义背景图片设置</h2>
<p>1、只有全局设置“启用自定义背景图”为选中时才会生效<br/>
2、图片格式必须是JPG</p><br/>
<p>图库地址：</p><p><label><input type="text" id="bg_img" name="bg_img" style="width: 100%"></label>
<p><label>最小序列：<input type="text" id="min_bg" name="min_bg" style="width: 50px"></label></p>
<p><label>最大序列：<input type="text" id="max_bg" name="max_bg" style="width: 50px"></label></p>
<br/><br/>
<input type="submit" value="保存设置"></form>