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
							<button class="btn btn-default js-show-users" data-expense-id="{{id}}">
								<i class="glyphicon glyphicon-ok text-success" title="Add expense to this trip"></i>
							</button>

							<button class="btn btn-default js-remove-expense" data-expense-id="{{id}}">
								<i class="glyphicon glyphicon-remove text-danger" title="Remove expense from this trip"></i>
							</button>
						</div>
					</td>
				</tr>
				<tr class="share hidden"></tr>
			{{/each}}
		</table>
	</div>
<?= '</script>' ?>

<?= '<script type="handlerbars-template" id="share-expense-template">' ?>
	<td colspan="4">
		<input type="hidden" name="id" value="{{expenseID}}">
		{{#each users}}
			<label>
				<input type="checkbox" name="user[]" value="{{id}}" checked> {{name}}
				<img src="img/{{name}}.png" height="32" width="24"/>
			</label>
		{{/each}}
		<button class="btn btn-primary js-add-expense">Share</button>
	</div>
<?= '</script>' ?>

<script>
	$(function() {
		<?php $data = new CashMoney\Data\Data(); ?>
		var expenses = <?= json_encode($data->getPendingExpenses()); ?>;
		var users = <?= json_encode($data->getUsers()); ?>;

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

		$('#expenses-container').on('click', '.js-show-users', function() {
			var button = $(this);
			var expenseID = button.data('expense-id');
			var row = button.closest('tr');
			var shareRow = row.next('tr.share');

			var data = {
				expenseID: expenseID,
				users: users
			};

			shareRow.html(renderTemplate('share-expense-template', data)).removeClass('hidden');
		});

		$('#expenses-container').on('click', '.js-add-expense', function() {
			var button = $(this);
			var cell = button.closest('td');

			$.post("process-expense.php?action=add", cell.find(':input').serialize())
				.done(function() {
					alert("Added!");
					console.log(arguments);
				})
				.fail(function() {
					alert("Something went wrong!");
					console.error(arguments);
				});
		});

		$('#expenses-container').on('click', '.js-remove-expense', function() {
			var button = $(this);
			var row = button.closest('tr');
			var expenseID = button.data('expense-id');

			$.post("process-expense.php?action=remove", {id: expenseID })
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