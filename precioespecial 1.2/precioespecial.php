<?php

if (!defined('_PS_VERSION_'))
  exit;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class PrecioEspecial extends Module
{
 
  public function __construct()
  {
    $this->name = 'precioespecial';
    $this->tab = 'administration';
    $this->version = '1.0';
    $this->author = 'Vidafull Dev Team';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
    $this->bootstrap = true;
		$this->error = false;
		$this->valid = false;
    
 
    parent::__construct();
 
    $this->displayName = $this->l('Precio Especial');
    $this->description = $this->l('Precio Especial');
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
  }
  
    public function install(){
        if (!parent::install() || !$this->createTable()){
            return false;
        }
        return true;
    }
    
    public function uninstall(){
        if (!parent::uninstall() || !$this->deleteTable()){
            return false;
        }
        return true;
    }
    
    public function createTable(){

        $sql = "CREATE TABLE `special_price` ("
                . "`id_special_price` int(10) unsigned NOT NULL AUTO_INCREMENT,"
                . "`name` varchar(50) character set utf8 NOT NULL,  "
                . "`code` varchar(50) character set utf8 NOT NULL,"
                . "`description` varchar(255) character set utf8 DEFAULT NULL,"
                . "`id_store` varchar(255) NOT NULL DEFAULT '0', "
                . "`percentage` tinyint(10) unsigned NOT NULL DEFAULT '0',"
                . "`active` tinyint(10) unsigned NOT NULL DEFAULT '0',"
                . "`show_text` varchar(70) character set utf8 DEFAULT NULL,"
                . "`image_name` varchar(70) character set utf8 DEFAULT NULL,"
                . "`upcs` varchar(500) DEFAULT NULL,"
                . "`id_manufacturer` varchar(255) NOT NULL DEFAULT '0',"
                . "`id_category` varchar(255) NOT NULL DEFAULT '0',"
                . "`set_additional` tinyint(10) unsigned NOT NULL DEFAULT '0',"
                . "`additional_rule` varchar(20) NOT NULL DEFAULT '0',"
                . "`set_date` tinyint(10) unsigned NOT NULL DEFAULT '0',"
                . "`date_from` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',"
                . "`date_to` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',"
                . "`date_add` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',"
                . "`date_upd` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',"
                . "PRIMARY KEY  (`id_special_price`)"
                . ") ENGINE=InnoDB DEFAULT CHARSET=utf8";

        if(!Db::getInstance()->execute($sql)){
            return false;
        }
        return true;
    }
    
    public function deleteTable(){

        $sql = "DROP TABLE IF EXISTS `special_price`";

        if(!Db::getInstance()->execute($sql)){
            return false;
        }
        return true;
    }  
    
}
