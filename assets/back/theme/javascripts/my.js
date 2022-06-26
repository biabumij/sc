var save_method; //for save method string
var table;

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
$(".select2").select2();

function load_table(url)
{
  //datatables
    table = $('.data-table').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "oLanguage": {
            "sProcessing": "<div class='loader-datatable'><img src='"+window.location.protocol + "//" + window.location.host + "/"+window.location.pathname.split('/')[1]+ "/"+window.location.pathname.split('/')[2]+"/assets/back/theme/images/loader.gif'></div>"
        },
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url+"/"+Math.random(),
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
}

function load_table_id(id,url)
{
  //datatables
    $(id).DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "oLanguage": {
            "sProcessing": "<div class='loader-datatable'><img src='"+window.location.protocol + "//" + window.location.host + "/"+window.location.pathname.split('/')[1]+ "/"+window.location.pathname.split('/')[2]+"/assets/back/theme/images/loader.gif'></div>"
        },
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url+"/"+Math.random(),
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
}

function delete_person(id,text=false)
    {
      var arr_id =  id.split("/");
      var data_id = arr_id[arr_id.length-1];

      if(text == false){
        text = 'Yes, delete it!';
      }

      if(arr_id[3] != null){

        swal({
          title: 'Are you sure?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: text
        },function() {
          $.ajax({
          type:'POST',
          url:id,
          data:{del_id:data_id},
          success: function(data){
            if(data=="success"){
              swal({
                title : 'Succes!',
                type : 'success'
              },function(){
                // reload_table();
                location.reload();
              });

            }else {
              swal(
                'Deleted Failed!',
                'Your file has not been deleted.',
                'error'
              );
            }
           }
         });
        });
      }else {
        swal(
          'Opps Sorry!',
          'Data Id Not Found!',
          'info'
        )
      }
    }

$(function(){

  // Validate
  $(".form-submit").validate({
        highlight: function(label) {
            $(label).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(label) {
            $(label).closest('.form-group').removeClass('has-error');
            label.remove();
        },
        submitHandler: function(form) {
          var button = $(form).attr('data-button');
          var redirect = $(form).attr('data-redirect');

            tinyMCE.triggerSave();
            var formData = new FormData($(form)[0]);

            $.ajax({
               type: "POST",
               url:  $(form).attr('action')+'/'+Math.random(),
               data: formData,
               dataType: 'json',
               beforeSend:function(){
                  $(button).button('loading');
               },
               success: function (data) {
                   var output = data.output;
                   $(button).button('reset');
                   if(output == 'true'){
                      swal({
                        title : 'Success!',
                        type : 'success'
                      },function(){
                          // location.reload();
                          window.location.href = redirect;
                      });
                   }else {
                      swal({
                          title: output,
                          type: 'error',
                      }).then(function(){
                          location.reload();
                      });
                   }
               },
              cache: false,
              contentType: false,
              processData: false
            });
            return false;
        }
    });

   if(typeof(form_control) != "undefined" && form_control !== null) {
      
    }else {
      $(".form-control").each(function () {
        var type = $(this).attr('type');
        var id = $(this).attr('id');
        var attr = $(this).attr('data-required');

        if (attr !== "false") {
            if( type == 'email'){
                $(this).rules('add', {
                    required: true,
                    email : true
                });
            }else
            if(type == 'number'){
                $(this).rules('add', {
                    required: true,
                    number : true
                });
            }else 
            if(type == 'password'){
                if(id == 'co-password'){
                    $(this).rules('add', {
                        required: true,
                        minlength : 5,
                        equalTo: "#password"
                    });
                }else {
                    $(this).rules('add', {
                        required: true,
                        minlength : 5
                    });
                }
            }else {
                $(this).rules('add', {
                    required: true
                });
            }  
        }
        
    });
    }
    



    // Select2
     //Set bootstrap theme
    // $.fn.select2.defaults.set( "theme", "bootstrap" );
    // $(".select2").select2();

});