<?php require_once 'header.php' ?>

<div class="row">
	<div class="col-sm-4">
		<h1 class="title">Trip Overview</h1>
		<div class="debt-list"></div>
	</div>
</div>

<script type="handlerbars-template" id="user-debt-list-template">
	{{> userDebtItem you}}
	{{#each others}}
		{{> userDebtItem}}
	{{/each}}
</script>

<script type="handlerbars-template" id="user-debt-item-template">
	<p class="{{lentOrBorrowed}}">{{name}} {{lentOrBorrowed}} {{amount}}</p>
</script>

<script>
	$(function() {
		Handlebars.registerPartial('userDebtItem', $('#user-debt-item-template').html());
		
		var fakeDebtData = {
			you: { id: 1, name: 'You', lentOrBorrowed: 'lent', amount: 375.30 },
			others: [
				{ id: 2, name: 'Brian', lentOrBorrowed: 'borrowed', amount: 200 },
				{ id: 3, name: 'Sam', lentOrBorrowed: 'borrowed', amount: 250 },
				{ id: 4, name: 'Erica', lentOrBorrowed: 'lent', amount: 74.70 },
			]
		};
		
		$('.debt-list').html(renderTemplate('user-debt-list-template', fakeDebtData));
	});
</script>

<?php require_once 'footer.php' ?>