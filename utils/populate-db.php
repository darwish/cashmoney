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
		[
			'id'                          => 1,
			'name'                        => 'Darwish',
			'address[line1]'              => "123 Main Street",
			'address[line2]'              => "#5A",
			'address[city]'               => "Arlington",
			'address[countrySubdivision]' => "VA",
			'address[postalCode]'         => 22207,
			'address[country]'            => "USA",
			'card[accountNumber]'         => "5444522282160973",
			'card[expiryMonth]'           => 11,
			'card[expiryYear]'            => 2018,
		],
		[
			'id'                          => 2,
			'name'                        => 'Hoffman',
			'address[line1]'              => "123 Main Street",
			'address[line2]'              => "",
			'address[city]'               => "Montreal",
			'address[countrySubdivision]' => "QC",
			'address[postalCode]'         => "H0H0H0",
			'address[country]'            => "CAN",
			'card[accountNumber]'         => "5170229905330625",
			'card[expiryMonth]'           => 11,
			'card[expiryYear]'            => 2018,
		],
		[
			'id'                          => 3,
			'name'                        => 'Dan',
			'address[line1]'              => "123 Fake Street",
			'address[line2]'              => "",
			'address[city]'               => "Ottawa",
			'address[countrySubdivision]' => "ON",
			'address[postalCode]'         => "M0M0M0",
			'address[country]'            => "CAN",
			'card[accountNumber]'         => "5194966477695867",
			'card[expiryMonth]'           => 11,
			'card[expiryYear]'            => 2018,
		],
		[
			'id'                          => 4,
			'name'                        => 'Andrew',
			'address[line1]'              => "123 Real Street",
			'address[line2]'              => "",
			'address[city]'               => "Schenectady",
			'address[countrySubdivision]' => "NY",
			'address[postalCode]'         => 12345,
			'address[country]'            => "USA",
			'card[accountNumber]'         => "5414400596755927",
			'card[expiryMonth]'           => 11,
			'card[expiryYear]'            => 2018,
		],
		[
			'id'                          => 5,
			'name'                        => 'Harold',
			'address[line1]'              => "123 Main Street",
			'address[line2]'              => "#5A",
			'address[city]'               => "Arlington",
			'address[countrySubdivision]' => "VA",
			'address[postalCode]'         => 22207,
			'address[country]'            => "USA",
			'card[accountNumber]'         => "5341737669178810",
			'card[expiryMonth]'           => 11,
			'card[expiryYear]'            => 2018,
		],
	];

	foreach ($users as $user) {
		try {
			$client = new GuzzleHttp\Client();
			$res = $client->request('POST', "http://localhost:{$port}/{$root}import-user.php", ['form_params' => $user]);

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