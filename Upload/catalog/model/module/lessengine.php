<?php
class ModelModuleLessEngine extends Model {
	
	private $json = array(
		'error'		=> array(),
		'success'	=> array()
	);
	
	private $compiled_files = array();
	
	public function getSetting($group, $store_id = 0) {
		$data = array(); 
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");
		
		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}
		
		return $data;
	}
	
	public function Start($ondemand = false) {
		$this->load->library('lessc.inc');
		
		// Default settings
		$less_files = array();
		
		$settings = array(
			'css_files_directory'		=>	DIR_APPLICATION . "view/theme/default/stylesheet/",
			'less_files_directory'		=>	DIR_APPLICATION . "view/theme/default/stylesheet/less_engine/",
			'imports_files_directory'	=>	DIR_APPLICATION . "view/theme/default/stylesheet/less_engine/imports/",
			'cache_files_directory'		=>	DIR_APPLICATION . "view/theme/default/stylesheet/less_engine/cache/",
			'compress'					=>	1,
			'comments'					=>	0
		);
		
		// Get settings
		if (!$ondemand) {
			/*	DB	*/	$database_settings = $this->getSetting('lessengine');
		} else {
			/* AJAX */	$database_settings = $this->request->post;
		}
		
		if (!empty($database_settings)) {
			$database_settings = $database_settings['lessengine'];
			
			if (!empty($database_settings['css_files_directory'])) {
				$settings['css_files_directory']		= dirname(DIR_APPLICATION) . '/' . $database_settings['css_files_directory'];
				if (substr($settings['css_files_directory'], -1) !== '/') {
					$settings['css_files_directory'] = $settings['css_files_directory'] . '/';
				}
			}
			
			if (!empty($database_settings['less_files_directory'])) {
				$settings['less_files_directory']		= dirname(DIR_APPLICATION) . '/' . $database_settings['less_files_directory'];
				if (substr($settings['less_files_directory'], -1) !== '/') {
					$settings['less_files_directory'] = $settings['less_files_directory'] . '/';
				}
			}
			
			if (!empty($database_settings['imports_files_directory'])) {
				$settings['imports_files_directory']	= dirname(DIR_APPLICATION) . '/' . $database_settings['imports_files_directory'];
				if (substr($settings['imports_files_directory'], -1) !== '/') {
					$settings['imports_files_directory'] = $settings['imports_files_directory'] . '/';
				}
			}
			
			if (!empty($database_settings['cache_files_directory'])) {
				$settings['cache_files_directory']		= dirname(DIR_APPLICATION) . '/' . $database_settings['cache_files_directory'];
				if (substr($settings['cache_files_directory'], -1) !== '/') {
					$settings['cache_files_directory'] = $settings['cache_files_directory'] . '/';
				}
			}
			
			if (isset($database_settings['compress'])) {
				$settings['compress']					= (int)$database_settings['compress'];
			}
			
			if (isset($database_settings['comments'])) {
				$settings['comments']					= (int)$database_settings['comments'];
			}
			
			// Start Your Engines!
			if ($database_settings['Enabled'] == 'yes' && (((int)$database_settings['AutomaticCompile'] == 1 && isset($_SESSION['user_id'])) || $ondemand)) {
				
				$less = new lessc;
				
				// CSS	
				if (!file_exists($settings['css_files_directory']) && !is_dir($settings['css_files_directory'])) {
					mkdir($settings['css_files_directory'], 0755, true);
					if ($ondemand) {
						$this->json['success'][] = "Success: Directory <b>" . $settings['css_files_directory']. "</b> has been created!";
					}
				}
				
				// Cache	
				if (!$ondemand) {
					if (!file_exists($settings['cache_files_directory']) && !is_dir($settings['cache_files_directory'])) {
						mkdir($settings['cache_files_directory'], 0755, true);
					}
				}
				
				// Imports	
				if (!file_exists($settings['imports_files_directory']) && !is_dir($settings['imports_files_directory'])) {
					mkdir($settings['imports_files_directory'], 0755, true);
					
					if ($ondemand) {
						$this->json['success'][] = "Success: Directory <b>" . $settings['imports_files_directory']. "</b> has been created!";
					}
				}
			
				// Scan for less files
				if (!file_exists($settings['less_files_directory']) && !is_dir($settings['less_files_directory'])) {
					mkdir($settings['less_files_directory'], 0755, true);
					
					if ($ondemand) {
						$this->json['success'][] = "Success: Directory <b>" . $settings['less_files_directory']. "</b> has been created! Add some LESS to it.";
					}
					
				} else {
					$less_files = array_diff(scandir($settings['less_files_directory']), array('..', '.'));
					
					if (!empty($less_files)) {
						foreach ($less_files as $less_file) {
							if (!is_dir($settings['less_files_directory'] . $less_file)) {
								if (pathinfo($settings['less_files_directory'] . $less_file, PATHINFO_EXTENSION) == 'less') {
									
									// 3, 2, 1 GO!
									$this->Compile($less_file, $less, $settings, $ondemand);
					
								}
							}
						}
					}	
				}
			}
			
			if ($ondemand && $database_settings['Enabled'] == 'no') {
				$this->json['error'][] = "Error: Enable LessEngine first!";
			}
			
			// Engine Check
			if (!$ondemand) {
				if (!empty($this->json['error'])) {
					$this->session->data['error'] = $this->json['error'][0];
				}
			} else {
				if (empty($this->compiled_files) && empty($this->json['error'])) {
					$this->json['error'][] = "Error: No LESS files found in <b>" . $settings['less_files_directory'] . "</b>";
				}
				
				return $this->json;
			}
		}
	}
	
	private function Compile($less_file, &$less, $settings, $ondemand) {
		$cacheFile = $settings['cache_files_directory'] . $less_file . ".cache";
		$inputFile = $settings['less_files_directory'] . $less_file;
		
		if (file_exists($inputFile)) {
			if (file_exists($cacheFile)) {
				$cache = unserialize(file_get_contents($cacheFile));
			} else {
				$cache = $inputFile;
			}
			
			$less->setImportDir(array($settings['imports_files_directory']));
			
			// Compress
			if ($settings['compress'] == 1) {
				$less->setFormatter("compressed");
			}
			
			// Comments	
			if ($settings['comments'] == 1) {
				$less->setPreserveComments(true);
			}
			
			$ext = ($settings['compress'] == 1) ? '.min.css' : '.css';
			$target = $settings['css_files_directory'] . basename(str_replace('.less', $ext, $less_file));
			
			// Compile
			if (!$ondemand) {
				try {
					$newCache = $less->cachedCompile($cache);
						
					if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
						
						file_put_contents($cacheFile, serialize($newCache));
						file_put_contents($target, $newCache['compiled']);
						
						if (!is_writable($cacheFile)) {
							$this->json['error'][] = "Error: Don't have permissions to write cache file <b>" . $cacheFile. "!</b>";	
						}
						
						if (!is_writable($target)) {
							$this->json['error'][] = "Error: Don't have permissions to write file <b>" . $target. "!</b>";		
						}
					}
				} catch (Exception $ex) {
					$this->json['error'][] = "Error in <i>" . $less_file. "</i>: <b>" . $ex->getMessage(). "</b>";
				}
			} else {
				try {
					$newFile = $less->compileFile($inputFile);
					
					file_put_contents($target, $newFile);
						
					if (is_writable($target)) {
						$this->json['success'][] = "Success: LESS file <b>" . $less_file. "</b> compiled successfully!";
					} else {
						$this->json['error'][] = "Error: Don't have permissions to write file <b>" . $target. "!</b>";	
					}
					
					$this->compiled_files[]		= $less_file;
				} catch (Exception $ex) {
					$this->json['error'][] = "Error in <i>" . $less_file. "</i>: <b>" . $ex->getMessage() . "!</b>";
				}
			}
		}
	}
}	
?>