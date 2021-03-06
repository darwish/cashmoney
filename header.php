<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?= isset($pageTitle) ? "$pageTitle - " : "" ?>FareShare</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
		
		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"> -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.3.min.js"><\/script>')</script>
    </head>
    <body>
	
	<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="your-trips.php"><img src="img/logo.png" style="max-height: 35px; margin-top: -7px; display: inline-block; margin-right: 7px;"><span style="
    top: -3px;
    position: relative;
">Home</span></a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	<ul class="nav navbar-nav">
    <?php $trips = (new CashMoney\Data\Data())->getTrips(); ?>
    <?php if (isset($trips) && count($trips) > 0 ): ?>
	      <li class="dropdown">
        <?php if (isset($trip)): ?>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $trip->getName(); ?> <span class="caret"></span></a>
        <?php else: ?>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Trips <span class="caret"></span></a>
        <?php endif; ?>

          <ul class="dropdown-menu">
            <?php foreach ($trips as $trip_a): ?>
            <li><a href="trip.php?id=<?=$trip_a->getID(); ?>"><?= $trip_a->getName(); ?></a></li>
            <?php endforeach; ?>
          </ul>
       </li>
     <?php endif; ?>
     <?php $pendingExpenses = (new CashMoney\Data\Data())->getPendingExpenses(); ?>
     <p class="navbar-text">
       <a class="navbar-btn" href="new-expenses.php">
         Pending Expenses
       </a>
       <?php if (count($pendingExpenses) > 0): ?>
        <span class="label label-danger" id="global-new-expenses-count"><?= count($pendingExpenses) ?></span>
       <?php endif; ?>
     </p>
	</ul>
	
      <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
	
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
		<div class="container">