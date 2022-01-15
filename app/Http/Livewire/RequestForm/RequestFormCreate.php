<?php

namespace App\Http\Livewire\RequestForm;
use Livewire\Component;
use App\Models\RequestForms\RequestForm;
use App\Models\RequestForms\RequestFormFile;
use App\Models\RequestForms\ItemRequestForm;
use App\Models\RequestForms\Passenger;
use App\Models\RequestForms\EventRequestForm;
use App\Models\Parameters\UnitOfMeasurement;
use App\Models\Parameters\PurchaseMechanism;
use App\User;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;

class RequestFormCreate extends Component
{
    use WithFileUploads;

    public $article, $unitOfMeasurement, $technicalSpecifications, $quantity, $typeOfCurrency, $articleFile,
            $unitValue, $taxes, $fileItem, $totalValue, $lstUnitOfMeasurement, $title, $edit, $key;

    public $name, $contractManagerId, $superiorChief, $purchaseMechanism, $messagePM,
            $program, $fileRequests = [], $justify, $totalDocument;

    public $items, $lstBudgetItem, $requestForm, $editRF, $deletedItems, $idRF, $savedFiles;
    public $budget_item_id, $lstPurchaseMechanism;

    public $passengers, $deletedPassengers;

    public $searchedUser, $isRFItems;

    protected $listeners = ['savedPassengers', 'savedItems', 'deletedItems', 'deletedPassengers'];

    protected $rules = [
        'unitValue'           =>  'required|numeric|min:1',
        'quantity'            =>  'required|numeric|min:0.1',
        'article'             =>  'required',
        'unitOfMeasurement'   =>  'required',
        'taxes'               =>  'required',
        'typeOfCurrency'      =>  'required'
    ];

    protected $messages = [
        'unitValue.required'          => 'Valor Unitario no puede estar vacio.',
        'unitValue.numeric'           => 'Valor Unitario debe ser numérico.',
        'unitValue.min'               => 'Valor Unitario debe ser mayor o igual a 1.',
        'quantity.required'           => 'Cantidad no puede estar vacio.',
        'quantity.numeric'            => 'Cantidad debe ser numérico.',
        'quantity.min'                => 'Cantidad debe ser mayor o igual a 0.1.',
        'article.required'            => 'Debe ingresar un Artículo.',
        'unitOfMeasurement.required'  => 'Debe seleccionar una Unidad de Medida',
        'taxes.required'              => 'Debe seleccionar un Tipo de Impuesto.',
        'typeOfCurrency.required'     => 'Debe seleccionar un Tipo de Moneda.',
    ];

    public function mount($requestForm){
      $this->isRFItems = request()->route()->getName() == 'request_forms.items.create' || ($requestForm && $requestForm->type_form == 'Bienes y/o Servicios');
      $this->purchaseMechanism      = "";
      $this->totalDocument          = 0;
      $this->items                  = array();
      $this->passengers             = array();
      $this->deletedItems           = array();
      $this->title                  = "Agregar Item";
      $this->edit                   = false;
      $this->editRF                 = false;
      $this->lstUnitOfMeasurement   = UnitOfMeasurement::all();
      $this->lstPurchaseMechanism   = PurchaseMechanism::all();
      if(!is_null($requestForm)){
        $this->requestForm = $requestForm;
        $this->setRequestForm();
      }
    }

    public function savedPassengers($passengers)
    {
      $this->passengers = $passengers;
    }

    public function savedItems($items)
    {
      $this->items = $items;
    }

    public function deletedItems($items)
    {
      $this->deletedItems = $items;
    }

    public function deletedPassengers($items)
    {
      $this->deletedPassengers = $items;
    }

