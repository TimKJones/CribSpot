<tr class = 'fl-cart-item'>
    <td>
        <span  class = 'address'><?php echo $fl->item->address;?></span>
    </td>
    <td>
        <span class = 'price'?><?php echo "$".number_format($fl->price,2);?></span>
    </td>
    <td class = 'actions'>
        <a href = '#' class = 'edit' id = '<?php echo $id;?>'><i class = 'icon-edit'></i></a>   
        <a href = '#' class = 'remove' id = '<?php echo $id;?>'><i class = 'icon-remove-circle'></i></a>
    </td>
</tr>