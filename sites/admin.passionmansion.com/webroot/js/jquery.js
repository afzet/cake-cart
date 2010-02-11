

$(function() {
    $('.order').editable('/orders/update_order_status', {
         id        : 'data[Order][id]',
         name      : 'data[Order][order_status]',
         type      : 'select',
         height    : '12px',
         data      : "{'Pending':'Pending','On Hold':'On Hold','Processed':'Processed','Refunded':'Refunded'}",
         submit    : 'Go',
         tooltip   : 'Click to edit the Order Status'
    });
    $('.product_cost').editable('/products/ajax_update_cost', {
         id        : 'data[Product][id]',
         name      : 'data[Product][product_cost]',
         type      : 'text',
         width     : '50px',
         height    : '12px',
         submit    : 'Go',
         tooltip   : 'Click to edit the Product Cost'
    });
    $('.product_price').editable('/products/ajax_update_price', {
         id        : 'data[Product][id]',
         name      : 'data[Product][product_price]',
         width     : '50px',
         height    : '12px',
         submit    : 'Go',
         tooltip   : 'Click to edit the Product Price'
    });
    $('.product_name').editable('/products/ajax_update_name', {
         id        : 'data[Product][id]',
         name      : 'data[Product][product_name]',
         height    : '12px',
         type      : 'text',
         submit    : 'Go',
         tooltip   : 'Click to edit the Product Name'
    });
});