    private function setRequestForm(){
      $this->name               =   $this->requestForm->name;
      $this->contractManagerId  =   $this->requestForm->contract_manager_id;
      $this->superiorChief      =   $this->requestForm->superior_chief;
      $this->program            =   $this->requestForm->program;
      $this->justify            =   $this->requestForm->justification;
      $this->purchaseMechanism  =   $this->requestForm->purchase_mechanism_id;
      $this->typeOfCurrency     =   $this->requestForm->type_of_currency;
      $this->estimated_expense  =   $this->requestForm->estimated_expense;
      $this->editRF             =   true;
      $this->idRF               =   $this->requestForm->id;
      $this->savedFiles         =   $this->requestForm->requestFormFiles;
      if($this->isRFItems)
        foreach($this->requestForm->itemRequestForms as $item)
          $this->setItems($item);
      else
        foreach($this->requestForm->passengers as $passenger)
          $this->setPassengers($passenger);
    }

    private function setItems($item){
      $this->items[]=[
            'id'                       => $item->id,
            'article'                  => $item->article,
            'unitOfMeasurement'        => $item->unit_of_measurement,
            'technicalSpecifications'  => $item->specification,
            'quantity'                 => $item->quantity,
            'unitValue'                => $item->unit_value,
            'taxes'                    => $item->tax,
            'totalValue'               => $item->expense,
      ];
    }

    private function setPassengers($passenger)
    {
      $this->passengers[]=[
            'id'                =>  $passenger->id,
            'passenger_type'    =>  $passenger->passenger_type,
            'run'               =>  $passenger->run,
            'dv'                =>  $passenger->dv,
            'name'              =>  $passenger->name,
            'fathers_family'    =>  $passenger->fathers_family,
            'mothers_family'    =>  $passenger->mothers_family,
            'birthday'          =>  $passenger->birthday,
            'phone_number'      =>  $passenger->phone_number,
            'email'             =>  $passenger->email,
            'round_trip'        =>  $passenger->round_trip,
            'origin'            =>  $passenger->origin,
            'destination'       =>  $passenger->destination,
            'departure_date'    =>  $passenger->departure_date,
            'return_date'       =>  $passenger->return_date,
            'baggage'           =>  $passenger->baggage,
            'unitValue'         =>  $passenger->unit_value
      ];
    }

    // public function deleteRequestService($key){
    //   if($this->editRF && array_key_exists('id',$this->items[$key]))
    //     $this->deletedItems[]=$this->items[$key]['id'];
    //   unset($this->items[$key]);
    //   $this->totalForm();
    //   $this->cancelRequestService();
    // }

    // public function editRequestService($key){
    //   $this->resetErrorBag();
    //   $this->title                    = "Editar Item Nro ". ($key+1);
    //   $this->edit                     = true;
    //   $this->article                  = $this->items[$key]['article'];
    //   $this->unitOfMeasurement        = $this->items[$key]['unitOfMeasurement'];
    //   $this->technicalSpecifications  = $this->items[$key]['technicalSpecifications'];
    //   $this->quantity                 = $this->items[$key]['quantity'];
    //   $this->unitValue                = $this->items[$key]['unitValue'];
    //   $this->taxes                    = $this->items[$key]['taxes'];
    //   //$this->budget_item_id           = $this->items[$key]['budget_item_id'];
    //   $this->key                      = $key;
    // }

    // public function updateRequestService(){
    //   $this->validate();
    //   $this->edit                                         = false;
    //   $this->items[$this->key]['article']                 = $this->article;
    //   $this->items[$this->key]['unitOfMeasurement']       = $this->unitOfMeasurement;
    //   $this->items[$this->key]['technicalSpecifications'] = $this->technicalSpecifications;
    //   $this->items[$this->key]['quantity']                = $this->quantity;
    //   $this->items[$this->key]['unitValue']               = $this->unitValue;
    //   $this->items[$this->key]['taxes']                   = $this->taxes;
    //   //$this->items[$this->key]['budget_item_id']          = $this->budget_item_id;
    //   $this->items[$this->key]['totalValue']              = $this->quantity * $this->unitValue;
    //   $this->totalForm();
    //   $this->cancelRequestService();
    // }

