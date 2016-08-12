<!DOCTYPE html>
<html ng-app="app">
	<head>
		<title>Katacart - Angular demo</title>
		<link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet" type="text/css">
		<!-- Bootstrap CSS -->    
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
		<!-- bootstrap theme -->
		<link href="/assets/css/bootstrap-theme.css" rel="stylesheet">

		<style>
			
			#head{
				background-color: #333;
				color: #fff;
				padding: 20px;
			}
			#head h1.title{
				padding: 0px;
				margin: 0px;
			}

			table, th, td {
				border: none;
				border-collapse: collapse;
			}

			table th, table td{
				padding: 10px;
			}

			table>thead>tr{
				background: #333;
				color: #fff;
			}

			.title {
			}
		</style>
	</head>
	<body>

		<div class="container-fluid col-lg-12">
			<div class="row">
				<div id="head" class="col-lg-12">
					<h1 class="title">KataCart</h1><small>the shopping cart you always needed</small>
				</div>
			</div>
			<div class="row">
				@yield('content')
			</div>
		</div>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular-route.min.js"></script>

		<script src="/app/cart.js"></script>
		<script src="/app/modules/cart.controller.js"></script>

	</body>
</html>
