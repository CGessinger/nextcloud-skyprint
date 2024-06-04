<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Christian Gessinger <christian@gessinger.de>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\SkyPrint\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

use OCA\Files\Event\LoadSidebar;
use OCA\SkyPrint\Listener\LoadSidebarListener;

class Application extends App implements IBootstrap
{
	public const APP_ID = 'skyprint';

	public function __construct()
	{
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void
	{
		$context->registerEventListener(LoadSidebar::class, LoadSidebarListener::class);
	}

	public function boot(IBootContext $context): void { }
}