    <div class="panel">
        <div class="panel-heading" id="process-order-id" data-idorder='{*$orderInfo['id_order']*}'>
            Procesar cantidades en pedidos
        </div>
        <form method="post" action="" id="process-quantity-form" class="form-horizontal clearfix">
            <table class="table orders filter row_hover">
                <thead>
                    <tr class="nodrag nodrop">
                      <th class="fixed-width-xs center" style="width: 25%"><span class="title_box">Nombre del producto</span></th>
                      <th class="fixed-width-xs center" style="width: 25%"><span class="title_box">Cantidad solicitada</span></th>
                      <th class="fixed-width-xs center" style="width: 25%"><span class="title_box">Buscar EAN / UPC</span></th>
                      <th class="fixed-width-xs center" style="width: 25%"><span class="title_box">Cantidad suministrada</span></th>
                    </tr>            
                </thead>
                <tr style="height: 25px; background-color: white; "></tr>
                {*assign var=tabindex value=1*}
                {*foreach $orderItems as $item*}
                <tr>
                    <td class="form-group" style="width: 25%">
                        <p><input type="text" name="p_name" id="{*$item['product_upc']*}-{*$item['product_ean13']*}" data-tabindex="{*$tabindex*}" value="{*$item['product_name']*}" /></p>
                    </td>
                    <td class="form-group" style="width: 25%">
                        <p><input type="text" name="p_quantity" value="{*$item['product_quantity']*}" /></p>
                    </td>
                    <td class="form-group" style="width: 25%">
                        <p><input type="text" name="p_search_{*$tabindex*}" id="p_search_{*$item['product_id']*}" tabindex="{*$tabindex*}" value="" /></p>
                    </td>
                    <td class="form-group" style="width: 25%">
                        <p><input type="text" name="p_verify_{*$tabindex*}" id="p_verify_{*$tabindex*}" data-min-qty="{*$item['minimal_quantity']*}" data-qty="{*$item['product_quantity']*}" value="0" /></p>
                    </td>
                </tr>
                    {*assign var=tabindex value=($tabindex+1)*}
                {*/foreach*}
                <tr>
                  <td class="form-group" style="width: 25%"></td>
                  <td class="form-group" style="width: 25%"></td>
                  <td class="form-group" style="width: 25%"></td>
                  <td class="form-group" style="width: 25%"><p class="submit"><input class="btn btn-default" style="width:300px;height:40px;" type="button" id="process-order-button" name="process-order-button" value="Procesar Orden" /></p></td>
                </tr>
            </table>
        </form>
                <div id='controller-data'
                     data-controller-path = '{*$cpath*}' 
                     data-controller-id = '{*$cid*}' 
                     style = 'visibility:hidden' 
                     >               
                </div>
    </div>