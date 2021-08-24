<?php
require_once ('db.php');
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2021 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * @category plugins
 * @package counter
 * @copyright MAGIX CMS Copyright (c) 2008 - 2015 Gerits Aurelien, http://www.magix-cms.com, http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author: Salvatore Di Salvo
 * @name plugins_counter_admin
 */
class plugins_counter_admin extends plugins_counter_db
{
	/**
	 * @var object
	 */
	protected
		$data,
		$template,
		$message,
		$plugins,
		$modelLanguage,
		$collectionLanguage,
		$header,
		$settings,
		$setting;

	/**
	 * @var string
	 */
	protected
        $controller;

	/**
	 * @var string $action
	 * @var string $tabs
	 */
	public
		$action,
		$tabs;

	/**
	 * @var array $counter
	 * @var array $order
	 */
	public
        $counter,
        $order;

    /**
     * @var integer $edit
     * @var integer $id
     */
    public
        $edit,
        $id;

    /**
	 * Construct class
	 */
	public function __construct()
    {
		$this->template = new backend_model_template();
		$this->plugins = new backend_controller_plugins();
		$this->message = new component_core_message($this->template);
		$this->modelLanguage = new backend_model_language($this->template);
		$this->collectionLanguage = new component_collections_language();
		$this->data = new backend_model_data($this);
		$this->settings = new backend_model_setting();
		$this->setting = $this->settings->getSetting();
		$this->header = new http_header();

		$formClean = new form_inputEscape();

		// --- GET
        //@ToDo Try isRequest with an key set on both get and post to see which one is dominant. It should be the value of the post
        //if (http_request::isGet('action')) $this->action = $formClean->simpleClean($_GET['action']);
        //elseif (http_request::isPost('action')) $this->action = $formClean->simpleClean($_POST['action']);
        if (http_request::isRequest('action')) $this->action = $formClean->simpleClean($_REQUEST['action']);
		if (http_request::isGet('controller')) $this->controller = $formClean->simpleClean($_GET['controller']);
		if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
		if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);

		// --- ADD or EDIT
		if (http_request::isPost('counter')) $this->counter = $formClean->arrayClean($_POST['counter']);
		if (http_request::isPost('id')) $this->id = $formClean->simpleClean($_POST['id']);

