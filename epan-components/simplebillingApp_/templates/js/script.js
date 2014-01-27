$.each({
  // ->calculateRow($rate_field,$quantity_field,$amount_field)
  //       ->calculateGrossTotal($amount_fields_array,$gross_total_field)
  //       ->calculateTotal($gross_total_field,$discount_field,$total_field)
  //       ->calculateTax($total_field,$tax_field,$tax_amount)
  //       ->calculateNetAmount($total_field,$tax_amount,$net_amount)

  calculateRow: function(rate_field,quantity_field,amount_field){
    $(amount_field).val(($(rate_field).val()*1) * ($(quantity_field).val()*1));
  },
  calculateGrossTotal: function (amount_fields_array,gross_total_field){
    var total =0;
    $.each(amount_fields_array, function(index, field) {
       /* iterate through array or object */
       total += ($(field).val() * 1);
    });
    $(gross_total_field).val(total);
  },
  calculateTotal: function(gross_total_field,discount_field,total_field){
    $(total_field).val(($(gross_total_field).val()*1) - ($(discount_field).val())*1);
  },
  calculateTax: function(total_field,tax_field,tax_amount){
    $(tax_amount).val($(total_field).val() * $(tax_field).val() / 100.00 );
  },
  calculateNetAmount: function(total_field,tax_amount,net_amount){
    $(net_amount).val(($(total_field).val()*1) + ($(tax_amount).val()*1));
  }



},$.univ._import);
