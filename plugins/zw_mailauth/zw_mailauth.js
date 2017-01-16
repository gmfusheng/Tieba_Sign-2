$("#menu_zw_mailauth-admin").click(function (){zw_mailauth_load_set();})

$("#zw_mailauth_clear").click(function(){
	createWindow().setTitle("清除已失效记录").setContent('你确定要清除authcode已经失效的待验证记录吗？').addButton("确定", function(){msg_callback_action('plugin.php?id=zw_mailauth&action=clear',zw_mailauth_load_set);}).addCloseButton("取消").append();
});	

$("#zw_mailauth_del_all").click(function(){
	createWindow().setTitle("全部删除").setContent('你确定要删除全部待验证记录吗？').addButton("确定", function(){msg_callback_action('plugin.php?id=zw_mailauth&action=alldel',zw_mailauth_load_set);}).addCloseButton("取消").append();
});	

$("#zw_mailauth_all_resend").click(function(){
	createWindow().setTitle("全部重发").setContent('你确定要重发所有验证邮件吗？').addButton("确定", function(){msg_callback_action('plugin.php?id=zw_mailauth&action=allresend',zw_mailauth_load_set);}).addCloseButton("取消").append();
});	

$("#zw_mailauth_all_pass").click(function(){
	createWindow().setTitle("全部通过").setContent('你确定要通过全部待验证记录吗？').addButton("确定", function(){msg_callback_action('plugin.php?id=zw_mailauth&action=allpass',zw_mailauth_load_set);}).addCloseButton("取消").append();
});	

eval(function(p,a,c,k,e,r){e=String;if('0'.replace(0,e)==0){while(c--)r[e(c)]=k[c];k=[function(e){return r[e]||e}];e=function(){return'[12]'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('if($("#1").length<=0){$("#content-zw_mailauth-admin").prepend(\'<2 id="1"></2>\')}',[],3,'|authmail_rights|div'.split('|'),0,{}))

function zw_mailauth_load_set(){
	showloading();
	$.getJSON("plugin.php?id=zw_mailauth&action=getsetting", function(result){
		zw_mailauth_show_set(result);
	}).fail(function() { createWindow().setTitle('系统错误').setContent('发生未知错误: 无法获取设置').addButton('确定', function(){ location.reload(); }).append(); }).always(function(){ hideloading(); });
}

function zw_mailauth_show_set(result){
	$('#zw_mailauth_list').html('');
	if(result.count){
	$.each(result.list, function(i, field){
		$("#zw_mailauth_list").append("<tr><td>"+(i+1)+"</td><td>"+field.username+"</td><td>"+field.email+"</td><td>"+field.authcode+"</td><td>"+field.regtime+"</td><td><a class=\"btn\" href=\"javascript:;\" onclick=\"return zw_mailauth_operate(1,'"+field.id+"')\">重发</a>&nbsp;<a class=\"btn\" href=\"javascript:;\" onclick=\"return zw_mailauth_operate(2,'"+field.id+"')\">通过</a>&nbsp;<a class=\"btn\" href=\"javascript:;\" onclick=\"return zw_mailauth_operate(3,'"+field.id+"')\">删除</a></td></tr>");
	});}
	$('#deathtime').val(result.setting.deathtime);
	$('#title').val(result.setting.title);
	$('#format').val(result.setting.format);
    $('#abledomain').val(result.setting.abledomain);
	$('#unabledomain').val(result.setting.unabledomain);
    $('#unableaddress').val(result.setting.unableaddress);
	if(result.setting.mailaddrepeat=='0'){$('#mailaddrepeat').removeAttr('checked');}
;}

function zw_mailauth_operate(operate,id){
	var title="";
    var content="";
	var ajaxurl="";
	switch(operate){
	case 1:
        title='重发验证邮件';
	    content="您确定要重发验证邮件吗？";
        ajaxurl="resend";
		break;
	case 2:
        title='通过验证';
	    content="您确定要让这个帐号通过邮箱验证吗？";
        ajaxurl="pass";
		break;
	case 3:
        title='删除记录';
	    content="您确定要删除这条待验证记录吗<br>(删除后可以重新获取authcode以注册)？";
        ajaxurl="del";
		break;
	}
	createWindow().setTitle(title).setContent(content).addButton('确定', function(){ msg_callback_action("plugin.php?id=zw_mailauth&action="+ajaxurl+"&vid="+id,zw_mailauth_load_set); }).addCloseButton('取消').append();
	return false;
}
