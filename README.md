### Features

Este documento fornece um guia passo a passo para baixar e executar um projeto Laravel a partir do Git. Ele abrange desde o download do projeto até a configuração do ambiente e a execução do projeto no Laravel.

# ApiRESTfull


## Passo 1: Baixando o projeto
                
1. Certifique-se de ter o Git instalado em seu ambiente de desenvolvimento. Caso não tenha, siga as instruções de instalação do Git para o seu sistema operacional.
2. Abra o terminal ou prompt de comando e navegue até o diretório onde deseja baixar o projeto.
3. Execute o seguinte comando para clonar o projeto a partir do repositório Git:
`$ git clone https://github.com/MarceloJay/ApiRESTfull.git`
                

## Passo 2: Configurando o ambiente
                
1. Certifique-se de ter o PHP e o Composer instalados em seu ambiente de desenvolvimento. Caso não tenha, siga as instruções de instalação do PHP e do Composer.
2. Abra o terminal ou prompt de comando e navegue até o diretório do projeto Laravel que foi baixado.
`$ cd ApiRESTfull`
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

![](public/swagger/Screenshot 2023-06-17 at 13.40.43.png "")



