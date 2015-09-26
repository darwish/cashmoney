<?php

require __DIR__ . '/../bootstrap.php';

$validCommands = ['all', 'expenses', 'users'];

if (!isset($argv[1])) {
	echo "Usage: {$argv[0]} <".implode('|', $validCommands).">\n";
	exit(1);
}

$command = $argv[1];
if (!in_array($command, $validCommands)) {
	echo "Command must be one of: \n";
	exit(1);
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

		$client = new GuzzleHttp\Client();
		$res = $client->request('POST', 'http://localhost:6789/import-expense.php', ['form_params' => $data]);

		echo $res->getStatusCode() . "\n";
		echo $res->getBody() . "\n";
	}
}

if ($command === 'all' || $command === 'users') {
	$users = [
		['name' => 'Darwish'],
		['name' => 'Hoffman'],
		['name' => 'Dan'],
		['name' => 'Andrew'],
		['name' => 'Harold']
	];

	foreach ($users as $user) {
		$data = [
			'name' => $user['name'],
		];

		$client = new GuzzleHttp\Client();
		$res = $client->request('POST', 'http://localhost:6789/import-user.php', ['form_params' => $data]);

		echo $res->getStatusCode() . "\n";
		echo $res->getBody() . "\n";
	}
}