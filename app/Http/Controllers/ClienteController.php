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
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/web/auth/login",
     *     operationId="login Cliente",
     *     tags={"Authentication"},
     *     summary="Cliente login",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Cliente credentials",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="User email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="User password"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
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

    public function logout()
    {
        if (auth()->check()) {
            auth()->logout();
            return redirect()->route('welcome')->with('success', 'Logout realizado com sucesso!');
        } else {
            return redirect()->route('welcome');
        }
    }

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

    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente excluído com sucesso!');
    }
}