		// --- Order
        if (http_request::isPost('counters')) $this->order = $formClean->arrayClean($_POST['counters']);
	}

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName(): string
    {
		return $this->template->getConfigVars('counter_plugin');
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	private function getItems(string $type, $id = null, $context = null, $assign = true)
    {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	/**
	 * @param array $data
	 * @return array
	 */
	private function setCounterData(array $data): array
    {
		$arr = [];
		if(!empty($data)) {
            foreach ($data as $counter) {
                if (!array_key_exists($counter['id_counter'], $arr)) {
                    $arr[$counter['id_counter']] = [];
                    $arr[$counter['id_counter']]['id_counter'] = $counter['id_counter'];
                    $arr[$counter['id_counter']]['number_counter'] = $counter['number_counter'];
                }

                $arr[$counter['id_counter']]['content'][$counter['id_lang']] = [
                    'id_lang' => $counter['id_lang'],
                    'title_counter' => $counter['title_counter'],
                    'desc_counter' => $counter['desc_counter'],
                    'url_counter' => $counter['url_counter'],
                    'blank_counter' => $counter['blank_counter']
                ];
            }
        }
		return $arr;
	}

	/**
	 * Insert data
	 * @param array $config
	 */
	private function add(array $config)
    {
		switch ($config['type']) {
			case 'counter':
			case 'counterContent':
				parent::insert(
					['type' => $config['type']],
					$config['data']
				);
				break;
		}
	}

	/**
	 * Update data
	 * @param array $config
	 */
	private function upd(array $config)
    {
		switch ($config['type']) {
			case 'counter':
			case 'counterContent':
			case 'order':
				parent::update(
					['type' => $config['type']],
					$config['data']
				);
				break;
		}
	}

	/**
	 * Delete a record
	 * @param array $config
	 */
	private function del(array $config)
    {
		switch ($config['type']) {
			case 'counter':
				parent::delete(
					['type' => $config['type']],
					$config['data']
				);
				$this->message->json_post_response(true,'delete',array('id' => $this->id));
				break;
		}
	}

	/**
	 * Update order
	 */
    private function order()
    {
	    if(isset($this->order) && !empty($this->order)) {
            $p = $this->order;
            for ($i = 0; $i < count($p); $i++) {
                $this->upd(['type' => 'order', 'data' => ['id' => $p[$i], 'order_counter' => $i]]);
            }
        }
	}

	/**
	 * Execute the plugin
	 */
	public function run()
    {
		if($this->action) {
			switch ($this->action) {
				case 'add':
				case 'edit':
					if(!empty($this->counter)) {
						$notify = 'update';

						if (!isset($this->counter['id_counter'])) {
							$this->add(['type' => 'counter']);
							$lastCounter = $this->getItems('lastCounter', null,'one',false);
							$this->counter['id_counter'] = $lastCounter['id_counter'];
							$notify = 'add_redirect';
						}

                        $this->upd([
                            'type' => 'counter',
                            'data' => [
                                'id' => $this->counter['id_counter'],
                                'number' => $this->counter['number_counter']
                            ]
                        ]);

						foreach ($this->counter['content'] as $lang => $counter) {
							$counter['id_lang'] = $lang;
							$counter['blank_counter'] = (!isset($counter['blank_counter']) ? 0 : 1);
							$counterLang = $this->getItems('counterContent',['id' => $this->counter['id_counter'],'id_lang' => $lang],'one',false);

							if(!empty($counterLang)) $counter['id_content'] = $counterLang['id_content'];
							else $counter['id_counter'] = $this->counter['id_counter'];

							$config = ['type' => 'counterContent', 'data' => $counter];

                            !empty($counterLang) ? $this->upd($config) : $this->add($config);
						}
						$this->message->json_post_response(true,$notify);
					}
					else {
						$this->modelLanguage->getLanguage();

						if($this->edit) {
							$collection = $this->getItems('counterContent',$this->edit,'all',false);
							$setEditData = $this->setCounterData($collection);
							try {
								$this->template->assign('counter', $setEditData[$this->edit]);
							} catch(Exception $e) {
								$logger = new debug_logger(MP_LOG_DIR);
								$logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
							}
						}

						try {
							$this->template->assign('edit',$this->action === 'edit');
						} catch(Exception $e) {
							$logger = new debug_logger(MP_LOG_DIR);
							$logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
						}
						$this->template->display('edit.tpl');
					}
					break;
				case 'delete':
					if(isset($this->id) && !empty($this->id)) {
						$this->del([
                            'type' => 'counter',
                            'data' => ['id' => $this->id]
                        ]);
					}
					break;
				case 'order':
					if (isset($this->counter) && is_array($this->counter)) {
						$this->order();
					}
					break;
			}
		}
		else {
			$this->modelLanguage->getLanguage();
			$defaultLanguage = $this->collectionLanguage->fetchData(['context'=>'one','type'=>'default']);
			$this->getItems('counters',['default_lang' => $defaultLanguage['id_lang']],'all');
			$assign = [
                'id_counter',
                'title_counter' => ['title' => 'name'],
                'number_counter' => ['title' => 'number_counter'],
                'desc_counter' => ['title' => 'desc_counter', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
                'url_counter' => ['title' => 'url_counter', 'type' => 'bin', 'input' => null, 'class' => '']
            ];
			$this->data->getScheme(['mc_counter','mc_counter_content'],['id_counter','number_counter','title_counter','desc_counter','url_counter'],$assign);
			$this->template->display('index.tpl');
		}
	}
}