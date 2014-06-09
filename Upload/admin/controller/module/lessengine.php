<?php
//ADMIN
class ControllerModuleLessEngine extends Controller {

    public function index() { 
        $this->load->language('module/lessengine');
        $this->load->model('module/lessengine');
        $this->load->model('setting/store');
        $this->load->model('localisation/language');
        $this->load->model('design/layout');
        $catalogURL = $this->getCatalogURL();
 
        $this->document->addScript($catalogURL . 'admin/view/javascript/lessengine/bootstrap/js/bootstrap.min.js');
        $this->document->addStyle($catalogURL  . 'admin/view/javascript/lessengine/bootstrap/css/bootstrap.min.css');
        $this->document->addStyle($catalogURL  . 'admin/view/stylesheet/lessengine/font-awesome/css/font-awesome.min.css');
        $this->document->addStyle($catalogURL  . 'admin/view/stylesheet/lessengine/lessengine.css');
        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) { 
            if (!$this->user->hasPermission('modify', 'module/lessengine')) {
                $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
            }

            if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post['lessengine']['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
            }

            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post['lessengine']['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
            }
			
			if ($this->user->hasPermission('modify', 'module/lessengine')) {
            	$this->model_module_lessengine->editSetting('lessengine', $this->request->post);
				$this->session->data['success'] = $this->language->get('text_success');
				$this->redirect($this->url->link('module/lessengine', 'token=' . $this->session->data['token'], 'SSL'));
			}
        }

        if (isset($this->error['code'])) {
            $this->data['error_code'] = $this->error['code'];
        } else {
            $this->data['error_code'] = '';
        }

        $this->data['breadcrumbs']   = array();
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/lessengine', 'token=' . $this->session->data['token'], 'SSL'),
        );

        $languageVariables = array(
            'heading_title',
            'text_module',
            'text_success',
            'entry_code',
            'error_permission',
            'error_input_form',
            'text_yes',
            'text_no',
            'text_enabled',
            'text_disabled',
            'entry_yes',
            'entry_no',
            'error_permission',
            'entry_layout_options',
            'entry_position_options',
            'text_content_top',         
            'text_content_bottom',       
            'text_column_left',         
            'text_column_right',     
            'text_default',  
            'text_custom',
			'text_developer_info',
			'text_automatic',
			'text_css_directory',
			'text_less_directory',
			'text_imports_directory',
			'text_cache_directory',
			'text_default_css',
			'text_default_less',
			'text_default_imports',
			'text_default_cache',
			'text_info_directory',
			'text_info_files',
			'text_info_cache',
			'text_comments',
			'text_compress',
            'entry_layout', 
            'entry_position',
            'entry_status', 
            'entry_sort_order',
            'entry_layout_options',
            'entry_position_options',
            'text_content_top',
            'text_content_bottom',
            'text_column_left',
            'text_column_right',
            'button_add_module',
            'button_remove',
            'text_settings',
            'text_support',
            'text_button_tooltip',
            'button_cancel',
            'button_compile',
            'save_changes'
        );
       
        foreach ($languageVariables as $languageVariable) {
            $this->data[$languageVariable] = $this->language->get($languageVariable);
        }
		
        $this->data['modules'] = array();
        
        if ($this->config->get('lessengine_module')) { 
            $this->data['modules'] = $this->config->get('lessengine_module');
        }
        
        $this->data['error_warning']          = '';  
        $this->data['token']                  = $this->session->data['token'];
        $this->data['action']                 = $this->url->link('module/lessengine', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel']                 = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['data']                   = $this->model_module_lessengine->getSetting('lessengine');
		
		if (!empty($this->data['data']['lessengine']['less_files_directory'])) {
			$this->data['less_files_directory'] = $this->data['data']['lessengine']['less_files_directory'];	
		} else {
			$this->data['less_files_directory']	= '';
		}
		
		if (!empty($this->data['data']['lessengine']['css_files_directory'])) {
			$this->data['css_files_directory'] = $this->data['data']['lessengine']['css_files_directory'];	
		} else {
			$this->data['css_files_directory']	= '';
		}
		
		if (!empty($this->data['data']['lessengine']['cache_files_directory'])) {
			$this->data['cache_files_directory'] = $this->data['data']['lessengine']['cache_files_directory'];	
		} else {
			$this->data['cache_files_directory']	= '';
		}
		
		if (!empty($this->data['data']['lessengine']['imports_files_directory'])) {
			$this->data['imports_files_directory'] = $this->data['data']['lessengine']['imports_files_directory'];	
		} else {
			$this->data['imports_files_directory']	= '';
		}
		
        $this->data['layouts']                = $this->model_design_layout->getLayouts();
        $this->template = 'module/lessengine.tpl';
        $this->children = array('common/header','common/footer');
        $this->response->setOutput($this->render());
    }
	
	private function getCatalogURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_CATALOG;
        } else {
            $storeURL = HTTP_CATALOG;
        } 
        return $storeURL;
    }

    private function getServerURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_SERVER;
        } else {
            $storeURL = HTTP_SERVER;
        } 
        return $storeURL;
    }
}

?>