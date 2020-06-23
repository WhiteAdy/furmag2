<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));
		$this->load->model('setting/module');
		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$data['banners'] = array();

		$slideshow_modules = $this->model_setting_module->getModulesByCode('slideshow');
		if(isset($slideshow_modules[0])) {
			$settings = json_decode($slideshow_modules[0]['setting'], true);
			if($settings['fullwidth_homepage']) {
				$results = $this->model_design_banner->getBanner($settings['banner_id']);
				foreach ($results as $result) {
					if (is_file(DIR_IMAGE . $result['image'])) {
						$image = 'image/' . $result['image'];
						$data['banners'][] = array(
							'title' => $result['title'],
							'link'  => $result['link'],
							'image' => $image
						);
					}
				}
			}
		}

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['slider_fullwidth'] = $data['banners'];

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
