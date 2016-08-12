/* global angular */


(function () {

	angular.module('app').controller('CartController', ['$rootScope', '$scope', '$http',
		function ($rootScope, $scope, $http) {
			$scope.newitem = {
				item:{}
			};
			
			$scope.init = function () {
				$scope.cart = {
					items: [],
					rules: [],
				};
				
				$scope.cartdata = {
					items: [],
					rules: {},
				};
				
				$http.get('/api/products').then(function(response){
					$scope.products = response.data;
				});
				$http.get('/api/products/rules').then(function(response){
					$scope.rules = response.data;
				});

			};
			
			$scope.changeProduct = function(){
				$scope.newitem.item = angular.copy($scope.newitem.product);
				$scope.newitem.item.quantity = parseInt($scope.newitem.item.unit);
			}
			
			$scope.updateCart = function(){
				$http({
					method: 'POST',
					url: '/api/products/update',
					data: $scope.cartdata
				}).then(function(response){
					$scope.cart.items = response.data.items;
					$scope.cart.total = response.data.total;
					
				});
			}
			
			$scope.addToCart = function(){
				$scope.cartdata.items.push(angular.copy($scope.newitem.item));
				$scope.newitem = {};
				$scope.updateCart();
			};


			$scope.init();
		}]);
})();