<?php

class PrecioEspecialModel extends ObjectModel {

    public $name;
    public $code;
    public $description;
    public $id_store;
    public $percentage;
    public $active;
    public $show_text;
    public $image_name;
    public $upcs;
    public $id_manufacturer;
    public $set_additional;
    public $additional_rule;
    public $set_date;
    public $date_from;
    public $date_to;
    public $date_add;
    public $date_upd;
    
    protected $idsp; 
    protected $font_path;
    protected $image_path;
    protected $font_name = 'Roboto-Regular.ttf';
    
    public static $definition = array(
        'table' => 'special_price',
        'primary' => 'id_special_price',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'code' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'description' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_store' => array('type' => self::TYPE_STRING, 'validate' => 'isSerializedArray', 'size' => 70, 'required' => false),
            'percentage' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'active' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'show_text' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'image_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'upcs' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_manufacturer' => array('type' => self::TYPE_STRING, 'validate' => 'isSerializedArray', 'size' => 70, 'required' => false),
            'set_additional' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'additional_rule' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'set_date' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'date_from' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'date_to' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'date_add' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'date_upd' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
        ),
    );
    
    public function setIdsp($id){
        $this->idsp = $id; //Set id_special_price DB field to $this->idsp 
        return $this->idsp;
    }

    public function getAllShops(){
        
        $formated = array();
        $data = $this->getShopData();
        
        $i = 0;
        $formated[$i]['id_option'] = $i;
        $formated[$i]['name'] = 'Todas las tiendas';
        foreach ($data as $element) {
            $i++;
            $formated[$i]['id_option'] = $element['id_shop'];
            $formated[$i]['name'] = $element['name'];
        }
        return $formated;
    }
    
    public function getSerializedShops($id){
        $ids = Db::getInstance()->executeS('SELECT id_store FROM special_price WHERE id_special_price = '.$id);
        return $ids[0]['id_store'];
    }
    
    public function getSerializedManufacturers($id){
        $ids = Db::getInstance()->executeS('SELECT id_manufacturer FROM special_price WHERE id_special_price = '.$id);
        return $ids[0]['id_manufacturer'];
    }
    
    public function getSerializedCategories($id){
        $ids = Db::getInstance()->executeS('SELECT id_category FROM special_price WHERE id_special_price = '.$id);
        return $ids[0]['id_category'];
    }
    
    public function getShopData(){
        return Db::getInstance()->executeS('SELECT id_shop, name FROM shop');
    }
    
    public function getManufacturersData(){
        return Db::getInstance()->executeS('
            SELECT id_manufacturer, name 
            FROM manufacturer 
            ORDER BY name ASC');
    }
    
    public function getAllManufacturers(){
        
        $formated = array();
        $data = $this->getManufacturersData();
        
        $i = 0;
        $formated[$i]['id_option'] = $i;
        $formated[$i]['name'] = 'Selecciona el Fabricante';
        foreach ($data as $element) {
            $i++;
            $formated[$i]['id_option'] = $element['id_manufacturer'];
            $formated[$i]['name'] = $element['name'];
        }
        return $formated;
    }
    
    public function getCategoriesData(){
        return Db::getInstance()->executeS('
            SELECT DISTINCT 
            id_category,name 
            FROM '._DB_PREFIX_.'category_lang 
            ORDER BY name');
    }
    
    public function getAllCategories(){
        
        $formated = array();
        $data = $this->getCategoriesData();
        
        $i = 0;
        $formated[$i]['id_option'] = $i;
        $formated[$i]['name'] = 'Seleccionar Categorías';
        foreach ($data as $element) {
            $i++;
            $formated[$i]['id_option'] = $element['id_category'];
            $formated[$i]['name'] = $element['name'];
        }
        return $formated;
    }
    
    public function getAdditionalPromos(){
        $formated = array();
        $data = $this->getAdditionalPromosIds();
        
        $i = 0;
        $formated[$i]['id_option'] = $i;
        $formated[$i]['name'] = 'Descuento adicional';
        foreach ($data as $element) {
            $i++;
            $formated[$i]['id_option'] = $element['id_special_price'];
            $formated[$i]['name'] = $element['name'];
        }
        return $formated;
    }
    
    public function getAdditionalPromosIds(){
        return Db::getInstance()->executeS("
            SELECT id_special_price, name 
            FROM "._DB_PREFIX_."special_price 
            WHERE code IN ('porfabricante','porupcs','porcategoria') 
            AND active = 1");
    }

    public function addSpecialPrice($data, $active){

        $query = "INSERT INTO "._DB_PREFIX_."special_price (".$data['fields'].") VALUES (".$data['values'].")";
        $saveItem = Db::getInstance()->executeS($query);
        
        //if($active)
            //$this->changeUniqueActive(Db::getInstance()->Insert_ID());
    
        return $saveItem;
    }
    
    public function updateSpecialPrice($data, $id, $active){
        
        $query = "UPDATE "._DB_PREFIX_."special_price  SET ".$data." WHERE id_special_price = ".$id;
        $updateItem = Db::getInstance()->executeS($query);

        //if($active)
            //$this->changeUniqueActive($id);
        
        return $updateItem;
    }
    
    public function serializeArray($array){
        $serialized_array = serialize($array);
        return $serialized_array;
    }
    
    public function unserializeDbField($field){
        $data = unserialize($field);
        return $data;
    }

    public function storesToString($stores_serialized){
        
        $stores = '';
        $stores_array = $this->unserializeDbField($stores_serialized);
        foreach ($stores_array as $store) {
            $name = $this->getDbStoreName($store);
                $stores .= $name[0]['name'].'<br>';
        }
        return $stores;
    }
    
    public function callDependency(){
        
        $dependency = '';
        $additional = $this->dbAdditionalRule($this->idsp);
        $additional = $additional[0];

        if($additional['nueva_regla']){
            $dependency = 'Aplica: '.$additional['adicional'].' ('.$additional['nueva_regla'].')';
        } else {
            $dependency = $additional['dependencia'];
        }
        return $dependency;
    }
    
    public function canDelete($id_rule){
        
        $dependency = Db::getInstance()->getRow('
            SELECT id_special_price AS idsp, additional_rule AS adicional, 
            (SELECT id_special_price FROM special_price WHERE additional_rule = idsp) AS relacionado 
            FROM special_price WHERE id_special_price ='.$id_rule);
        
        (empty($dependency['adicional']) && empty($dependency['relacionado'])) ? $canDelete = true : $canDelete = false;
        
        return $canDelete;
    }

    protected function dbAdditionalRule($id_rule){
        
        return Db::getInstance()->ExecuteS('SELECT id_special_price, name, additional_rule AS nueva_regla, 
            IF(code="porporcentaje","Base","Dependiente") AS dependencia, 
            (SELECT name FROM special_price WHERE id_special_price = nueva_regla) AS adicional 
            FROM special_price WHERE id_special_price ='.$id_rule);
    }

    protected function getDbStoreName($id){
        return Db::getInstance()->ExecuteS('SELECT name FROM shop WHERE id_shop = '.$id);
    }

    protected function changeUniqueActive($id){
    
        $query = 'SELECT id_special_price FROM '._DB_PREFIX_.'special_price WHERE active = 1';
        $results = Db::getInstance()->ExecuteS($query);
        
        $updated = false;
        foreach ($results as $element) {
            $remove = 'UPDATE '._DB_PREFIX_.'special_price SET active = 0 WHERE id_special_price = '.$element['id_special_price'];
            $updated = Db::getInstance()->executeS($remove);
        }
        
        $this->setUniqueActive($id);
        
        return $updated;
    }
    
    protected function setUniqueActive($id){
        $setactive = 'UPDATE '._DB_PREFIX_.'special_price SET active = 1 WHERE id_special_price = '.$id;
        return Db::getInstance()->executeS($setactive);
    }

    public function getSpecialPriceById($id){
        
        $query = "SELECT * FROM "._DB_PREFIX_."special_price WHERE id_special_price = ".$id;
        $getItem = Db::getInstance()->executeS($query);
        return $getItem;
    }
    
    public function createImage($percentage, $image_name){
        
        $this->font_path = _PS_ROOT_DIR_.'/themes/vidafull/fonts/';
        $this->image_path = _PS_ROOT_DIR_.'/img/stage/';
        
        $image_name = trim($image_name);
        $percentage = trim($percentage);
        
        $cadena = $percentage.'% '.$image_name;
        $clean_name = str_replace(" ", "-",$image_name);
        $long = (7 * strlen($cadena));

        if(!empty($image_name) && !empty($percentage)){
            // Cargar la fuente
            $fuente = $this->font_path.$this->font_name;
            //Ruta y nombre de la imagen
            $imagen = $this->image_path.$percentage.'-'.$clean_name.'.png';

            // Crear imagen (ancho, alto)
            $im = imagecreatetruecolor($long, 25); //create Image
            imagealphablending($im, false);
            imagesavealpha($im, true);
            $color_fondo = imagecolorallocatealpha($im, 0, 0, 255, 127 ); 
            imagefill($im, 0, 0, $color_fondo);
            $color_texto = imagecolorallocate($im, 255,0,0);
            $px = (imagesx($im) - (7.5 * strlen($cadena))) / 2;
            imagettftext($im, 10, 0, 5, 17, $color_texto, $fuente, $cadena);
            imagepng($im,$imagen); 
            imagedestroy($im);
        }
    }
    
    public function removeImageByIdElement($id){
        $image_name = $this->buildImageName($id);
        if (is_readable($image_name)) {
            unlink($image_name);
        }
    }
    
    public function updateImage($id, $percentage, $image_name){

        $this->removeImageByIdElement($id);
        $this->createImage($percentage, $image_name);
    }
    
    protected function buildImageName($id){
        
        $this->image_path = _PS_ROOT_DIR_.'/img/stage/';
        $query = "SELECT percentage, image_name FROM "
                ._DB_PREFIX_."special_price WHERE id_special_price = ".$id;
        $getItem = Db::getInstance()->executeS($query);
        $clean_name = str_replace(" ", "-",$getItem[0]['image_name']);
        $imagen = $this->image_path.$getItem[0]['percentage'].'-'.$clean_name.'.png';
        
        return $imagen;
    }

}
