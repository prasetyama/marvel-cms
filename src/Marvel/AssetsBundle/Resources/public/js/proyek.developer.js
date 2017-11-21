
$('#file-5').fileinput({
    uploadUrl: '/upload/post?type=gallery&token='+$('#token_gallery').val(),
    showBrowse: false,
    uploadAsync: false,
    browseOnZoneClick: true,
    overwriteInitial: false,
    allowedFileExtensions: ['doc','docx','pdf','csv','xls','xlsx','jpg','jpeg','gif','png'],
    uploadIcon: '<i class="fa fa-upload"></i>',
    uploadClass: 'btn btn-primary',
    uploadLabel: 'Upload All',
    showRemove: false,
    otherActionButtons: '<a class="btn btn-sm btn-edit gallerySetPrimary" {dataKey}>Set Primary</a>',
    layoutTemplates: {
        footer: '<div class="file-thumbnail-footer" style ="height:94px">\n' +
                '   <div style="margin:5px 0">\n' +
                '       <input class="file-caption-input form-control input-sm text-center" value="{caption}" placeholder="Enter caption..." data-fileid="{TAG_ID}" data-initcaption="{TAG_VALUE}">\n' +
                '   </div>\n' +
                '   {actions}\n' +
                '</div>',
        fileIcon: '<i class="fa fa-file kv-caption-icon"></i> '
    },
    previewThumbTags: {
        '{TAG_VALUE}': '',
        '{TAG_ID}' : ''
    },
    allowedPreviewTypes: null,
    uploadExtraData: function () {
        var out = {};

        $(':input.file-caption-input:visible').each(function () {
            var input = $(this);
            var caption = input.val();

            if (input.data('fileid')) {
                // At this point this input belongs to a file that is already uploaded
                // Only send the caption, if it has an updated value
                if (input.data('initcaption') != caption) {
                    // only update the caption
                    out['caption_update_' + input.data('fileid')] = caption;
                }

            } else {
                // new file upload request

                var container = input.closest('.file-preview-frame');
                var index = container.data('fileindex');

                out['caption_' + index] = caption;
            }
        });

        return out;
    }
    
    }).on('fileuploaded', function(event, data, previewId, index) {

        //updateFileCaptions(data.response.updatedCaptions);

    }).on('filebatchuploadsuccess', function(event, data, previewId, index) {

        //updateFileCaptions(data.response.updatedCaptions);
    }).on("filebeforedelete", function() {

        return !window.confirm('Are you sure you want to delete this file?');

    });

$('#file-6').fileinput({
    uploadUrl: '/upload/post?type=denah&token='+$('#token_denah').val(),
    showBrowse: false,
    uploadAsync: false,
    browseOnZoneClick: true,
    overwriteInitial: false,
    allowedFileExtensions: ['doc','docx','pdf','csv','xls','xlsx','jpg','jpeg','gif','png'],
    uploadIcon: '<i class="fa fa-upload"></i>',
    uploadClass: 'btn btn-primary',
    uploadLabel: 'Upload All',
    showRemove: false,
    otherActionButtons: '<a class="btn btn-sm btn-edit denahSetPrimary" {dataKey}>Set Primary</a>',
    layoutTemplates: {
        footer: '<div class="file-thumbnail-footer" style ="height:94px">\n' +
                '   <div style="margin:5px 0">\n' +
                '       <input class="file-caption-input form-control input-sm text-center" value="{caption}" placeholder="Enter caption..." data-fileid="{TAG_ID}" data-initcaption="{TAG_VALUE}">\n' +
                '   </div>\n' +
                '   {actions}\n' +
                '</div>',
        fileIcon: '<i class="fa fa-file kv-caption-icon"></i> '
    },
    previewThumbTags: {
        '{TAG_VALUE}': '',
        '{TAG_ID}' : ''
    },
    allowedPreviewTypes: null,
    uploadExtraData: function () {
        var out = {};

        $(':input.file-caption-input:visible').each(function () {
            var input = $(this);
            var caption = input.val();

            if (input.data('fileid')) {
                // At this point this input belongs to a file that is already uploaded
                // Only send the caption, if it has an updated value
                if (input.data('initcaption') != caption) {
                    // only update the caption
                    out['caption_update_' + input.data('fileid')] = caption;
                }

            } else {
                // new file upload request

                var container = input.closest('.file-preview-frame');
                var index = container.data('fileindex');

                out['caption_' + index] = caption;
            }
        });

        return out;
    }
    
    }).on('fileuploaded', function(event, data, previewId, index) {

        //updateFileCaptions(data.response.updatedCaptions);

    }).on('filebatchuploadsuccess', function(event, data, previewId, index) {

        //updateFileCaptions(data.response.updatedCaptions);
    }).on("filebeforedelete", function() {

        return !window.confirm('Are you sure you want to delete this file?');

    });


