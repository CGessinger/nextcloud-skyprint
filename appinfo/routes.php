<?php

return [
	'routes' => [
		['name' => 'cloudprint#printfile', 'url' => '/printfile', 'verb' => 'POST'],
		['name' => 'cloudprint#printers', 'url' => '/printers', 'verb' => 'GET']
	]
];