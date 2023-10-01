<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Christian Gessinger <christian@gessinger.de>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\CloudPrint\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

use \OCP\Util;

class Application extends App implements IBootstrap
{
	public const APP_ID = 'cloudprint';

	public function __construct()
	{
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void
	{
	}

	public function boot(IBootContext $context): void
	{
		$server = $context->getServerContainer();
		$eventDispatcher = $server->getEventDispatcher();

		$eventDispatcher->addListener('OCA\Files::loadAdditionalScripts', function () {
			// \OCP\Util::addStyle('cloudprint', 'tabview');
			Util::addScript(self::APP_ID, 'tabview');
			Util::addScript(self::APP_ID, 'plugin');
		});
	}
}