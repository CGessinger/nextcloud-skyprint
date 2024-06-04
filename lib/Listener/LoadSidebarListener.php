<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Christian Gessinger <christian@gessinger.de>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\SkyPrint\Listener;

use OCA\SkyPrint\AppInfo\Application;
use OCA\Files\Event\LoadSidebar;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Util;

class LoadSidebarListener implements IEventListener {
	public function handle(Event $event): void {
		if (!($event instanceof LoadSidebar)) {
			return;
		}

		Util::addScript(Application::APP_ID, 'tabview');
	}
}