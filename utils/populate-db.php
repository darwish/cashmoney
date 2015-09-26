<?php

require __DIR__ . '/../bootstrap.php';

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
	// "200"

	echo $res->getBody() . "\n";
	// {"type":"User"...'
}