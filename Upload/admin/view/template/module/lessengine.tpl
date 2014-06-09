<?php echo $header;?>
<div id="content">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
	<div id="notification">
		<?php echo (empty($data['lessengine']['LicensedOn'])) ? base64_decode('ICAgIDxkaXYgY2xhc3M9ImFsZXJ0IGFsZXJ0LWRhbmdlciBmYWRlIGluIj4NCiAgICAgICAgPGJ1dHRvbiB0eXBlPSJidXR0b24iIGNsYXNzPSJjbG9zZSIgZGF0YS1kaXNtaXNzPSJhbGVydCIgYXJpYS1oaWRkZW49InRydWUiPsOXPC9idXR0b24+DQogICAgICAgIDxoND5XYXJuaW5nISBVbmxpY2Vuc2VkIHZlcnNpb24gb2YgdGhlIG1vZHVsZSE8L2g0Pg0KICAgICAgICA8cD5Zb3UgYXJlIHJ1bm5pbmcgYW4gdW5saWNlbnNlZCB2ZXJzaW9uIG9mIHRoaXMgbW9kdWxlISBZb3UgbmVlZCB0byBlbnRlciB5b3VyIGxpY2Vuc2UgY29kZSB0byBlbnN1cmUgcHJvcGVyIGZ1bmN0aW9uaW5nLCBhY2Nlc3MgdG8gc3VwcG9ydCBhbmQgdXBkYXRlcy48L3A+PGRpdiBzdHlsZT0iaGVpZ2h0OjVweDsiPjwvZGl2Pg0KICAgICAgICA8YSBjbGFzcz0iYnRuIGJ0bi1kYW5nZXIiIGhyZWY9ImphdmFzY3JpcHQ6dm9pZCgwKSIgb25jbGljaz0iJCgnYVtocmVmPSNzdXBwb3J0XScpLnRyaWdnZXIoJ2NsaWNrJykiPkVudGVyIHlvdXIgbGljZW5zZSBjb2RlPC9hPg0KICAgIDwvZGl2Pg==') : '' ?>
		<?php if ($error_warning) { ?>
			<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">×</button>
				<i class="fa fa-exclamation"></i>&nbsp;&nbsp;<?php echo $error_warning; ?>
			</div>
		<?php } ?>
			<div class="alert alert-danger alert-dismissable" style="display:none;" id="formError"><button type="button" class="close" data-dismiss="alert">×</button>
				<i class="fa fa-exclamation"></i>&nbsp;&nbsp;<?php echo $error_input_form; ?>
			</div>
		<?php if (!empty($this->session->data['success'])) { ?>
			<div class="alert alert-success autoSlideUp alert-dismissable"><button type="button" class="close" data-dismiss="alert">×</button>
				<i class="fa fa-check"></i>&nbsp;&nbsp;<?php echo $this->session->data['success']; ?>
			</div>
			<script type="text/javascript">
				$('.autoSlideUp').delay(3000).fadeOut(600, function(){ $(this).show().css({'visibility':'hidden'}); }).slideUp(600);
			</script>
		<?php $this->session->data['success'] = null; } ?>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">
				<img src="view/image/lessengine/lessengine_admin_icon.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>"/>
				<?php echo $heading_title; ?>
			</h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div class="tabbable">
					<div class="tab-navigation form-inline">
						<ul class="nav nav-tabs mainMenuTabs">
							<li class="active"><a href="#main_settings" data-toggle="tab"><i class="fa fa-cog"></i>&nbsp;&nbsp;<?php echo $text_settings; ?></a></li>
							<li><a href="#support" data-toggle="tab"><i class="glyphicon glyphicon-share"></i>&nbsp;&nbsp;<?php echo $text_support; ?></a></li>
						</ul>
						<div class="tab-buttons">
							<button type="submit" class="btn btn-success save-changes"><?php echo $save_changes; ?></button>
							<a onclick="location = '<?php echo $cancel; ?>'" class="btn btn-warning"><?php echo $button_cancel; ?></a> </div>
					</div>
					<!-- /.tab-navigation -->
					<div class="tab-content">
						<?php require_once(DIR_APPLICATION.'view/template/module/lessengine/tab_settings.php'); ?>
						<?php require_once(DIR_APPLICATION.'view/template/module/lessengine/tab_support.php'); ?>
						<!-- /.tab-content --> 
					</div>
				</div>
				<!-- /.tabbable -->
			</form>
		</div>
	</div>
</div>
<script>
    if (window.localStorage && window.localStorage['currentTab']) {
        $('.mainMenuTabs a[href='+window.localStorage['currentTab']+']').trigger('click');  
    }
    if (window.localStorage && window.localStorage['currentSubTab']) {
        $('a[href='+window.localStorage['currentSubTab']+']').trigger('click');  
    }
    $('.fadeInOnLoad').css('visibility','visible');
    $('.mainMenuTabs a[data-toggle="tab"]').click(function() {
        if (window.localStorage) {
            window.localStorage['currentTab'] = $(this).attr('href');
        }
    });

    $('a[data-toggle="tab"]:not(.mainMenuTabs a[data-toggle="tab"])').click(function() {
        if (window.localStorage) {
            window.localStorage['currentSubTab'] = $(this).attr('href');
        }
    });
	
	$(document).ready(function(e) {
		$('#compile').tooltip();
		
		$('#compile').live('click',function(){
			$.ajax({
				url:  '<?php echo HTTP_CATALOG; ?>index.php?route=module/lessengine/compile&token=' + getURLVar('token'),
				type: 'post',
				data: $('#form input, #form select'),
				dataType: 'json',
				success: function(json) {
					if (json['success'].length > 0) {
						for (i in json['success']) {
							$('#notification').append('<div class="alert alert-success json alert-dismissable"><button type="button" class="close" data-dismiss="alert">×</button><i class="fa fa-check"></i>&nbsp;&nbsp;'+ json['success'][i] +'</div>');
						}
						
						setTimeout(function(){
							$('.alert-success.json').fadeOut(600, function(){ $(this).show().css({'visibility':'hidden'}); }).slideUp(600,function(){
								$('.alert-success.json').remove();
							});
						}, 10000);
					}
					
					if (json['error'].length > 0) {
						for (i in json['error']) {
							$('#notification').append('<div class="alert alert-danger json alert-dismissable"><button type="button" class="close" data-dismiss="alert">×</button><i class="fa fa-exclamation"></i>&nbsp;&nbsp;'+ json['error'][i] +'</div>');
						}
						
						setTimeout(function(){
							$('.alert-danger.json').fadeOut(600, function(){ $(this).show().css({'visibility':'hidden'}); }).slideUp(600,function(){
								$('.alert-danger.json').remove();
							});
						}, 10000);
					}
				}
			});
		});
	});
	
</script> 
<?php echo $footer; ?>