    // public function addRequestService(){
    //   $this->validate();
    //   $this->items[]=[
    //         'id'                       => null,
    //         'article'                  => $this->article,
    //         'unitOfMeasurement'        => $this->unitOfMeasurement,
    //         'technicalSpecifications'  => $this->technicalSpecifications,
    //         'quantity'                 => $this->quantity,
    //         'unitValue'                => $this->unitValue,
    //         'taxes'                    => $this->taxes,
    //         //'budget_item_id'           => $this->budget_item_id,
    //         'totalValue'               => $this->quantity * $this->unitValue,
    //         'typeOfCurrency'           => $this->typeOfCurrency,
    //         'articleFile'              => $this->articleFile,
    //   ];
    //   // dd($this->items);
    //   $this->totalForm();
    //   $this->cancelRequestService();
    // }

    // public function cancelRequestService(){
    //   $this->title = "Agregar Item";
    //   $this->edit  = false;
    //   $this->resetErrorBag();
    //   $this->article=$this->technicalSpecifications=$this->quantity=$this->unitValue="";
    //   $this->taxes=$this->budget_item_id=$this->unitOfMeasurement="";
    // }

   public function messageMechanism(){
      $this->messagePM = array();
      switch ($this->purchaseMechanism) {
          case 1: // MENORES A 3 UTM.
              $this->messagePM[] = "Especificaciones Técnicas";
              break;
          case 2: //Convenio Marco
              $this->messagePM[] = "Adjuntar ID Mercado Público";
              $this->messagePM[] = "Especificaciones Técnicas";
              $this->messagePM[] = "Decretos Presupuestarios, si procede.";
              $this->messagePM[] = "Convenios Mandatos, si procede.";
              $this->messagePM[] = "Resoluciones Aprobatorias de Programa Ministeriales, si procede.";
              break;
          case 3: // Trato Directo
              $this->messagePM[] = "Términos de Referencia y Especificaciones Técnicas.";
              $this->messagePM[] = "Decretos Presupuestarios, si procede.";
              $this->messagePM[] = "Convenios Mandatos, si procede.";
              $this->messagePM[] = "Resoluciones Aprobatorias de Programa Ministeriales, si procede.";
              $this->messagePM[] = "En el caso de realizar ADDENDUM O RENOVACIONES en los convenios
                                    vigentes, se debe adjuntar lo siguiente: Informe técnico que señale
                                    la justificación de la adquisición, una cotización por parte de la
                                    empresa que indique la compra asociada, y un correo de respaldo que
                                    la empresa acepta la nueva adquisición.";
              break;
          case 4: //LICITACIÓN PÚBLICA
              $this->messagePM[] = "Bases y  Especificaciones Técnicas.";
              $this->messagePM[] = "Decretos Presupuestarios, si procede.";
              $this->messagePM[] = "Convenios Mandatos, si procede.";
              $this->messagePM[] = "Resoluciones Aprobatorias de Programa Ministeriales, si procede.";
              $this->messagePM[] = "En el caso de realizar ADDENDUM O RENOVACIONES en los convenios
                                    vigentes, se debe adjuntar lo siguiente: Informe técnico que señale
                                    la justificación de la adquisición, una cotización por parte de la
                                    empresa que indique la compra asociada, y un correo de respaldo que
                                    la empresa acepta la nueva adquisición.";
              break;
          case "":
              break;
      }
    }

    public function totalForm(){
      $total = 0;
      foreach($this->isRFItems ? $this->items : $this->passengers as $item)
        $total += $item[$this->isRFItems ? 'totalValue' : 'unitValue'];

      return $total;
    }

