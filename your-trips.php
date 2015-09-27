<?php require __DIR__ . '/bootstrap.php'; ?>
<?php require 'header.php'; ?>

<div class="row">
	<div class="col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading"><h1 class="title">CashMoney</h1><img class="piggybank-icon pull-right" src="img/piggybank.png"/></div>
			<div class="panel-body">
		
		<p><a class="add-trip" href="#">Add a new trip</a></p>
		<div class="list-group past-trips"></div>
		</div>
		</div>
	</div>
</div>

<script type="handlerbars-template" id="trip-item-template">
	{{#each this}}
		<a href="trip.php?id={{id}}" data-id="{{id}}" class="list-group-item">{{name}}<span class="remove glyphicon glyphicon-remove pull-right"/></a>
	{{/each}}
</script>

<script>
	$(function() {
		<?php $data = new CashMoney\Data\Data(); ?>
		var lessFakeTripData = <?= json_encode($data->getTrips()) ?>;
		
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
			var name = $('.past-trips input').val();
			$('.past-trips input').val('');
			
			$.getJSON('process-trip.php?action=add', { name: name }, function(response) {
				lessFakeTripData.unshift(response);
				listTrips();				
			}).fail(function() { alert('Failed to create trip.') });
		}
		
		function listTrips() {
			$('.past-trips').html(renderTemplate('trip-item-template', lessFakeTripData));
		}
		
		$('.past-trips').on('click', '.remove', function(e) {
			e.preventDefault();
			
			if (confirm("Are you sure you want to delete this trip?")) {
				var id = $(e.target).closest('a').data('id');
				lessFakeTripData = lessFakeTripData.filter(function(x) { return x.id !== id; });
				listTrips();
				$.get('process-trip.php?action=remove', { id: id });
			}
		});
	})
</script>

<?php require 'footer.php'; ?>
