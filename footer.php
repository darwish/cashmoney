		</div>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.3/handlebars.js"></script>
		<script src="js/plugins.js"></script>
		<script src="js/main.js"></script>

		<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
		<script>
			(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
			function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
			e=o.createElement(i);r=o.getElementsByTagName(i)[0];
			e.src='https://www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
			ga('create','UA-XXXXX-X','auto');ga('send','pageview');
		</script>

		<!-- User modals -->
		<?php foreach ((new CashMoney\Data\Data())->getUsers() as $user): ?>
			<div class="modal fade" id="user-modal-<?= $user->getID(); ?>" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"><?= $user->getName(); ?></h4>
						</div>
						<div class="modal-body">
							<span class="pull-right" style="margin-right:10px;"><img src="img/<?= $user->getName(); ?>.png" class="img-circle" height="64" width="48" /></span>

							<h3>User Details</h3>
							<table class="table table-striped table-condensed">
								<tr>
									<th>User ID</th>
									<td>#<?= $user->getId(); ?></td>
								</tr>
								<tr>
									<th>Name</th>
									<td><?= $user->getname(); ?></td>
								</tr>
								<tr>
									<th>Phone</th>
									<td><?= $user->getPhone(); ?></td>
								</tr>
							</table>

							<h3>Billing Address</h3>
							<table class="table table-striped table-condensed">
								<tr>
									<th>Line1</th>
									<td><?= $user->getAddress()->getLine1(); ?></td>
								</tr>
								<tr>
									<th>Line2</th>
									<td><?= $user->getAddress()->getLine2(); ?></td>
								</tr>
								<tr>
									<th>City</th>
									<td><?= $user->getAddress()->getCity(); ?></td>
								</tr>
								<tr>
									<th>CountrySubdivision</th>
									<td><?= $user->getAddress()->getCountrySubdivision(); ?></td>
								</tr>
								<tr>
									<th>PostalCode</th>
									<td><?= $user->getAddress()->getPostalCode(); ?></td>
								</tr>
								<tr>
									<th>Country</th>
									<td><?= $user->getAddress()->getCountry(); ?></td>
								</tr>
							</table>

							<h3>Credit Card</h3>
							<table class="table table-striped table-condensed">
								<tr>
									<th>AccountNumber</th>
									<td><?= $user->getMaskedAccountNumber(); ?>
								</tr>
								<tr>
									<th>ExpiryMonth</th>
									<td><?= $user->getCard()->getExpiryMonth(); ?>
								</tr>
								<tr>
									<th>ExpiryYear</th>
									<td><?= $user->getCard()->getExpiryYear(); ?>
								</tr>
							</table>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</body>
</html>
