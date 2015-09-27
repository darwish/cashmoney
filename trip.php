<?php require __DIR__ . '/bootstrap.php'; ?>
<?php
	$data = new CashMoney\Data\Data();

	$tripID = isset($_GET['tripID']) ? $_GET['tripID'] : null;

	// $trip = $data->getTripByID($tripID);
	$expenses = $data->getExpenses();

	$payments = $data->splitExpenses($expenses);
?>
<?php require 'header.php'; ?>

<div class="row doodlite" id="trip-expenses-container">
</div>

<?= '<script type="handlerbars-template" id="trip-expense-template">' ?>
	<div class="col-sm-8">
		<h1>Trip Expenses</h1>

		<table class="table table-striped table-hover expenses">
			<tr>
				<th>Expense</th>
				<th>Amount</th>
				<th>Paid By</th>
			</tr>

			{{#each expenses}}
				<tr>
					<td><b>{{name}}</b></td>

					<td>{{formattedAmount}}</td>

					<td>{{paidBy.name}}</td>
				</tr>
			{{/each}}
		</table>
	</div>
<?= '</script>' ?>

<?= '<script type="handlerbars-template" id="trip-expense-by-user-template">' ?>
	<div class="col-sm-8">
		<h1>Trip Expenses</h1>

		<table class="table table-bordered table-hover expenses">
			<tr>
				<th></th>
				{{#each users}}
					<th width="100" class="text-center">{{name}}</th>
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

<!-- <script src="js/doodlite.js"></script> -->

<script>
	$(function() {
		var expenses = <?= json_encode($expenses); ?>;
		var users = <?= json_encode($data->getUsers()); ?>;

		// Process data for rendering
		for (var i = 0; i < expenses.length; i++) {
			expenses[i].formattedAmount = formatMoney(expenses[i].amount, 2, '.', ',', true);
			expenses[i].usedByIDs = expenses[i].usedBy.map(function(a) { return a.id; });
		}

		var expenseData = {
			expenseCount: expenses.length,
			expenseInflected: expenses.length === 1 ? "expense" : "expenses",
			expenses: expenses,
			users: users
		};

		$('#trip-expenses-container').html(renderTemplate('trip-expense-by-user-template', expenseData));

		$('#trip-expenses-container').append(renderTemplate('trip-expense-template', expenseData));
	})
</script>

<?php require 'footer.php'; ?>