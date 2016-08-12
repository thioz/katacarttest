(function () {

	angular.module('app', ['ngRoute'])
					.config(['$routeProvider', function ($routeProvider) {

							$routeProvider.when('/', {
								'controller': 'CartController',
								templateUrl: '/app/modules/cart.html'
							});
							
							$routeProvider.otherwise('/');
						}]);



})();