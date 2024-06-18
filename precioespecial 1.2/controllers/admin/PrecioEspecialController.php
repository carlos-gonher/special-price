<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(_PS_MODULE_DIR_.'precioespecial/classes/precioespecial.php');

class PrecioEspecialController extends ModuleAdminController
{

    private $moduleModel;
    
    public function __construct(){
        
        $this->moduleModel = new PrecioEspecialModel();

        $this->module = 'precioespecial';
        $this->lang = false;
        $this->explicitSelect = false;
        $this->context = Context::getContext();
        $this->bootstrap = true;
        
        $this->table = 'special_price';
        $this->className = 'PrecioEspecialModel';
        $this->identifier = "id_special_price";
        $this->orderBy = 'id_special_price';
        $this->_orderWay = 'DESC';
        $this->actions = ['edit', 'delete'];

        $this->fields_list = array(
            'id_special_price' => array(
                'title' => $this->l('ID'),
                'callback' => 'setId',
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'name' => array(
                'title' => $this->l('name'),
                'search' => true,
                'orderby' => false,
                'align' => 'center',
            ),
            'id_store' => array(
                'title' => $this->l('Tienda'),
                'search' => false,
                'orderby' => false,
                'callback' => 'storeName',
                'align' => 'center',
            ),
            'code' => array(
                'title' => $this->l('code'),
                'search' => true,
                'orderby' => false,
                'align' => 'center',
            ),
            'additional_rule' => array(
                'title' => $this->l('Dependencia'),
                'search' => false,
                'orderby' => false,
                'callback' => 'dependency',
                'align' => 'center',
                'filter_key' => 'id_special_price'
            ),
            'active' => array(
                'title' => $this->l('Activo'),
                'search' => false,
                'width' => 'auto'
            ),
            'percentage' => array(
                'title' => $this->l('Porcentaje'),
                'search' => false,
                'width' => 'auto'
            ),
            'image_name' => array(
                'title' => $this->l('Texto de la imagen'),
                'search' => false,
                'width' => 'auto'
            ),            
            'date_from' => array(
                'title' => $this->l('Desde: '),
                'search' => false,
                'align' => 'center'
            ),
            'date_to' => array(
                'title' => $this->l('Hasta: '),
                'search' => false,
                'align' => 'center'
            ),
        );
        
        parent::__construct();
    }

    public function setId($id_special_price){
        return $this->moduleModel->setIdsp($id_special_price);
    }

    public function storeName($id_store){
        return $this->moduleModel->storesToString($id_store);
    }
    
    public function dependency($additional_rule){
        return $this->moduleModel->callDependency();
    }

    public function initContent(){
        parent::initContent();
    }
    
    public function display() {

        parent::display();
    }

    public function renderForm(){

        global $smarty;
        
        $_controller = $this->context->controller;
        $_controller->addJs('modules/precioespecial/views/js/precioespecial.js');
    
        $allShops = $this->moduleModel->getAllShops();
        $allManufacturers = $this->moduleModel->getAllManufacturers();
        $allCategories = $this->moduleModel->getAllCategories();
        $additionalPromos = $this->moduleModel->getAdditionalPromos();
        
        //Datos de los Multi Select al editar
        $selectedStores = array();
        $selectedManufacturers = array();
        $selectedCategories = array();
        if(!empty(Tools::getValue('id_special_price'))){
            $storeIdsFromDb = $this->moduleModel->getSerializedShops(Tools::getValue('id_special_price'));
            $ManufacturerIdsFromDb = $this->moduleModel->getSerializedManufacturers(Tools::getValue('id_special_price'));
            $CategoryIdsFromDb = $this->moduleModel->getSerializedCategories(Tools::getValue('id_special_price'));
            $selectedStores = @unserialize($storeIdsFromDb);
            $selectedManufacturers = @unserialize($ManufacturerIdsFromDb);
            $selectedCategories = @unserialize($CategoryIdsFromDb);
            $this->fields_value['id_store[]'] = $selectedStores;
            $this->fields_value['id_manufacturer[]'] = $selectedManufacturers;
            $this->fields_value['id_category[]'] = $selectedCategories;
        }
        
        $typeoptions = array(
          array(
            'id_option' => 'start',
            'name' => 'Selecciona descuento'
          ),
          array(
            'id_option' => 'porporcentaje',
            'name' => 'Solo Porcentaje'
          ),
          array(
            'id_option' => 'porfabricante',
            'name' => 'Por Fabricante'
          ),
          array(
            'id_option' => 'porupcs',
            'name' => 'Por UPCs'
          ),
          array(
            'id_option' => 'porcategoria',
            'name' => 'Por Categoría'
          ),
        );
        
        $checkoptions = array(
          array(
            'id_option' => '1',
            'name' => 'Sí'
          )
        );
        
        $this->toolbar_title = $this->l('Add a new feature');
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Feature'),
                'icon' => 'icon-info-sign'
            ),
            'input' => array(
                array(
                  'type' => 'select', 
                  'label' => $this->l('Tiendas'), 
                  'name' => 'id_store[]', 
                  'multiple' => true,
                  'required' => true, 
                  'options' => array(
                    'query' => $allShops,
                    'id' => 'id_option',
                    'name' => 'name'
                  )
                ),
                array(
                  'type' => 'select', 
                  'label' => $this->l('Tipo de descuento:'), 
                  'name' => 'code', 
                  'required' => true, 
                  'options' => array(
                    'query' => $typeoptions,
                    'id' => 'id_option',
                    'name' => 'name'
                  )
                ),
                array(
                  'type' => 'select', 
                  'label' => $this->l('Fabricantes'),
                  'name' => 'id_manufacturer[]', 
                  'multiple' => true,
                  'required' => false, 
                  'options' => array(
                    'query' => $allManufacturers,
                    'id' => 'id_option',
                    'name' => 'name'
                  )
                ),
                array(
                  'type' => 'select', 
                  'label' => $this->l('Categoría'),
                  'name' => 'id_category[]', 
                  'multiple' => true,
                  'required' => false, 
                  'options' => array(
                    'query' => $allCategories,
                    'id' => 'id_option',
                    'name' => 'name'
                  )
                ),
		array(
                    'type' => 'text',
                    'label' => $this->l('Nombre de la promo'),
                    'name' => 'name',
                    'lang' => false,
                    'required' => true,
                    'hint' => $this->l('Nombre de la promo:')
                ),
		array(
                    'type' => 'text',
                    'label' => $this->l('Porcentaje'),
                    'name' => 'percentage',
                    'required' => true,
                    'hint' => $this->l('Porcentaje').''
                ),
		array(
                    'type' => 'text',
                    'label' => $this->l('Texto Alt'),
                    'name' => 'show_text',
                    'required' => false,
                    'hint' => $this->l('Texto Alt').''
                ),
		array(
                    'type' => 'text',
                    'label' => $this->l('Texto de la Imagen'),
                    'name' => 'image_name',
                    'required' => true,
                    'hint' => $this->l('Texto de la Imagen').''
                ),
		array(
                    'type' => 'text',
                    'label' => $this->l('Lista de UPCs'),
                    'name' => 'upcs',
                    'required' => false,
                    'hint' => $this->l('Lista de UPCs')
                ),
                array(
                    'type' => 'switch',
                    'label' => 'Establecer fecha',
                    'name' => 'set_date',
                    'is_bool' => true,
                    'values' => array(
                            array(
                                    'id' => 'set_date_1',
                                    'value' => 1,
                                    'label' => $this->l('Yes')
                            ),
                            array(
                                    'id' => 'set_date_0',
                                    'value' => 0,
                                    'label' => $this->l('No'),
                            )                        
                    ),
                    'hint' => $this->l('Establecer fecha para la promo')
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Fecha inicio '),
                    'name' => 'date_from',
                    'hint' => $this->l('Fecha inicio ').'',
                    'required' => false
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Fecha final '),
                    'name' => 'date_to',
                    'hint' => $this->l('Fecha final ').'',
                    'required' => false
                ),
                array(
                    'type' => 'hidden',
                    'value' => 'default',
                    'name' => 'operation'
                ),
                array(
                    'type' => 'switch',
                    'label' => 'Establecer como Activo',
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => array(
                            array(
                                    'id' => 'active_1',
                                    'value' => 1,
                                    'label' => $this->l('Yes'),
                            ),
                            array(
                                    'id' => 'active_0',
                                    'value' => 0,
                                    'label' => $this->l('No')
                            )
                    ),
                    'hint' => $this->l('Al activar se aplicará esta promoción')
                ),
                array(
                    'type' => 'switch',
                    'label' => 'Establecer descuento adicional',
                    'name' => 'set_additional',
                    'is_bool' => true,
                    'values' => array(
                            array(
                                    'id' => 'set_additional_1',
                                    'value' => 1,
                                    'label' => $this->l('Yes'),
                            ),
                            array(
                                    'id' => 'set_additional_0',
                                    'value' => 0,
                                    'label' => $this->l('No')
                            )
                    ),
                    'hint' => $this->l('Activar un segundo descuento')
                ),                
                array(
                  'type' => 'select',
                  'label' => $this->l('Segundo descuento:'),
                  'desc' => $this->l('Aplicar segundo descuento'),
                  'name' => 'additional_rule',
                  'required' => false,
                  'options' => array(
                    'query' => $additionalPromos,
                    'id' => 'id_option',
                    'name' => 'name'
                  )
                ),
                
            )
        );

