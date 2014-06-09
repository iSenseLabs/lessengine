<div class="tab-pane active" id="main_settings">
	<table class="form">
		<tr>
			<td colspan="2">
				<a class="btn btn-primary btn-lg pull-right" id="compile" data-toggle="tooltip" data-placement="bottom" title="<?php echo $text_button_tooltip; ?>"><i class="fa fa-cogs fa-lg"></i>&nbsp;&nbsp;<?php echo $button_compile; ?></a>
				<h5 style="margin:0;"><?php echo $text_developer_info; ?></h5><br />
			</td>
		</tr>
		<tr>
			<td><label class="h5" for="lessengine_Enabled"><span class="required">* </span><?php echo $entry_code; ?></label></td>
			<td><select id="lessengine_Enabled" name="lessengine[Enabled]" class="form-control">
					<option value="yes" <?php echo (!empty($data['lessengine']['Enabled']) && $data['lessengine']['Enabled'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled;?></option>
					<option value="no"  <?php echo (empty($data['lessengine']['Enabled']) || $data['lessengine']['Enabled']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled;?></option>
				</select></td>
		</tr>
		<tr>
			<td><label class="h5" for="lessengine_AutomaticCompile"><span class="required">* </span><?php echo $text_automatic; ?></label></td>
			<td><select id="lessengine_AutomaticCompile" name="lessengine[AutomaticCompile]" class="form-control">
					<option value="1" <?php echo (!empty($data['lessengine']['AutomaticCompile']) && $data['lessengine']['AutomaticCompile'] == '1') ? 'selected=selected' : '' ?>><?php echo $text_enabled;?></option>
					<option value="0"  <?php echo (empty($data['lessengine']['AutomaticCompile']) || $data['lessengine']['AutomaticCompile'] == '0') ? 'selected=selected' : '' ?>><?php echo $text_disabled;?></option>
				</select></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info" style="margin:0;"><i class="fa fa-info fa-lg"></i>&nbsp;&nbsp;<?php echo $text_info_files; ?></div></td>
		</tr>
		<tr>
			<td><label class="h5" for="lessengine_less_files_directory"><i class="fa fa-folder-open-o fa-lg"></i>&nbsp;&nbsp;<?php echo $text_less_directory; ?></label></td>
			<td><input class="form-control" type="text" id="lessengine_less_files_directory" name="lessengine[less_files_directory]" value="<?php echo $less_files_directory; ?>" placeholder="<?php echo $text_default_less; ?>"/></td>
		</tr>
		<tr>
			<td><label class="h5" for="lessengine_css_files_directory"><i class="fa fa-folder-open-o fa-lg"></i>&nbsp;&nbsp;<?php echo $text_css_directory; ?></label></td>
			<td><input class="form-control" type="text" id="lessengine_css_files_directory" name="lessengine[css_files_directory]" value="<?php echo $css_files_directory; ?>" placeholder="<?php echo $text_default_css; ?>"/></td>
		</tr>
		<tr>
			<td><label class="h5" for="lessengine_cache_files_directory"><i class="fa fa-folder-open-o fa-lg"></i>&nbsp;&nbsp;<?php echo $text_cache_directory; ?></label>
				<div class="alert alert-warning" style="margin:0;"><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?php echo $text_info_cache; ?></div></td>
			<td><input class="form-control" type="text" id="lessengine_cache_files_directory" name="lessengine[cache_files_directory]" value="<?php echo $cache_files_directory; ?>" placeholder="<?php echo $text_default_cache; ?>"/></td>
		</tr>
		<tr>
			<td><label class="h5" for="lessengine_imports_files_directory"><i class="fa fa-folder-open-o fa-lg"></i>&nbsp;&nbsp;<?php echo $text_imports_directory; ?></label></td>
			<td><input class="form-control" type="text" id="lessengine_imports_files_directory" name="lessengine[imports_files_directory]" value="<?php echo $imports_files_directory; ?>" placeholder="<?php echo $text_default_imports; ?>"/></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info" style="margin:0;"><i class="fa fa-info fa-lg"></i>&nbsp;&nbsp;<?php echo $text_info_directory; ?></div></td>
		</tr>
		<tr>
			<td><label class="h5" for="lessengine_compress"><i class="fa fa-compress fa-lg"></i>&nbsp;&nbsp;<?php echo $text_compress; ?></label></td>
			<td><select id="lessengine_compress" name="lessengine[compress]" class="form-control">
					<option value="1" <?php echo (!empty($data['lessengine']['compress']) && $data['lessengine']['compress'] == '1') ? 'selected=selected' : '' ?>><?php echo $text_enabled;?></option>
					<option value="0"  <?php echo (empty($data['lessengine']['compress']) || $data['lessengine']['compress'] == '0') ? 'selected=selected' : '' ?>><?php echo $text_disabled;?></option>
				</select></td>
		</tr>
		<tr>
			<td><label class="h5" for="lessengine_comments"><i class="fa fa-comment-o fa-lg"></i>&nbsp;&nbsp;<?php echo $text_comments; ?></label></td>
			<td><select id="lessengine_comments" name="lessengine[comments]" class="form-control">
					<option value="1" <?php echo (!empty($data['lessengine']['comments']) && $data['lessengine']['comments'] == '1') ? 'selected=selected' : '' ?>><?php echo $text_enabled;?></option>
					<option value="0"  <?php echo (empty($data['lessengine']['comments']) || $data['lessengine']['comments'] == '0') ? 'selected=selected' : '' ?>><?php echo $text_disabled;?></option>
				</select></td>
		</tr>
	</table>
</div>