    public function saveRequestForm(){
      // dd($this->items);
      $this->validate(
        [ 'name'                         =>  'required',
          'contractManagerId'            =>  'required',
          'purchaseMechanism'            =>  'required',
          'program'                      =>  'required',
          'justify'                      =>  'required',
          'fileRequests'                 =>  (!$this->editRF) ? 'required' : '',
          ($this->isRFItems ? 'items' : 'passengers') => 'required'
        ],
        [ 'name.required'                =>  'Debe ingresar un nombre a este formulario.',
          'contractManagerId.required'   =>  'Debe ingresar un Administrador de Contrato.',
          'purchaseMechanism.required'   =>  'Seleccione un Mecanismo de Compra.',
          'program.required'             =>  'Ingrese un Programa Asociado.',
          'fileRequests.required'        =>  'Debe agregar los archivos solicitados',
          'justify.required'             =>  'Campo Justificación de Adquisición es requerido',
          ($this->isRFItems ? 'items.required' : 'passengers.required') => ($this->isRFItems ? 'Debe agregar al menos un Item para Bien y/o Servicio' : 'Debe agregar al menos un Pasajero')
        ],
      );

      DB::transaction(function () {

        $req = RequestForm::updateOrCreate(
          [
            'id'                    =>  $this->idRF,
          ],
          [
            'contract_manager_id'   =>  $this->contractManagerId,
            'contract_manager_ou_id' => User::with('organizationalUnit')->find($this->contractManagerId)->organizationalUnit->id,
            'name'                  =>  $this->name,
            'superior_chief'        =>  $this->superiorChief,
            'justification'         =>  $this->justify,
            'type_form'             =>  $this->isRFItems ? 'Bienes y/o Servicios' : 'Pasajes Aéreos',
            'request_user_id'       =>  Auth()->user()->id,
            'request_user_ou_id'    =>  Auth()->user()->organizationalUnit->id,
            'estimated_expense'     =>  $this->totalForm(),
            'type_of_currency'      =>  $this->typeOfCurrency,
            'purchase_mechanism_id' =>  $this->purchaseMechanism,
            'program'               =>  $this->program,
            'status'                =>  'pending'
        ]);

        if($this->isRFItems){
          // save items
          foreach($this->items as $item){
            ItemRequestForm::updateOrCreate(
              [
                'id'                    =>      $item['id'],
              ],
              [
                'request_form_id'       =>      $req->id,
                'article'               =>      $item['article'],
                'unit_of_measurement'   =>      $item['unitOfMeasurement'],
                'specification'         =>      $item['technicalSpecifications'],
                'quantity'              =>      $item['quantity'],
                'unit_value'            =>      $item['unitValue'],
                'tax'                   =>      $item['taxes'],
                'expense'               =>      $item['totalValue'],
                // 'article_file'          =>      $item['articleFile'] ? $item['articleFile']->storeAs('/ionline/request_forms_dev/item_files/', $file_name.'.'.pathinfo($item['articleFile'], PATHINFO_EXTENSION), 'gcs') : null
            ]);
          }
        } else {
          foreach($this->passengers as $passenger){
            // save passengers
            Passenger::updateOrCreate(
              [
                'id'                =>  $passenger['id'],
              ],
              [
                'user_id'           =>  Auth()->user()->id,
                'run'               =>  $passenger['run'],
                'dv'                =>  $passenger['dv'],
                'name'              =>  $passenger['name'],
                'fathers_family'    =>  $passenger['fathers_family'],
                'mothers_family'    =>  $passenger['mothers_family'],
                'birthday'          =>  $passenger['birthday'],
                'phone_number'      =>  $passenger['phone_number'],
                'email'             =>  $passenger['email'],
                'round_trip'        =>  $passenger['round_trip'],
                'origin'            =>  $passenger['origin'],
                'destination'       =>  $passenger['destination'],
                'departure_date'    =>  $passenger['departure_date'],
                'return_date'       =>  $passenger['return_date'],
                'baggage'           =>  $passenger['baggage'],
                'unit_value'        =>  $passenger['unitValue'],
                'request_form_id'   =>  $req->id
              ]);
          }
        }

        if($this->editRF){
          $this->isRFItems ? ItemRequestForm::destroy($this->deletedItems) : Passenger::destroy($this->deletedPassengers);
          session()->flash('info', 'Formulario de requrimiento N° '.$req->id.' fue editado con exito.');
        }
        else{
          EventRequestform::createLeadershipEvent($req);
          EventRequestform::createPreFinanceEvent($req);
          EventRequestform::createFinanceEvent($req);
          EventRequestform::createSupplyEvent($req);
          session()->flash('info', 'Formulario de requrimiento N° '.$req->id.' fue creado con exito.');
        }

        // Se guarda los archivos del form req cuando ya todo lo anteior se guardó exitosamente
        foreach($this->fileRequests as $nFiles => $fileRequest){
          $reqFile = new RequestFormFile();
          if(env('APP_ENV') == 'local' || env('APP_ENV') == 'testing'){
              $now = Carbon::now()->format('Y_m_d_H_i_s');
              $file_name = $now.'_req_file_'.$nFiles;
              $reqFile->name = $fileRequest->getClientOriginalName();
              $reqFile->file = $fileRequest->storeAs('/ionline/request_forms_dev/request_files/', $file_name.'.'.$fileRequest->extension(), 'gcs');
              $reqFile->request_form_id = $req->id;
              $reqFile->user_id = Auth()->user()->id;
              $reqFile->save();
          }
      }

      });

      return redirect()->to('/request_forms/my_forms');
    }

