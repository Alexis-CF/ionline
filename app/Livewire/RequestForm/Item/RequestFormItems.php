<?php

namespace App\Livewire\RequestForm\Item;

use App\Models\Parameters\UnitOfMeasurement;
use App\Models\Unspsc\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\RequestForms\ItemRequestForm;

class RequestFormItems extends Component
{
    use WithFileUploads;

    public $article, $unitOfMeasurement, $technicalSpecifications, $quantity, $articleFile, $savedArticleFile, $editRF, $savedItems, $deletedItems, $iteration,
            $unitValue, $taxes, $fileItem, $totalValue, $lstUnitOfMeasurement, $title, $edit, $key, $items, $totalDocument, $withholding_tax, $precision_currency,
                $product_id, $product_name, $search_product;
    
    public $bootstrap, $form;

    protected $listeners = [
        'savedTypeOfCurrency',
        'myProductId'
    ];

    protected $rules = [
        'product_id'          =>  'required',
        'unitValue'           =>  'required|numeric|min:1',
        'quantity'            =>  'required|numeric|min:0.1',
        'unitOfMeasurement'   =>  'required',
        'taxes'               =>  'required',
        'technicalSpecifications' => 'required'
    ];

    protected $messages = [
        'product_id.required'         => 'Producto o servicio no puede estar vacio.',
        'unitValue.required'          => 'Valor Unitario no puede estar vacio.',
        'unitValue.numeric'           => 'Valor Unitario debe ser numérico.',
        'unitValue.min'               => 'Valor Unitario debe ser mayor o igual a 1.',
        'quantity.required'           => 'Cantidad no puede estar vacio.',
        'quantity.numeric'            => 'Cantidad debe ser numérico.',
        'quantity.min'                => 'Cantidad debe ser mayor o igual a 0.1.',
        'unitOfMeasurement.required'  => 'Debe seleccionar una Unidad de Medida',
        'taxes.required'              => 'Debe seleccionar un Tipo de Impuesto.',
        'technicalSpecifications.required' => 'Debe ingresar especificaciones técnicas al item.'
    ];

    protected function validationData()
    {
        $this->sanitize();
        return parent::validationData(); /* TODO: Change the autogenerated stub */
    }

    public function addItem()
    {
        $this->validate();
        $now = Carbon::now()->format('Y_m_d_H_i_s');
        $filename = $this->articleFile ? $this->articleFile->storeAs('/ionline/request_forms/item_files', $now.'_item_file.'.$this->articleFile->extension(), 'gcs') : null;
        $product = Product::find($this->product_id);

        $this->items[] = [
            'id'                       => null,
            'article'                  => $this->article,
            'product_id'               => $this->product_id,
            'product_name'             => $product->name,
            'unitOfMeasurement'        => $this->unitOfMeasurement,
            'technicalSpecifications'  => $this->technicalSpecifications,
            'quantity'                 => $this->quantity,
            'unitValue'                => $this->unitValue,
            'taxes'                    => $this->taxes,
            'totalValue'               => $this->quantity * $this->totalValueWithTaxes($this->unitValue),
            'articleFile'              => $filename
        ];

        $this->estimateExpense();
        $this->cleanItem();
        $this->emitUp('savedItems', $this->items);
        $this->dispatch('onClearSearch');
    }

    public function totalValueWithTaxes($value)
    {
        if($this->taxes == 'iva') return $value * 1.19;
        if($this->taxes == 'bh') return isset($this->withholding_tax[date('Y')]) ? round($value / (1 - $this->withholding_tax[date('Y')])) : round($value / (1 - end($this->withholding_tax)));
        return $value;
    }

    public function editItem($key)
    {
        $this->resetErrorBag();
        $this->title                    = "Editar Item Nro ". ($key+1);
        $this->edit                     = true;
        $this->article                  = $this->items[$key]['article'];
        $this->product_id               = $this->items[$key]['product_id'];
        $this->product_name             = $this->items[$key]['product_name'];
        $this->unitOfMeasurement        = $this->items[$key]['unitOfMeasurement'];
        $this->technicalSpecifications  = $this->items[$key]['technicalSpecifications'];
        $this->quantity                 = $this->items[$key]['quantity'];
        $this->unitValue                = $this->items[$key]['unitValue'];
        $this->taxes                    = $this->items[$key]['taxes'];
        $this->savedArticleFile         = $this->items[$key]['articleFile'];
        $this->key                      = $key;

        $this->dispatch('searchProduct', $this->items[$key]['product_name']);
        $this->dispatch('productId', $this->items[$key]['product_id']);
    }