        $this->fields_form['submit'] = array(
            'title' => $this->l('Save'),
        );

    return parent::renderForm();
        
    }
    
    public function processAdd(){
        
        $data = $this->getFormData();
        $this->moduleModel->addSpecialPrice($data, Tools::getValue('active'));
        $this->moduleModel->createImage(Tools::getValue('percentage'), Tools::getValue('image_name'));
    }
    
    public function getFormData(){
    
        $formData = array();
        $todayTime = date('Y-m-d H:i:s');
        $type = Tools::getValue('code');
        $stores = $this->moduleModel->serializeArray(Tools::getValue('id_store'));
        $manufacturers = $this->moduleModel->serializeArray(Tools::getValue('id_manufacturer'));
        $categories = $this->moduleModel->serializeArray(Tools::getValue('id_category'));
        
        switch ($type){
            case 'porporcentaje':
                $formData['fields'] = 'name, code, id_store, percentage, show_text, image_name, active, date_add, date_upd';
                $formData['values'] = '\''.Tools::getValue('name').'\', \''.$type.'\', \''.$stores.'\', \''.Tools::getValue('percentage').'\', ';
                $formData['values'] .= '\''.Tools::getValue('show_text').'\', \''.Tools::getValue('image_name').'\', \''.Tools::getValue('active').'\', ';
                $formData['values'] .= '\''.$todayTime.'\', \''.$todayTime.'\'';
                
                if(Tools::getValue('set_date')){
                    $formData['fields'] .= ', set_date, date_from, date_to';
                    $formData['values'] .= ', \''.Tools::getValue('set_date').'\', \''.Tools::getValue('date_from').'\', \''.Tools::getValue('date_to').'\'';
                }
                
                if(Tools::getValue('set_additional')){
                    $formData['fields'] .= ', set_additional, additional_rule';
                    $formData['values'] .= ', \''.Tools::getValue('set_additional').'\', \''.Tools::getValue('additional_rule').'\'';
                }
                
                break;
            case 'porupcs':
                $formData['fields'] = 'name, code, id_store, percentage, show_text, image_name, active, upcs, date_add, date_upd';
                $formData['values'] = '\''.Tools::getValue('name').'\', \''.$type.'\', \''.$stores.'\', \''.Tools::getValue('percentage').'\', ';
                $formData['values'] .= '\''.Tools::getValue('show_text').'\', \''.Tools::getValue('image_name').'\', \''.Tools::getValue('active').'\', \''.Tools::getValue('upcs').'\', ';
                $formData['values'] .= '\''.$todayTime.'\', \''.$todayTime.'\'';
                
                if(Tools::getValue('set_date')){
                    $formData['fields'] .= ', set_date, date_from, date_to';
                    $formData['values'] .= ', \''.Tools::getValue('set_date').'\', \''.Tools::getValue('date_from').'\', \''.Tools::getValue('date_to').'\'';
                }
                
                break;
            case 'porfabricante':
                $formData['fields'] = 'name, code, id_store, percentage, show_text, image_name, active, id_manufacturer, date_add, date_upd';
                $formData['values'] = '\''.Tools::getValue('name').'\', \''.$type.'\', \''.$stores.'\', \''.Tools::getValue('percentage').'\', ';
                $formData['values'] .= '\''.Tools::getValue('show_text').'\', \''.Tools::getValue('image_name').'\', \''.Tools::getValue('active').'\', \''.$manufacturers.'\', ';
                $formData['values'] .= '\''.$todayTime.'\', \''.$todayTime.'\'';
                
                if(Tools::getValue('set_date')){
                    $formData['fields'] .= ', set_date, date_from, date_to';
                    $formData['values'] .= ', \''.Tools::getValue('set_date').'\', \''.Tools::getValue('date_from').'\', \''.Tools::getValue('date_to').'\'';
                }
                
                break;

            case 'porcategoria':
                $formData['fields'] = 'name, code, id_store, percentage, show_text, image_name, active, id_category, date_add, date_upd';
                $formData['values'] = '\''.Tools::getValue('name').'\', \''.$type.'\', \''.$stores.'\', \''.Tools::getValue('percentage').'\', ';
                $formData['values'] .= '\''.Tools::getValue('show_text').'\', \''.Tools::getValue('image_name').'\', \''.Tools::getValue('active').'\', \''.$categories.'\', ';
                $formData['values'] .= '\''.$todayTime.'\', \''.$todayTime.'\'';
                
                if(Tools::getValue('set_date')){
                    $formData['fields'] .= ', set_date, date_from, date_to';
                    $formData['values'] .= ', \''.Tools::getValue('set_date').'\', \''.Tools::getValue('date_from').'\', \''.Tools::getValue('date_to').'\'';
                }
                
                break;
                
            default :
                break;
        }
        
        return $formData;
    }

    /* Actualizar los datos */
    public function processUpdate(){
        
        $data = $this->getFormUpdateData();
        $this->moduleModel->updateImage(Tools::getValue('id_special_price'), Tools::getValue('percentage'), Tools::getValue('image_name'));
        $this->moduleModel->updateSpecialPrice($data, Tools::getValue('id_special_price'), Tools::getValue('active'));
    }
    
    public function getFormUpdateData(){

        $formData = '';
        $todayTime = date('Y-m-d H:i:s');
        $type = Tools::getValue('code');
        $stores = $this->moduleModel->serializeArray(Tools::getValue('id_store'));
        $manufacturers = $this->moduleModel->serializeArray(Tools::getValue('id_manufacturer'));
        $categories = $this->moduleModel->serializeArray(Tools::getValue('id_category'));
        
        switch ($type){
            case 'porporcentaje':
                $formData .= 'name = \''.Tools::getValue('name').'\', code = \''.$type.'\', id_store = \''.$stores.'\', upcs = \'\', id_manufacturer = \'\', id_category = \'\', ';
                $formData .= 'percentage = \''.Tools::getValue('percentage').'\', show_text = \''.Tools::getValue('show_text').'\', image_name = \''.Tools::getValue('image_name').'\', active = \''.Tools::getValue('active').'\', ';
                $formData .= 'date_upd = \''.$todayTime.'\'';

                if(Tools::getValue('set_date')){
                    $formData .= ', set_date = \''.Tools::getValue('set_date').'\', date_from = \''.Tools::getValue('date_from').'\', date_to = \''.Tools::getValue('date_to').'\' ';
                } else {
                    $formData .= ', set_date = \'0\', date_from = \'0000-00-00 00:00:00\', date_to = \'0000-00-00 00:00:00\' ';
                }

                if(Tools::getValue('set_additional')){
                    $formData .= ', set_additional = \''.Tools::getValue('set_additional').'\', additional_rule = \''.Tools::getValue('additional_rule').'\' ';
                } else {
                    $formData .= ', set_additional = \'0\', additional_rule = \'0\' ';
                }
                
                break;
            case 'porupcs':
                $formData .= 'name = \''.Tools::getValue('name').'\', code = \''.$type.'\', id_store = \''.$stores.'\', upcs = \''.Tools::getValue('upcs').'\', id_manufacturer = \'0\', id_category = \'\', ';
                $formData .= 'percentage = \''.Tools::getValue('percentage').'\', show_text = \''.Tools::getValue('show_text').'\', image_name = \''.Tools::getValue('image_name').'\', active = \''.Tools::getValue('active').'\', ';
                $formData .= 'date_upd = \''.$todayTime.'\'';

                if(Tools::getValue('set_date')){
                    $formData .= ', set_date = \''.Tools::getValue('set_date').'\', date_from = \''.Tools::getValue('date_from').'\', date_to = \''.Tools::getValue('date_to').'\' ';
                } else {
                    $formData .= ', set_date = \'0\', date_from = \'0000-00-00 00:00:00\', date_to = \'0000-00-00 00:00:00\' ';
                }
                
                break;
            case 'porfabricante':
                $formData .= 'name = \''.Tools::getValue('name').'\', code = \''.$type.'\', id_store = \''.$stores.'\', upcs = \'\', id_manufacturer = \''.$manufacturers.'\', id_category = \'\',  ';
                $formData .= 'percentage = \''.Tools::getValue('percentage').'\', show_text = \''.Tools::getValue('show_text').'\', image_name = \''.Tools::getValue('image_name').'\', active = \''.Tools::getValue('active').'\', ';
                $formData .= 'date_upd = \''.$todayTime.'\'';

                if(Tools::getValue('set_date')){
                    $formData .= ', set_date = \''.Tools::getValue('set_date').'\', date_from = \''.Tools::getValue('date_from').'\', date_to = \''.Tools::getValue('date_to').'\' ';
                } else {
                    $formData .= ', set_date = \'0\', date_from = \'0000-00-00 00:00:00\', date_to = \'0000-00-00 00:00:00\' ';
                }
                
                break;
                
            case 'porcategoria':
                $formData .= 'name = \''.Tools::getValue('name').'\', code = \''.$type.'\', id_store = \''.$stores.'\', upcs = \'\', id_manufacturer = \'\', id_category = \''.$categories.'\',  ';
                $formData .= 'percentage = \''.Tools::getValue('percentage').'\', show_text = \''.Tools::getValue('show_text').'\', image_name = \''.Tools::getValue('image_name').'\', active = \''.Tools::getValue('active').'\', ';
                $formData .= 'date_upd = \''.$todayTime.'\'';

                if(Tools::getValue('set_date')){
                    $formData .= ', set_date = \''.Tools::getValue('set_date').'\', date_from = \''.Tools::getValue('date_from').'\', date_to = \''.Tools::getValue('date_to').'\' ';
                } else {
                    $formData .= ', set_date = \'0\', date_from = \'0000-00-00 00:00:00\', date_to = \'0000-00-00 00:00:00\' ';
                }
                
                break;
                
            default :
                break;
        }
        
        return $formData;
    }
    
    public function processSave()
    {
        return parent::processSave();
    }
    
    public function processDelete()
    {
        $canDelete = $this->moduleModel->canDelete(Tools::getValue('id_special_price'));
        
        if($canDelete){
            $this->moduleModel->removeImageByIdElement(Tools::getValue('id_special_price'));
            parent::processDelete();
        }else{
            $this->errors[] = Tools::displayError('No se puede borrar por su Dependencia');
        }
    
    }
    
}
