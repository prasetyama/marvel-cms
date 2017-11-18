// This function is called from the pop-up menus to transfer to
// a different page. Ignore if the value returned is a null string:
function goPage (newURL) {

    // if url is empty, skip the menu dividers and reset the menu selection to default
    if (newURL != "") {
    
        // if url is "-", it is this page -- reset the menu:
        if (newURL == "-" ) {
            resetMenu();            
        } 
        // else, send page to designated URL            
        else {  
          document.location.href = newURL;
        }
    }
}

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}

$(document).on('change', '#province', function(e) {
    if($(this).val() != "0"){
        $.ajax({
            type: "GET",
            url: '/bridge/city',
            dataType: 'json',
            data: {
                provinceID : $(this).val() 
            }, 
            beforeSend:function(){
                //$('.loader').show();
                //$('.btn-step1').hide();
            },
            success:function(result){
                //$('.loader').hide();
                //$('.btn-step1').show();
                $('#city').html('');
                $('#city').html('<option value="0">Pilih Kota</option>');
                $('#area').html('');
                $('#area').html('<option value="0">Pilih Area</option>');
                $.each(result.data, function(index, value) {
                    //console.log(value['city_id']);
                    $('#city').append('<option value="'+value['city_id']+'">'+value['city_name']+'</option>');
                });
            }
        });
    }
});

$(document).on('change', '#city', function(e) {
    if($(this).val() != "0"){
        $.ajax({
            type: "GET",
            url: '/bridge/area',
            dataType: 'json',
            data: {
                cityID : $(this).val() 
            }, 
            beforeSend:function(){
                //$('.loader').show();
                //$('.btn-step1').hide();
            },
            success:function(result){
                //$('.loader').hide();
                //$('.btn-step1').show();
                $('#area').html('');
                $('#area').html('<option value="0">Pilih Area</option>');
                $.each(result.data, function(index, value) {
                    $('#area').append('<option value="'+value['area_id']+'">'+value['area_name']+'</option>');
                });
            }
        });
    }
});

//=============================== NUMBER FORMAT ===============================//
function numberFormat(input){
    var nStr = input.value + '';
    nStr = nStr.replace( /\./g, "");
    var x = nStr.split( ',' );
    var x1 = x[0];
    var x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while ( rgx.test(x1) ){
        x1 = x1.replace( rgx, '$1' + '.' + '$2' );
    }
    input.value = x1 + x2;
}

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    
    if(charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
    
    return true;
}

function priceFormat(price){
    price += '';
    x = price.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
//=============================== NUMBER FORMAT ===============================//