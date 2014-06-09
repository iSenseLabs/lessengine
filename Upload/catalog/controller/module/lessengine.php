<?php class ControllerModuleLessEngine extends Controller  {
	public function compile() {
		$token = $this->request->get['token'];
		
		if (isset($_SESSION['token']) && !empty($token) && $token == $_SESSION['token']) {
			
			// LessEngine
			$this->load->model('module/lessengine');
			$this->response->setOutput(json_encode($this->model_module_lessengine->Start(true)));
		}
	}
}
?>