$(document).ready(function(){

    $.ajax({
        type: "GET",
        url: '/bridge/province',
        dataType: 'json',
        success:function(result){
            $.each(result.data, function(index, value) {
                $('#province').append('<option value="'+value['province_id']+'">'+value['province_name']+'</option>');
            });

            //$("#province").select2("val", "900");
        }
    });

    $.ajax({
        type: "GET",
        url: '/bridge/propertytype',
        dataType: 'json',
        data: {
            type : 'project'
        }, 
        success:function(result){
            var x = 1;
            $.each(result.data, function(index, value) {
                $('#propertyType').append('<option value="'+value['property_type_id']+'">'+value['property_type_name']+'</option>');

                if(x == 1){$("#propertyType").select2("val", value['property_type_id']);}

                x++;
            });
        }
    });

    $.ajax({
        type: "GET",
        url: '/developer/list/',
        dataType: 'json',
        data: {
            type : 'bridge'
        }, 
        success:function(result){
            $.each(result.data, function(index, value) {
                $('#developerName').append('<option value="'+value['developer_id']+'">'+value['developer_name']+'</option>');
            });
        }
    });
    
    $('input[type=checkbox],input[type=radio]').uniform();
    
    $('select').select2();
    
    // Form Validation
    /*$("#postProyek").validate({
        rules:{
            projectName:{required:true},
            developerName:{required:true},
            startPrice:{required:true},
            endPrice:{required:true},
            desc:{required:true},
            province:{required:true},
            city:{required:true},
            area:{required:true},
            address:{required:true},
            unitTotalAll:{required:true}
        },
        
        submitHandler: function(form) {
            if($("#developerName").val() == ""){
                alert("Nama Developer tidak boleh kosong");
                $("#developerName").focus();
                return false;
            }

            if($("#desc").val() == ""){
                alert("Deskripsi tidak boleh kosong");
                $("#desc").focus();
                return false;
            }

            if($("#province").val() == ""){
                alert("Provinsi tidak boleh kosong");
                $("#province").focus();
                return false;
            }

            if($("#city").val() == ""){
                alert("Kota tidak boleh kosong");
                $("#city").focus();
                return false;
            }

            if($("#area").val() == ""){
                alert("Area tidak boleh kosong");
                $("#area").focus();
                return false;
            }

            if($("#address").val() == ""){
                alert("Alamat tidak boleh kosong");
                $("#address").focus();
                return false;
            }

            $("#btn").hide();
            $("#loading").show();
            var formData = new FormData(form);

            var url = "/proyek-developer/post";

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function() {
                    $("#btn").show();
                    $("#loading").hide();
                    alert('Yeeah.. You did it!!!');
                    
                },
                error: function() {
                    $("#btn").show();
                    $("#loading").hide();
                    alert('Urmhh.. You failed. Try again later!!!');
                },
                contentType: false,
                processData: false
            });
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });*/
});

