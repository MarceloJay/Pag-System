<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use App\Services\AsaasAdapter;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AuthController;

class ClienteController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $auth = new AuthController();
    }

    /**
     * Mostrar Lista de Clientes
     *
     * Esta função é responsável por exibir uma lista de clientes.
     * Ela recupera os dados dos clientes e os paginiza para exibir até 10 clientes por página.
     * Em seguida, os clientes são passados para a visualização 'clientes.index' para renderização.
     *
     * Autor: Marcelo Ferreira
     * Atualizado em: 13/07/2023
     */
    public function index()
    {
        $clientes = Cliente::paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }
    /**
     * Get a JWT via given credentials.
     *
     * Efetuar login
     *
     * Esta função é responsável por processar o pedido de login.
     * Ela utiliza a instância de AuthController para realizar o login com base nos dados do Request fornecido.
     * Após o login bem-sucedido, o redirecionamento é feito para a rota 'pagamentos.index'.
     * Em caso de erro, uma mensagem de erro é armazenada na sessão e o redirecionamento é feito de volta à página anterior.
     *
     * @param Request $request O objeto Request contendo os dados do login.
     *
     * @return \Illuminate\Http\RedirectResponse O redirecionamento para a rota 'pagamentos.index' em caso de login bem-sucedido.
     * @throws \Throwable Em caso de erro, uma exceção é lançada e uma mensagem de erro é armazenada na sessão.
     * @return \Illuminate\Http\RedirectResponse O redirecionamento de volta à página anterior em caso de erro.
     */
    public function login(Request $request)
    {
        try {
            $auth = new AuthController();
            $auth->login($request);

            return redirect()->route('pagamentos.index');

        } catch (\Throwable $th) {
            session()->flash('errorMessage', $th->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Efetuar logout
     *
     * Esta função é responsável por processar o pedido de logout.
     * Se o usuário estiver autenticado, o logout será realizado chamando o método logout() do objeto auth().
     * Após o logout bem-sucedido, o redirecionamento é feito para a rota 'welcome' com uma mensagem de sucesso.
     * Se o usuário não estiver autenticado, o redirecionamento será feito diretamente para a rota 'welcome'.
     *
     * Autor: Marcelo Ferreira
     * Atualizado em: 13/07/2023
     *
     * @return \Illuminate\Http\RedirectResponse O redirecionamento para a rota 'welcome' com uma mensagem de sucesso em caso de logout bem-sucedido.
     * @return \Illuminate\Http\RedirectResponse O redirecionamento direto para a rota 'welcome' caso o usuário não esteja autenticado.
     */
    public function logout()
    {
        if (auth()->check()) {
            auth()->logout();
            return redirect()->route('welcome')->with('success', 'Logout realizado com sucesso!');
        } else {
            return redirect()->route('welcome');
        }
    }

    /**
     * Armazenar Cliente
     *
     * Esta função é responsável por armazenar um novo cliente.
     * Ela envia uma requisição POST para criar um novo cliente usando o adaptador AsaasAdapter.
     * Em seguida, verifica se o email já está em uso por outro usuário.
     * Se o email estiver em uso, uma exceção será lançada.
     * Caso contrário, o cliente será criado no banco de dados usando o modelo Cliente.
     * Além disso, registra o usuário usando o AuthController e atribui o papel de cliente ao usuário.
     * Por fim, retorna a visualização 'clientes.login' com o cliente criado e uma mensagem de sucesso.
     * Se ocorrer um erro durante o processo, o redirecionamento será feito para a rota 'clientes.create' com uma mensagem de erro.
     *
     * Autor: Marcelo Ferreira
     * Atualizado em: 13/07/2023
     *
     * @param Request $request O objeto Request contendo os dados do cliente a serem armazenados.
     *
     * @return \Illuminate\View\View A visualização 'clientes.login' com o cliente criado e uma mensagem de sucesso em caso de sucesso.
     * @return \Illuminate\Http\RedirectResponse O redirecionamento para a rota 'clientes.create' com uma mensagem de erro em caso de erro.
     * @throws \Throwable Em caso de exceção durante o processo, uma mensagem de erro é retornada no redirecionamento.
     */
    public function store(Request $request)
    {
        try {
            $asaasAdapter = new AsaasAdapter();
            $response = $asaasAdapter->sendRequest('POST', 'customers', $request->all());
            $response = json_decode($response);
            
            $request['customer'] = $response->id;
            $existingUser = User::where('email', $request->input('email'))->first();
            if ($existingUser) {
                throw new \Exception('Email already in use');
            } else {
                $cliente = Cliente::create($request->all());
            }

            $auth = new AuthController();
            $tokenId = $auth->register($request);
            $user = User::where('email', $request->email)->first();
            $role = Role::where('name', 'cliente')->first();
            RoleUser::create(['user_id' => $user->id, 'role_id' => $role->id]);
            
            return view('clientes.login', compact('cliente'))->with('success', 'Cliente criado com sucesso!');
        } catch (\Throwable $th) {
            return redirect()->route('clientes.create')->with('errorMessage', $th->getMessage());
        }
    }

    /**
     * Mostrar Cliente
     *
     * Esta função é responsável por exibir os detalhes de um cliente específico.
     * Ela retorna a visualização 'clientes.show', passando o cliente como dado compactado.
     *
     * Autor: Marcelo Ferreira
     * Atualizado em: 13/07/2023
     *
     * @param Cliente $cliente O objeto Cliente que representa o cliente a ser mostrado.
     *
     * @return \Illuminate\View\View A visualização 'clientes.show' com os detalhes do cliente.
     */
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Editar Cliente
     *
     * Esta função é responsável por exibir o formulário de edição para um cliente específico.
     * Ela retorna a visualização 'clientes.edit', passando o cliente como dado compactado.
     *
     * Autor: Marcelo Ferreira
     * Atualizado em: 13/07/2023
     *
     * @param Cliente $cliente O objeto Cliente que representa o cliente a ser editado.
     *
     * @return \Illuminate\View\View A visualização 'clientes.edit' com o formulário de edição para o cliente.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Atualizar Cliente
     *
     * Esta função é responsável por atualizar os dados de um cliente existente.
     * Ela atualiza os dados do cliente com base nos dados fornecidos no objeto Request.
     * Após a atualização bem-sucedida, o redirecionamento é feito para a rota 'clientes.index'
     * com uma mensagem de sucesso informando que o cliente foi atualizado com sucesso.
     *
     * Autor: Marcelo Ferreira
     * Atualizado em: 13/07/2023
     *
     * @param Request $request O objeto Request contendo os novos dados do cliente.
     * @param Cliente $cliente O objeto Cliente que representa o cliente a ser atualizado.
     *
     * @return \Illuminate\Http\RedirectResponse O redirecionamento para a rota 'clientes.index' com uma mensagem de sucesso em caso de atualização bem-sucedida.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Excluir Cliente
     *
     * Esta função é responsável por excluir um cliente existente.
     * Ela exclui o cliente especificado pelo objeto Cliente fornecido.
     * Após a exclusão bem-sucedida, o redirecionamento é feito para a rota 'clientes.index'
     * com uma mensagem de sucesso informando que o cliente foi excluído com sucesso.
     *
     * Autor: Marcelo Ferreira
     * Atualizado em: 13/07/2023
     *
     * @param Cliente $cliente O objeto Cliente que representa o cliente a ser excluído.
     *
     * @return \Illuminate\Http\RedirectResponse O redirecionamento para a rota 'clientes.index' com uma mensagem de sucesso em caso de exclusão bem-sucedida.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente excluído com sucesso!');
    }
}
