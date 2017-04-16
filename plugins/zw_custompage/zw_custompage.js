$("#menu_zw_custompage-admin").click(function (){zw_custompage_load_set();})

$("#zw_custompage_add").click(function(){
	createWindow().setTitle("添加页面").setContent('<form method="get" action="plugin.php?id=zw_custompage&action=addpage" id="addpage" onsubmit="return post_win(this.action, this.id, zw_custompage_load_set)"><p>页面名称:<input type="text" id="page_title" name="page_title" style="width:100%"/></p><p>页面内容<textarea id="page_content" name="page_content" style="width:100%;height:300px"/></textarea></p><p>是否启用：<input type="checkbox" id="this_page_switch" name="this_page_switch" value="1" checked/>启用</p></form>').addButton("确定", function(){ $('#addpage').submit(); }).addCloseButton("取消").append();
	});


$("#zw_custompage_del_all").click(function(){
	createWindow().setTitle("全部删除").setContent('你确定要删除全部页面吗？').addButton("确定", function(){msg_callback_action('plugin.php?id=zw_custompage&action=delall',zw_custompage_load_set);}).addCloseButton("取消").append();
});	

$("#zw_custompage_all_able").click(function(){
	createWindow().setTitle("全部启用").setContent('你确定要启用全部页面吗？').addButton("确定", function(){msg_callback_action('plugin.php?id=zw_custompage&action=allable',zw_custompage_load_set);}).addCloseButton("取消").append();
});	

$("#zw_custompage_all_unable").click(function(){
	createWindow().setTitle("全部关闭").setContent('你确定要关闭全部页面吗？').addButton("确定", function(){msg_callback_action('plugin.php?id=zw_custompage&action=allunable',zw_custompage_load_set);}).addCloseButton("取消").append();
});	

$("#zw_custompage_turnedtoother").click(function(){
	createWindow().setTitle("全部关闭").setContent('你确定要反向开关吗？').addButton("确定", function(){msg_callback_action('plugin.php?id=zw_custompage&action=turnedtoother',zw_custompage_load_set);}).addCloseButton("取消").append();
});	

function zw_custompage_load_set(){
	showloading();
	$.getJSON("plugin.php?id=zw_custompage&action=getsetting", function(result){
		zw_custompage_show_set(result);
	}).fail(function() { createWindow().setTitle('系统错误').setContent('发生未知错误: 无法获取设置').addButton('确定', function(){ location.reload(); }).append(); }).always(function(){ hideloading(); });
}

function zw_custompage_show_set(result){
	var page_switch="";
	var title="";
	var content="";
	$('#zw_custompage_list').html('');
	if(result.count){
	$.each(result.pages, function(i, field){
        page_switch=field.pswitch==1?"开启":"关闭";
		$("#zw_custompage_list").append("<tr><td>"+(i+1)+"</td><td>"+field.title+"</td><td>"+field.content+"</td><td>"+page_switch+"</td><td><a href=\"javascript:;\" onclick=\"return edit_custompage('"+field.id+"')\">编辑</a>&nbsp;<a href=\"javascript:;\" onclick=\"return del_custompage('"+field.id+"')\">删除</a></td></tr>");
	});}
	$('#page_footer_js').val(result.setting.page_footer_js);
	$('#page_footer_text').val(result.setting.page_footer_text);
    	$('#bg_img').val(result.setting.bg_img);
	$('#min_bg').val(result.setting.min_bg);
	$('#max_bg').val(result.setting.max_bg);
	if(result.setting.page_switch=='0'){$('#page_switch').removeAttr('checked');}
	if(result.setting.footer_text_switch=='0'){$('#footer_text_switch').removeAttr('checked');}
	if(result.setting.footer_js_switch=='0'){$('#footer_js_switch').removeAttr('checked');}
	if(result.setting.bg_switch=='0'){$('#bg_switch').removeAttr('checked');}
;}

function edit_custompage(id){
	showloading();
	$.getJSON("plugin.php?id=zw_custompage&action=getpage&pid="+id, function(result){
		var checked=""
		checked=result.this_page.pswitch==1?'checked':''
		createWindow().setTitle("编辑页面").setContent('<form method="get" action="plugin.php?id=zw_custompage&action=setpage&pid='+id+'" id="editpage" name="editpage" onsubmit="return post_win(this.action, this.id, zw_custompage_load_set)"><p>页面名称:<input type="text" id="page_title" name="page_title" value='+result.this_page.title+' style="width:100%"/></p><p>页面内容<textarea id="page_content" name="page_content" style="width:100%;height:300px"/>'+result.this_page.content+'</textarea></p><p>是否启用：<input type="checkbox" id="this_page_switch" name="this_page_switch" value="1" '+checked+'/>启用</p></form>').addButton("确定", function(){ $('#editpage').submit(); }).addCloseButton("取消").append();
	}).fail(function() { createWindow().setTitle('系统错误').setContent('发生未知错误: 无法获取指定内容').addButton('确定', function(){}).append(); }).always(function(){ hideloading(); });
}


function del_custompage(id){
	createWindow().setTitle('删除页面').setContent('确认要删除这个页面吗？').addButton('确定', function(){ msg_callback_action("plugin.php?id=zw_custompage&action=delpage&pid="+id,zw_custompage_load_set); }).addCloseButton('取消').append();
	return false;
}
