<?php require __DIR__ . '/bootstrap.php'; ?>
<?php
	$data = new CashMoney\Data\Data();

	$tripID = isset($_GET['tripID']) ? $_GET['tripID'] : null;

	$trip = $data->getTrip($tripID);
	$expenses = $trip->getExpenses();

	$payments = $data->splitExpenses($expenses);
?>
<?php require 'header.php'; ?>

<div class="row" id="trip-expenses-container">
</div>

<?= '<script type="handlerbars-template" id="trip-expense-template">' ?>
	<div class="col-sm-8">
		<h2>Total Expenses</h2>
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
<?= '</script>' ?>

<?= '<script type="handlerbars-template" id="trip-payment-template">' ?>
	<div class="col-sm-8">
		<h2>Pay Me Back</h2>
		<ul class="list-group payments">
			{{#each payments}}
				<li class="list-group-item clearfix">
					<b>{{debtor.name}}</b> owes <b>{{formattedAmount}}</b> to <b>{{lender.name}}</b>

					<span class="pull-right">
						{{#if isPaid}}
							<button class="btn btn-primary">
								<img src="img/mastercard.ico">
								Paid
							</button>
						{{else}}
							<button class="btn btn-default do-payment" data-debtor-id="{{debtor.id}}" data-lender-id="{{lender.id}}">
								<img src="img/mastercard.ico">
								Pay {{lender.name}}
							</button>
						{{/if}}
					</span>
				</li>
			{{/each}}
		</table>
	</div>
<?= '</script>' ?>

<?= '<script type="handlerbars-template" id="trip-expense-by-user-template">' ?>
	<div class="col-sm-8">
		<h2>Expense Participation Matrix</h2>
		<p>Check marks in this table represent participation in the expense</p>

		<table class="table table-bordered table-hover expenses">
			<tr>
				<th></th>
				{{#each users}}
					<th width="150" class="text-center">{{name}} <img src="img/{{name}}.png" class="img-circle" height="32" width="24" /></th>
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
							<td class="text-center success expense-shared">
								<i class="glyphicon glyphicon-ok text-success"></i>
							</td>
						{{else}}
							<td></td>
						{{/ifContains}}
					{{/each}}
				</tr>
			{{/each}}
		</table>
	</div>
<?= '</script>' ?>

<script>
	$(function() {
		var expenses = <?= json_encode($expenses); ?>;
		var users = <?= json_encode($data->getUsers()); ?>;
		var payments = <?= json_encode($payments); ?>;
		var total = 0;

		// Process data for rendering
		for (var i = 0; i < expenses.length; i++) {
			total += expenses[i].amount;
			expenses[i].formattedAmount = formatMoney(expenses[i].amount, 2, '.', ',', true);
			expenses[i].usedByIDs = expenses[i].usedBy.map(function(a) { return a.id; });
		}

		for (var i = 0; i < payments.length; i++) {
			payments[i].formattedAmount = formatMoney(payments[i].amount, 2, '.', ',', true);
		}

		var expenseData = {
			expenseCount: expenses.length,
			expenseInflected: expenses.length === 1 ? "expense" : "expenses",
			formattedTotal: formatMoney(total, 2, '.', ',', true),
			expenses: expenses,
			users: users
		};

		var paymentData = {
			payments: payments
		}

		$('#trip-expenses-container').html(renderTemplate('trip-expense-by-user-template', expenseData));
		$('#trip-expenses-container').append(renderTemplate('trip-payment-template', paymentData));
		$('#trip-expenses-container').append(renderTemplate('trip-expense-template', expenseData));

		$('#trip-expenses-container').on('click', '.do-payment', function() {
			var button = $(this);
			var debtorID = button.data('debtor-id');
			var lenderID = button.data('lender-id');

			$.post('do-payment.php', { tripID: <?= $trip->getID() ?>, debtorID: debtorID, lenderID: lenderID })
				.done(function() {
					alert("Paid!");
					console.log(arguments);
				})
				.fail(function() {
					alert("Something went wrong!");
					console.error(arguments);
				});
		});
	});
</script>

<iframe width="800" height="600" frameborder="1" style="border:0" src="https://www.google.com/maps/embed/v1/search?key=AIzaSyCsA7P1BKADJMcmSgi9z31iY3dIIOGewYs&q={{location}}" allowfullscreen></iframe>

<?php require 'footer.php'; ?>