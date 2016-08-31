<?php
/**
 * @copyright Copyright (c) 2016, Joas Schilling <coding@schilljs.com>
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\NetworkConfig\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;

class Admin extends Controller {

	/** @var IConfig */
	protected $config;

	/** @var IL10N */
	protected $l;

	/**
	 * @param string $appName
	 * @param IRequest $request
	 * @param IConfig $config
	 * @param IL10N $l
	 */
	public function __construct($appName,
								IRequest $request,
								IConfig $config,
								IL10N $l) {
		parent::__construct($appName, $request);

		$this->config = $config;
		$this->l = $l;
	}

	/**
	 * @param string $option
	 * @param string $value
	 * @return DataResponse
	 */
	public function set($option, $value) {
		switch ($option) {
			case 'instanceUrl':
				if (strpos($value, 'https://') !== 0 && strpos($value, 'http://') !== 0) {
					return new DataResponse(['error' => $this->l->t('Invalid URL')], Http::STATUS_BAD_REQUEST);
				} else if ($value[strlen($value) - 1] !== '/') {
					$value .= '/';
				}
				$this->config->setSystemValue('overwrite.cli.url', $value);
				return new DataResponse(['value' => $value], Http::STATUS_OK);
		}

		return new DataResponse([], Http::STATUS_BAD_REQUEST);
	}
}
