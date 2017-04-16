<?php
if (!defined('IN_KKFRAME')) exit('Access Denied!');

class plugin_zw_custompage extends Plugin {
	var $description = '本插件可以在前台添加页面、页底代码和修改背景。';
	var $modules = array();
	var $version = '1.2.3';
	private $setting;
	private $background;

	function install() {
		runquery("CREATE TABLE IF NOT EXISTS `zw_custompage_pages` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL DEFAULT '自定义页面',
`content` text,
`pswitch` tinyint(1) NOT NULL DEFAULT 1,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		$this -> saveSetting('setting', json_encode(array('page_switch' => 1,
					'footer_js_switch' => 1,
					'footer_text_switch' => 1,
					'bg_switch' => 0,
					'page_footer_js' => '',
					'page_footer_text' => '',
					'bg_img' => '',
					'min_bg' => '',
					'max_bg' => '',
					)));
	} 

	function uninstall() {
		runquery("DROP TABLE `zw_custompage_pages`;
DELETE FROM `setting` WHERE `k` LIKE 'zw_custompage%';
DELETE FROM `plugin_var` WHERE `pluginid`='zw_custompage';
");
	} 

	function on_install() { // 兼容模式
		$this -> install();
	} 

	function on_uninstall() { // 兼容模式
		$this -> uninstall();
	} 

	function getMethods() {
		$this -> setting = json_decode($this -> getSetting('setting'), true);
		if ($this -> setting['bg_switch'] == 1) {
			$this -> image = trim ($this -> setting['bg_img']);
			$this -> background = rand((int)trim ($this -> setting['min_bg']), (int)trim ($this -> setting['max_bg']));
		} 
		$query = DB :: query ("SELECT * FROM zw_custompage_pages WHERE pswitch=1");
		while ($result = DB :: fetch ($query)) {
			$pages [] = $result;
		} 
		if ($this -> setting['page_switch'] == 1) {
			foreach($pages as $page) {
				$modules[] = array('id' => $page['id'],
					'type' => 'page',
					'title' => $page['title'],
					'file' => 'index.inc.php'
					);
			} 
		} 
		$modules[] = array('id' => 'admin',
			'type' => 'page',
			'title' => '自定义页面管理',
			'file' => 'admin.inc.php',
			'admin' => 1
			);
		return $modules;
	} 

	function page_footer_js() {
		global $uid;
		if (is_admin($uid)) echo '<script src="plugins/zw_custompage/zw_custompage.js"></script>';
		if ($this -> setting['footer_js_switch'] == 1) echo $this -> setting['page_footer_js'];
		if ($this -> setting['bg_switch'] == 1) {
			if (!is_mobile_request()) {
				echo "<script type='text/javascript'>$('#page_index').css({'background':'url({$this -> image}{$this -> background}.jpg)','background-size':'100% 100%','background-attachment':'fixed','color':'#e5e5e5'});</script>";
			}
		} 
	} 

	function page_footer() {
		if ($this -> setting['footer_text_switch'] == 1) echo $this -> setting['page_footer_text'];
	} 

	function member_footer() {
		if ($this -> setting['footer_switch'] == 1) echo $this -> setting['page_footer_js'];
		if ($this -> setting['bg_switch'] == 1) {
			if (!is_mobile_request()) {
				echo "<script src='" . jquery_path() . "'></script><script type='text/javascript'>$('#page_login').css({'background':'url({$this -> image}{$this -> background}.jpg) no-repeat 50% 50%','background-size':'100% 100%'});</script>";
			} else {echo "<script src='" . jquery_path() . "'></script>";}
		} 
	} 

	function handleAction() {
		global $uid;
		if (!is_admin($uid)) exit('Access Denied');
		$data = array();
		$data['msgx'] = 0;
		switch ($_GET['action']) {
			case 'getsetting':
				$query = DB :: query("SELECT * FROM `zw_custompage_pages`");
				while ($result = DB :: fetch ($query)) {
					$result['title'] = strip_tags(trim($result['title']));
					$result['content'] = strip_tags(trim($result['content']));
					$result['content'] = cutstr($result['content'], 50, '...');
					$data ['pages'] [] = $result;
				} 
				$data ['count'] = count($data ['pages']);
				$setting = json_decode($this -> getSetting('setting'), true);
				$data ['setting'] = $setting ? $setting : array("page_switch" => 1, "footer_js_switch" => 1, "footer_text_switch" => 1, "bg_switch" => 0, "page_footer_js" => "", "page_footer_text" => "", "bg_img" => "", "min_bg" => "", "max_bg" => ""
					);
				break;
			case 'savesetting':
				$this -> saveSetting('setting', json_encode(array('page_switch' => $_POST['page_switch'] == 1?1:0,
							'footer_js_switch' => $_POST['footer_js_switch'] == 1?1:0,
							'footer_text_switch' => $_POST['footer_text_switch'] == 1?1:0,
							'bg_switch' => $_POST['bg_switch'] == 1?1:0,
							'page_footer_js' => trim($_POST['page_footer_js']),
							'page_footer_text' => trim($_POST['page_footer_text']),
							'bg_img' => trim($_POST['bg_img']),
							'min_bg' => trim($_POST['min_bg']),
							'max_bg' => trim($_POST['max_bg']),
							)));
				$data['msg'] = '保存成功！';
				break;
			case 'addpage':
				DB :: insert('zw_custompage_pages', array('title' => daddslashes(trim($_POST['page_title'])),
						'content' => daddslashes(trim($_POST['page_content'])),
						'pswitch' => $_POST['this_page_switch'] == 1 ? 1 : 0,
						));
				$data['msg'] = '添加成功！';
				break;
			case 'delall':
				DB :: query('TRUNCATE TABLE zw_custompage_pages;');
				$data['msg'] = '已经全部删除！';
				break;
			case 'allable':
				DB :: query('UPDATE `zw_custompage_pages` SET  `pswitch` = 1 WHERE `pswitch` = 0');
				$data['msg'] = '已经全部启用！';
				break;
			case 'allunable':
				DB :: query('UPDATE `zw_custompage_pages` SET  `pswitch` = 0 WHERE `pswitch` = 1');
				$data['msg'] = '已经全部关闭！';
				break;
			case 'turnedtoother':
				DB :: query("UPDATE `zw_custompage_pages`  SET pswitch=1-pswitch");
				$data['msg'] = '已经反向开启/关闭所有页面！';
				break;
			case 'setpage':
				DB :: query("UPDATE `zw_custompage_pages` SET  `title` =   '" . daddslashes(trim($_POST['page_title'])) . "',`content`  =  '" . daddslashes(trim($_POST['page_content'])) . "',`pswitch` =" . ($_POST['this_page_switch'] == 1 ? 1 : 0) . " WHERE id=" . intval($_GET['pid']));
				$data['msg'] = '保存成功！';
				break;
			case 'getpage':
				$result = DB :: fetch_first("SELECT * FROM `zw_custompage_pages` WHERE id=" . intval($_GET['pid']));
				$data ['this_page'] = $result;
				break;
			case 'delpage':
				DB :: query("DELETE FROM `zw_custompage_pages` WHERE id=" . intval($_GET['pid']));
				$data['msg'] = '删除成功！';
				break;
			default:
				$data['msg'] = '没有指定Action！！';
		} 
		echo json_encode ($data);
	} 
} 



function is_mobile_request()  
{  
 $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
 $mobile_browser = '0';  
 if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
  $mobile_browser++;  
 if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
  $mobile_browser++;  
 if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
  $mobile_browser++;  
 if(isset($_SERVER['HTTP_PROFILE']))  
  $mobile_browser++;  
 $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
 $mobile_agents = array(  
    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
    'wapr','webc','winw','winw','xda','xda-'
    );  
 if(in_array($mobile_ua, $mobile_agents))  
  $mobile_browser++;  
 if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
  $mobile_browser++;  
 // Pre-final check to reset everything if the user is on Windows  
 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
  $mobile_browser=0;  
 // But WP7 is also Windows, with a slightly different characteristic  
 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
  $mobile_browser++;  
 if($mobile_browser>0)  
  return true;  
 else
  return false;
}