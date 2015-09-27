<?php require __DIR__ . '/bootstrap.php'; ?>
<?php require 'header.php'; ?>

<div class="row" id="expenses-container">
</div>

<?= '<script type="handlerbars-template" id="expense-template">' ?>
	<div class="col-sm-10">
		<div class="panel panel-default">
			<div class="panel-heading"><h1>New Expenses</h1></div>
			<div class="panel-body">
				<p>You have {{expenseCount}} new {{expenseInflected}} to file.</p>

				<table class="table table-striped table-hover expenses">
					<tr>
						<th class="col-sm-4">Expense</th>
						<th class="col-sm-2">Amount</th>
						<th class="col-sm-2">Action</th>
						<th class="col-sm-4">Trip</th>
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

									<button class="btn btn-primary js-add-expense hidden" data-expense-id="{{id}}">
										Share
									</button>

									<button class="btn btn-default js-remove-expense" data-expense-id="{{id}}">
										<i class="glyphicon glyphicon-remove text-danger" title="Remove expense from this trip"></i>
									</button>
								</div>
							</td>

							<td>
								<select name="tripID" class="form-control hidden">
									{{#each ../trips}}
										<option value="{{id}}">{{name}}</option>
									{{/each}}
								</select>
							</td>
						</tr>
						<tr class="share hidden"></tr>
					{{/each}}
				</table>
			</div>
		</div>
	</div>
<?= '</script>' ?>

<?= '<script type="handlerbars-template" id="share-expense-template">' ?>
	<td colspan="4" class="text-center">
		<div class="row">
			<div class="col-sm-12">
				<input type="hidden" name="expenseID" value="{{expenseID}}">
				{{#each users}}
					<label>
						<input type="checkbox" name="user[]" value="{{id}}" checked> {{name}}
						<img src="img/{{name}}.png" class="img-circle" height="32" width="24"/>
					</label>
				{{/each}}
			</div>
		</div>

		<!-- <button class="btn btn-primary js-add-expense">Share</button> -->
	</div>
<?= '</script>' ?>

<script>
	$(function() {
		<?php $data = new CashMoney\Data\Data(); ?>
		var expenses = <?= json_encode($data->getPendingExpenses()); ?>;
		var users = <?= json_encode($data->getUsers()); ?>;
		var trips = <?= json_encode($data->getTrips()); ?>;

		render(expenses, users, trips);

		// Event handlers
		$('#expenses-container').on('click', '.js-show-users', function() {
			var button = $(this);
			var expenseID = button.data('expense-id');
			var row = button.closest('tr');
			var shareRow = row.next('tr.share');
			var shareButton = row.find('.js-add-expense');
			var tripsSelect = row.find('[name=tripID]');

			var data = {
				expenseID: expenseID,
				users: users
			};

			button.remove(); // We remove it so that the sharebutton gets first-child styles.
			shareButton.removeClass('hidden');
			tripsSelect.removeClass('hidden');

			shareRow.html(renderTemplate('share-expense-template', data)).removeClass('hidden');
		});

		$('#expenses-container').on('click', '.js-add-expense', function() {
			var button = $(this);
			var row = button.closest('tr');
			var shareRow = row.next('tr.share');
			var tripsSelect = row.find('[name=tripID]');

			$.post("process-expense.php?action=add", shareRow.find(':input').add(tripsSelect).serialize())
				.done(function(remainingExpenses) {
					$.growl({ title: "Added!", message: "Your expense has been shared." });
					render(remainingExpenses, users, trips);
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

			$.post("process-expense.php?action=remove", { expenseID: expenseID })
				.done(function(remainingExpenses) {
					render(remainingExpenses, users, trips);
				})
				.fail(function() {
					alert("Something went wrong!");
					console.error(arguments);
				});
		});

		function render(expenses, users, trips) {
			// Process data for rendering
			for (var i = 0; i < expenses.length; i++) {
				expenses[i].formattedAmount = formatMoney(expenses[i].amount, 2, '.', ',', true);
			}

			var expenseData = {
				expenseCount: expenses.length,
				expenseInflected: expenses.length === 1 ? "expense" : "expenses",
				expenses: expenses,
				trips: trips
			};

			$('#expenses-container').html(renderTemplate('expense-template', expenseData));

			if (expenses.length > 0) {
				$('#global-new-expenses-count').text(expenses.length);
			} else {
				$('#global-new-expenses-count').text('');
			}
		}
	})
</script>

<?php require 'footer.php'; ?>