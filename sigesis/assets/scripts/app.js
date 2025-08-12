//Define an angular module for our app
var app = angular.module('myApp', ['ui.bootstrap']);

app.controller('autocompleteController', function($scope, $http, $window) {
	$scope.da  = function(nome){
          $http.get("webservices/buscaaluno.php?nome="+nome).success(function(data){
		  $scope.alunos = data;
       });
      };
	  $scope.enviar  = function(id){
         if(id != undefined) {
			$window.location.href = 'index.php?do=aluno&id='+id;
	  } else {
			 $.bootstrapGrowl("Selecione um nome na lista de alunos.", {
				ele: "body",
				type: "info", // (null, 'info', 'danger', 'success', 'warning')
				offset: {
					from: "top",
					amount: 50
				},
				align: "center",
				width: "auto",
				stackup_spacing: 10
			});
		 }
      };
});
