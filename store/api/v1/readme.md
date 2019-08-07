- API
# Tecnologias utilizadas

. Como linguagem de programação, foi utilizado PHP(7.3.6).
. Para estruturação da API REST, foi utilizado o Slim Framework(3.0). O Slim é um micro-framework, para apis simples, porém poderosa. É uma ótima utilização para o desafio apresentado por ser um framework estruturado para trabalhar com microserviços, podendo ser escalável.    
. Para o banco de dados, MySql. Companheiro perfeito para o PHP, onde utilizamos a extensão PDO, para a comunicação dos dados.
. Servidor HTTP Apache(2.4).
. Postman para os testes.

# Dependências e requisitos
. tuupola/slim-jwt-auth - Autenticação jwt
. Porta 80 liberada
. Composer
. O root do projeto deverá ser o primeiro diretório, onde se encontra as pastas 'store' e 'simulador'

# REGRA DE NEGÓCIO

. Os produtos serão cadastrados, e consultados via API REST. Haverá validação para que não ocorra duplicidade de produtos.
. Os carrinhos serão mantidos pelo sistema que irá utilizar a API. Sempre que um item for adicionado ao carrinho, a api irá salvar os dados desse item e o carrinho em que foi inserido.
. Haverá a possibilidade de adicionar o mesmo item mais de uma vez para o mesmo carrinho, pois será considerado como a quantidade daquele item no carrinho.
. Ao finalizar a compra, a API irá criar a transação, armazenar os dados do cartão e limpar o carrinho.
. Fica disponível para consulta todas as compras que os clientes realizaram, podendo filtrar por cliente

#Escalabilidade
. Priorizando velocidade de execução para muitas requisições, foi decidido a criação de dois bancos de dados em diferentes servidores. Um cuidará das consultas de logs que não dependem diretamente da estrutura principal do banco. Visto que a consulta por informações, dependendo da quantidade, pode comprometer os serviços principais, que nesse cenário, remete a venda dos produtos.

#Deploy

. Clone o repositório
. Inicie os serviços Apache, PHP e Mysql
. Abra o terminal, vá até a pasta root do projeto, entre em store\api\v1 e rode o comando 'composer install', ele irá cuidar de instalar as dependências necessárias 
. Pronto, tudo certo para iniciarmos os testes

#TESTES
. Podemos realizar os testes de duas formas:
    . Postman - É uma ferramenta com o objetivo de testar serviços REST.
        . Abra a ferramenta Postman.
        . Importe a collection que se encontra na pasta root do projeto e o environment
        . Rode as requisições para acompanhar os resultados
    . Sistema para simulação - Tomei a liberdade, de criar um sistema super básico, somente para testar o funcionamento da api.
        . No navegador, definido o primeiro diretório como root, acesse http://localhost/simulador.
        . Será exibida uma tela, com uma grid e alguns botões, simulando as ações do desafio. 
        . Cada ação dessa tela se comunica com a api que criamos

#Documentação
https://documenter.getpostman.com/view/7909799/SVYrtecf

#Tecnologias utilizadas no sistema simulador
. PHP(7.3.6)
. AngularJs
. Codeigniter(2.2.6)
. Bootstrap 4.0
