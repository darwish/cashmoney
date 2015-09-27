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
			'card[accountNumber]'         => "5184680430000006",
			'card[expiryMonth]'           => 11,
			'card[expiryYear]'            => 2018,
			'phone'                       => '14281234567',
		],
		[
			'id'                          => 2,
			'name'                        => 'Hoffman',
			'address[line1]'              => "123 Main Street",
			'address[line2]'              => "4",
			'address[city]'               => "Montreal",
			'address[countrySubdivision]' => "QC",
			'address[postalCode]'         => "H0H0H0",
			'address[country]'            => "CAN",
			'card[accountNumber]'         => "5184680430000014",
			'card[expiryMonth]'           => 11,
			'card[expiryYear]'            => 2018,
			'phone'                       => '15141234567',
		],
		[
			'id'                          => 3,
			'name'                        => 'Dan',
			'address[line1]'              => "123 Fake Street",
			'address[line2]'              => "3A",
			'address[city]'               => "Ottawa",
			'address[countrySubdivision]' => "ON",
			'address[postalCode]'         => "M0M0M0",
			'address[country]'            => "CAN",
			'card[accountNumber]'         => "5184680430000022",
			'card[expiryMonth]'           => 11,
			'card[expiryYear]'            => 2018,
			'phone'                       => '16131234567',
		],
		[
			'id'                          => 4,
			'name'                        => 'Andrew',
			'address[line1]'              => "123 Real Street",
			'address[line2]'              => "201",
			'address[city]'               => "Schenectady",
			'address[countrySubdivision]' => "NY",
			'address[postalCode]'         => 12345,
			'address[country]'            => "USA",
			'card[accountNumber]'         => "5184680430000030",
			'card[expiryMonth]'           => 11,
			'card[expiryYear]'            => 2018,
			'phone'                       => '15671234567',
		],
		[
			'id'                          => 5,
			'name'                        => 'Harold',
			'address[line1]'              => "123 Another Street",
			'address[line2]'              => "4",
			'address[city]'               => "Beverly Hills",
			'address[countrySubdivision]' => "CA",
			'address[postalCode]'         => 90210,
			'address[country]'            => "USA",
			'card[accountNumber]'         => "5184680430000261",
			'card[expiryMonth]'           => 11,
			'card[expiryYear]'            => 2018,
			'phone'                       => '16781234567',
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
		'Junk Food', 'Beer', 'Lift Tickets', 'Damage Deposit', 'Gas', 'Parking', 'Beer',
	];

	for ($i = 0; $i < 7; $i++) {
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

if ($command === 'all' || $command === 'trips') {
	$trips = [
		'Ski Trip',
	];

	foreach ($trips as $trip) {

		try {
			$client = new GuzzleHttp\Client();
			$res = $client->request('GET', "http://localhost:{$port}/{$root}process-trip.php?action=add&name={$trip}");
			echo $res->getStatusCode() . "\n";
			echo $res->getBody() . "\n";
		} catch (Exception $e) {
			echo $e->getResponse()->getBody();
			die;
		}
	}
}