    public function btnCancelRequestForm(){
      return redirect()->to('/request_forms/my_forms');
    }

    // private function saveItem($item, $id){
    //     // dd($item['articleFile']);
    //     // if($item['articleFile']) $item['articleFile'] = new TemporaryUploadedFile($item['articleFile'], config('filesystems.default'));
    //     $now = Carbon::now()->format('Y_m_d_H_i_s');
    //     $file_name = $now.'item_file_'.$id;
    //     ItemRequestForm::updateOrCreate(
    //       [
    //         'id'                    =>      $item['id'],
    //       ],
    //       [
    //         'request_form_id'       =>      $id,
    //         'article'               =>      $item['article'],
    //         'unit_of_measurement'   =>      $item['unitOfMeasurement'],
    //         'specification'         =>      $item['technicalSpecifications'],
    //         'quantity'              =>      $item['quantity'],
    //         'unit_value'            =>      $item['unitValue'],
    //         'tax'                   =>      $item['taxes'],
    //         'expense'               =>      $item['totalValue'],
    //         // 'article_file'          =>      $item['articleFile'] ? $item['articleFile']->storeAs('/ionline/request_forms_dev/item_files/', $file_name.'.'.pathinfo($item['articleFile'], PATHINFO_EXTENSION), 'gcs') : null
    //     ]);
    //   return;
    // }

    // private function savePassenger($passenger, $id){
    //     $now = Carbon::now()->format('Y_m_d_H_i_s');
    //     $file_name = $now.'art_file_'.$id;
    //     $req = Passenger::updateOrCreate(
    //         [
    //           'id'                =>  $passenger['id'],
    //         ],
    //         [
    //           'user_id'           =>  Auth()->user()->id,
    //           'run'               =>  $passenger['run'],
    //           'dv'                =>  $passenger['dv'],
    //           'name'              =>  $passenger['name'],
    //           'fathers_family'    =>  $passenger['fathers_family'],
    //           'mothers_family'    =>  $passenger['mothers_family'],
    //           'birthday'          =>  $passenger['birthday'],
    //           'phone_number'      =>  $passenger['phone_number'],
    //           'email'             =>  $passenger['email'],
    //           'round_trip'        =>  $passenger['round_trip'],
    //           'origin'            =>  $passenger['origin'],
    //           'destination'       =>  $passenger['destination'],
    //           'departure_date'    =>  $passenger['departure_date'],
    //           'return_date'       =>  $passenger['return_date'],
    //           'baggage'           =>  $passenger['baggage'],
    //           'unit_value'        =>  $passenger['unitValue'],
    //           'request_form_id'   =>  $id
    //         ]);
    //   return;
    // }

    public function destroyFile($id)
    {
      $requestFormFile = RequestFormFile::find($id);
      Storage::delete($requestFormFile->file);
      $requestFormFile->delete();

      $this->savedFiles = RequestFormFile::where('request_form_id', $this->requestForm->id)->get();
    }

    public function render(){
        $this->messageMechanism();
        $users = User::where('organizational_unit_id', Auth::user()->organizational_unit_id)->orderBy('name', 'ASC')->get();
        return view('livewire.request-form.request-form-create', compact('users'));
    }

  //   public function searchedUser(User $user){
  //     $this->searchedUser = $user;
  //     $this->contractManagerId = $user->id;
  // }
}