$(document).on('submit', '#postProyek', function(e) {
    e.preventDefault();

    if($("#projectName").val() == ""){
        alert("Nama Proyek tidak boleh kosong");
        $("#projectName").focus();
        return false;
    }

    if($("#developerName").val() == "0"){
        alert("Nama Developer tidak boleh kosong");
        $("#developerName").focus();
        return false;
    }

    if($("#startPrice").val() == ""){
        alert("Harga Mulai tidak boleh kosong");
        $("#startPrice").focus();
        return false;
    }

    if($("#endPrice").val() == ""){
        alert("Harga Akhir tidak boleh kosong");
        $("#endPrice").focus();
        return false;
    }

    if($("#desc").val() == ""){
        alert("Deskripsi tidak boleh kosong");
        $("#desc").focus();
        return false;
    }

    if($("#province").val() == "0"){
        alert("Provinsi tidak boleh kosong");
        $("#province").focus();
        return false;
    }

    if($("#city").val() == "0"){
        alert("Kota tidak boleh kosong");
        $("#city").focus();
        return false;
    }

    if($("#area").val() == "0"){
        alert("Area tidak boleh kosong");
        $("#area").focus();
        return false;
    }

    if($("#address").val() == ""){
        alert("Alamat tidak boleh kosong");
        $("#address").focus();
        return false;
    }

    if($("#postcode").val() == ""){
        alert("Kodepos tidak boleh kosong");
        $("#postcode").focus();
        return false;
    }

    if($("#unitTotalAll").val() == ""){
        alert("Total Unit tidak boleh kosong");
        $("#unitTotalAll").focus();
        return false;
    }

    $("#btn").hide();
    $("#loading").show();
    var formData = new FormData(this);

    var url = "/proyek-developer/post";

    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        success: function() {
            $("#btn").show();
            $("#loading").hide();
            alert('Yeeah.. You did it!!!');
            
        },
        error: function() {
            $("#btn").show();
            $("#loading").hide();
            alert('Urmhh.. You failed. Try again later!!!');
        },
        contentType: false,
        processData: false
    });

});

$(document).on('click', '.addUnit', function(e) {

    var html = "";
    html += '<br>';
    html += '<div class="control-group"><label class="control-label">Nama Unit :</label>';
    html += '<div class="controls"><input type="text" name="unitName[]" id="unitName" class="span11" placeholder="Lavenia 2" />';
    html += '</div></div>';

    html += '<div class="control-group"><label class="control-label">Jumlah Unit :</label>';
    html += '<div class="controls"><input type="text" name="unitTotal[]" id="unitTotal" class="span11" placeholder="6" />';
    html += '</div></div>';

    html += '<div class="control-group"><label class="control-label">Harga :</label>';
    html += '<div class="controls"><input type="text" name="unitPrice[]" id="unitPrice" class="span11" placeholder="180.000.000" onkeypress="return isNumberKey(event)" onkeyup="numberFormat(this)" autocomplete="off" />';
    html += '</div></div>';

    html += '<div class="control-group"><label class="control-label">Tipe :</label>';
    html += '<div class="controls"><input type="text" name="unitType[]" id="unitType" class="span11" placeholder="90" />';
    html += '</div></div>';

    html += '<div class="control-group"><label class="control-label">LT/LB :</label>';
    html += '<div class="controls controls-row"><input type="text" name="unitLT[]" id="unitLT" placeholder="LT (120)" class="span5 m-wrap"><input type="text" name="unitLB[]" id="unitLB" placeholder="LB (90)" class="span5 m-wrap">';
    html += '</div></div>';

    html += '<div class="control-group"><label class="control-label">KT/KM :</label>';
    html += '<div class="controls controls-row"><input type="text" name="unitKT[]" id="unitKT" placeholder="KT (3)" class="span5 m-wrap"><input type="text" name="unitKM[]" id="unitKM" placeholder="KM (1)" class="span5 m-wrap">';
    html += '</div></div>';

    html += '<div class="control-group"><label class="control-label">Spesifikasi :</label>';
    html += '<div class="controls"><textarea class="span11" name="unitSpesifikasi[]" id="unitSpesifikasi" placeholder="Pondasi : Pondasi tapak + batu kali + sloof beton bertulang, Struktur : Beton bertulang" ></textarea>';
    html += '</div></div>';
    

    $(".listUnit").append(html);
});

$(document).on('click', '.denahSetPrimary', function(e) {

    var _value = $(this).attr("data-key");
    $("#denahSetPrimary").val(_value);

    alert('Set Primary Success');

});

$(document).on('click', '.gallerySetPrimary', function(e) {

    var _value = $(this).attr("data-key");
    $("#gallerySetPrimary").val(_value);

    alert('Set Primary Success');

});


/*$('#file-5').fileinput({
    uploadUrl: '/upload/post?type=proyek&token='+$('#token').val(),
    uploadAsync: false,
    showUpload: false, // hide upload button
    showRemove: false, // hide remove button
    minFileCount: 1,
    maxFileCount: 5,
    allowedFileExtensions : ['jpg', 'png','gif']
});

$('#file-5').on('filebatchselected', function(event, files) {
    $('#file-5').fileinput("upload");
});*/


/*$('#file-5').on('filebatchuploadsuccess', function(event, data) {
    var response = data.response, 
        reader = data.reader;
    console.log(response);
});*/