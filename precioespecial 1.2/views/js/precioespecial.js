$(document).ready(function(){ specialprice.INIT(); });

var specialprice = ( function($) {
    
    var codeInput; // Valor del campo Tipo de descuento
    
    var init = function(){
        
        codeInput = document.getElementById('code');
        
        listenEvents();
        setDefaultOnHidden();
        setDateOn();
        disableOnUpdate();
    }
    
    var listenEvents = function(){
        $("#code").bind('change', function() { processType(this); });
        $("#special_price_form_submit_btn").bind('click', function(event) { checkEmptyData(event); });
    }
    
    var disableOnUpdate = function(){
        processType(codeInput);
    }
    
    var checkEmptyData = function(event){
        event.preventDefault();
        
        //Hay que seleccionar al menos una tienda
        var stores = document.getElementById('id_store[]').value;
        
        //Hay que seleccionar un tipo de descuento
        var type = document.getElementById('code').value;
        
        //campos obligatorios vacios
        var name = document.getElementById('name').value;
        var percen = document.getElementById('percentage').value;
        var imgname = document.getElementById('image_name').value;
        
        //Fecha vacía en caso de establecer fecha
        var setDate = document.getElementById('set_date_on').checked;
        var dateFrom = document.getElementById('date_from').value;
        var dateTo = document.getElementById('date_to').value;
        
        if(stores == 0){
            alert('Debe seleccionar al menos una tienda');
            return false;
        }
        
        if(type == 'start'){
            alert('Debe seleccionar un tipo de descuento');
            return false;
        }
        
        if(!name || !percen || !imgname){
            alert('Hay campos obligatorios vacios');
            return false;
        }
        
        if(setDate){
            if(!dateFrom || !dateTo){
                alert('Debe establecer rango de fechas');
                return false;
            }
        }
        
        $("#special_price_form").submit();
    }
    
    var processType = function(sptype){
        
        var type = sptype.value;
        
        switch(type){
            case 'porporcentaje':
                changeHidden(type);
                porporcentajeOperation();
                break;
            case 'porupcs':
                changeHidden(type);
                porupcsOperation();
                break;
            case 'porfabricante':
                changeHidden(type);
                porfabricanteOperation();
                break;
            case 'porcategoria':
                changeHidden(type);
                porcategoriaOperation();
                break;
                
            default:
            break;
        }
    }
    
    var porporcentajeOperation = function(){
        
        //Disable
        $("[name^='id_manufacturer']").parent().parent().css("opacity", "0.3");
        $("[name^='id_category']").parent().parent().css("opacity", "0.3");
        $('#upcs').parent().parent().css("opacity", "0.3");
        
        //Enable
        $("[name='set_additional']").parent().parent().css("opacity", "1.0");
        $("[name='additional_rule']").parent().parent().css("opacity", "1.0");
        
    }
    
    var porupcsOperation = function(){
        
        //Disable
        $("[name^='id_manufacturer']").parent().parent().css("opacity", "0.3");
        $("[name^='id_category']").parent().parent().css("opacity", "0.3");
        $("[name^='id_category']").parent().parent().css("opacity", "0.3");
        $("[name='set_additional']").parent().parent().css("opacity", "0.0");
        $("[name='additional_rule']").parent().parent().css("opacity", "0.0");
        
        //Enable
        $('#upcs').parent().parent().css("opacity", "1.0");
        
        setAllShops();
    }
    
    var porfabricanteOperation = function(){
        
        //Disable
        $("#upcs").parent().parent().css("opacity", "0.3");
        $("[name^='id_category']").parent().parent().css("opacity", "0.3");
        $("[name='set_additional']").parent().parent().css("opacity", "0.0");
        $("[name='additional_rule']").parent().parent().css("opacity", "0.0");
        
        //Enable
        $("[name^='id_manufacturer']").parent().parent().css("opacity", "1.0");
        
        setAllShops();
    }
    
    var porcategoriaOperation = function(){
        
        //Disable
        $("#upcs").parent().parent().css("opacity", "0.4");
        $("[name^='id_manufacturer']").parent().parent().css("opacity", "0.4");
        $("[name='set_additional']").parent().parent().css("opacity", "0.0");
        $("[name='additional_rule']").parent().parent().css("opacity", "0.0");
        
        //Enable
        $("[name^='id_category']").parent().parent().css("opacity", "1.0");
    }
    
    var setAllShops = function(){
        $('#id_store').val('0');
    }
    
    var changeHidden = function(value){
        $('#operation').val(value);
    }
    var setDateOn = function(){
        $('#set_date_on').click();
    }
    var setDefaultOnHidden = function(){
        $('#operation').val('default');
    }
    
    var changeButton = function(){
        $('#special_price_form_submit_btn').prop('type','button');
    }
    
    return {INIT:init}
    
}(jQuery));