    public function updateItem()
    {
        $product = Product::find($this->product_id);

        $this->validate();
        $this->edit                                         = false;
        $this->items[$this->key]['article']                 = $this->article;
        $this->items[$this->key]['product_id']              = $this->product_id;
        $this->items[$this->key]['product_name']            = $product->name;
        $this->items[$this->key]['unitOfMeasurement']       = $this->unitOfMeasurement;
        $this->items[$this->key]['technicalSpecifications'] = $this->technicalSpecifications;
        $this->items[$this->key]['quantity']                = $this->quantity;
        $this->items[$this->key]['unitValue']               = $this->unitValue;
        $this->items[$this->key]['taxes']                   = $this->taxes;
        $this->items[$this->key]['totalValue']              = $this->quantity * $this->totalValueWithTaxes($this->unitValue);

        if($this->articleFile)
        {
            $now = Carbon::now()->format('Y_m_d_H_i_s');
            $filename = $this->articleFile ? $this->articleFile->storeAs('/ionline/request_forms/item_files', $now.'_item_file.'.$this->articleFile->extension(), 'gcs') : null;
            $this->items[$this->key]['articleFile']         = $filename;
        }
        $this->estimateExpense();
        $this->cleanItem();
        $this->emitUp('savedItems', $this->items);
        $this->dispatch('onClearSearch');
    }

    public function deleteItem($key)
    {
        if($this->editRF && array_key_exists('id',$this->items[$key]))
        {
            $this->deletedItems[]=$this->items[$key]['id'];
            $this->emitUp('deletedItems', $this->deletedItems);
        }
        if($this->items[$key]['articleFile']) $this->deleteFile($key);
        unset($this->items[$key]);
        $this->estimateExpense();
        $this->cleanItem();
        $this->emitUp('savedItems', $this->items);
    }

    public function estimateExpense()
    {
        $this->totalDocument = 0;
        foreach($this->items as $item){
          $this->totalDocument = $this->totalDocument + $item['totalValue'];}
    }

    public function cleanItem()
    {
        $this->title = "Agregar Item";
        $this->edit  = false;
        $this->resetErrorBag();
        $this->article = $this->technicalSpecifications = $this->quantity = $this->unitValue = "";
        $this->taxes = $this->budget_item_id = $this->unitOfMeasurement = "";
        $this->articleFile = null;
        $this->savedArticleFile = null;
        $this->search_product = null;
        $this->iteration++;
    }

    public function mount($savedItems, $savedTypeOfCurrency, $purchasePlan = null)
    {
        $this->iteration = 0;
        $this->totalDocument          = 0;
        $this->lstUnitOfMeasurement   = UnitOfMeasurement::orderBy('name')->get();
        $this->items                  = array();
        $this->title                  = "Agregar Item";
        $this->edit                   = false;
        $this->editRF                 = false;
        // Porcentaje retención boleta de honorarios según el año vigente
        $this->withholding_tax        = [2021 => 0.115, 2022 => 0.1225, 2023 => 0.13, 2024 => 0.1375, 2025 => 0.145, 2026 => 0.1525, 2027 => 0.16, 2028 => 0.17];

        if(!is_null($purchasePlan))
        {
            $purchasePlan->load('purchasePlanItems.unspscProduct');
            $this->savedItems = $purchasePlan->purchasePlanItems;
            $this->setSavedItems();
        }

        if(!is_null($savedItems))
        {
            $this->editRF = true;
            $this->savedItems = $savedItems;
            $this->setSavedItems();
        }

        if(!is_null($savedTypeOfCurrency))
        {
            $this->precision_currency = $savedTypeOfCurrency == 'peso' ? 0 : 2;
        }

        $this->results = collect([]);
    }

    private function setSavedItems()
    {
        foreach($this->savedItems as $item)
        {
            $this->items[] = [
                'id'                       => $item->id,
                'article'                  => $item->article,
                'product_id'               => ($item->product_id) ? $item->product_id : $item->unspsc_product_id,
                'product_name'             => ($item->product_id) ? optional($item->product)->name : optional($item->unspscProduct)->name,
                'unitOfMeasurement'        => $item->unit_of_measurement,
                'technicalSpecifications'  => $item->specification,
                'quantity'                 => $item->quantity,
                'unitValue'                => $item->unit_value,
                'taxes'                    => $item->tax,
                'totalValue'               => $item->expense,
                'articleFile'              => $item->article_file
            ];
            $this->estimateExpense();
        }
    }

    public function render()
    {
        return view('livewire.request-form.item.request-form-items');
    }

    public function savedTypeOfCurrency($typeOfCurrency)
    {
        $this->precision_currency = $typeOfCurrency == 'peso' ? 0 : 2;
    }

    public function showFile($key)
    {
        return Storage::disk('gcs')->response($this->items[$key]['articleFile']);
    }

    public function deleteFile($key)
    {
        $fileDeleteRecord = ItemRequestForm::find($this->items[$key]['id']);

        Storage::disk('gcs')->delete($this->items[$key]['articleFile']);
        $this->items[$key]['articleFile'] = null;
        $this->articleFile = $this->savedArticleFile = null;
        $this->iteration++;

        /* NO SE ESTABA ACTUALIZANDO EL OBJETO */
        $fileDeleteRecord               = ItemRequestForm::find($this->items[$key]['id']);
        $fileDeleteRecord->article_file = null;
        $fileDeleteRecord->save();
        $this->cleanItem();
         /* *********************************** */
    }

    public function updatedSearchProduct()
    {
        $this->dispatch('searchProduct', $this->search_product);
    }

    public function myProductId($value)
    {
        $this->product_id = $value;
    }
}