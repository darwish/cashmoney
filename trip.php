<?php require __DIR__ . '/bootstrap.php'; ?>
<?php
	$data = new CashMoney\Data\Data();

	$tripID = isset($_GET['id']) ? $_GET['id'] : null;

	$trip = $data->getTrip($tripID);

	$expenses = $trip->getExpenses();
	$payments = $trip->getPayments();

	$pageTitle = htmlentities($trip->getName());
?>
<?php require 'header.php'; ?>

<div class="row equal">
	<div id="trip-expense-matrix-container" class="col-md-6"></div>
	<div id="trip-repayment-container" class="col-md-6"></div>
</div>
<div class="row">
	<div id="trip-expenses-container" class="col-md-6"></div>
	<div id="map-container" class="col-md-6">
		<div class="panel panel-default payments-panel" style="width:100%;">
			<div class="panel-heading">
				<h2>Trip Map</h2>
			</div>
		
		<div class="panel-body">
			<div id="atm-map"></div>
		</div>
		</div>
	</div>	
</div>

<?= '<script type="handlerbars-template" id="trip-expense-template">' ?>
	<div class="panel panel-default">
		<div class="panel-heading"><h2>Total Expenses</h2></div>

		<div class="panel-body">
			<table class="table table-striped table-hover expenses">
				<tr>
					<th>Expense</th>
					<th>Amount</th>
					<th>Paid By</th>
				</tr>

				{{#each expenses}}
					<tr>
						<td><b>{{name}} </b></td>

						<td>{{formattedAmount}}</td>

						<td>{{paidBy.name}}</td>
					</tr>
				{{/each}}

				<tr>
					<th>Total</th>
					<th>{{formattedTotal}}</th>
					<th></th>
				</tr>
			</table>
		</div>
	</div>
<?= '</script>' ?>

<?= '<script type="handlerbars-template" id="trip-payment-template">' ?>
	<div class="panel panel-default payments-panel" style="width:100%;">
		<div class="panel-heading">
			<h2>
				Pay Me Back

				<span class="pull-right">
					{{#if isAllPaid}}
						<button class="btn btn-default" disabled>
							<img src="img/mastercard.ico">
							All Paid
						</button>
					{{else}}
						<button class="btn btn-default do-all-payments">
							<img src="img/mastercard.ico">
							<span class="do-payment-text">Settle Trip</span>
						</button>
					{{/if}}
				</span>
			</h2>
		</div>

		<div class="panel-body">
			<p>Payments that need to be made to reconcile all debts for the trip.</p>

			<ul class="list-group payments">
				{{#each payments}}
					<li class="list-group-item clearfix">
						<b>{{debtor.name}}</b> owes <b>{{formattedAmount}}</b> to <b>{{lender.name}}</b>

						<span class="pull-right">
							{{#if isPaid}}
								<button class="btn btn-default" disabled>
									<img src="img/mastercard.ico">
									Paid
								</button>
							{{else}}
								<button class="btn btn-default do-payment" data-debtor-id="{{debtor.id}}" data-lender-id="{{lender.id}}">
									<img src="img/mastercard.ico">
									<span class="do-payment-text">Pay {{lender.name}}</span>
								</button>
							{{/if}}
						</span>
					</li>
				{{/each}}
			</ul>
		</div>
	</div>
<?= '</script>' ?>

<?= '<script type="handlerbars-template" id="trip-expense-by-user-template">' ?>
	<div class="panel panel-default">
		<div class="panel-heading"><h2>Expense Matrix</h2></div>

		<div class="panel-body">
			<p>Check marks in this table represent participation in the expense</p>

			<table class="table table-bordered table-hover expenses">
				<tr>
					<th></th>
					{{#each users}}
						<th width="150" class="text-center">
							<a href="#" data-toggle="modal" data-target="#user-modal-{{id}}">
								<img src="img/{{name}}.png" class="img-circle" height="32" width="24" />
							</a><br>
							{{name}}
						</th>
					{{/each}}
				</tr>

				{{#each expenses}}
					<tr>
						<td>
							<b>{{name}}</b><br>
							<small>{{formattedAmount}}</small>
						</td>

						{{#each ../users}}
							{{#ifContains id ../usedByIDs}}
								<td class="toggleable text-center success expense-shared" data-user-id="{{id}}" data-expense-id="{{../id}}">
									<i class="glyphicon glyphicon-ok text-success"></i>
							{{else}}
								<td class="toggleable" data-user-id="{{id}}" data-expense-id="{{../id}}">
							{{/ifContains}}
								</td>
						{{/each}}
					</tr>
				{{/each}}
			</table>
		</div>
	</div>
<?= '</script>' ?>

<script>
	$(function() {
		var expenses = <?= json_encode($expenses); ?>;
		var users = <?= json_encode($data->getUsers()); ?>;
		var payments = <?= json_encode($payments); ?>;

		function renderAll(expenses, users, payments) {
			var total = 0;
			var isAllPaid = true;

			for (var i = 0; i < expenses.length; i++) {
				total += expenses[i].amount;
				expenses[i].formattedAmount = formatMoney(expenses[i].amount, 2, '.', ',', true);
				expenses[i].usedByIDs = expenses[i].usedBy.map(function(a) { return a.id; });
			}

			for (var i = 0; i < payments.length; i++) {
				payments[i].formattedAmount = formatMoney(payments[i].amount, 2, '.', ',', true);
				if (!payments[i].isPaid) {
					isAllPaid = false;
				}
			}


			var paymentData = {
				isAllPaid: isAllPaid,
				payments: payments
			}

			var expenseData = {
				expenseCount: expenses.length,
				expenseInflected: expenses.length === 1 ? "expense" : "expenses",
				formattedTotal: formatMoney(total, 2, '.', ',', true),
				expenses: expenses,
				users: users
			};

			$('#trip-expense-matrix-container').html(renderTemplate('trip-expense-by-user-template', expenseData));
			$('#trip-repayment-container').html(renderTemplate('trip-payment-template', paymentData));
			$('#trip-expenses-container').html(renderTemplate('trip-expense-template', expenseData));

			return expenseData
		}

		/**
		* have an object referencing the data ~as viewed by the user~
		* regardless of truth
		***/
		var expenseData = renderAll(expenses, users, payments);

		$('#trip-expense-matrix-container').on('click', '.toggleable', function() {
			var self = this;
			expenseData.expenses.map(function(expense) {
				if(expense.id==self.dataset.expenseId){
					// the objects in the DOM are not the truth
					// figure out if user is party to expense item
					// and send the command that would toggle them
					var userIds = expense.usedBy.map(function(u){return u.id});
					var isParty = userIds.indexOf(parseInt(self.dataset.userId))!==-1;
					var command = isParty ? "remove" : "add";
					var args = {tripID: "<?= $trip->getID() ?>", expenseId: expense.id, debtorID: self.dataset.userId };

					$.post('update-expenses-shares.php?action='+command, args)
						.done(function(response) {
							expenseData = renderAll.apply(this, response);
						})
						.fail(function() { alert('fail') })
				}
			});
		})

		$('#trip-repayment-container').on('click', '.do-payment', function() {
			var button = $(this);
			var buttonText = button.find('.do-payment-text');
			var debtorID = button.data('debtor-id');
			var lenderID = button.data('lender-id');

			var originalText = buttonText.text();

			button.prop('disabled', true);
			buttonText.text('Processing...');

			$.post('do-payment.php', { tripID: "<?= $trip->getID() ?>", debtorID: debtorID, lenderID: lenderID })
				.done(function() {
					buttonText.text('Paid');
					$.growl({ title: "Paid!", message: "Your payment has been processed." });
					console.log(arguments);
				})
				.fail(function() {
					button.prop('disabled', false);
					buttonText.text(originalText);

					alert("Something went wrong!");
					console.error(arguments);
				});
		});

		$('#trip-repayment-container').on('click', '.do-all-payments', function() {
			var button = $(this);
			var buttonText = button.find('.do-payment-text');

			var originalText = buttonText.text();

			button.prop('disabled', true);
			buttonText.text('Processing...');

			$.post('do-payment.php', { tripID: "<?= $trip->getID() ?>" })
				.done(function() {
					buttonText.text('All Paid');

					// Disable all individual payment buttons too
					button.closest('.payments-panel').find('.do-payment')
						.prop('disabled', true)
						.find('.do-payment-text').text('Paid');

					$.growl({ title: "Paid!", message: "Your trip expenses have been settled." });
					console.log(arguments);
				})
				.fail(function() {
					button.prop('disabled', false);
					buttonText.text(originalText);

					alert("Something went wrong!");
					console.error(arguments);
				});
		});
	});

	function initMap() {
		navigator.geolocation.getCurrentPosition(function(position) {

		  var map = new google.maps.Map(document.getElementById('atm-map'), {
		  	center: {lat: position.coords.latitude, lng: position.coords.longitude},
		  	zoom: 10
		  });

		  $.get('/find-atm.php?latitude='+position.coords.latitude+'&longitude='+position.coords.longitude, function (data, status, xhr) {
		  	for (var i = 0; i < data.length; i++) {
		  		var latLng = new google.maps.LatLng(data[i].position.lat, data[i].position.lng);
		  		var marker = new google.maps.Marker({
		  			position: latLng,
		  			title: data[i].title,
		  			visible: true
		  		});
		  		marker.setMap(map);
		  	}
		  });
		});
	}

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsA7P1BKADJMcmSgi9z31iY3dIIOGewYs&callback=initMap" async defer></script>

<!-- <iframe id="atm-map" width="800" height="600" frameborder="1" style="border:0" src="https://www.google.com/maps/embed/v1/search?key=AIzaSyCsA7P1BKADJMcmSgi9z31iY3dIIOGewYs&q={{location}}" allowfullscreen></iframe> -->

<?php require 'footer.php'; ?>
