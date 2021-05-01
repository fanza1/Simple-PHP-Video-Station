<?php if (!defined('_IN_STATION_')) die('Access Denied');
	include 'template/header.php'; ?>

<h1 id="title"><? echo $global['site']['name']; ?></h1>

<? if (!$admin['isAdmin']) { ?>
	<div class="mdui-card mdui-hoverable">
		<div class="mdui-card-content" style="padding-bottom:0">
			<p style="margin: 0 4px 4px 4px;">您还未登录，请立即登录。登录后才可进行管理操作。</p>
			
			<div class="mdui-textfield mdui-textfield-floating-label" style="padding-top:0">
				<i class="mdui-icon material-icons">account_circle</i>
				<label class="mdui-textfield-label">管理用户名</label>
				<input id="admin_account" class="mdui-textfield-input" type="username" required />
				<div class="mdui-textfield-error">请输入用户名</div>
			</div>
			<div class="mdui-textfield mdui-textfield-floating-label" style="padding-top:0">
				<i class="mdui-icon material-icons">lock</i>
				<label class="mdui-textfield-label">管理密码</label>
				<input id="admin_password" class="mdui-textfield-input" type="password" required />
				<div class="mdui-textfield-error">请输入密码</div>
			</div>
		</div>
		<div class="mdui-card-actions">
			<button onclick="adminLogin()" class="mdui-btn mdui-ripple mdui-text-color-theme-accent mdui-float-right">登录</button>
		</div>
	</div>
	<script>
		function adminLogin() {
			$('#mdui_loading').removeClass('mdui-hidden');
			mdui.$.showOverlay();
			
			mdui.$.ajax({
				method : 'POST',
				url : './data/admin.php',
				data : {
					type : 'login',
					user : document.getElementById('admin_account').value,
					password : document.getElementById('admin_password').value,
					userhash : userHash
				},
				complete : function(xhr, textStatus) {
					$('#mdui_loading').addClass('mdui-hidden');
					mdui.$.hideOverlay();
				},
				success : function(data, textStatus, xhr) {
					if (data == '0') {
						mdui.alert('登录成功', '提示', function() {
							document.getElementById('JumpLink').href = 'admin.php';
							document.getElementById('JumpLink').click();
						});
					} else {
						mdui.alert('登录失败，错误码：' + data, '提示');
					}
				},
				error : function(xhr, textStatus) {
					mdui.alert('登录失败', '提示');
				}
			});
			
		}
	</script>
	<br>
<? } ?>

<div class="mdui-tab" mdui-tab>
	<a href="#global" class="mdui-ripple">全局设置</a>
	<a href="#media" class="mdui-ripple">媒体设置</a>
</div>

<div id="global" class="mdui-p-a-2">
	<div class="mdui-textfield">
		<label class="mdui-textfield-label">站点名称</label>
		<input class="mdui-textfield-input" id="siteName" type="text" value="<? echo $admin['name']; ?>" required/>
	</div>
	
	<div class="mdui-textfield">
		<label class="mdui-textfield-label">站点关键词</label>
		<input class="mdui-textfield-input" id="siteKeyw" type="text" value="<? echo $admin['keywords']; ?>" placeholder="用于 SEO 优化，多个请用,隔开，可不填" />
	</div>
	
	<div class="mdui-textfield">
		<label class="mdui-textfield-label">站点描述</label>
		<input class="mdui-textfield-input" id="siteDesc" type="text" value="<? echo $admin['description']; ?>" placeholder="用于 SEO 优化，可不填" />
	</div>
	
	<div class="mdui-textfield">
		<label class="mdui-textfield-label">站点作者</label>
		<input class="mdui-textfield-input" id="siteAuth" type="text" value="<? echo $admin['author']; ?>" placeholder="用于 SEO 优化，可不填" />
	</div>
	
	<div class="mdui-textfield">
		<label class="mdui-textfield-label">站点关于</label>
		<textarea class="mdui-textfield-input" id="siteAbou" placeholder="站点关于页内容，支持 HTML，可不填"><? echo $admin['about']; ?></textarea>
	</div>
	
	<div class="mdui-textfield">
		<label class="mdui-textfield-label">站点页脚</label>
		<textarea class="mdui-textfield-input" id="siteFoot" placeholder="支持 HTML，可不填"><? echo $admin['footer']; ?></textarea>
	</div>
	
	<div class="mdui-card-actions">
		<? if ($admin['isAdmin']) { ?>
			<button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent mdui-float-right" onclick="submitGlobalChanges()">提交修改</button>
		<? } else { ?>
			<button class="mdui-btn mdui-ripple mdui-text-color-red mdui-float-right">您还没有登录</button>
		<? } ?>
	</div>
</div>

<div id="media" class="mdui-p-a-2">
	<h1>	站点公告</h1>
	<div class="mdui-card mdui-hoverable">
		<div class="mdui-card-content">
			<div class="mdui-typo">
				<? if (!empty($admin['notice'])) {
					 echo $global['site']['notice'];
				} else { ?>
					<center class="mdui-text-color-theme-disabled">你还没有设置站点公告</center>
				<? } ?>
			</div>
		</div>
		
		<div class="mdui-card-actions">
			<? if ($admin['isAdmin']) { ?>
				<button onclick="changeNotice()" class="mdui-btn mdui-ripple mdui-text-color-theme-accent mdui-float-right">修改公告</button>
			<? } else { ?>
				<button class="mdui-btn mdui-ripple mdui-text-color-red mdui-float-right">您还没有登录</button>
			<? } ?>
		</div>
	</div>
	<br>
	
	<h1>视频列表</h1>
	<div class="mdui-card mdui-hoverable">
		<div class="mdui-card-content">
			<div class="mdui-container-fluid" style="padding:0 0 0 0;margin-top:5px;">
				<div class="mdui-row" style="margin:0 0 0 0;">
					<? if (isset($admin['videos'])) {
							for ($i = 0; $i < count($admin['videos']); $i++) { ?>
								<div class="mdui-col-xs-12 mdui-col-md-6" style="padding:0 0 0 0;">
									<a href="javascript:editMedia(<? echo $admin['videos'][$i]['id']; ?>);" class="mdui-btn mdui-ripple mdui-center mdui-text-color-theme-accent mdui-text-truncate"><? echo $admin['videos'][$i]['name']; ?></a>
								</div>
						<? }
						} else { ?>
							<center><p>当前没找到视频～</p></center>
					<? } ?>
				</div>
			</div>
		</div>
		
		<div class="mdui-card-actions">
			<? if ($admin['isAdmin']) { ?>
				<button onclick="addMedia()" class="mdui-btn mdui-ripple mdui-text-color-theme-accent mdui-float-right">添加视频</button>
			<? } else { ?>
				<button class="mdui-btn mdui-ripple mdui-text-color-red mdui-float-right">您还没有登录</button>
			<? } ?>
		</div>
	</div>
</div>

<script>
	var userHash = '<? echo $_SESSION['user']; ?>';
</script>

</div>
<br>
<? include "template/footer.php"; ?>
