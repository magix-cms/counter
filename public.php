<?php
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
 * @name plugins_counter_public
 */
class plugins_counter_public extends plugins_counter_db
{
    /**
     * @var object
     */
    protected
        $template,
        $data;

    /**
     * @var string
     */
    protected
        $lang;

    /**
     * plugins_counter_public constructor.
     * @param frontend_model_template|null $t
     */
    public function __construct($t = null)
    {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->data = new frontend_model_data($this,$this->template);
		$this->lang = $this->template->lang;
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
                $arr[$counter['id_counter']] = [];
                $arr[$counter['id_counter']]['id_counter'] = $counter['id_counter'];
                $arr[$counter['id_counter']]['id_lang'] = $counter['id_lang'];
                $arr[$counter['id_counter']]['number'] = $counter['number_counter'];
                $arr[$counter['id_counter']]['title'] = $counter['title_counter'];
                $arr[$counter['id_counter']]['desc'] = $counter['desc_counter'];
                $arr[$counter['id_counter']]['url'] = $counter['url_counter'];
                $arr[$counter['id_counter']]['blank'] = $counter['blank_counter'];
            }
        }
		return $arr;
	}

	/**
	 * @return array
	 */
    public function getCounters(): array
    {
        $counters = $this->getItems('homeCounters', ['lang' => $this->lang],'all', false);
        return empty($counters) ? [] : $this->setCounterData($counters);
    }
}