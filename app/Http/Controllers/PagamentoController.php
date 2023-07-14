<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pagamento;
use App\Models\PgtoBoleto;
use App\Models\PgtoCartao;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use App\Services\AsaasAdapter;
use GuzzleHttp\Client;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class PagamentoController extends Controller
{
    
    public function index()
    {
        $pagamentos = null;
        
        if(auth('web')->check() && auth('web')->user()->hasRole('admin')) {
            $pagamentos = Pagamento::orderBy('dueDate', 'desc')->paginate(10);
        } else if(auth('web')->check() && auth('web')->user()->hasRole('cliente')){
            
            $cliente = Cliente::where('email', auth('web')->user()->email)->first();
            $pagamentos = Pagamento::where('customer', $cliente->customer)->orderBy('dueDate', 'desc')->paginate(10);
        }
        
        foreach ($pagamentos as $pagamento) {
            $pagamento['cliente'] = Cliente::where('customer', $pagamento->customer)->first();
        }
        
        return view('pagamentos.index', compact('pagamentos'));
    }


    public function create()
    {
        $userLogged = User::where('id', session()->get('userId'))->first();
        $clienteLog = Cliente::where('email', $userLogged->email)->first();
        $clientes = Cliente::all();
        return view('pagamentos.create', compact('clientes', 'clienteLog'));
    }

    public function store(Request $request)
    {
        try {
            $cliente = Cliente::find($request['customer']);
            $request['customer'] = $cliente->customer;  
            $pagamento = Pagamento::create($request->all());
            // dd($pagamento->toArray());
            switch ($request['billingType']) {
                case 'BOLETO':
                    $pgtoBoleto = new PgtoBoleto();
                    $pgtoBoleto = [
                        'customer' => $request['customer'],
                        'payment' => $pagamento->id, 
                        'billingType' => $pagamento->billingType,
                        'dueDate' => $request['dueDate'],
                        'value' => $request['value'],
                        'description' => $request['description'],
                        'externalReference' => $request['externalReference'],
                        'discountValue' => $request['discountValue'],
                        'dueDateLimitDays' => $request['dueDateLimitDays'],
                        'fineValue' => $request['fineValue'],
                        'interestValue' => $request['interestValue'],
                        'postalService' => isset($request['postalService']) ? (bool)$request['postalService'] : false,
                    ];
                    PgtoBoleto::create($pgtoBoleto);
                    print_r($pgtoBoleto);
                    
                    break;
                case 'CREDIT_CARD':
                    $pgtoCartao = new PgtoCartao();
                    $pgtoCartao = [
                        'customer' => $request['customer'],
                        'payment' => $pagamento->id, 
                        'billingType' => $pagamento->billingType,
                        'dueDate' => $request['dueDate'],
                        'value' => $request['value'],
                        'description' => $request['description'],
                        'externalReference' => $request['externalReference'],
                        'creditCardHolderName' => $request['creditCardHolderName'],
                        'creditCardNumber' => $request['creditCardNumber'],
                        'creditCardExpiryMonth' => $request['creditCardExpiryMonth'],
                        'creditCardExpiryYear' => $request['creditCardExpiryYear'],
                        'creditCardCcv' => $request['creditCardCcv'],
                        // 'creditCardToken' => $request['creditCardToken'],
                        'creditCardHolderEmail' => $request['creditCardHolderEmail'],
                        'creditCardHolderCpfCnpj' => $request['creditCardHolderCpfCnpj'],
                        'creditCardHolderPostalCode' => $request['creditCardHolderPostalCode'],
                        'creditCardHolderAddressNumber' => $request['creditCardHolderAddressNumber'],
                        'creditCardHolderAddressComplement' => $request['creditCardHolderAddressComplement'],
                        'creditCardHolderPhone' => $request['creditCardHolderPhone'],
                        'creditCardHolderMobilePhone' => $request['creditCardHolderMobilePhone'],
                    ];
                    PgtoCartao::create($pgtoCartao); 
                    $request->merge(['creditCard' => [
                        'holderName' => $request['creditCardHolderName'],
                        'number' => $request['creditCardNumber'],
                        'expiryMonth' => $request['creditCardExpiryMonth'],
                        'expiryYear' => $request['creditCardExpiryYear'],
                        'ccv' => $request['creditCardCcv'],
                    ]]);    
                    
                    $request->merge(['creditCardHolderInfo' => [
                        'name' => $request['creditCardHolderName'],
                        'email' => $request['creditCardHolderEmail'],
                        'cpfCnpj' => $request['creditCardHolderCpfCnpj'],
                        'postalCode' => $request['creditCardHolderPostalCode'],
                        'addressNumber' => $request['creditCardHolderAddressNumber'],
                        'addressComplement' => $request['creditCardHolderAddressComplement'],
                        'phone' => $request['creditCardHolderPhone'],
                        'mobilePhone' => $request['creditCardHolderMobilePhone'],
                    ]]);
                    
                    break; 
            } 

            $request['billingType'] = $pagamento->billingType;
            $asaasAdapter = new AsaasAdapter();
            $response = $asaasAdapter->sendRequest('POST', 'payments', $request->all());
            $response = json_decode($response);
            if ($pagamento) {
                $pagamento->asaas_id = $response->id;
                $pagamento->save();
                
            }
            return redirect()->route('pagamentos.index')->with('success', 'Pagamento criado com sucesso.');

        } catch (\Throwable $th) {
            $found = substr($th->getMessage(), strpos($th->getMessage(),'description')+strlen('description')+3);
            $found = substr($found, 0, strpos($found,'.')+1);
            if($found){
                return redirect()->route('pagamentos.create')->with('errorMessage', $found);
            }
            else{
                return redirect()->route('pagamentos.crate')->with('errorMessage', $th->getMessage());
            }
        }
    }

    public function show(Pagamento $pagamento)
    {
        
        $pagamento['cliente'] = Cliente::where('customer', $pagamento->customer)->first();
        
        switch ($pagamento->billingType) {
            case 'BOLETO':
                // dd($pagamento->asaas_id);
                $asaasAdapter = new AsaasAdapter();
                $response = $asaasAdapter->sendRequest('POST', 'payments/'.$pagamento->asaas_id.'/identificationField', $pagamento->asaas_id);
                $response = json_decode($response);
                // dd($response);
                $pgtoBoleto = PgtoBoleto::where('payment', $pagamento->id)->first();
                return view('pagamentos.show', compact('pagamento', 'pgtoBoleto','response'));
            case 'CREDIT_CARD':
                $pgtoCartao = PgtoCartao::where('payment', $pagamento->id)->first();
                return view('pagamentos.show', compact('pagamento', 'pgtoCartao'));
            case 'PIX':
                $url = 'payments/'.$pagamento->asaas_id.'/pixQrCode';
                $asaasAdapter = new AsaasAdapter();
                $response = $asaasAdapter->sendRequest('GET', $url);
                $response = json_decode($response);
                return view('pagamentos.show', compact('pagamento', 'response'));    
            default:
                return view('pagamentos.show', compact('pagamento'));
        }
    }

    public function edit(Pagamento $pagamento)
    {
        $clientes = Cliente::all();
        switch ($pagamento->billingType) {
            case 'BOLETO':
                $pgtoBoleto = PgtoBoleto::where('payment', $pagamento->id)->first();
                $pgtoCartao = new PgtoCartao();
                return view('pagamentos.edit', compact('pagamento', 'pgtoBoleto', 'pgtoCartao', 'clientes'));
            case 'CREDIT_CARD':
                $pgtoCartao = PgtoCartao::where('payment', $pagamento->id)->first();
                $pgtoBoleto = new PgtoBoleto();
                return view('pagamentos.edit', compact('pagamento', 'pgtoBoleto', 'pgtoCartao', 'clientes'));
            default:
                $pgtoCartao = new PgtoCartao();
                $pgtoBoleto = new PgtoBoleto();
                return view('pagamentos.edit', compact('pagamento', 'pgtoBoleto', 'pgtoCartao', 'clientes'));
        }
    }

    public function update(Request $request, Pagamento $pagamento)
    {
        $request->validate([
            'customer' => 'required',
            'dueDate' => 'required',
            'value' => 'required',
            'billingType' => 'required',
            'paymentMethod' => 'required',
        ]);

        $cliente = Cliente::find($request['customer']);
        $pagamentoRequest = [];
        $pagamentoRequest['customer'] = $cliente->customer;
        $pagamentoRequest['billingType'] = $pagamento->billingType;
        $pagamentoRequest['dueDate'] = $pagamento->dueDate;
        $pagamentoRequest['value'] = $pagamento->value;
        $pagamentoRequest['description'] = $pagamento->description;
        $pagamentoRequest['externalReference'] = $pagamento->externalReference;

        $pagamentoUpdate = Pagamento::find($pagamento->id);
        $pagamentoUpdate->customer = $cliente->customer;
        $pagamentoUpdate->billingType = $pagamento->billingType;
        $pagamentoUpdate->dueDate = $pagamento->dueDate;
        $pagamentoUpdate->value = $pagamento->value;
        $pagamentoUpdate->description = $pagamento->description;
        $pagamentoUpdate->externalReference = $pagamento->externalReference;
        $pgtoBoleto = PgtoBoleto::where('payment', $pagamento->id)->first();
        $pgtoCartao = PgtoCartao::where('payment', $pagamento->id)->first();

        switch ($pagamento->billingType) {
            case 'BOLETO':
                $pgtoBoleto->customer = $pagamento->customer;
                $pgtoBoleto->payment = $pagamento->id; 
                $pgtoBoleto->billingType = $pagamento->billingType;
                $pgtoBoleto->dueDate = $pagamento->dueDate;
                $pgtoBoleto->value = $pagamento->value;
                $pgtoBoleto->description = $pagamento->description;
                $pgtoBoleto->externalReference = $pagamento->externalReference;
                $pgtoBoleto->discountValue = $request['discountValue'];
                $pgtoBoleto->dueDateLimitDays = $request['dueDateLimitDays'];
                $pgtoBoleto->fineValue = $request['fineValue'];
                $pgtoBoleto->interestValue = $request['interestValue'];
                $pgtoBoleto->postalService = isset($request['postalService']) ? (bool)$request['postalService'] : false;
                break;
            case 'CREDIT_CARD':
                $pgtoCartao->customer = $pagamento->customer;
                $pgtoCartao->payment = $pagamento->id; 
                $pgtoCartao->billingType = $pagamento->billingType;
                $pgtoCartao->dueDate = $pagamento->dueDate;
                $pgtoCartao->value = $pagamento->value;
                $pgtoCartao->description = $pagamento->description;
                $pgtoCartao->externalReference = $pagamento->externalReference;
                $pgtoCartao->creditCardHolderName = $request['creditCardHolderName'];
                $pgtoCartao->creditCardNumber = $request['creditCardNumber'];
                $pgtoCartao->creditCardExpiryMonth = $request['creditCardExpiryMonth'];
                $pgtoCartao->creditCardExpiryYear = $request['creditCardExpiryYear'];
                $pgtoCartao->creditCardCcv = $request['creditCardCcv'];
                // $pgtoCartao->creditCardToken = $request['creditCardToken'];
                $pgtoCartao->creditCardHolderEmail = $request['creditCardHolderEmail'];
                $pgtoCartao->creditCardHolderCpfCnpj = $request['creditCardHolderCpfCnpj'];
                $pgtoCartao->creditCardHolderPostalCode = $request['creditCardHolderPostalCode'];
                $pgtoCartao->creditCardHolderAddressNumber = $request['creditCardHolderAddressNumber'];
                $pgtoCartao->creditCardHolderAddressComplement = $request['creditCardHolderAddressComplement'];
                $pgtoCartao->creditCardHolderPhone = $request['creditCardHolderPhone'];
                $pgtoCartao->creditCardHolderMobilePhone = $request['creditCardHolderMobilePhone'];

                $creditCard = [
                    'holderName' => $request['creditCardHolderName'],
                    'number' => $request['creditCardNumber'],
                    'expiryMonth' => $request['creditCardExpiryMonth'],
                    'expiryYear' => $request['creditCardExpiryYear'],
                    'ccv' => $request['creditCardCcv'],
                ];
                $pagamentoRequest['creditCard'] = $creditCard;  
                
                $creditCardHolderInfo = [
                    'name' => $request['creditCardHolderName'],
                    'email' => $request['creditCardHolderEmail'],
                    'cpfCnpj' => $request['creditCardHolderCpfCnpj'],
                    'postalCode' => $request['creditCardHolderPostalCode'],
                    'addressNumber' => $request['creditCardHolderAddressNumber'],
                    'addressComplement' => $request['creditCardHolderAddressComplement'],
                    'phone' => $request['creditCardHolderPhone'],
                    'mobilePhone' => $request['creditCardHolderMobilePhone'],
                ];
                $pagamentoRequest['creditCardHolderInfo'] = $creditCardHolderInfo;
                break;
        }
        
        try {
            $asaasAdapter = new AsaasAdapter();
            $response = $asaasAdapter->sendRequest('POST', 'payments/'.$pagamento->asaas_id, $pagamentoRequest);
            $response = json_decode($response);
            switch ($pagamento->billingType){
                case 'BOLETO':
                    $pgtoBoleto->save();
                    $pgtoCartao = PgtoCartao::where('payment', $pagamento->id)->first();
                    if (isset($pgtoCartao)) {
                        $pgtoCartao->delete();
                    }
                    break;
                case 'CREDIT_CARD':
                    $pgtoCartao->save();
                    $pgtoBoleto = PgtoBoleto::where('payment', $pagamento->id)->first();
                    if (isset($pgtoBoleto)) {
                        $pgtoBoleto->delete();
                    }
                    break;
            }
            $pagamentoUpdate->save();
    
            return redirect()->route('pagamentos.index')
                ->with('success', 'Pagamento atualizado com sucesso.');
        } catch (\Throwable $th) {
            $found = json_decode(substr($th->getMessage(), strpos($th->getMessage(),'response')+strlen('response')+1));
            return redirect()->route('pagamentos.index')->with('errorMessage', $found->errors[0]->description ?? 'Erro ao editar pagamento.');
        }
    }

    public function Executar(Pagamento $pagamento)
    {
        try { 
            $pagamentoRequest = [];
            if ($pagamento->billingType == 'CREDIT_CARD') {

                $pgtoCartao = PgtoCartao::where('payment', $pagamento->id)->first();
                
                $creditCard = [
                    'holderName' => $pgtoCartao->creditCardHolderName,
                    'number' => $pgtoCartao->creditCardNumber,
                    'expiryMonth' => $pgtoCartao->creditCardExpiryMonth,
                    'expiryYear' => $pgtoCartao->creditCardExpiryYear,
                    'ccv' => $pgtoCartao->creditCardCcv,
                ];
                $pagamentoRequest['creditCard'] = $creditCard;  
                
                $creditCardHolderInfo = [
                    'name' => $pgtoCartao->creditCardHolderName,
                    'email' => $pgtoCartao->creditCardHolderEmail,
                    'cpfCnpj' => $pgtoCartao->creditCardHolderCpfCnpj,
                    'postalCode' => $pgtoCartao->creditCardHolderPostalCode,
                    'addressNumber' => $pgtoCartao->creditCardHolderAddressNumber,
                    'addressComplement' => $pgtoCartao->creditCardHolderAddressComplement,
                    'phone' => $pgtoCartao->creditCardHolderPhone,
                    'mobilePhone' => $pgtoCartao->creditCardHolderMobilePhone,
                ];
                $pagamentoRequest['creditCardHolderInfo'] = $creditCardHolderInfo;
            }

            $asaasAdapter = new AsaasAdapter();
            $response = $asaasAdapter->sendRequest('POST', 'payments/'.$pagamento->asaas_id.'/payWithCreditCard', $pagamentoRequest);
            $response = json_decode($response);
            return redirect()->route('pagamentos.index')->with('success', 'Pagamento excluído com sucesso.');
            
        } catch (\Throwable $th) {
            $found = json_decode(substr($th->getMessage(), strpos($th->getMessage(),'response')+strlen('response')+1));
            return redirect()->route('pagamentos.index')->with('errorMessage', $found->errors[0]->description ?? 'Erro ao excluir pagamento.');
        }
    }

    public function destroy(Pagamento $pagamento)
    {
        $response = null;
        try {
            $asaasAdapter = new AsaasAdapter();
            $response = $asaasAdapter->sendRequest('DELETE', 'payments/'.$pagamento->asaas_id);
            switch ($pagamento->billingType) {
                case 'BOLETO':
                    $pgtoBoleto = PgtoBoleto::where('payment', $pagamento->id)->first();
                    $pgtoBoleto->delete();
                    break;
                case 'CREDIT_CARD':
                    $pgtoCartao = PgtoCartao::where('payment', $pagamento->id)->first();
                    $pgtoCartao->delete();
                    break;
            }
            $pagamento->delete();

            return redirect()->route('pagamentos.index')->with('errorMessage', 'Pagamento excluído com sucesso.');
            
        } catch (\Throwable $th) {
            $found = json_decode(substr($th->getMessage(), strpos($th->getMessage(),'response')+strlen('response')+1));
            return redirect()->route('pagamentos.index')->with('errorMessage', $found->errors[0]->description ?? 'Erro ao excluir pagamento.');
        }
        
    }
}
