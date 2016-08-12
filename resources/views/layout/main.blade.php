<!DOCTYPE html>
<html>
	<head>
		<title>Katacart</title>

		<link href='https://fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>
		<style>
			html, body {
				height: 100%;
			}

			body {
				margin: 0;
				padding: 0;
				width: 100%;
				display: table;
				font-weight: 100;
				font-family: 'Slabo 27px', serif;
			}

			.container {
				text-align: left;
			}

			#head{
				background-color: #333;
				color: #fff;
				padding: 20px;
			}
			#head h1.title{
				padding: 0px;
				margin: 0px;
			}

			.content {
				padding:20px;
				text-align: left;
				display: inline-block;
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

		<div class="container">
			<div id="head">
				<h1 class="title">KataCart</h1><small>the shopping cart you always needed</small>
			</div>
			<div class="content">
				@yield('content')
			</div>
		</div>
		
	</body>
</html>
