<?php
class plugins_counter_db
{
	/**
	 * @param array $config
	 * @param bool $params
	 * @return array|false|null
	 * @throws Exception
	 */
	public function fetchData(array $config, $params = false)
	{
		$sql = '';

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'counters':
					$sql = 'SELECT 
								mc.id_counter,
								mc.number_counter,
								mcc.title_counter,
								mcc.desc_counter,
								mcc.url_counter
							FROM mc_counter mc
							JOIN mc_counter_content mcc USING(id_counter)
							JOIN mc_lang ml USING(id_lang)
							WHERE mcc.id_lang = :default_lang
							ORDER BY order_counter';
					break;
				case 'homeCounters':
					$sql = 'SELECT 
									mc.id_counter,
									mc.number_counter,
									mcc.title_counter,
									mcc.desc_counter,
									mcc.url_counter,
									mcc.blank_counter
 								FROM mc_counter mc
								LEFT JOIN mc_counter_content mcc USING(id_counter)
								LEFT JOIN mc_lang ml USING(id_lang)
								WHERE ml.iso_lang = :lang
								ORDER BY order_counter';
					break;
				case 'counter':
					$sql = 'SELECT mc.*, mcc.*
							FROM mc_counter mc
							JOIN mc_counter_content mcc USING(id_counter)
							JOIN mc_lang ml ON(mcc.id_lang = ml.id_lang)
							WHERE mcc.id_lang = :default_lang';
					break;
				case 'counterContent':
					$sql = 'SELECT mc.*, mcc.*
							FROM mc_counter mc
							JOIN mc_counter_content mcc USING(id_counter)
							JOIN mc_lang ml USING(id_lang)
							WHERE mcc.id_counter = :id';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'counterContent':
					$sql = 'SELECT * FROM mc_counter_content WHERE id_counter = :id AND id_lang = :id_lang';
					break;
				case 'lastCounter':
					$sql = 'SELECT * FROM mc_counter ORDER BY id_counter DESC LIMIT 0,1';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
		}
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert(array $config, $params = [])
	{
		$sql = '';

		switch ($config['type']) {
			case 'counter':
				$sql = 'INSERT INTO mc_counter (order_counter, date_register) SELECT COUNT(id_counter), NOW() FROM mc_counter';
				break;
			case 'counterContent':
				$sql = 'INSERT INTO mc_counter_content(id_counter, id_lang, title_counter, desc_counter, url_counter, blank_counter)
						VALUES (:id_counter, :id_lang, :title_counter, :desc_counter, :url_counter, :blank_counter)';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception : '.$e->getMessage();
		}
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update(array $config, $params = [])
	{
		$sql = '';

		switch ($config['type']) {
			case 'counter':
				$sql = 'UPDATE mc_counter SET number_counter = :number WHERE id_counter = :id';
				break;
			case 'counterContent':
				$sql = 'UPDATE mc_counter_content
						SET 
							title_counter = :title_counter,
							desc_counter = :desc_counter,
							url_counter = :url_counter,
							blank_counter = :blank_counter
						WHERE id_content = :id_content 
						AND id_lang = :id_lang';
				break;
			case 'order':
				$sql = 'UPDATE mc_counter 
						SET order_counter = :order_counter
						WHERE id_counter = :id';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception : '.$e->getMessage();
		}
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete(array $config, $params = [])
	{
			$sql = '';

			switch ($config['type']) {
				case 'counter':
					$sql = 'DELETE FROM mc_counter WHERE id_counter IN('.$params['id'].')';
					$params = [];
					break;
			}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}
}