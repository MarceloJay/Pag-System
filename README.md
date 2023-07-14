## Versões
![Static Badge](https://img.shields.io/badge/Laravel%20Framework-10.13.5-darkred)  ![Static Badge](https://img.shields.io/badge/PHP-8.2.6-blue)  ![Static Badge](https://img.shields.io/badge/tymon%2Fjwt_auth-v2.0.0-red?link=https%3A%2F%2Fgithub.com%2Ftymondesigns%2Fjwt-auth)  ![Static Badge](https://img.shields.io/badge/darkaonline%2Fl5_swagger%20-v8.5.1-green)  ![Static Badge](https://img.shields.io/badge/mysql%20-v8.0.33-darkblue)

### Features

Este documento fornece um guia passo a passo para baixar e executar um projeto Laravel a partir do Git. Ele abrange desde o download do projeto até a configuração do ambiente e a execução do projeto no Laravel.

# Pag System


## Passo 1: Baixando o projeto
                
1. Certifique-se de ter o Git instalado em seu ambiente de desenvolvimento. Caso não tenha, siga as instruções de instalação do Git para o seu sistema operacional.
2. Abra o terminal ou prompt de comando e navegue até o diretório onde deseja baixar o projeto.
3. Execute o seguinte comando para clonar o projeto a partir do repositório Git:
`$ git clone https://github.com/MarceloJay/Pag-System.git`
                

## Passo 2: Configurando o ambiente
                
1. Certifique-se de ter o PHP e o Composer instalados em seu ambiente de desenvolvimento. Caso não tenha, siga as instruções de instalação do PHP e do Composer.
2. Abra o terminal ou prompt de comando e navegue até o diretório do projeto Laravel que foi baixado.
`$ cd Pag-System`
3. Execute o seguinte comando para instalar as dependências do projeto Laravel:
`$ composer install`
                
## Passo 3: Configuração do banco de dados

Abra o arquivo .env na raiz do projeto e configure as informações de conexão com o banco de dados MySQL (como nome do banco de dados, usuário e senha).
Execute o comando `$ php artisan migrate` para criar as tabelas necessárias no banco de dados.

## Passo 4: Executando o projeto
                
1. No terminal ou prompt de comando, execute o seguinte comando para iniciar o servidor de desenvolvimento do Laravel:
`$ php artisan serve`
                
> O projeto Laravel será executado no servidor de desenvolvimento e você poderá acessá-lo em seu navegador através da URL fornecida pelo comando anterior.

**Pronto! Agora você pode explorar e utilizar o projeto Laravel localmente.**
> Certifique-se de estudar a documentação oficial do Laravel para obter mais informações detalhadas sobre o desenvolvimento e execução de projetos Laravel.

# Acesso ao Sistema como Administrador.
>No sistema foi criado uma classe UserSeeder.php que criara um usuario Administrador:
![Pag System](https://github.com/MarceloJay/Pag-System/blob/dev/public/images/seeder.png)
> onde terá acesso a todos pagamentos e todos clientes , diferente 
>de quando se regitra na pagina login, que será criado usuario cliente.
>Para executar o seeder e criar o usuário "Admin", execute o seguinte comando no terminal: `$ php artisan db:seed --class=UserSeeder`
 
## Só faça os passo a baixo se quiser usar esse projeto gerando cobrança com sua conta no Asaas.

## Para fazer pagamentos integrado ao ambiente de homologação do Asaas.
>Eu poderia ter instalado as dependencias informando no composer.json:
![Pag System](https://github.com/MarceloJay/Pag-System/blob/dev/public/images/composer.png)


>e também informando a dependência na tag require:
![Pag System](https://github.com/MarceloJay/Pag-System/blob/dev/public/images/require.png)


>assim ficaria tudo mais fácil e rápido, mas preferi fazer essa integração na mão , para poder de alguma forma, mostrar um pouco mais da minha capacidade de
>fazer um sistema funcional.


> Se você verificar no .env tem a variável ASAAS_BASE_URI que é o endereço do endpoint para envio de requisições também tem a variável ASAAS_API_KEY que esta setado 
> com api key da minha conta , se preferir vc pode criar sua conta no portal Asaas para testa seguindo os passos a baixo obter a API key.
## Credenciais de Sandbox:
>Crie uma conta no Asaas Sandbox( https://sandbox.asaas.com/ ), na parte de Configuração de Conta->Integrações você irá conseguir a API Key de Sandbox para iniciar a integração.


![Pag System](https://github.com/MarceloJay/Pag-System/blob/dev/public/images/Welcome.png)
![Pag System](https://github.com/MarceloJay/Pag-System/blob/dev/public/images/listapag.png)
![Pag System](https://github.com/MarceloJay/Pag-System/blob/dev/public/images/listaclient.png)
![Pag System](https://github.com/MarceloJay/Pag-System/blob/dev/public/images/PagPix.png)
![Pag System](https://github.com/MarceloJay/Pag-System/blob/dev/public/images/PagCartao.png)
![Pag System](https://github.com/MarceloJay/Pag-System/blob/dev/public/images/PagBoleto.png)
![Pag System](https://github.com/MarceloJay/Pag-System/blob/dev/public/images/Login.png)

 
> É usado JWT para autenticar solicitações de usuários,  
> conforme estipulados em routes.  


  
  
