<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Gerador de Links</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous"> 
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <style>
  .icone{
    padding:20px;
  }
    html, 
body {
    height: 100%;
    background-color:#0dc2ee;
}
  </style>
</head>

<body class="d-flex">
  
<div class="container-fluid" ng-app="app" ng-controller="controller">
  <div class="row" style="padding: 20px 20px 20px 20px;color:white;">
    <div class="col-md-12">
      Carrinho: {{cart_id}}
      <br>Cliente: {{client_id}}
    </div>
  </div>
  <div class="row" style="padding: 20px 20px 20px 20px;color:white;">
    <div class="col-md-6">
      <h3>Produtos</h3>
      <br>
      <div class="row">
        <div class="col-md-12">
          <input type="text" placeholder="Identificador" ng-model="novo.product_id">
        <button class="btn btn-success" ng-click="addProduto()" ng-disabled="!novo.product_id">Novo Produto</button>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-12">
          <table class="table">
            <tr>
              <th>ID Produto</th>
              <th>Artista</th>
              <th>Preço</th>
              <th>&nbsp;</th>
            </tr>
            <tr ng-repeat="produto in produtos">
              <td>{{produto.product_id}}</td>
              <td>{{produto.artist}}</td>
              <td>{{produto.price}}</td>
              <td><button class="btn btn-warning" ng-click="addCarrinho(produto)">Adicionar ao Carrinho</button></td>
            </tr>
          </table>
        </div>
      </div>
      
    </div>
    <div class="col-md-6">
      <h3>Carrinho</h3>
      <br>
      <div class="row">
        <div class="col-md-12">
        <button class="btn btn-success"  href="#" ng-click="finalizar()" ng-disabled="itensCarrinho.length <= 0">Finalizar Compra</button>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-12">
          <table class="table">
            <tr>
              <th>ID Produto</th>
              
            </tr>
            <tr ng-repeat="item in itensCarrinho">
              <td>{{item.product_id}}</td>
              
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <h3>Histórico de Compras</h3>
      <br>
      <div class="row">
        <div class="col-md-12">
          <table class="table">
            <tr>
              <th>ID Produto</th>
              
            </tr>
            <tr ng-repeat="historico in historicos">
              <td>{{historico.order_id}}</td>
              
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.4/angular.min.js"></script>

  <script src="<?php echo base_url(); ?>assets/js/mask.js"></script>  
  <!-- Argon JS -->
  <script type="text/javascript">
      var app = angular.module("app", ['ui.mask']);
    </script>
    <script type="text/javascript">
      
      app.controller("controller", function ($scope, $http) {
      $scope.tela = 'login';
      $scope.baseUrl = '<?php echo base_url(); ?>index.php/home/';
      $scope.cart_id = "569c30dc-6bdb-407a-b18b-3794f9b206a1" 
      $scope.client_id = "4321-1234" 
      $scope.client_nome = "Rafael Alves de Lima" 
      $scope.itensCarrinho = [];

      $scope.getProdutos = function()
      {
        $http.get($scope.baseUrl+"getProdutos")
          .success(function (data, status, headers, config) {
            if (data.success) {
              $scope.produtos = data.produtos;
            } 
          }
        )
        
      };
      $scope.getProdutos();

      $scope.finalizar = function()
      {
        $http.get($scope.baseUrl+"finalizar")
          .success(function (data, status, headers, config) {
            if (data.success) {
              $scope.getItensCarrinho();
              $scope.getHistorico();
            } 
          }
        )
        
      };

      $scope.addCarrinho = function(item){
        data = {};
        data.client_id = $scope.client_id;
        data.cart_id = $scope.cart_id;
        data.product_id = item.product_id;
        data.date = '12/07/2019';
        data.time = '12:00:00';
        $http.post($scope.baseUrl+"addCarrinho", data)
          .success(function (data, status, headers, config) {
            if (data.success) {
              $scope.getItensCarrinho();
            } 
          }
        )
      };
      $scope.addProduto = function(){
        $http.post($scope.baseUrl+"addProduto", $scope.novo)
          .success(function (data, status, headers, config) {
            if (data.success) {
              $scope.getProdutos();
            } else{
              alert(data.message);
            }
          }
        )
      };

      

      $scope.getHistorico = function()
      {
        $http.get($scope.baseUrl+"getHistorico/")
          .success(function (data, status, headers, config) {
            if (data.success) {
              $scope.historicos = data.historico;
            } 
          }
        )
        
      };
      $scope.getHistorico();

      $scope.getItensCarrinho = function()
      {
        $http.get($scope.baseUrl+"getItensCarrinho/" + $scope.cart_id)
          .success(function (data, status, headers, config) {
            if (data.success) {
              $scope.itensCarrinho = data.itens;
            } 
          }
        )
        
      };
      $scope.getItensCarrinho();
    });

  function showalert(message, alerttype,elemento) {
    $('#'+elemento).append('<div id="alertdiv" class="alert ' + alerttype + '"><a class="close" data-dismiss="alert">×</a><span>' + message.split("\n").join("<br>") + '</span></div>')
    setTimeout(function () {
      $('#alertdiv').fadeOut('slow');
      $("#alertdiv").remove();
    }, 3000);
  }
</script>
</body>

</html>