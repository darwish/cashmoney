<?php require __DIR__ . '/bootstrap.php'; ?>
<?php require 'header.php'; ?>

<div class="row" id="expenses-container">
</div>

<?= '<script type="handlerbars-template" id="expense-template">' ?>
	<div class="col-sm-8">
		<h1>New Expenses</h1>
		<p>You have {{expenseCount}} new {{expenseInflected}} to file.</p>

		<table class="table table-striped table-hover expenses">
			<tr>
				<th>Expense</th>
				<th>Amount</th>
				<th>Action</th>
			</tr>

			{{#each expenses}}
				<tr>
					<td><b>{{name}}</b></td>

					<td>{{formattedAmount}}</td>

					<td>
						<div class="btn-group">
							<button class="btn btn-default js-add-expense" data-expense-id="{{id}}">
								<i class="glyphicon glyphicon-ok text-success" title="Add expense to this trip"></i>
							</button>

							<button class="btn btn-default js-remove-expense" data-expense-id="{{id}}">
								<i class="glyphicon glyphicon-remove text-danger" title="Remove expense from this trip"></i>
							</button>
						</div>
					</td>
				</tr>
			{{/each}}
		</table>
	</div>
<?= '</script>' ?>

<script>
	$(function() {
		<?php $data = new CashMoney\Data\Data(); ?>
		var expenses = <?= json_encode($data->getExpenses()) ?>;

		// Process data for rendering
		for (var i = 0; i < expenses.length; i++) {
			expenses[i].formattedAmount = formatMoney(expenses[i].amount, 2, '.', ',', true);
		}

		var expenseData = {
			expenseCount: expenses.length,
			expenseInflected: expenses.length === 1 ? "expense" : "expenses",
			expenses: expenses
		};

		$('#expenses-container').html(renderTemplate('expense-template', expenseData));

		$('#expenses-container').on('click', '.js-add-expense', function() {
		});

		$('#expenses-container').on('click', '.js-remove-expense', function() {
			var button = $(this);
			var row = button.closest('tr');
			var expenseID = button.data('expense-id');

			$.get("process-expense.php?id=" + expenseID + "&action=remove")
				.done(function() {
					row.remove();
				})
				.fail(function() {
					alert("Something went wrong!");
					console.error(arguments);
				});
		});
	})
</script>

<?php require 'footer.php'; ?>