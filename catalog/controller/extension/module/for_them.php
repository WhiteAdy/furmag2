<?php
class ControllerExtensionModuleForThem extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/for_them');

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		$this->load->model('tool/image');

		$this->document->addScript('catalog/view/javascript/swiper/swiper.min.js');
		$this->document->addStyle('catalog/view/theme/furmag2/stylesheet/swiper.min.css');

		$data['products'] = array();

		$data['carousel_id'] = $setting['carousel_id'];

		if (isset($setting['title'])) {
			$data['heading_title'] = $setting['title'];
		}

		if (!empty($setting['product'])) {
			$products = $setting['product'];

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				$product_images = $this->model_catalog_product->getProductImages($product_id);
				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					if(isset($product_images[1])) {
						$secondary_image = $product_images[1]['image'];
					}
					else {
						$secondary_image = false;
					}

					$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
						'secondary_image' => $this->model_tool_image->resize($secondary_image, $setting['width'], $setting['height'])
					);
				}
			}
		}

		if (!empty($setting['category'])) {
			$categories = $setting['category'];
			
			foreach($categories as $category_id) {
				$category_info = $this->model_catalog_category->getCategory($category_id);
				if ($category_info) {
					$data['categories'][] = array(
						'category_id' => $category_info['category_id'],
						'name'		  => $category_info['name'],
						'href'		  => $this->url->link('product/category', 'path=' . $category_info['category_id']) 
					);
				}
			}
		}

		if ($data['products']) {
			return $this->load->view('extension/module/for_them', $data);
		}
	}
}