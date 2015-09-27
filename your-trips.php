<?php require 'header.php'; ?>

<div class="row">
	<div class="col-sm-4">
		<h1 class="title">CashMoney</h1><img class="piggybank-icon pull-right" src="img/piggybank.png"/>
		<p><a class="add-trip" href="#">Add a new trip</a></p>
		<div class="list-group past-trips"></div>
	</div>
</div>

<script type="handlerbars-template" id="trip-item-template">
	{{#each this}}
		<a href="trip/{{id}}" data-id="{{id}}" class="list-group-item">{{name}}<span class="remove glyphicon glyphicon-remove pull-right"/></a>
	{{/each}}
</script>

<script>
	$(function() {
		var fakeTripData = [
			{ id: 3, name: "New Zealand Ski Trip" },
			{ id: 2, name: "Last week at the pub" },
			{ id: 1, name: "International Space Station" },
		];	
		
		listTrips();
		
		$('.add-trip').click(function(e) {
			e.preventDefault();
			
			$('.past-trips').prepend('<input class="list-group-item trip-name"/>');
			$('.past-trips input').focus();
		});
		
		$('.past-trips').on('blur keydown', 'input', function(e) {
			if ($('.past-trips input').val().length > 0)
				if (e.type == "focusout" || e.which == 13)
					addTrip();
		});
		
		$('.past-trips').on('click', 'a', function(e) {
		//	e.preventDefault();
		});
		
		function addTrip()
		{
			var nextID = fakeTripData[0].id + 1;
			var name = $('.past-trips input').val();
			$('.past-trips input').val('');
			fakeTripData.unshift({ id: nextID, name: name });
			listTrips();
			
			$.get('add-trip', fakeTripData[0])
				.fail(function() { alert('Failed to create trip.') });
		}
		
		function listTrips() {
			$('.past-trips').html(renderTemplate('trip-item-template', fakeTripData));
		}
		
		$('.past-trips').on('click', '.remove', function(e) {
			e.preventDefault();
			
			if (confirm("Are you sure you want to delete this trip?")) {
				var id = $(e.target).closest('a').data('id');
				fakeTripData = fakeTripData.filter(function(x) { return x.id !== id; });
				listTrips();
				$.get('remove-trip', id);
			}
		});
	})
</script>

<?php require 'footer.php'; ?>
