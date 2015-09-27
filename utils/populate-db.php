<?php

require __DIR__ . '/../bootstrap.php';

$validCommands = ['all', 'expenses', 'users'];

if (!isset($argv[1])) {
	echo "Usage: {$argv[0]} -c <".implode('|', $validCommands)."> [-p port] [-d root]\n";
	exit(1);
}

$opts = getopt("c:p:d:");
$command = isset($opts['c']) ? $opts['c'] : null;
$port = isset($opts['p']) && $opts['p'] ? $opts['p'] : 80;
$root = isset($opts['d']) && $opts['d'] ? $opts['d'] : '';

if (!in_array($command, $validCommands)) {
	echo "Command must be one of: " . implode(", ", $validCommands) . "\n";
	exit(1);
}

if ($command === 'all' || $command === 'users') {
	$users = [
		['id' => 1, 'name' => 'Darwish'],
		['id' => 2, 'name' => 'Hoffman'],
		['id' => 3, 'name' => 'Dan'],
		['id' => 4, 'name' => 'Andrew'],
		['id' => 5, 'name' => 'Harold']
	];

	foreach ($users as $user) {
		$data = [
			'id'   => $user['id'],
			'name' => $user['name'],
		];

		try {
			$client = new GuzzleHttp\Client();
			$res = $client->request('POST', 'http://localhost:{$port}/{$root}import-user.php', ['form_params' => $data]);

			echo $res->getStatusCode() . "\n";
			echo $res->getBody() . "\n";
		} catch (Exception $e) {
			echo $e->getResponse()->getBody();
			die;
		}
	}
}

if ($command === 'all' || $command === 'expenses') {
	$things = [
		'Beef', 'Beer', 'Mustard', 'Tents', 'Ski Tickets', 'Insurance', 'Gas',
	];

	for ($i = 0; $i < 5; $i++) {
		$data = [
			'name' => $things[mt_rand(0, count($things) - 1)],
			'amount' => mt_rand(5, 200),
		];


		try {
			$client = new GuzzleHttp\Client();
			$res = $client->request('POST', "http://localhost:{$port}/{$root}import-expense.php", ['form_params' => $data]);
			echo $res->getStatusCode() . "\n";
			echo $res->getBody() . "\n";
		} catch (Exception $e) {
			echo $e->getResponse()->getBody();
			die;
		}
	}
}