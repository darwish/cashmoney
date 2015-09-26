<?php require 'header.php'; ?>

<div class="row">
	<div class="col-sm-4">
		<h1>CashMoney</h1>
		<p><a class="add-trip" href="#">Add a new trip</a></p>
		<div class="list-group past-trips"></div>
	</div>
</div>

<script type="handlerbars-template" id="trip-item-template">
	{{#each this}}
		<a href="#" class="list-group-item">{{name}}</a>
	{{/each}}
</script>

<script>
	$(function() {
		var fakeTripData = [
			{ id: 1, name: "New Zealand Ski Trip" },
			{ id: 2, name: "Last week at the pub" },
			{ id: 3, name: "International Space Station" },
		];	
		
		$('.past-trips').html(renderTemplate('trip-item-template', fakeTripData));
		
		$('.add-trip').click(function(e) {
			e.preventDefault();
			
			$('.past-trips').prepend('<input class="list-group-item"/>')
		});
	})
</script>

<?php require 'footer.php'; ?>