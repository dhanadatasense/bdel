
$(document).ready(function(){
    var baseurl = $('.geturl').val();

    var _success = function(title,text) {
        toastr.success(text, title, {   
            timeOut: 5e3,
            closeButton: !0,
            debug: !1,
            newestOnTop: !0,
            progressBar: !0,
            positionClass: "toast-bottom-right",
            preventDuplicates: !0,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
            tapToDismiss: !1
        })
    }

    var _error = function(title,text) {
        toastr.error(text, title, {
            positionClass: "toast-bottom-right",
            timeOut: 5e3,
            closeButton: !0,
            debug: !1,
            newestOnTop: !0,
            progressBar: !0,
            preventDuplicates: !0,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
            tapToDismiss: !1
        })
    }

    function loadAdmin_count() {
        $.ajax({
            method: 'POST',
            data: {
                'method' : '_adminOrderCount',
            },
            url: baseurl+'index.php/admin/dashboard/order_count',
            dataType: 'json',
        }).done(function (response) 
        {
            if(response['data']['outlet_order'] != 0)
            {
                $('.admStr_ord').removeClass('hide').addClass('show');
            }
            else
            {
                $('.admStr_ord').removeClass('show').addClass('hide');
            }

            if(response['data']['distributor_order'] != 0)
            {
                $('.admDis_ord').removeClass('hide').addClass('show');
            }
            else
            {
                $('.admDis_ord').removeClass('show').addClass('hide');
            }

        });
    }

    loadAdmin_count();

    // Shop Assign Button
    var _SAL = function() {
        $(".shop_submit").attr("disabled", "disabled").button('refresh');
        $('.assign_btn').removeClass('show').addClass('hide');
        $('.assign_span').removeClass('hide').addClass('show');
    }

    var _SAR = function() {
        $(".shop_submit").removeAttr("disabled").button('refresh');
        $('.assign_btn').removeClass('hide').addClass('show');
        $('.assign_span').removeClass('show').addClass('hide');
    }

    // Target Assign Button
    var _TAL = function() {
        $(".target_submit").attr("disabled", "disabled").button('refresh');
        $('.assign_btn').removeClass('show').addClass('hide');
        $('.assign_span').removeClass('hide').addClass('show');
    }

    var _TAR = function() {
        $(".target_submit").removeAttr("disabled").button('refresh');
        $('.assign_btn').removeClass('hide').addClass('show');
        $('.assign_span').removeClass('show').addClass('hide');
    }

    // Price Assign Button
    var _PAL = function() {
        $(".price_submit").attr("disabled", "disabled").button('refresh');
        $('.price_btn').removeClass('show').addClass('hide');
        $('.price_span').removeClass('hide').addClass('show');
    }

    var _PAR = function() {
        $(".price_submit").removeAttr("disabled").button('refresh');
        $('.price_btn').removeClass('hide').addClass('show');
        $('.price_span').removeClass('show').addClass('hide');
    }

    var _PL = function() {
        $(".process_submit").attr("disabled", "disabled").button('refresh');
        $('.first_btn').removeClass('show').addClass('hide');
        $('.span_btn').removeClass('hide').addClass('show');
    }

    var _PR = function() {
        $(".process_submit").removeAttr("disabled").button('refresh');
        $('.first_btn').removeClass('hide').addClass('show');
        $('.span_btn').removeClass('show').addClass('hide');
    }

    // Production Process Button
    var _PPL = function() {
        $(".production_submit").attr("disabled", "disabled").button('refresh');
        $('.production_btn').removeClass('show').addClass('hide');
        $('.production_span').removeClass('hide').addClass('show');
    }

    var _PPR = function() {
        $(".production_submit").removeAttr("disabled").button('refresh');
        $('.production_btn').removeClass('hide').addClass('show');
        $('.production_span').removeClass('show').addClass('hide');
    }
       // outletOrder Button
       var _ARLD = function() {
        $(".outletOrder_submit").attr("disabled", "disabled").button('refresh');
        $('.outletOrder_btn').removeClass('show').addClass('hide');
        $('.outletOrder_span').removeClass('hide').addClass('show');
        // $('#state_id').Attr('disabled', true);
    }

    var _ARRD = function() {
        $(".outletOrder_submit").removeAttr("disabled").button('refresh');
        $('.outletOrder_btn').removeClass('hide').addClass('show');
        $('.outletOrder_span').removeClass('show').addClass('hide');
        // $(".state_id").Attr("disabled",false) ;
    }

    var _ARLDSK = function() {
        $(".outletstock_submit").attr("disabled", "disabled").button('refresh');
        $('.out_stock_btn').removeClass('show').addClass('hide');
        $('.out_stock_span').removeClass('hide').addClass('show');
        // $('#state_id').Attr('disabled', true);
    }

    var _ARRDSK = function() {
        $(".outletstock_submit").removeAttr("disabled").button('refresh');
        $('.out_stock_btn').removeClass('hide').addClass('show');
        $('.out_stock_span').removeClass('show').addClass('hide');
        // $(".state_id").Attr("disabled",false) ;
    }

    // Attendance Button////////////////
    var _ARL = function() {
        $(".attendance_submit").attr("disabled", "disabled").button('refresh');
        $('.order_btn').removeClass('show').addClass('hide');
        $('.order_span').removeClass('hide').addClass('show');
    }
  

    var _ARR = function() {
        $(".attendance_submit").removeAttr("disabled").button('refresh');
        $('.attendance_btn').removeClass('hide').addClass('show');
        $('.attendance_span').removeClass('show').addClass('hide');
    }

    // Vendor Purchas Report Button
    var _VPRL = function() {
        $(".vendorPurchase_submit").attr("disabled", "disabled").button('refresh');
        $('.vendorPurchase_btn').removeClass('show').addClass('hide');
        $('.vendorPurchase_span').removeClass('hide').addClass('show');
    }

    var _VPRR = function() {
        $(".vendorPurchase_submit").removeAttr("disabled").button('refresh');
        $('.vendorPurchase_btn').removeClass('hide').addClass('show');
        $('.vendorPurchase_span').removeClass('show').addClass('hide');
    }

    // Vendor Overall Report Button
    var _VORL = function() {
        $(".vendorOverall_submit").attr("disabled", "disabled").button('refresh');
        $('.vendorOverall_btn').removeClass('show').addClass('hide');
        $('.vendorOverall_span').removeClass('hide').addClass('show');
    }

    var _VORR = function() {
        $(".vendorOverall_submit").removeAttr("disabled").button('refresh');
        $('.vendorOverall_btn').removeClass('hide').addClass('show');
        $('.vendorOverall_span').removeClass('show').addClass('hide');
    }

    // Vendor Overall Report Button
    var _RPTL = function() {
        $(".rpt_submit").attr("disabled", "disabled").button('refresh');
        $('.rpt_btn').removeClass('show').addClass('hide');
        $('.rpt_span').removeClass('hide').addClass('show');
    }

    var _RPTR = function() {
        $(".rpt_submit").removeAttr("disabled").button('refresh');
        $('.rpt_btn').removeClass('hide').addClass('show');
        $('.rpt_span').removeClass('show').addClass('hide');
    }

    ! function(t, e, n) {
        "use strict";
        n(".custom-file input").change((function(t) {
            n(this).next(".custom-file-label").html(t.target.files[0].name)
        }))
    }(window, document, jQuery);

    if($("#state_id").length)
    {
        $('#state_id').on('change',function(){
            var state_id  = $(this).val();
            var assign_id = $('#assign_id').val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'state_id'  : state_id,
                    'assign_id' : assign_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getCity_name',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#city_id').empty('').html(response['data']);
                    $('#zone_id').empty('');
                    $('#outlet_id').empty('');
                }
            });
        });
    }

    if($("#city_id").length)
    {
        $('#city_id').on('change',function(){
            var city_id   = $(this).val();
            var state_id  = $('#state_id').val();            
            var assign_id = $('#assign_id').val();
            var product_id = $('#product_id').val(); 
            var type_id   = $('#type_id').val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'assign_id' : assign_id,
                    'state_id'  : state_id,
                    'city_id'   : city_id,
                    'type_id'   : type_id,
                    'product_id'   : product_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getZone_name',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#zone_id').empty('').html(response['data']);
                    $('#outlet_id').empty('');
                }
            });
        });
    }

    if($("#vendor_id").length)
    {
        $('#vendor_id').on('change',function(){
            var vendor_id = $(this).val();
            var value    = $('#value').val();
            var cntrl    = $('#cntrl').val();
            var func     = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'vendor_id' : vendor_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getVendor_details',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#gst_no').empty('').val(response['data']['gst_no']);
                    $('#contact_no').empty('').val(response['data']['contact_no']);
                    $('#address').empty('').val(response['data']['address']);
                }
            });
        });
    }

    if($("#vendor_id").length)
    {
        $('#vendor_id').on('change',function(){
            var vendor_id = $(this).val();
            var value    = $('#value').val();
            var cntrl    = $('#cntrl').val();
            var func     = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'vendor_id' : vendor_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getVendor_invoice',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#invoice_id').empty('').html(response['data']);
                }
            });
        });
    }

    if($(".add_items").length)
    {
        $(document).on('click','.add_items',function(){
            var rowCount  = $('.additemform tr').length;
            var row_count = $('#row_count').val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            if(row_count)
            {
                npage = row_count;
            } 
            else
            {
                npage = 1;
            }

            $.ajax({
                method: 'POST',
                data: {
                    'rowCount' : npage,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getProduct_row',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.additemform').append(response['data']);
                    $('#row_count').empty().val(response['count']);
                }
            });
        });
    }

    $(document).on('click','.remove_item',function(){
        $(this).parent().closest('td').closest('tr').remove();
    });

    // Purchase Order
    if($(".add_purchase").length)
    {
        $('.add_purchase').on('click',function(){

            var rowCount  = $('.additemform tr').length;
            var vendor_id = $('#vendor_id').val();
            var row_count = $('#row_count').val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            if(vendor_id != '')
            {
                if(row_count)
                {
                    npage = row_count;
                } 
                else
                {
                    npage = 1;
                }

                $.ajax({
                    method: 'POST',
                    data: {
                        'rowCount'  : npage,
                        'vendor_id' : vendor_id,
                    },
                    url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getPurchase_row',
                    dataType: 'json',
                }).done(function (response)
                {
                    if(response['status'] == 1)
                    {
                        $('.additemform').append(response['data']);
                        $('#row_count').empty().val(response['count']);
                    }
                });
            }
            else
            {
                var title = "";
                var text  = "Please Select Vendor Name";
                _error(title,text);
            }
        });
    }   


    if($(".state_id").length)
    {
        $('.state_id').on('change',function(){
            var state_id  = $(this).val();
            
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'state_id'  : state_id,
                    
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getCity_name',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#city_id').empty('').html(response['data']);
                }
            });
        });
    }

    if($(".city_id").length)
    {
        $('.city_id').on('change',function(){
            var city_id  = $(this).val();
            var state_id     = $('#state_id').val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'city_id'  : city_id,
                    'state_id'  : state_id,

                    
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getZone_name',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#zone_id').empty('').html(response['data']);
                }
            });
        });
    }

//designation code is mention position_id like RSM,ASM ...
    if($("#position_id").length)
    {
        $('#position_id').on('change',function(){
        
            var position_id  = $(this).val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'designation_code'  : position_id,
                    
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/emp_list',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#employee_id').empty('').html(response['data']);
                }
            });
        });
    }

    if($("#category_id").length)
    {
        $('#category_id').on('change',function(){
        
            var category_id  = $(this).val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'cat_id'  : category_id,
                    
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/sub_cat',
                dataType: 'json',
            }).done(function (response)
            {
                
                if(response['status'] == 1)
                {
                  
                    $('#sub_cat_id').empty('').html(response['data']);
                }
            });
        });
    }

    // Remove Purchase 
    $(document).on('click','.remove_purchase',function(){
        $(this).parent().closest('td').closest('tr').remove();
    });

    // Product Details
    if($(".product_id").length)
    {
        $(".product_id").on("change",function(){
            var auto_id    = $(this).attr("data-te");
            var product_id = $(this).val();
            var value      = $('#value').val();
            var cntrl      = $('#cntrl').val();
            var func       = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'product_id' : product_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getProduct_details',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.unit_id'+auto_id).empty().html(response['data']);
                    $('.product_price'+auto_id).empty().val(response['price']);
                }
            });
        });
    }

    // Assign Shop
    if($(".shop_list").length)
    {
        $('.shop_list').on('click',function(){
            _SAL();
            var employee_id = $('#employee_id').val();
            var month_id    = $('#month_id').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'employee_id' : employee_id,
                    'month_id'    : month_id,
                    'method'      : 'getMonthData',
                },
                dataType: 'json',
            }).done(function (response)
            {
                _SAR();

                if(response['status'] == 1)
                {
                    $('.assign_value').removeClass('hide').addClass('show');
                    $('.submit_btn').removeClass('hide').addClass('show');

                    $('#assign_error').removeClass('show').addClass('hide');
                    $('#getShopList').empty().html(response['data']);
                    $('.zone_id').select2(
                        {
                            placeholder: "Select Value",
                            tags:false
                        }                        
                    );

                    $('#month_val').empty().val(response['month_val']);
                    $('#year_val').empty().val(response['year_val']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        });
    }

    $(document).on('click','.remove_store',function(){
        $(this).parent().closest('td').closest('tr').remove();
    });

    $('.rate, .qty').keyup(function(){
        var val  = $(this).data('val');
        var rate = $('.rate_'+val).val();
        var qty  = $('.qty_'+val).val();

        var stod = parseFloat(qty)*parseFloat(rate);

        if(stod)
        {   
            $('.amount_'+val).html(stod.toFixed(2));
        }
        else
        {
            $('.amount_'+val).html('0');   
        }
    }); 

    // Order Item Update    
    $(document).on('click', '.order_update', function () {
        var val   = $(this).data('id');
        var rate  = $('.rate_'+val).val();
        var qty   = $('.qty_'+val).val();
        var value = $(this).data('value');
        var cntrl = $(this).data('cntrl');
        var func  = $(this).data('func');

        $.ajax({
            method: 'POST',
            data: {
                'id'   : val,
                'rate' : rate,
                'qty'  : qty,
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/order_update',
            dataType: 'json',
        }).done(function (response) 
        {
            if(response['status'] == 1)
            {
                var title = "";
                var text  = response['message'];
                _success(title,text);
            }
            else
            {
                var title = "";
                var text  = response['message'];
                _error(title,text);
            }
        });
    });

    // Order Item Delete
    $(document).on('click', '.delete-order', function () { 

        var id       = $(this).data('id');
        var progress = $(this).data('progress');
        var value    = $(this).data('value');
        var cntrl    = $(this).data('cntrl');
        var func     = $(this).data('func');
        var row      = $(this).data('row');

        Swal.fire({
            title: 'Are you sure?',
            text: "You wan't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            confirmButtonClass: 'btn btn-warning',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    method: 'POST',
                    data: {
                        'id'       : id,
                        'progress' : progress,
                    },
                    url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/delete',
                    dataType: 'json',
                }).done(function (response) 
                {
                    if(response['status'] == 1)
                    {
                        $('.row_'+row).remove();

                        var title = ""; 
                        var text  = response['message'];
                        _success(title,text);

                        location.reload();
                    }
                    else
                    {
                        var title = "";
                        var text  = response['message'];
                        _error(title,text);
                    }
                });
            }
            else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your data is safe :)',
                    type: 'error',
                    confirmButtonClass: 'btn btn-success',
                })
            }
        });
    });

    // Order Progress Button
    $('.progress_option').on('change',function(){
        var progress_id   = $("input[name='options']:checked").val();

        if(progress_id == 2)
        {
            $('.order_discount').removeClass('hide').addClass('show');
            $('.order_message').removeClass('show').addClass('hide');
            $('.delivery_message').removeClass('show').addClass('hide');
            $('.delivery_address').removeClass('show').addClass('hide');
        }

        else if(progress_id == 8)
        {
            $('.order_message').removeClass('hide').addClass('show');
            $('.order_discount').removeClass('show').addClass('hide');
            $('.delivery_message').removeClass('show').addClass('hide');
            $('.delivery_address').removeClass('show').addClass('hide');
        }

        else if(progress_id == 9)
        {
            $('.order_message').removeClass('hide').addClass('show');
            $('.order_discount').removeClass('show').addClass('hide');
            $('.delivery_message').removeClass('show').addClass('hide');
            $('.delivery_address').removeClass('show').addClass('hide');
        }

        else if(progress_id == 10)
        {
            $('.delivery_message').removeClass('hide').addClass('show');
            $('.order_discount').removeClass('show').addClass('hide');
            $('.order_message').removeClass('show').addClass('hide');
            $('.delivery_address').removeClass('show').addClass('hide');
        }
        else if(progress_id == 11)
        {
            $('.delivery_message').removeClass('show').addClass('hide');
            $('.order_discount').removeClass('show').addClass('hide');
            $('.order_message').removeClass('show').addClass('hide');
            $('.delivery_address').removeClass('hide').addClass('show');
        }

        else
        {
            $('.order_discount').removeClass('show').addClass('hide');
            $('.order_message').removeClass('show').addClass('hide');
            $('.delivery_message').removeClass('show').addClass('hide');
            $('.delivery_address').removeClass('show').addClass('hide');
        }

        $('.pro_type_button').removeClass('active');
        $('.progress_'+progress_id).addClass('active');
    });

    // Order Progress Submit
    $(document).on('click','.process_button', function () {
        _PL();

        var progress_id                 = $("input[name='options']:checked").val();
        var value                       = $('#value').val();
        var cntrl                       = $('#cntrl').val();
        var func                        = $('#func').val();
        var order_id                    = $('#order_id').val();
        var invoice_id                  = $('#invoice_id').val();
        var inv_random                  = $('#inv_random').val();
        var pre_status                  = $('#pre_status').val();
        var message                     = $('#message').val();
        var discount                    = $('#discount').val();
        var due_days                    = $('#due_days').val();
        var length                      = $('#length').val();
        var breadth                     = $('#breadth').val();
        var height                      = $('#height').val();
        var weight                      = $('#weight').val();
        var dly_address                 = $('#dly_address').val();
        var e_inv_status                = $('#e_inv_status').val();
        var e_way_status                = $('#e_way_status').val();
        var transporter_id              = $('#transporter_id').val();
        var transporter_name            = $('#transporter_name').val();
        var transportation_mode         = $('#transportation_mode').val();
        var transportation_distance     = $('#transportation_distance').val();
        var transporter_document_number = $('#transporter_document_number').val();
        var transporter_document_date   = $('#transporter_document_date').val();
        var vehicle_number              = $('#vehicle_number').val();
        var vehicle_type                = $('#vehicle_type').val();

        $.ajax({
            method: 'POST',
            data: {
                'order_id'                    : order_id,
                'invoice_id'                  : invoice_id,
                'inv_random'                  : inv_random,
                'pre_status'                  : pre_status,
                'order_status'                : progress_id,
                'message'                     : message,
                'discount'                    : discount,
                'due_days'                    : due_days,
                'length'                      : length,
                'breadth'                     : breadth,
                'height'                      : height,
                'weight'                      : weight,
                'dly_address'                 : dly_address,
                'e_inv_status'                : e_inv_status,
                'e_way_status'                : e_way_status,
                'transporter_id'              : transporter_id,
                'transporter_name'            : transporter_name,
                'transportation_mode'         : transportation_mode,
                'transportation_distance'     : transportation_distance,
                'transporter_document_number' : transporter_document_number,
                'transporter_document_date'   : transporter_document_date,
                'vehicle_number'              : vehicle_number,
                'vehicle_type'                : vehicle_type,
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/changeOrder_process',
            dataType: 'json',
        }).done(function (response)
        {
            _PR();
            if(response['status'] == 1)
            {
                var title = "";
                var text  = response['message'];
                _success(title,text);
                location.reload(); 
            }
            else
            {
                var title = "";
                var text  = response['message'];
                _error(title,text);
            }
        });
    });

    // Order Progress Submit
    $(document).on('click','.delivery_button', function () {
        _PL();

        var progress_id = $("input[name='options']:checked").val();
        var value       = $('#value').val();
        var cntrl       = $('#cntrl').val();
        var func        = $('#func').val();
        var order_id    = $('#order_id').val();
        var dc_id       = $('#dc_id').val();
        var pre_status  = $('#pre_status').val();
        var message     = $('#message').val();
        var discount    = $('#discount').val();
        var due_days    = $('#due_days').val();
        var length      = $('#length').val();
        var breadth     = $('#breadth').val();
        var height      = $('#height').val();
        var weight      = $('#weight').val();

        $.ajax({
            method: 'POST',
            data: {
                'order_id'     : order_id,
                'dc_id'        : dc_id,
                'pre_status'   : pre_status,
                'order_status' : progress_id,
                'message'      : message,
                'discount'     : discount,
                'due_days'     : due_days,
                'length'       : length,
                'breadth'      : breadth,
                'height'       : height,
                'weight'       : weight,
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/changeOrder_process',
            dataType: 'json',
        }).done(function (response)
        {
            _PR();
            if(response['status'] == 1)
            {
                var title = "";
                var text  = response['message'];
                _success(title,text);
                location.reload(); 
            }
            else
            {
                var title = "";
                var text  = response['message'];
                _error(title,text);
            }
        });
    });

    // Order Progress Submit
    $(document).on('click','.vendor_process_button', function () {
        _PL();

        var progress_id = $("input[name='options']:checked").val();
        var value       = $('#value').val();
        var cntrl       = $('#cntrl').val();
        var func        = $('#func').val();
        var order_id    = $('#order_id').val();
        var vendor_id   = $('#vendor_id').val();
        var message     = $('#message').val();
        var length      = $('#length').val();
        var breadth     = $('#breadth').val();
        var height      = $('#height').val();
        var weight      = $('#weight').val();

        $.ajax({
            method: 'POST',
            data: {
                'order_id'     : order_id,
                'vendor_id'    : vendor_id,
                'order_status' : progress_id,
                'message'      : message,
                'length'       : length,
                'breadth'      : breadth,
                'height'       : height,
                'weight'       : weight,
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/changeOrder_process',
            dataType: 'json',
        }).done(function (response)
        {
            _PR();
            if(response['status'] == 1)
            {
                var title = "";
                var text  = response['message'];
                _success(title,text);
                location.reload(); 
            }
            else
            {
                var title = "";
                var text  = response['message'];
                _error(title,text);
            }
        });
    });

    // Order Progress Submit
    $(document).on('click','.distributor_process_button', function () {
        _PL();
        var progress_id                 = $("input[name='options']:checked").val();
        var value                       = $('#value').val();
        var cntrl                       = $('#cntrl').val();
        var func                        = $('#func').val();
        var order_id                    = $('#order_id').val();
        var inv_value                   = $('#inv_value').val();
        var distributor_id              = $('#distributor_id').val();
        var zone_value                  = $('#zone_value').val();
        var pre_status                  = $('#pre_status').val();
        var message                     = $('#message').val();
        var length                      = $('#length').val();
        var breadth                     = $('#breadth').val();
        var height                      = $('#height').val();
        var weight                      = $('#weight').val();
        var e_inv_status                = $('#e_inv_status').val();
        var e_way_status                = $('#e_way_status').val();
        var transporter_id              = $('#transporter_id').val();
        var transporter_name            = $('#transporter_name').val();
        var transportation_mode         = $('#transportation_mode').val();
        var transportation_distance     = $('#transportation_distance').val();
        var transporter_document_number = $('#transporter_document_number').val();
        var transporter_document_date   = $('#transporter_document_date').val();
        var vehicle_number              = $('#vehicle_number').val();
        var vehicle_type                = $('#vehicle_type').val();

        $.ajax({
            method: 'POST',
            data: {
                'order_id'                    : order_id,
                'inv_value'                   : inv_value,
                'pre_status'                  : pre_status,
                'zone_value'                  : zone_value,
                'distributor_id'              : distributor_id,
                'order_status'                : progress_id,
                'message'                     : message,
                'length'                      : length,
                'breadth'                     : breadth,
                'height'                      : height,
                'weight'                      : weight,
                'e_inv_status'                : e_inv_status,
                'e_way_status'                : e_way_status,
                'transporter_id'              : transporter_id,
                'transporter_name'            : transporter_name,
                'transportation_mode'         : transportation_mode,
                'transportation_distance'     : transportation_distance,
                'transporter_document_number' : transporter_document_number,
                'transporter_document_date'   : transporter_document_date,
                'vehicle_number'              : vehicle_number,
                'vehicle_type'                : vehicle_type,
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/changeOrder_process',
            dataType: 'json',
        }).done(function (response)
        {
            _PR();
            if(response['status'] == 1)
            {
                var title = "";
                var text  = response['message'];
                _success(title,text);
                location.reload(); 
            }
            else
            {
                var title = "";
                var text  = response['message'];
                _error(title,text);
            }
        });
    });

    // Order Production Process
    if($(".production_button").length)
    {
        $(document).on('click','.production_button', function () {
            _PPL();
            var start_date = $('#start_date').val();
            var end_date   = $('#end_date').val();
            var value      = $(this).data('value');
            var cntrl      = $(this).data('cntrl');
            var func       = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date' : start_date,
                    'end_date'   : end_date,
                    'method'     : 'getProductionData',
                },
                dataType: 'json',
            }).done(function (response)
            {
                _PPR();
                if(response['status'] == 1)
                {
                    $('.production_value').removeClass('hide').addClass('show');
                    $('#production_error').removeClass('show').addClass('hide');
                    $('.submit_btn').removeClass('hide').addClass('show');
                    $('#getProductionList').empty().html(response['data']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    $('.production_value').removeClass('show').addClass('hide');
                    $('#production_error').removeClass('hide').addClass('show');
                    $('.submit_btn').removeClass('show').addClass('hide');

                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });

        }); 
    }

    // Admin Outlet Order
    if($("#zone_id").length)
    {
        $('#zone_id').on('change',function(){
            var zone_id  = $(this).val();
            var state_id = $('#state_id').val();            
            var city_id  = $('#city_id').val();

            var value    = $('#value').val();
            var cntrl    = $('#cntrl').val();
            var func     = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'state_id' : state_id,
                    'city_id'  : city_id,
                    'zone_id'  : zone_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getOutlet_name',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#outlet_id').empty('').html(response['data']);
                }
            });
        });
    }

    // Outlet Details
    if($("#outlet_id").length)
    {
        $('#outlet_id').on('change',function(){

            var outlet_id = $(this).val();                                            
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'outlet_id' : outlet_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getOutlet_details',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#available_limit').empty('').val(response['data']['available_limit']);
                    $('#contact_no').empty('').val(response['data']['contact_no']);
                    $('#gst_no').empty('').val(response['data']['gst_no']);
                    $('#address').empty('').val(response['data']['address']);

                    $('#discount').empty('').val(response['data']['discount']);
                    $('#due_days').empty('').val(response['data']['due_days']);

                    $('.product_price').empty('').val('');
                    $('.product_qty').empty('').val('');
                    $('.hsn_code').empty('').val('');
                    $('.gst_val').empty('').val('');

                    $(".order_add_btn").removeAttr("disabled").button('refresh');
                    $(".product_val").removeAttr("disabled").button('refresh');
                }
            });
        });
    }

    // Outlet Details
    if($("#outlet_id").length)
    {
        $('#outlet_id').on('change',function(){

            var outlet_id = $(this).val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'outlet_id' : outlet_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_getDistributorInvoice',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#invoice_id').empty('').html(response['data']);
                }
            });
        });
    }
    // dis Details
    if($("#dis_id").length)
    {
        $('#dis_id').on('change',function(){

            var dis_id = $(this).val();                                            
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'dis_id' : dis_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getOutlet_details',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#available_limit').empty('').val(response['data']['available_limit']);
                    $('#contact_no').empty('').val(response['data']['contact_no']);
                    $('#gst_no').empty('').val(response['data']['gst_no']);
                    $('#address').empty('').val(response['data']['address']);

                    $('#discount').empty('').val(response['data']['discount']);
                    $('#due_days').empty('').val(response['data']['due_days']);

                    $('.product_price').empty('').val('');
                    $('.product_qty').empty('').val('');
                    $('.hsn_code').empty('').val('');
                    $('.gst_val').empty('').val('');
                }
            });
        });
    }

    // dis Details
    if($("#dis_id").length)
    {
        $('#dis_id').on('change',function(){

            var dis_id = $(this).val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'dis_id' : dis_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_getDistributorInvoice',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#invoice_id').empty('').html(response['data']);
                }
            });
        });
    }
    // Vendor Product List
    if($("#vendor_id").length)
    {
        $('#vendor_id').on('change',function(){
            var vendor_id = $(this).val();
            var value    = $('#value').val();
            var cntrl    = $('#cntrl').val();
            var func     = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'vendor_id' : vendor_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getVendor_products',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.product_id').empty('').html(response['data']);
                }
            });
        });
    }

    // Product Type List
    if($(".product_id").length)
    {
        $('.product_id').on('change',function(){
            var product_id = $(this).val();
            var auto_id    = $(this).attr("data-te");
            var value      = $('#value').val();
            var cntrl      = $('#cntrl').val();
            var func       = $('#func').val();
            var order_type = $('#order_type').val();
            var outlet_id  = $('#outlet_id').val();
            var zone_id    = $('#zone_id').val();

            if(order_type == 1)
            {
                if(outlet_id == '')
                {
                    var title = "";
                    var text  = "Please Select Outlet Name";
                    _error(title,text);

                }    
                else
                {
                    $.ajax({
                        method: 'POST',
                        data: {
                            'product_id' : product_id,
                            'zone_id'    : zone_id,
                            'order_type' : 1,
                        },
                        url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getVendor_productType',
                        dataType: 'json',
                    }).done(function (response)
                    {
                        if(response['status'] == 1)
                        {
                            $('.type_id'+auto_id).empty('').html(response['data']);
                        }
                    });
                }
            }
            else
            {
                $.ajax({
                    method: 'POST',
                    data: {
                        'product_id' : product_id,
                    },
                    url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getVendor_productType',
                    dataType: 'json',
                }).done(function (response)
                {
                    if(response['status'] == 1)
                    {
                        $('.type_id'+auto_id).empty('').html(response['data']);
                    }
                });
            }
        });
    }

    // Product Type Details
    if($(".type_id").length)
    {
        $(".type_id").on("change",function(){
            var $row = $(this).closest("tr");
            // var auto_id   = $(this).attr("data-te");
            var type_id    = $(this).val();
            var state_id   = $('#state_id').val();
            var city_id    = $('#city_id').val();
            var zone_id    = $('#zone_id').val();
            var outlet_id  = $('#outlet_id').val();
            var value      = $('#value').val();
            var cntrl      = $('#cntrl').val();
            var func       = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'state_id'  : state_id,
                    'city_id'   : city_id,
                    'zone_id'   : zone_id,
                    'outlet_id' : outlet_id,
                    'type_id'   : type_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getProductType_details',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    

                    $row.find(".unit_id").empty().html(response['data']);
                    $row.find(".product_price").empty().val(response['price']);
                    $row.find(".pack_qty").empty().val(response['pack_cnt']);
                    $row.find(".stock_check").empty().val(response['stock_check']);
                    // $('.unit_id'+auto_id).empty().html(response['data']);
                    // $('.product_price'+auto_id).empty().val(response['price']);
                }
            });
        });
    }

    // Add Order Row
    if($(".add_orders").length)
    {
        $(document).on('click','.add_orders', function () {

            var rowCount  = $('.addOrderform tr').length;
            var vendor_id = $('#vendor_id').val();
            var row_count = $('#row_count').val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            if(vendor_id != '')
            {
                if(row_count)
                {
                    npage = row_count;
                } 
                else
                {
                    npage = 1;
                }

                $.ajax({
                    method: 'POST',
                    data: {
                        'rowCount'  : npage,
                        'vendor_id' : vendor_id,
                    },
                    url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getOrder_row',
                    dataType: 'json',
                }).done(function (response)
                {
                    if(response['status'] == 1)
                    {
                        $('.addOrderform').append(response['data']);
                        $('#row_count').empty().val(response['count']);
                    }
                });
            }
            else
            {
                var title = "Error";
                var text  = "Please Select Vendor Name";
                _error(title,text);
            }
        });
    }

    // Product Details
    if($(".product_val").length)
    {
        $(".product_val").on("change",function(){
            var auto_id    = $(this).attr("data-te");
            var product_id = $(this).val();
            var value      = $('#value').val();
            var cntrl      = $('#cntrl').val();
            var func       = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'product_id' : product_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getOrderProduct_details',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.hsn_code'+auto_id).empty().val(response['hsn_code']);
                    $('.gst_val'+auto_id).empty().val(response['gst_val']);
                }
            });
        }); 
    }

    // Category Details
    if($(".distributor_id").length)
    {
        $(document).on('change','.distributor_id', function () {
            var distributor_id = $(this).val();
            var value       = $('#value').val();
            var cntrl       = $('#cntrl').val();
            var func        = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'distributor_id' : distributor_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getZone_list',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.zone_id').empty('').html(response['data']);
                }
            });
        });
    }
    // Category Details
   if($(".category_id").length)
   {
       $(document).on('change','.category_id', function () {
        var category_id = $(this).val();
        var dis_id      = $('#distributor_id').val();
        var value       = $('#value').val();
        var cntrl       = $('#cntrl').val();
        var func        = $('#func').val();

           $.ajax({
               method: 'POST',
               data: {
                   'category_id'    : category_id,
                   'distributor_id' : dis_id,
               },
               url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/get_sub_cat_list',
               dataType: 'json',
           }).done(function (response)
           {
               if(response['status'] == 1)
               {
                   $('.sub_cat_id').empty('').html(response['data']);
               }
               if(response['status'] == 0)
               {
                   $('.sub_cat_id').empty('').html(response['data']);
                   $('.type_id').empty('');
               }
           });
       });
   }
   if($(".distributor_id").length)
   {
   
       $(document).on('change','.distributor_id', function () {
        var distributor_id = $(this).val();
        var value       = $('#value').val();
        var cntrl       = $('#cntrl').val();
        var func        = $('#func').val();

           $.ajax({
               method: 'POST',
               data: {
                   
                   'distributor_id' : distributor_id,
               },
               url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/get_dis_cat_list',
               dataType: 'json',
           }).done(function (response)
           {
               if(response['status'] == 1)
               {
                   $('.category_id').empty('').html(response['data']);
               }
           });
       });
   }
    // Category Details
    if($(".sub_cat_id").length)
    {
        $(document).on('change','.sub_cat_id', function () {
            var sub_cat_id = $(this).val();
            var value       = $('#value').val();
            var cntrl       = $('#cntrl').val();
            var func        = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'sub_cat_id'    : sub_cat_id,
                    
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getSubProduct_list',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.type_id').empty('').html(response['data']);
                }
            });
        });
    }
       // Category Details
       if($(".sub_cat_id").length)
       {
           $(document).on('change','.sub_cat_id', function () {
               var sub_cat_id = $(this).val();
               var dis_id      = $('#distributor_id').val();
               var value       = $('#value').val();
               var cntrl       = $('#cntrl').val();
               var func        = $('#func').val();
   
               $.ajax({
                   method: 'POST',
                   data: {
                       'sub_cat_id'    : sub_cat_id,
                       'distributor_id' : dis_id,
                   },
                   url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getProduct_list',
                   dataType: 'json',
               }).done(function (response)
               {
                   if(response['status'] == 1)
                   {
                       $('.type_id').empty('').html(response['data']);
                   }
                   if(response['status'] == 0)
                   {
                       $('.type_id').empty('').html(response['data']);
                   }
               });
           });
       }
    // Category Details
    if($(".category_id").length)
    {
        $(document).on('change','.category_id', function () {
            var category_id = $(this).val();
            var dis_id      = $('#distributor_id').val();
            var value       = $('#value').val();
            var cntrl       = $('#cntrl').val();
            var func        = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'category_id'    : category_id,
                    'distributor_id' : dis_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getProduct_list',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.type_id').empty('').html(response['data']);
                }
            });
        });
    }

    // Distributor Product List
    if($(".category_id").length)
    {
        $('.category_id').on('change',function(){

            // alert('123');

            var category_id = $(this).val();
            // var auto_id    = $(this).attr("data-te");
            var $row       = $(this).closest("tr");
            var value      = $('#value').val();
            var cntrl      = $('#cntrl').val();
            var func       = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'category_id' : category_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_getDistributorProduct',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $row.find(".type_id").empty().html(response['data']);
                }
            });
        });
    }

    
    $(document).on('change','.grade_id', function () {
        var role_id = $(this).val();
      
        var grade_id      = $('.grade_id').val();
        var value       = $('#value').val();
        var cntrl       = $('#cntrl').val();
        var func        = $('#func').val();
        $('.commen').empty('').val('');
     
        $.ajax({
            method: 'POST',
            data: {
                'role_id'    : grade_id,
                
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/get_state',
            dataType: 'json',
        }).done(function (response)
        {
            if(response['status'] == 1)
            {   if(response['grade'] == "RSM"){
                   $('#astate_id').empty('').html(response['data']);
                }else if(response['grade'] == "ASM"){
                    $('#asmstate_id').empty('').html(response['data']);
                }else if(response['grade'] == "SO"){
                    $('#asmstate_id').empty('').html(response['data']);
                }else if(response['grade'] == "TSI"){
                    $('#asmstate_id').empty('').html(response['data']);
                }else if(response['grade'] == "BDE"){
                    $('#asmstate_id').empty('').html(response['data']);
                }
               
            }
        });
    });

    $(document).on('change','.asmstate_id', function () {
        var role_id = $(this).val();
      
        var asmstate_id      = $('.asmstate_id').val();
        var grade_id      = $('.grade_id').val();
        var value       = $('#value').val();
        var cntrl       = $('#cntrl').val();
        var func        = $('#func').val();

        $.ajax({
            method: 'POST',
            data: {
                'role_id'    : grade_id,
                'state_id'   : asmstate_id
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/get_city',
            dataType: 'json',
        }).done(function (response)
        {
           
            if(response['status'] == 1)
            {   if(response['grade'] == "SO"){
                 
                    $('#acity_id').empty('').html(response['data']);
                }else if(response['grade'] == "TSI"){
                    
                    $('#ascity_id').empty('').html(response['data']);
                   // $('#astate_id').empty('').html(response['data']);
                }else if(response['grade'] == "BDE"){
                    
                    $('#ascity_id').empty('').html(response['data']);
                   // $('#astate_id').empty('').html(response['data']);
                }
               
            }
        });
    });
    
    $(document).on('change','.ascity_id', function () {
        var role_id = $(this).val();
        var ascity_id      = $('.ascity_id').val();
        var asmstate_id      = $('.asmstate_id').val();
        var grade_id      = $('.grade_id').val();
        var value       = $('#value').val();
        var cntrl       = $('#cntrl').val();
        var func        = $('#func').val();

        $.ajax({
            method: 'POST',
            data: {
                'role_id'    : grade_id,
                'state_id'   : asmstate_id,
                'city_id'    : ascity_id
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/get_zone',
            dataType: 'json',
        }).done(function (response)
        {
           
            if(response['status'] == 1)
            {   if(response['grade'] == "TSI"){
                
                    $('#mzone').empty('').html(response['data']);
                }
                else if(response['grade'] == "BDE"){
                 
                    $('#bmzone').empty('').html(response['data']);
                }
               
            }
        });
    });

    if($(".type_id").length)
    {
        $(".type_id").on("change",function(){
            var $row    = $(this).closest("tr");
            var type_id = $(this).val();
            var value   = $('#value').val();
            var cntrl   = $('#cntrl').val();
            var func    = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'type_id' : type_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_getDistributorPdtdetails',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $row.find(".unit_id").empty().html(response['data']);
                    $row.find(".product_price").empty().val(response['price']);
                }
            });
        });
    }

    // Purchase Order
    if($(".add_outlet_return").length)
    {
        $('.add_outlet_return').on('click',function(){

            var rowCount  = $('.additemform tr').length;
            var vendor_id = $('#vendor_id').val();
            var row_count = $('#row_count').val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            if(vendor_id != '')
            {
                if(row_count)
                {
                    npage = row_count;
                } 
                else
                {
                    npage = 1;
                }

                $.ajax({
                    method: 'POST',
                    data: {
                        'rowCount'  : npage,
                        'vendor_id' : vendor_id,
                    },
                    url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getOutletReturn_row',
                    dataType: 'json',
                }).done(function (response)
                {
                    if(response['status'] == 1)
                    {
                        $('.additemform').append(response['data']);
                        $('#row_count').empty().val(response['count']);
                    }
                });
            }
            else
            {
                var title = "Error";
                var text  = "Please Select Vendor Name";
                _error(title,text);
            }
        });
    }   

    $(document).on('change','.amt_type', function () {
        var amt_type = $(this).val();

        if(amt_type == '1')
        {
            $('.desc_val').removeClass('show').addClass('hide');   
            $('.cheque_val').removeClass('show').addClass('hide');
        }
        else if(amt_type == '2')
        {
            $('.cheque_val').removeClass('hide').addClass('show');
            $('.desc_val').removeClass('show').addClass('hide');   
        }
        else if(amt_type == '3')
        {
            $('.desc_val').removeClass('hide').addClass('show');
            $('.cheque_val').removeClass('show').addClass('hide');
        }
        else if(amt_type == '4')
        {
            $('.desc_val').removeClass('hide').addClass('show');
            $('.cheque_val').removeClass('show').addClass('hide');
        }
    });

    $(document).on('click','.vendor_payment', function () {
        var value         = $(this).attr("data-value");
        var cntrl         = $(this).attr("data-cntrl");
        var func          = $(this).attr("data-func");
        var vendor_id     = $(this).attr("data-id");
        var auto_id       = $(this).attr("data-auto");
        var method        = $(this).attr("data-method");
        var pay_id        = $('.pay_id').val();
        var amount        = $('.amount').val();
        var entry_date    = $('.entry_date').val();
        var discount      = $('.discount').val();
        var amt_type      = $('.amt_type').val();
        var bank_name     = $('.bank_name').val();
        var cheque_no     = $('.cheque_no').val();
        var collect_date  = $('.collect_date').val();
        var penalty_amt   = $('.penalty_amt').val();
        var bank_charge   = $('.bank_charge').val();
        var cheque_status = $('.cheque_status').val();
        var description   = $('.description').val();

        $.ajax({
            method: 'POST',
            data: {
                'auto_id'       : auto_id,
                'vendor_id'     : vendor_id,
                'pay_id'        : pay_id,
                'amount'        : amount,
                'entry_date'    : entry_date,
                'discount'      : discount,
                'amt_type'      : amt_type,
                'bank_name'     : bank_name,
                'cheque_no'     : cheque_no,
                'collect_date'  : collect_date,
                'penalty_amt'   : penalty_amt,
                'bank_charge'   : bank_charge,
                'cheque_status' : cheque_status,
                'description'   : description,
                'method'        : method,
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
            dataType: 'json',
        }).done(function (response)
        {
            if(response['status'] == 1)
            {
                

                var title = "";
                var text  = response['message'];
                _success(title,text);

                window.location = response['redirect']; 
                // location.reload();
            }
            else
            {
                var title = "";
                var text  = response['message'];
                _error(title,text);
                location.reload();
            }
        });
    });

    $(document).on('click','.distributor_payment', function () {

        var value          = $(this).attr("data-value");
        var cntrl          = $(this).attr("data-cntrl");
        var func           = $(this).attr("data-func");
        var distributor_id = $(this).attr("data-id");
        var auto_id        = $(this).attr("data-auto");
        var method_val     = $(this).attr("data-method");
        var pay_id         = $('.pay_id').val();
        var amount         = $('.amount').val();
        var discount       = $('.discount').val();
        var entry_date     = $('.entry_date').val();
        var amt_type       = $('.amt_type').val();
        var description    = $('.description').val();
        var bank_name      = $('.bank_name').val();
        var cheque_no      = $('.cheque_no').val();
        var collect_date   = $('.collect_date').val();
        var penalty_amt    = $('.penalty_amt').val();
        var bank_charge    = $('.bank_charge').val();
        var cheque_status  = $('.cheque_status').val();

        $.ajax({
            method: 'POST',
            data: {
                'auto_id'        : auto_id,
                'distributor_id' : distributor_id,
                'pay_id'         : pay_id,
                'amount'         : amount,
                'discount'       : discount,
                'entry_date'     : entry_date,
                'amt_type'       : amt_type,
                'description'    : description,
                'bank_name'      : bank_name,
                'cheque_no'      : cheque_no,
                'collect_date'   : collect_date,
                'penalty_amt'    : penalty_amt,
                'bank_charge'    : bank_charge,
                'cheque_status'  : cheque_status,
                'method'         : method_val,
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
            dataType: 'json',
        }).done(function (response)
        {
            if(response['status'] == 1)
            {
                

                var title = "";
                var text  = response['message'];
                _success(title,text);

                window.location = response['redirect']; 
                // location.reload();
            }
            else
            {
                var title = "";
                var text  = response['message'];
                _error(title,text);

                location.reload();
                // window.location = response['redirect'];
            }
        });
    });

    $(document).on('click','.outlet_payment', function () {

        var value         = $(this).attr("data-value");
        var cntrl         = $(this).attr("data-cntrl");
        var func          = $(this).attr("data-func");
        var assign_id     = $(this).attr("data-id");
        var auto_id       = $(this).attr("data-auto");
        var method_val    = $(this).attr("data-method");
        var pay_id        = $('.pay_id').val();
        var amount        = $('.amount').val();
        var discount      = $('.discount').val();
        var amt_type      = $('.amt_type').val();
        var description   = $('.description').val();
        var entry_date    = $('.entry_date').val();
        var bank_name     = $('.bank_name').val();
        var cheque_no     = $('.cheque_no').val();
        var collect_date  = $('.collect_date').val();
        var penalty_amt   = $('.penalty_amt').val();
        var bank_charge   = $('.bank_charge').val();
        var cheque_status = $('.cheque_status').val();

        $.ajax({
            method: 'POST',
            data: {
                'auto_id'       : auto_id,
                'assign_id'     : assign_id,
                'pay_id'        : pay_id,
                'amount'        : amount,
                'discount'      : discount,
                'amt_type'      : amt_type,
                'description'   : description,
                'entry_date'    : entry_date,
                'bank_name'     : bank_name,
                'cheque_no'     : cheque_no,
                'collect_date'  : collect_date,
                'penalty_amt'   : penalty_amt,
                'bank_charge'   : bank_charge,
                'cheque_status' : cheque_status,
                'method'        : method_val,
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
            dataType: 'json',
        }).done(function (response)
        {
            if(response['status'] == 1)
            {
                var title = "";
                var text  = response['message'];
                _success(title,text);
                
                window.location = response['redirect']; 
            }
            else
            {
                var title = "";
                var text  = response['message'];
                _error(title,text);
            }
        });
    });
    
    $(document).on('click', '.subadmin_order_update', function () {
        var val   = $(this).data('id');
        var qty   = $('.qty_'+val).val();
        var value = $(this).data('value');
        var cntrl = $(this).data('cntrl');
        var func  = $(this).data('func');

        $.ajax({
            method: 'POST',
            data: {
                'id'   : val,
                'qty'  : qty,
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_subadminOrderlist',
            dataType: 'json',
        }).done(function (response) 
        {
            if(response['status'] == 1)
            {
                var title = "";
                var text  = response['message'];
                _success(title,text);
                location.reload(); 
            }
            else
            {
                var title = "";
                var text  = response['message'];
                _error(title,text);
            }
        });
    });

    // Get Bill Details
    if($("#pay_id").length)
    {
        $('#pay_id').on('change',function(){
            var pay_id  = $(this).val();

            var value    = $('#value').val();
            var cntrl    = $('#cntrl').val();
            var func     = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'pay_id' : pay_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getBill_detail',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#amount').empty('').val(response['data']['bal_amt']);
                }
            });

        });
    }

    // Assign Shop
    if($(".invoice_list").length)
    {
        $('.invoice_list').on('click',function(){
            // _SAL();
            var employee_id = $('#employee_id').val();
            var month_id    = $('#month_id').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'employee_id' : employee_id,
                    'month_id'    : month_id,
                    'method'      : 'getMonthData',
                },
                dataType: 'json',
            }).done(function (response)
            {
                // _SAR();

                if(response['status'] == 1)
                {
                    $('.assign_value').removeClass('hide').addClass('show');
                    $('.submit_btn').removeClass('hide').addClass('show');

                    $('#assign_error').removeClass('show').addClass('hide');
                    $('#getShopList').empty().html(response['data']);
                    $('.order_id').select2(
                        {
                            placeholder: "Select Value",
                            tags:false
                        }                        
                    );

                    $('#month_val').empty().val(response['month_val']);
                    $('#year_val').empty().val(response['year_val']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        });
    }

    // Product Details
    if($(".product_id").length)
    {
        $(".product_id").on("change",function(){
            var $row           = $(this).closest("tr");
            var distributor_id = $('#distributor_id').val();
            var product_id     = $(this).val();
            var value          = $('#value').val();
            var cntrl          = $('#cntrl').val();
            var func           = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'distributor_id' : distributor_id,
                    'product_id'     : product_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getDistributorProduct_details',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $row.find(".dis_unit_id").empty().html(response['data']);
                    $row.find(".dis_product_price").empty().val(response['price']);
                    $row.find(".dis_price_val").empty().val(response['price']);
                    $row.find(".dis_product_qty").empty().val(response['qty']);
                }
            });
        });
    }

    // Distributor Price List
    if($(".distributor_price_list").length)
    {
        $('.distributor_price_list').on('click',function(){
            // _PAL();
            var distributor_id = $('#distributor_id').val();
            var category_id    = $('#category_id').val();
            var sub_cat_id     = $('#sub_cat_id').val();
            var value          = $(this).data('value');
            var cntrl          = $(this).data('cntrl');
            var func           = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'distributor_id' : distributor_id,
                    'sub_cat_id'     : sub_cat_id,
                    'category_id'    : category_id,
                    'method'         : 'getDistributorPriceList',
                },
                dataType: 'json',
            }).done(function (response)
            {
                // _PAR();

                if(response['status'] == 1)
                {
                    $('.price_value').removeClass('hide').addClass('show');
                    $('.submit_btn').removeClass('hide').addClass('show');

                    $('#price_error').removeClass('show').addClass('hide');
                    $('#getPriceList').empty().html(response['data']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        });
    }

    // Outlet Price List
    if($(".outlet_price_list").length)
    {
        $('.outlet_price_list').on('click',function(){
            // _PAL();
            var outlet_id   = $('#outlet_id').val();
            var category_id = $('#category_id').val();
            var sub_cat     = $('#sub_cat_id').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'outlet_id'   : outlet_id,
                    'category_id' : category_id,
                    'sub_cat' : sub_cat,
                    'method'      : 'getOutletPriceList',
                },
                dataType: 'json',
            }).done(function (response)
            {
                // _PAR();

                if(response['status'] == 1)
                {
                    $('.price_value').removeClass('hide').addClass('show');
                    $('.submit_btn').removeClass('hide').addClass('show');

                    $('#price_error').removeClass('show').addClass('hide');
                    $('#getPriceList').empty().html(response['data']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        });
    }

    // Purchase Order
    if($(".add_dis_purchase").length)
    {
        $('.add_dis_purchase').on('click',function(){

            var rowCount  = $('.additemform tr').length;
            var row_count = $('#row_count').val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            if(row_count)
            {
                npage = row_count;
            } 
            else
            {
                npage = 1;
            }

            $.ajax({
                method: 'POST',
                data: {
                    'rowCount'  : npage,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getDistributorPurchase_row',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.additemform').append(response['data']);
                    $('#row_count').empty().val(response['count']);
                }
            });
        });
    }

    $(document).on('change','.msme_type', function () {
        var msme_type = $(this).val();

        if(msme_type == '2')
        {
            $('.getCertificate').removeClass('show').addClass('hide');
        }
        else
        {
            $('.getCertificate').removeClass('hide').addClass('show');   
        }
    });

    $(document).on('change','.einv_status', function () {
        var einv_status = $(this).val();

        if(einv_status == '2')
        {
            $('.getAccessCode').removeClass('show').addClass('hide');
        }
        else
        {
            $('.getAccessCode').removeClass('hide').addClass('show');   
        }
    });

    $(document).on('change','.msme_status', function () {
        var msme_status = $(this).val();

        if(msme_status == '2')
        {
            $('.getMsmeNumber').removeClass('show').addClass('hide');
        }
        else
        {
            $('.getMsmeNumber').removeClass('hide').addClass('show');   
        }
    });

    $("#id2").click(function (e) {
        $("#id1").css('visibility','hidden');
        $("#id3").css('visibility','hidden');
        $("#id2").css('visibility','visible');
    });


    $('#grade_id').on('change', function () {
        var grade_id = $(this).val();
        
        if(grade_id == 'RSM'){
            $('.rsm').removeClass('hide').addClass('show');
            $('.asm').removeClass('show').addClass('hide');
            $('.so').removeClass('show').addClass('hide');
            $('.tsi').removeClass('show').addClass('hide'); 
            $('.aso').removeClass('show').addClass('hide');
            $('.bde').removeClass('show').addClass('hide');
        }else if(grade_id == 'ASM'){
            $('.rsm').removeClass('show').addClass('hide');
            $('.asm').removeClass('hide').addClass('show');
            $('.so').removeClass('show').addClass('hide');
            $('.tsi').removeClass('show').addClass('hide');
            $('.aso').removeClass('show').addClass('hide');
            $('.bde').removeClass('show').addClass('hide'); 
        }else if(grade_id == 'SO'){
            $('.rsm').removeClass('show').addClass('hide');
            $('.asm').removeClass('hide').addClass('show');
            $('.so').removeClass('hide').addClass('show');
            $('.tsi').removeClass('show').addClass('hide');
            $('.aso').removeClass('show').addClass('hide'); 
            $('.bde').removeClass('show').addClass('hide');
        } else if(grade_id == 'TSI')
        {   
            $('.aso').removeClass('hide').addClass('show');
            $('.rsm').removeClass('show').addClass('hide');
            $('.asm').removeClass('hide').addClass('show');
            $('.so').removeClass('show').addClass('hide');
            $('.tsi').removeClass('hide').addClass('show'); 
            $('.bde').removeClass('show').addClass('hide');   
        }else if(grade_id == ''){
            $('.rsm').removeClass('show').addClass('hide');
            $('.asm').removeClass('hide').addClass('show');
            $('.so').removeClass('show').addClass('hide');
            $('.tsi').removeClass('show').addClass('hide');
            $('.bde').removeClass('show').addClass('hide');
            $('.aso').removeClass('show').addClass('hide');

        }
        else if(grade_id == 'BDE'){
            $('.rsm').removeClass('show').addClass('hide');
            $('.asm').removeClass('hide').addClass('show');
            $('.so').removeClass('show').addClass('hide');
            $('.tsi').removeClass('show').addClass('hide');
            $('.bde').removeClass('hide').addClass('show');
            $('.aso').removeClass('hide').addClass('show');
        }
    });
      
      
    // });
    // Report Module
    // *****************************************************************

    if($("#state_c_id").length)
    {
        $('#state_c_id').on('change',function(){

            // alert('1111');

            var state_id   = $(this).val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                   
                    'state_id'  : state_id,
                    'method'    : 'getCityname'
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                { 
                    $('#city_z_id').empty('').html(response['data']);
                    $('#zone_z_id').empty('');
                    
                   
                }else{
                    $('#city_z_id').empty('');
                    $('#zone_z_id').empty('');
                }
                
            });
        });
    }
    if($("#city_z_id").length)
    {
        $('#city_z_id').on('change',function(){

            // alert('1111');

            var city_id   = $(this).val();
            var state_id = $('#state_c_id').val(); 
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                   
                    'state_id'  : state_id,
                    'city_id'  : city_id,
                    'method'    : 'getZonename'
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#zone_z_id').empty('').html(response['data']);
                    
                   
                }
            });
        });
    }
    
        // $('#password').on('input', function() {
           
        //   var password = $(this).val();
        //   var regex = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[A-Z])(?=.*[a-z]).{8,}$/
      
        //   if (!regex.test(password)) {
            
        //     $('#password-error').text('Password must contain atleast 8 characters, 1 uppercase, 1 numeric value, and 1 special character.');
        //   } else {
           
        //     $('#password-error').text('');
        //   }
        // });

        // $("#password").change(function () {      
        //     var inputvalues = $(this).val();      
        //     var reg = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[A-Z])(?=.*[a-z]).{8,}$/;    
        //                   if (inputvalues.match(reg)) {
                                
        //                       return true;    
        //                   }    
        //                   else 
        //                   {    
        //                        $(".password").val("");    
                             
        //                       var title ='';  
        //                       var text  = ["Password must contain atleast 8 characters, 1 uppercase, 1 numeric value, and 1 special character."];
        //                       _error(title,text);    
                             
        //                       return false;    
        //                   }    
        //   }); 
     // hierarchy
     if($(".hierarchy_list").length)
     {
         $('.hierarchy_list').on('click',function(){
 
             _RPTL()
             var state_c_id  = $('#state_c_id').val();
             var city_z_id    = $('#city_z_id').val();
             var zone_z_id = $('#zone_z_id').val();
             var method      = $('#method').val();
             var value       = $(this).data('value');
             var cntrl       = $(this).data('cntrl');
             var func        = $(this).data('func');
 
             $.ajax({
                 type : 'POST',
                 url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                 data : {
                     'state_c_id'  : state_c_id,
                     'city_z_id'    : city_z_id,
                     'zone_z_id' : zone_z_id,
                     'method'      : method,
                 },
                 dataType: 'json',
             }).done(function (response)
             {
                 _RPTR()
                 if(response['status'] == 1)
                 {
                     $('.hierarchy_value').removeClass('hide').addClass('show');
                     $('#hierarchy_error').removeClass('show').addClass('hide');
                     $('#getHierarchyList').empty().html(response['data']);
                     $('#rsm').empty().val(response['rsm']);
                     $('#asm').empty().val(response['asm']);
                     $('#so').empty().val(response['so']);
                    //  $('.excel_val').empty().html(response['excel_btn']);
                     // $('.pdf_val').empty().html(response['pdf_btn']);
 
                     var title = "";
                     var text  = response['message'];
                     _success(title,text);
                 }
                 else
                 {
                     var title = "";
                     var text  = response['message'];
                     _error(title,text);
 
                     $('.hierarchy_value').removeClass('show').addClass('hide');
                     $('#hierarchy_error').removeClass('hide').addClass('show');
                     $('#getHierarchyList').empty();
                     $('#rsm').empty();
                     $('#asm').empty();
                     $('#so').empty();
                    //  $('.excel_val').empty();
                     // $('.pdf_val').empty();
                 }
             });
         });
     }


    // Report Module
    // *****************************************************************

    // Attendace Report
    if($(".attendance_report").length)
    {
        $('.attendance_report').on('click',function(){

            _RPTL()
            var start_date  = $('#start_date').val();
            var end_date    = $('#end_date').val();
            var employee_id = $('#employee_id').val();
            var position_id = $('#position_id').val();
            var method      = $('#method').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date'  : start_date,
                    'end_date'    : end_date,
                    'position_id' : position_id,
                    'employee_id' : employee_id,
                    'method'      : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR()
                if(response['status'] == 1)
                {
                    $('.attendance_value').removeClass('hide').addClass('show');
                    $('#attendance_error').removeClass('show').addClass('hide');
                    $('#getAttendanceList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.attendance_value').removeClass('show').addClass('hide');
                    $('#attendance_error').removeClass('hide').addClass('show');
                    $('#getAttendanceList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    } 

    // Outlet Order Report
    if($(".outletOrder_report").length)
    {
        $('.outletOrder_report').on('click',function(){

            _ARLD();
         
            // $('.Loading').removeClass('hide').addClass('show');
            // $('.Normal').removeClass('show').addClass('hide');
            var state_id   = $('#state_id').val();
            var city_id    = $('#city_id').val();
            var zone_id    = $('#zone_id').val();
            var start_date = $('#start_date').val();
            var end_date   = $('#end_date').val();
            var order_by   = $('#order_by').val();
            var method     = $('#method').val();
            var value      = $(this).data('value');
            var cntrl      = $(this).data('cntrl');
            var func       = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'state_id'   : state_id,
                    'city_id'    : city_id,
                    'zone_id'    : zone_id,
                    'start_date' : start_date,
                    'end_date'   : end_date,
                    'order_by'   : order_by,
                    'method'     : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _ARRD();
                // _LoadingFinish();
                if(response['status'] == 1)
                {
                    $('.order_value').removeClass('hide').addClass('show');
                    $('#order_error').removeClass('show').addClass('hide');
                    $('#getOrderList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);
                    // $('.Normal').removeClass('hide').addClass('show');
                    // $('.Loading').removeClass('show').addClass('hide');
                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                    $('.Normal').removeClass('hide').addClass('show');
                    $('.Loading').removeClass('show').addClass('hide');
                    $('.order_value').removeClass('show').addClass('hide');
                    $('#order_error').removeClass('hide').addClass('show');
                    $('#getOrderList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }
   // Outlet Stock Report
    if($(".outletstock_report").length)
    {
        $('.outletstock_report').on('click',function(){

            _ARLDSK();
        
            // $('.Loading').removeClass('hide').addClass('show');
            // $('.Normal').removeClass('show').addClass('hide');
            var year_id   = $('#year_id').val();
            var month_id    = $('#month_id').val();
            var state_id   = $('#state_id').val();
            var city_id    = $('#city_id').val();
            var zone_id       = $('#zone_id').val();
            var outlet_id     = $('#outlet_id').val();
            var category_id   = $('#category_id').val();
            var sub_cat_id =$('#sub_cat_id').val();
            var type_id    = $('#type_id').val();
            var method     = $('#method').val();
            var value      = $(this).data('value');
            var cntrl      = $(this).data('cntrl');
            var func       = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'state_id'   : state_id,
                    'city_id'    : city_id,
                    'zone_id'    : zone_id,
                    'month_id'   : month_id,
                    'year_id'    : year_id,
                    'outlet_id'  : outlet_id,
                    'category_id': category_id,
                    'sub_cat_id' : sub_cat_id,
                    'type_id'    : type_id,
                    'method'     : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _ARRDSK();
                // _LoadingFinish();
                if(response['status'] == 1)
                {
                    $('.order_value').removeClass('hide').addClass('show');
                    $('#order_error').removeClass('show').addClass('hide');
                    $('#getOrderList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);
                    // $('.Normal').removeClass('hide').addClass('show');
                    // $('.Loading').removeClass('show').addClass('hide');
                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                    $('.Normal').removeClass('hide').addClass('show');
                    $('.Loading').removeClass('show').addClass('hide');
                    $('.order_value').removeClass('show').addClass('hide');
                    $('#order_error').removeClass('hide').addClass('show');
                    $('#getOrderList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }
    // Vendor Purchas Report Report
    if($(".vendor_purchase").length)
    {
        $('.vendor_purchase').on('click',function(){

            _RPTL();
            var start_date  = $('#start_date').val();
            var end_date    = $('#end_date').val();
            var vendor_id   = $('#vendor_id').val();
            var product_id  = $('#product_id').val();
            var type_id     = $('#type_id').val();
            var method      = $('#method').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date' : start_date,
                    'end_date'   : end_date,
                    'vendor_id'  : vendor_id,
                    'product_id' : product_id,
                    'type_id'    : type_id,
                    'method'     : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.vendorPurchase_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getVendorPurchaseList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.vendorPurchase_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getVendorPurchaseList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Vendor Overall Report Report
    if($(".vendorOverall_report").length)
    {
        $('.vendorOverall_report').on('click',function(){

            _RPTL();
            var start_date  = $('#start_date').val();
            var end_date    = $('#end_date').val();
            var state_id    = $('#state_id').val();
            var city_id     = $('#city_id').val();
            var zone_id     = $('#zone_id').val();
            var employee_id = $('#employee_id').val();
            var category_id = $('#category_id').val();
            var vendor_id   = $('#vendor_id').val();
            var method      = $('#method').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date'  : start_date,
                    'end_date'    : end_date,
                    'state_id'    : state_id,
                    'city_id'     : city_id,
                    'zone_id'     : zone_id,
                    'employee_id' : employee_id,
                    'category_id' : category_id,
                    'vendor_id'   : vendor_id,
                    'method'      : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.vendorOverall_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getVendorOverallList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.vendorOverall_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getVendorOverallList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Beat Outlet Report Report
    if($(".beatOutlet_report").length)
    {
        $('.beatOutlet_report').on('click',function(){

            _RPTL();
            var state_id   = $('#state_id').val();
            var city_id    = $('#city_id').val();
            var zone_id    = $('#zone_id').val();
            var status_val = $('#status_val').val();
            var method     = $('#method').val();
            var value      = $(this).data('value');
            var cntrl      = $(this).data('cntrl');
            var func       = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'state_id'   : state_id,
                    'city_id'    : city_id,
                    'zone_id'    : zone_id,
                    'status_val' : status_val,
                    'method'     : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.beatOutlet_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getBeatOutletList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.beatOutlet_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getBeatOutletList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }
 
    // Beat Outlet Report Report
    if($(".outletOverall_report").length)
    {
        $('.outletOverall_report').on('click',function(){

            _RPTL();
            var start_date = $('#start_date').val();
            var end_date   = $('#end_date').val();
            var outlet_id  = $('#outlet_id').val();
            var method     = $('#method').val();
            var value      = $(this).data('value');
            var cntrl      = $(this).data('cntrl');
            var func       = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date' : start_date,
                    'end_date'   : end_date,
                    'outlet_id'  : outlet_id,
                    'method'     : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.outletOverall_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getOutletOverallList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    $('.order_val').empty().html(response['order_btn']);
                    $('.invoice_val').empty().html(response['invoice_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.outletOverall_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getOutletOverallList').empty();
                    $('.excel_val').empty();
                    $('.order_val').empty();
                    $('.invoice_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Beat Outlet Report Report
    if($(".order_report").length)
    {
        $('.order_report').on('click',function(){

            _RPTL();
            var start_date = $('#start_date').val();
            var end_date   = $('#end_date').val();
            var method     = $('#method').val();
            var value      = $(this).data('value');
            var cntrl      = $(this).data('cntrl');
            var func       = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date' : start_date,
                    'end_date'   : end_date,
                    'method'     : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.order_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getOrderList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.order_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getOrderList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Beat Outlet Report Report
    if($(".target_report").length)
    {
        $('.target_report').on('click',function(){

            _RPTL();
            var month_id    = $('#month_id').val();
            var year_id     = $('#year_id').val();
            var employee_id = $('#employee_id').val();
            var view_type   = $('#view_type').val();
            var position_id = $('#position_id').val();
            var method      = $('#method').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'month_id'    : month_id,
                    'year_id'     : year_id,
                    'employee_id' : employee_id,
                    'view_type'   : view_type,
                    'method'      : method,
                    'position_id' : position_id
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.target_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getTargetList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.target_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getTargetList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Outlet History Report Report
    if($(".outletHistory_report").length)
    {
        $('.outletHistory_report').on('click',function(){

            _RPTL();
            var outlet_id = $('#outlet_id').val();
            var method    = $('#method').val();
            var value     = $(this).data('value');
            var cntrl     = $(this).data('cntrl');
            var func      = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'outlet_id' : outlet_id,
                    'method'    : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.target_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getOutletHistoryDetails').empty().html(response['data']);
                    // $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.target_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getOutletHistoryDetails').empty();
                    // $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Beat Outlet Report Report
    if($(".employee_report").length)
    {
        $('.employee_report').on('click',function(){

            _RPTL();
            var month_id    = $('#month_id').val();
            var year_id     = $('#year_id').val();
            var employee_id = $('#employee_id').val();
            var method      = $('#method').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'month_id'    : month_id,
                    'year_id'     : year_id,
                    'employee_id' : employee_id,
                    'method'      : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.employee_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getOrderList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.employee_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getOrderList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Beat Outlet Report Report
    if($(".productStock_report").length)
    {
        $('.productStock_report').on('click',function(){

            _RPTL();
            var category_id = $('#category_id').val();
            var sub_cat_id = $('#sub_cat_id').val();
            var method      = $('#method').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'sub_cat_id'  : sub_cat_id,
                    'category_id' : category_id,
                    'method'      : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.productStock_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getProductStockList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.productStock_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getProductStockList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Beat Outlet Report Report
    if($(".stockEntry_report").length)
    {
        $('.stockEntry_report').on('click',function(){

            _RPTL();
            var start_date = $('#start_date').val();
            var end_date   = $('#end_date').val();
            var method     = $('#method').val();
            var value      = $(this).data('value');
            var cntrl      = $(this).data('cntrl');
            var func       = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date' : start_date,
                    'end_date'   : end_date,
                    'method'     : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.stock_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getStockList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.stock_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getStockList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Beat Outlet Report Report
    if($(".inventory_report").length)
    {
        $('.inventory_report').on('click',function(){

            _RPTL();
            var start_date = $('#start_date').val();
            var end_date   = $('#end_date').val();
            var method     = $('#method').val();
            var value      = $(this).data('value');
            var cntrl      = $(this).data('cntrl');
            var func       = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date' : start_date,
                    'end_date'   : end_date,
                    'method'     : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.inventory_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getInventoryList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.inventory_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getInventoryList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }


    // Category Details
    if($(".distributor_id").length)
    {
        $(document).on('change','.distributor_id', function () {
            var distributor_id = $(this).val();
            var value       = $('#value').val();
            var cntrl       = $('#cntrl').val();
            var func        = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'distributor_id' : distributor_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getCategotry_list',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.category_id').empty('').html(response['data']);

                }
            });
        });
    }

    if($(".category_id").length)
    {
        $(document).on('change','.category_id', function () {
            var category_id = $('#category_id').val();
            var distributor_id = $('#distributor_id').val();
            var value       = $('#value').val();
            var cntrl       = $('#cntrl').val();
            var func        = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'distributor_id' : distributor_id,
                    'category_id'    : category_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getSubCategotry_list',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.sub_cat_id').empty('').html(response['data']);
                   
                }
            });
        });
    }
    if($(".disPdtStock_report").length)
    {
        $('.disPdtStock_report').on('click',function(){

            _RPTL();
            var distributor_id = $('#distributor_id').val();
            var category_id    = $('#category_id').val();
             var sub_cat_id    = $('#sub_cat_id').val();
            var method         = $('#method').val();
            var value          = $(this).data('value');
            var cntrl          = $(this).data('cntrl');
            var func           = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'distributor_id' : distributor_id,
                    'sub_cat_id'    : sub_cat_id,
                    'category_id'    : category_id,
                    'method'         : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.disPdtStock_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getDisProductStockList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.disPdtStock_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getDisProductStockList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Attendace Report
    if($(".expense_report").length)
    {
        $('.expense_report').on('click',function(){

            _RPTL();
            var start_date  = $('#start_date').val();
            var end_date    = $('#end_date').val();
            var method      = $('#method').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date' : start_date,
                    'end_date'   : end_date,
                    'method'     : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.expense_value').removeClass('hide').addClass('show');
                    $('#expense_error').removeClass('show').addClass('hide');
                    $('#getExpenseList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.expense_value').removeClass('show').addClass('hide');
                    $('#expense_error').removeClass('hide').addClass('show');
                    $('#getExpenseList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Beat Outlet Report Report
    if($(".empTarget_report").length)
    {
        $('.empTarget_report').on('click',function(){
            
            _RPTL();
            var month_id    = $('#month_id').val();
            var year_id     = $('#year_id').val();
            var employee_id = $('#employee_id').val();
            var method      = $('#method').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'month_id'    : month_id,
                    'year_id'     : year_id,
                    'employee_id' : employee_id,
                    'method'      : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.target_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getTargetDetails').empty().html(response['data']);
                    // $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.target_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getTargetDetails').empty();
                    // $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Beat Outlet Report Report
    if($(".empDaily_report").length)
    {
        $('.empDaily_report').on('click',function(){
            
            _RPTL();
            var sel_date    = $('#sel_date').val();
            var employee_id = $('#employee_id').val();
            var method      = $('#method').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'sel_date'    : sel_date,
                    'employee_id' : employee_id,
                    'method'      : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.target_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getEmployeeRptDetails').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.target_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getEmployeeRptDetails').empty();
                    // $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // *****************************************************************

    $(document).on('click','.process_bth',function(){
        var value    = $(this).data('value');
        var cntrl    = $(this).data('cntrl');
        var func     = $(this).data('func');
        var order_id = $(this).data('id');
        var method   = $(this).data('method');

        $.ajax({
            type : 'POST',
            url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
            data : {
                'order_id' : order_id,
                'method'   : method,
            },
            dataType: 'json',
        }).done(function (response)
        {
            if(response['status'] == 1)
            {
                location.reload(); 
                
                var title = "";
                var text  = response['message'];
                _success(title,text);
            }
            else
            {
                var title = "";
                var text  = response['message'];
                _error(title,text);
            }
        });
    });

    $(document).on('click','.pack_btn',function(){
       var value     = $(this).data('value');
        var cntrl    = $(this).data('cntrl');
        var func     = $(this).data('func');
        var order_id = $(this).data('id');
        var method   = $(this).data('method');

        $.ajax({
            type : 'POST',
            url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_changePackStatus',
            data : {
                'order_id' : order_id,
            },
            dataType: 'json',
        }).done(function (response)
        {
            if(response['status'] == 1)
            {
                location.reload(); 
                
                var title = "";
                var text  = response['message'];
                _success(title,text);
            }
            else
            {
                var title = "";
                var text  = response['message'];
                _error(title,text);
            }
        });
    });

    $(document).on('change','.bill_type',function(){
        var option = $('.bill_type').val();

        if(option == 1)
        {
            $('.discount_view').removeClass('hide').addClass('show');
            $('.due_view').removeClass('show').addClass('hide');
        }
        else
        {
            $('.due_view').removeClass('hide').addClass('show');
            $('.discount_view').removeClass('show').addClass('hide');
        }
    });


    // Assign Shop
    if($(".target_list").length)
    {
        $('.target_list').on('click',function(){
            // _TAL();
            var month_id = $('#month_id').val();
            var year_id  = $('#year_id').val();
            var value    = $(this).data('value');
            var cntrl    = $(this).data('cntrl');
            var func     = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'month_id' : month_id,
                    'year_id'  : year_id,
                    'method'   : 'getEmployeeData',
                },
                dataType: 'json',
            }).done(function (response)
            {
                // _TAR();

                if(response['status'] == 1)
                {
                    $('.assign_value').removeClass('hide').addClass('show');
                    $('.submit_btn').removeClass('hide').addClass('show');

                    $('#assign_error').removeClass('show').addClass('hide');
                    $('#getTargetList').empty().html(response['data']);
                    

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        });
    }

    // $( "#getListValue" ).sortable({
    //     placeholder : "ui-state-highlight",
    //     update  : function(event, ui)
    //     {
    //         var n_menu      = $('.n_menu').val();
    //         var section_ids = new Array();
    //         $('#getListValue tr').each(function(){
    //             section_ids.push($(this).data("section-id"));
    //         });
    //         $.ajax({
    //             url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/sorting',
    //             method:"POST",
    //             dataType: 'json',
    //             data:{
    //                 'n_menu'      : n_menu,
    //                 'section_ids' : section_ids
    //             },
    //             success:function(response)
    //             {
    //                 var _tab = '#o-message';

    //                 if(response['status'] == 1)
    //                 {
    //                     $(_tab).empty();
    //                     $(_tab).removeClass().addClass('alert alert-success text-left me-8 p-1').html(response['message']);
    //                     setTimeout(function() {
    //                         $(_tab).empty().removeClass();
    //                     }, 5000);


    //                 }
    //                 else
    //                 {
    //                     $(_tab).removeClass().addClass('alert alert-danger text-left me-8 p-1');
    //                     $(_tab).html(response['message']);
    //                     setTimeout(function() {
    //                         $(_tab).empty().removeClass();
    //                     }, 5000);
    //                 }
    //             }
    //         });
    //     },
    //     stop: function(event, ui) {
    //         countRows();
    //     }
    // });
    // Outlet Order Report
    if($(".sales_report").length)
    {
        $('.sales_report').on('click',function(){

            _RPTL();
            var start_date     = $('#start_date').val();
            var end_date       = $('#end_date').val();
            var distributor_id = $('#distributor_id').val();
            var method         = $('#method').val();
            var value          = $(this).data('value');
            var cntrl          = $(this).data('cntrl');
            var func           = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date'     : start_date,
                    'end_date'       : end_date,
                    'distributor_id' : distributor_id,
                    'method'         : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.sales_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getTotalCountDetails').empty().html(response['count_val']);
                    $('#getSalesList').empty().html(response['data']);
                    $('.sales_export').empty().html(response['sales_btn']);
                    $('.tally_export').empty().html(response['tally_btn']);
                    $('.commission_export').empty().html(response['commission_btn']);
                    $('.invoice_export').empty().html(response['invoice_btn']);
                    $('.xml_export').empty().html(response['xml_btn']);
                    $('.gst_export').empty().html(response['gst_btn']);
                    $('.new_export').empty().html(response['new_btn']);
                    $('.cancel_export').empty().html(response['cancel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.sales_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getTotalCountDetails').empty();
                    $('#getSalesList').empty();
                    $('.sales_export').empty();
                    $('.tally_export').empty();
                    $('.commission_export').empty();
                    $('.invoice_export').empty();
                    $('.xml_export').empty();
                    $('.gst_export').empty();
                    $('.new_export').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Outlet Order Report
    if($(".distributorOverall_report").length)
    {
        $('.distributorOverall_report').on('click',function(){

            _RPTL();
            var start_date     = $('#start_date').val();
            var end_date       = $('#end_date').val();
            var distributor_id = $('#distributor_id').val();
            var method         = $('#method').val();
            var value          = $(this).data('value');
            var cntrl          = $(this).data('cntrl');
            var func           = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date'     : start_date,
                    'end_date'       : end_date,
                    'distributor_id' : distributor_id,
                    'method'         : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.overall_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getOverallList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.overall_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getOverallList').empty();
                    $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    $(document).on('keydown', '.int_value', function () {
       
        if (event.shiftKey == true) {
            event.preventDefault();
        }

        if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

        } else {
            event.preventDefault();
        }
        
        if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
            event.preventDefault();
    });

    $(document).ready(function(){
        $('#dis_code').keyup(function(){
            $(this).val($(this).val().toUpperCase());
        });
    });

    
    $(".int_value").on("input", function() {
       
        if (/^0/.test(this.value)) {
          this.value = this.value.replace(/^0/, "")
        }
        // if(event.charCode == 0) {
        //     event.preventDefault();
        // }
      })
      $(".toggle-password").click(function () {
        $(this).toggleClass("la-eye la-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

      $(".toggle-icon").click(function() {
        var passwordField = $("#password");
        var fieldType = passwordField.attr("type");
        
        if (fieldType === "password") {
          passwordField.attr("type", "text");
          $(this).removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
          passwordField.attr("type", "password");
          $(this).removeClass("fa-eye-slash").addClass("fa-eye");
        }
      });
      if($("#pincode").length)
      {
          $('#pincode').on('change',function(){
              var pincode = $(this).val();
              
              var value    = $('#value').val();
              var cntrl    = $('#cntrl').val();
              var func     = $('#func').val();
  
              $.ajax({
                  method: 'POST',
                  data: {
                      'pincode' : pincode,
                  },
                  url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/get_pin',
                  dataType: 'json',
              }).done(function (response)
              {
                  if(response['status'] == 1)
                  {
                      $('#country_name').empty('').val(response['data']['country_name']);
                      $('#state_name').empty('').val(response['data']['c_state']);
                      $('#district_name').empty('').val(response['data']['c_district']);
                      $('#pincode').empty('').val(response['data']['c_pincode']);
                  }else{
                    
                    $(".pincode").val(""); 
                    $('#district_name').val("");
                    $('#state_name').val("");
                    $('#country_name').val("");   
                       
                              var title ='';  
                              var text  = ["You entered invalid Pncode Number"];
                              _error(title,text); 
                             
                              return false;  
                  }
              });
          });
      }
    //   $('#pincode').on('keydown',function(){
        
    //   if (event.which === 8) { // Backspace key code
    //     $('#district_name').val("");
    //     $('#state_name').val("");
    //     $('#country_name').val("");
    //   }
    //   if (event.which === 46) { // Delete key code
    //     $('#district_name').val("");
    //     $('#state_name').val("");
    //     $('#country_name').val("");
    //   }
    // });
      $("#designation_code").on("input", function(event) {
        $(this).val($(this).val().toUpperCase());
        var keyCode = event.which;
         
      });
      $("#designation_code").on("keypress", function(event) {
        var keyCode = event.which;
        if (keyCode === 32) {
            event.preventDefault();
          }
         
      });
      $("#email").on("input", function(event) {
        $(this).val($(this).val().toLowerCase());
      });
      $("#email").on("keypress", function(event) {
        $(this).val($(this).val().toLowerCase());
        var keyCode = event.which;
        var inputValue = String.fromCharCode(keyCode);
       
       
         // Prevent space key (keyCode 32)
         if (keyCode === 32) {
            event.preventDefault();
          }
          var specialChars = "!#$%^&*()+=-[]\\\';,/{}|\":<>?~_";
          if (specialChars.indexOf(inputValue) !== -1) {
            event.preventDefault();
          }
      });
      $("#pan_no").on("input", function(event) {
        $(this).val($(this).val().toUpperCase());
      });
      $("#pan_no").on("keypress", function(event) {
        var keyCode = event.which;
        var inputValue = String.fromCharCode(keyCode);
       
        // Allow only numeric characters (0-9)
        if (!/^[0-9A-Za-z]+$/.test(inputValue)) {
          event.preventDefault();
        }
        
        // Prevent space key (keyCode 32)
        if (keyCode === 32) {
          event.preventDefault();
        }
        
        // Prevent special characters (except space)
        var specialChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?~_";
        if (specialChars.indexOf(inputValue) !== -1) {
          event.preventDefault();
        }
      });
      $('#ifsc_code').on('input', function(e) {
        var inputValue = $(this).val(); // Get the value of the input field
        var sanitizedValue = inputValue.replace(/[^a-zA-Z0-9]/g, ''); // Remove any characters that are not alphabets or numeric
        
        if (inputValue !== sanitizedValue) {
          $(this).val(sanitizedValue); // Update the input field value with the sanitized value
        }
      });
      $('.no_entry').on('keydown', function(event) {
      
          event.preventDefault();
        
      });
      $('#door').on('input', function(e) {
        var inputValue = $(this).val(); // Get the value of the input field
        var sanitizedValue = inputValue.replace(/[^a-zA-Z0-9,.\/]/g, ''); // Remove any characters that are not ",", ".", or "/"
        // var specialCharacters = ['.', ',', '/']; // Array of special characters
        // var specialCharactersCount = 0;
        
        // for (var i = 0; i < inputValue.length; i++) {
        //   if (specialCharacters.indexOf(inputValue[i]) !== -1) {
        //     specialCharactersCount++;
        //   }
        // }
        
        // if (specialCharactersCount > 1) {
        //   sanitizedValue = sanitizedValue.replace(/[.,/]/g, ''); // Remove all occurrences of special characters except the first one
        // }
        if (inputValue !== sanitizedValue) {
          $(this).val(sanitizedValue); // Update the input field value with the sanitized value
        }
      });
    // //   $('#company_name').on('input', function(e) {
    // //     var inputValue = $(this).val(); // Get the value of the input field
    // //     var sanitizedValue = inputValue.replace(/[^a-zA-Z0-9,.\/]/g, ''); // Remove any characters that are not ",", ".", or "/"
    // //     // var specialCharacters = ['.', ',', '/']; // Array of special characters
    //     // var specialCharactersCount = 0;
        
    //     // for (var i = 0; i < inputValue.length; i++) {
    //     //   if (specialCharacters.indexOf(inputValue[i]) !== -1) {
    //     //     specialCharactersCount++;
    //     //   }
    //     // }
        
    //     // if (specialCharactersCount > 1) {
    //     //   sanitizedValue = sanitizedValue.replace(/[.,/]/g, ''); // Remove all occurrences of special characters except the first one
    //     // }
    //     if (inputValue !== sanitizedValue) {
    //       $(this).val(sanitizedValue); // Update the input field value with the sanitized value
    //     }
    //   });

       $(".ifsc_code").change(function () {      
          var inputvalues = $(this).val();      
          var reg = /[A-Z|a-z]{4}[0][a-zA-Z0-9]{6}$/;    
                        if (inputvalues.match(reg)) {
                              
                            return true;    
                        }    
                        else 
                        {    
                             $(".ifsc_code").val("");    
                           
                            var title ='';  
                            var text  = ["You entered invalid IFSC code"];
                            _error(title,text);    
                           
                            return false;    
                        }    
        }); 
        $(".gst_no").change(function () {      
            var inputvalues = $(this).val();      
            var reg =  /^\d{2}[A-Z]{5}\d{4}[A-Z]{1}\d[Z]{1}[A-Z\d]{1}$/;
              
                          if (inputvalues.match(reg)) {
                                
                              return true;    
                          }    
                          else 
                          {    
                               $(".gst_no").val("");    
                       
                              var title ='';  
                              var text  = ["You entered invalid GST Number"];
                              _error(title,text); 
                             
                              return false;    
                          }    
        });
        $(".tan_no").change(function () {      
            var inputvalues = $(this).val();      
            var reg =  /^[A-Z]{4}[0-9]{5}[A-Z]$/ ;
              
                          if (inputvalues.match(reg)) {
                                
                              return true;    
                          }    
                          else 
                          {    
                               $(".tan_no").val("");    
                       
                              var title ='';  
                              var text  = ["You entered invalid Tan Number"];
                              _error(title,text); 
                             
                              return false;    
                          }    
        });
        $(".pan_no").change(function () {      
            var inputvalues = $(this).val();      
            var reg = /^([A-Z]){5}([0-9]){4}([A-Z]){1}?$/;    
                          if (inputvalues.match(reg)) {
                                
                              return true;    
                          }    
                          else 
                          {    
                               $(".pan_no").val("");    
                       
                              var title ='';  
                              var text  = ["You entered invalid Pan Number"];
                              _error(title,text); 
                             
                              return false;    
                          }    
        });
        $(".aadhar_no").change(function () {      
            var inputvalues = $(this).val();      
            var reg = /^\d{12}$/;
             
                          if (reg.test(inputvalues)) {
                                
                              return true;    
                          }    
                          else 
                          {    
                               $(".aadhar_no").val("");    
                       
                              var title ='';  
                              var text  = ["You entered invalid Aadhar Number"];
                              _error(title,text); 
                             
                              return false;    
                          }    
        });
        $("#aadhar_no").on("input", function() {
            var inputValue = $(this).val();
            
            // Replace the first character if it is "1" or "0" with an empty string
            if (inputValue.charAt(0) === "1" || inputValue.charAt(0) === "0") {
              $(this).val(inputValue.substr(1));
            }
          });

        $('#mobile').on('keypress', function(event) {
            var regex = new RegExp("^[0-9]+$"); // Regular expression to allow only numbers
    
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
              event.preventDefault();
              return false;
            }
          });

          $('#account_no').on('keypress', function(event) {
            var regex = new RegExp("^[0-9]+$"); // Regular expression to allow only numbers
    
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
              event.preventDefault();
              return false;
            }
          });
       
        //   $("#mobile").keydown(function(event) {
        //     var firstChar = event.key.charAt(0);
            
        //     if (firstChar === "0" || firstChar === "1" || firstChar === "2" || firstChar === "3" || firstChar === "4" || firstChar === "5") {
             
        //       event.preventDefault();
        //     }
        //   });
  
    $(document).on('keyup', '.received_qty', function () {
        var data_id  = $(this).data('id');
        var received = $('.received_'+data_id).val();
        var balance  = $('.balance_'+data_id).val();

       

        if(balance >= received)
        {
           
        }
        else
        {
            $(".received_"+data_id).val('');
        }
    });

    // Outlet Order Report
    if($(".process_click").length)
    {
        $('.process_click').on('click',function(){

            var value    = $(this).data('value');
            var cntrl    = $(this).data('cntrl');
            var func     = $(this).data('func');
            var value    = $(this).data('value');
            var method   = $(this).data('method');
            var order_id = $(this).data('id');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'order_id' : order_id,
                    'method'   : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    var title = "";
                    var text  = response['message'];
                    _success(title,text);

                    location.reload(); 
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        });
    }

    if($(".del_btn").length)
    {
        $('.del_btn').on('click',function(){

            var value    = $(this).data('value');
            var cntrl    = $(this).data('cntrl');
            var func     = $(this).data('func');
            var value    = $(this).data('value');
            var method   = $(this).data('method');
            var order_id = $(this).data('id');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'order_id' : order_id,
                    'method'   : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    var title = "";
                    var text  = response['message'];
                    _success(title,text);

                    location.reload(); 
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        });
    }

    if($(".add_distributor_return").length)
    {
        $('.add_distributor_return').on('click',function(){

            var rowCount       = $('.additemform tr').length;
            var distributor_id = $('#distributor_id').val();
            var row_count      = $('#row_count').val();
            var value          = $('#value').val();
            var cntrl          = $('#cntrl').val();
            var func           = $('#func').val();

            if(distributor_id != '0')
            {
                if(row_count)
                {
                    npage = row_count;
                } 
                else
                {
                    npage = 1;
                }

                $.ajax({
                    method: 'POST',
                    data: {
                        'rowCount'       : npage,
                        'distributor_id' : distributor_id,
                    },
                    url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getDistributor_row',
                    dataType: 'json',
                }).done(function (response)
                {
                    if(response['status'] == 1)
                    {
                        $('.additemform').append(response['data']);
                        $('#row_count').empty().val(response['count']);
                    }
                });
            }
            else
            {
                var title = "";
                var text  = "Please Select Distributor Name";
                _error(title,text);
            }
        });
    }

    // Outlet Details
    if($("#distributor_id").length)
    {
        $('#distributor_id').on('change',function(){

            var distri_id = $(this).val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'distributor_id' : distri_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_getDistributorInvoice',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#invoice_id').empty('').html(response['data']);
                }
            });
        });
    }

    if($("#distributor_id").length)
    {
        $('#distributor_id').on('change',function(){

            var distri_id = $(this).val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'distributor_id' : distri_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_getDistributorProduct',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.product_id').empty('').html(response['data']);
                }
            });
        });
    }

    // Product Details
    if($(".product_id").length)
    {
        $(document).on('change', '.product_id', function () {

            var $row           = $(this).closest("tr");
            var distributor_id = $('#distributor_id').val();
            var product_id     = $(this).val();
            var value          = $('#value').val();
            var cntrl          = $('#cntrl').val();
            var func           = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'distributor_id' : distributor_id,
                    'product_id'     : product_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getDisProduct_details',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $row.find(".unit_id").empty().html(response['data']);
                    $row.find(".product_price").empty().val(response['price']);
                }
            });
        });
    }

    // Product Details
    if($(".distributor_id").length)
    {
        $(document).on('change', '.distributor_id', function () {

            var distributor_id = $(this).val();
            var value          = $('#value').val();
            var cntrl          = $('#cntrl').val();
            var func           = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'distributor_id' : distributor_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_getDistributorDetails',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#gst_no').empty('').val(response['data']['gst_no']);
                    $('#contact_no').empty('').val(response['data']['contact_no']);
                    $('#address').empty('').val(response['data']['address']);
                }
            });

        }); 
    }   

    // Assign Product Details
    if($(".stock_report").length)
    {
        $(document).on('click', '.stock_report', function () {
            var category_id = $('#category_id').val();
             var sub_cat_id = $('#sub_cat_id').val();
            var value       = $('#value').val();
            var cntrl       = $('#cntrl').val();
            var func        = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'category_id' : category_id,
                     'sub_cat_id'  : sub_cat_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_getAssignProduct',
                dataType: 'json',
            }).done(function (response)
            {
                // _ARR();
                if(response['status'] == 1)
                {
                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                    
                    $('.stock_value').removeClass('hide').addClass('show');
                    $('#stock_error').removeClass('show').addClass('hide');
                    $('#getStockList').empty().html(response['data']);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.stock_value').removeClass('show').addClass('hide');
                    $('#stock_error').removeClass('hide').addClass('show');
                    $('#getStockList').empty();
                }   
            });
        });
    }

    // Assign Product Details
    if($(".admin_stock_entry").length)
    {
        $(document).on('click', '.admin_stock_entry', function () {
            var category_id = $('#category_id').val();
            var value       = $('#value').val();
            var cntrl       = $('#cntrl').val();
            var func        = $('#func').val();

            $.ajax({
                method: 'POST',
                data: {
                    'category_id' : category_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_getProductList',
                dataType: 'json',
            }).done(function (response)
            {
                // _ARR();
                if(response['status'] == 1)
                {
                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                    
                    $('.stock_value').removeClass('hide').addClass('show');
                    $('#stock_error').removeClass('show').addClass('hide');
                    $('#getStockList').empty().html(response['data']);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.stock_value').removeClass('show').addClass('hide');
                    $('#stock_error').removeClass('hide').addClass('show');
                    $('#getStockList').empty();
                }   
            });
        });
    }

    // Assign Shop
    if($(".product_list").length)
    {
        $('.product_list').on('click',function(){
            // _TAL();
            var month_id    = $('#month_id').val();
            var year_id     = $('#year_id').val();
            var category_id = $('#category_id').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'month_id'    : month_id,
                    'year_id'     : year_id,
                    'category_id' : category_id,
                    'method'      : 'getProductData',
                },
                dataType: 'json',
            }).done(function (response)
            {
                // _TAR();

                if(response['status'] == 1)
                {
                    $('.assign_value').removeClass('hide').addClass('show');
                    $('.submit_btn').removeClass('hide').addClass('show');

                    $('#assign_error').removeClass('show').addClass('hide');
                    $('#getProductList').empty().html(response['data']);
                    

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        });
    }


    // Average sales details
    if($(".average_list").length)
    {
        $('.average_list').on('click',function(){
            // _TAL();
            var month_id    = $('#month_id').val();
            var year_id     = $('#year_id').val();
            var category_id = $('#category_id').val();
            var employee_id = $('#employee_id').val();
            var setaveragenxt = $('#setaveragenxt').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            // console.log(month_id);
            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'month_id'    : month_id,
                    'year_id'     : year_id,
                    'category_id' : category_id,
                    'employee_id' : employee_id,
                    'setaveragenxt' : setaveragenxt,
                    'method'      : 'getaverageData',
                },
                dataType: 'json',
            }).done(function (response)
            {

                if(response['status'] == 1)
                {
                    $('.assign_value').removeClass('hide').addClass('show');
                    $('.submit_btn').removeClass('hide').addClass('show');

                    $('#assign_error').removeClass('show').addClass('hide');
                    $('#getProductList').empty().html(response['data']);
                    
                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        });
    }

    // Assign Shop
    if($(".beat_list").length)
    {
        $('.beat_list').on('click',function(){
            // _TAL();
            var month_id    = $('#month_id').val();
            var year_id     = $('#year_id').val();
            var employee_id = $('#employee_id').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'month_id'    : month_id,
                    'year_id'     : year_id,
                    'employee_id' : employee_id,
                    'method'      : 'getBeatData',
                },
                dataType: 'json',
            }).done(function (response)
            {
                // _TAR();

                if(response['status'] == 1)
                {
                    $('.assign_value').removeClass('hide').addClass('show');
                    $('.submit_btn').removeClass('hide').addClass('show');

                    $('#assign_error').removeClass('show').addClass('hide');
                    $('#getBeatList').empty().html(response['data']);
                    

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        });
    }
        
    if($(".add_expense").length)
    {
        $('.add_expense').on('click',function(){
            var rowCount    = $('.additemform tr').length;
            var row_count   = $('#row_count').val();
            var value       = $('#value').val();
            var cntrl       = $('#cntrl').val();
            var func        = $('#func').val();

            if(row_count)
            {
                npage = row_count;
            } 
            else
            {
                npage = 1;
            }

            $.ajax({
                method: 'POST',
                data: {
                    'rowCount'    : npage,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getExpense_row',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.addExpenseform').append(response['data']);
                    $('#row_count').empty().val(response['count']);
                }
            });
                
        });
    }

    // Purchase Order
    if($(".add_dis_inventory").length)
    {
        $('.add_dis_inventory').on('click',function(){

            var rowCount  = $('.additemform tr').length;
            var row_count = $('#row_count').val();
            var value     = $('#value').val();
            var cntrl     = $('#cntrl').val();
            var func      = $('#func').val();

            if(row_count)
            {
                npage = row_count;
            } 
            else
            {
                npage = 1;
            }

            $.ajax({
                method: 'POST',
                data: {
                    'rowCount'  : npage,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getDistributorInventory_row',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.additemform').append(response['data']);
                    $('#row_count').empty().val(response['count']);
                }
            });
        });
    }

    $(document).on('change', '.year_id, .month_id', function () {
        var month_id = $('.month_id').val();
        var year_id  = $('.year_id').val();
        var value    = $('#value').val();
        var cntrl    = $('#cntrl').val();
        var func     = $('#func').val();

        if(month_id != '')
        {   
            $.ajax({
                method: 'POST',
                data: {
                    'month_id' : month_id,
                    'year_id'  : year_id,
                    'method'   : '_getEmployeeList',
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#employee_id').empty('').html(response['data']);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        }
        else
        {
            var title = "";
            var text  = 'Please Select The Month Name';
            _error(title,text);
        }
    });

    // Vendor Overall Report Report
    if($(".outletInvoice_report").length)
    {
        $('.outletInvoice_report').on('click',function(){

            _RPTL();
            var start_date     = $('#start_date').val();
            var end_date       = $('#end_date').val();
            var distributor_id = $('#distributor_id').val();
            var view_type      = $('#view_type').val();
            var method         = $('#method').val();
            var value          = $(this).data('value');
            var cntrl          = $(this).data('cntrl');
            var func           = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'distributor_id' : distributor_id,
                    'start_date'     : start_date,
                    'end_date'       : end_date,
                    'view_type'      : view_type,
                    'method'         : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.outletInvoice_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getOutletInvoiceList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    $('.cancel_val').empty().html(response['cancel_btn']);
                    $('.invoice_val').empty().html(response['invoice_btn']);
                    $('.commission_val').empty().html(response['commission_btn']);
                    $('.new_val').empty().html(response['new_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);
                    $('.delivery_val').empty().html(response['delivery_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.outletInvoice_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getOutletInvoiceList').empty();
                    $('.excel_val').empty();
                    $('.cancel_val').empty();
                    $('.invoice_val').empty();
                    $('.commission_val').empty();
                    $('.new_val').empty();
                    // $('.pdf_val').empty();
                    $('.delivery_val').empty();
                }
            });
        });
    }

    // Outlet Order Report
    if($(".distributorOrder_report").length)
    {
        $('.distributorOrder_report').on('click',function(){

            _RPTL();
            var start_date = $('#start_date').val();
            var end_date   = $('#end_date').val();
            var method     = $('#method').val();
            var value      = $(this).data('value');
            var cntrl      = $(this).data('cntrl');
            var func       = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date' : start_date,
                    'end_date'   : end_date,
                    'method'     : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.order_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getOrderlList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    $('.invoice_val').empty().html(response['invoice_btn']);
                    $('.order_val').empty().html(response['order_btn']);
                    $('.hoisst_val').empty().html(response['hoisst_btn']);
                    $('.del_val').empty().html(response['del_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.order_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getOrderlList').empty();
                    $('.excel_val').empty();
                    $('.invoice_val').empty();
                    $('.order_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    // Vendor Overall Report Report
    if($(".productOrder_report").length)
    {
        $('.productOrder_report').on('click',function(){

            _RPTL();
            var start_date  = $('#start_date').val();
            var end_date    = $('#end_date').val();
            var method      = $('#method').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date' : start_date,
                    'end_date'   : end_date,
                    'method'     : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                if(response['status'] == 1)
                {
                    $('.order_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getProductOrderList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    $('.cancel_val').empty().html(response['cancel_btn']);
                    $('.invoice_val').empty().html(response['invoice_btn']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.order_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getProductOrderList').empty();
                    $('.excel_val').empty();
                    $('.cancel_val').empty();
                    $('.invoice_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    if($(".account_report").length)
    {
        $('.account_report').on('click',function(){

            // _RPTL();
            var start_date     = $('#start_date').val();
            var end_date       = $('#end_date').val();
            var vendor_id      = $('#vendor_id').val();
            var distributor_id = $('#distributor_id').val();
            var outlet_id      = $('#outlet_id').val();
            var method         = $('#method').val();
            var value          = $(this).data('value');
            var cntrl          = $(this).data('cntrl');
            var func           = $(this).data('func');

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'start_date'     : start_date,
                    'end_date'       : end_date,
                    'vendor_id'      : vendor_id,
                    'distributor_id' : distributor_id,
                    'outlet_id'      : outlet_id,
                    'method'         : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                // _RPTR();
                if(response['status'] == 1)
                {
                    $('.order_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getOrderList').empty().html(response['data']);
                    $('.excel_val').empty().html(response['excel_btn']);
                    $('.new_pay').empty().html(response['new_pay']);
                    $('.new_rec').empty().html(response['new_rec']);
                    // $('.pdf_val').empty().html(response['pdf_btn']);

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.order_value').removeClass('show').addClass('hide');
                    $('#page_error').removeClass('hide').addClass('show');
                    $('#getOrderList').empty();
                    $('.excel_val').empty();
                    $('.new_pay').empty();
                    $('.new_rec').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }

    $(document).on('change','#checkAll',function(){
        $(".table-responsive-md td input").prop('checked', $(this).prop("checked"));
    });

    $(document).on('change','.accessModule',function(){
        if($(this).data("item"))
        {
            let data_id = $(this).data("item");

            $(".data_"+data_id).prop('checked', $(this).prop("checked"));
        }
    });


    $(function() {
        $('.c_username').on('keypress', function(e) {
            if (e.which == 32){
                return false;
            }
        });
    });

    $(document).on('change','.collateral_type',function(){
        var type_val = $('select.collateral_type option:selected').val();

        if(type_val == 2)
        {
            $(".date-picker").removeAttr("disabled").button('refresh');
            $('.required').removeClass('hide').addClass('contents');
        }
        else
        {
            $(".date-picker").attr("disabled", "disabled").button('refresh');
            $('.required').removeClass('contents').addClass('hide');
        }
    });

    if($(".add_loyalty").length)
    {
        $('.add_loyalty').on('click',function(){

            var rowCount       = $('.additemform tr').length;
            var outlet_id      = $('#random_val').val();
            var row_count      = $('#row_count').val();
            var value          = $('#value').val();
            var cntrl          = $('#cntrl').val();
            var func           = $('#func').val();

            if(row_count)
            {
                npage = row_count;
            } 
            else
            {
                npage = 1;
            }

            $.ajax({
                method: 'POST',
                data: {
                    'rowCount'  : npage,
                    'outlet_id' : outlet_id,
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/getOrderLoyalty_row',
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('.addloyaltyform').append(response['data']);
                    $('#row_count').empty().val(response['count']);
                }
            });
        });
    }

    if($(".outletLoyalty_list").length)
    {
        $('.outletLoyalty_list').on('click',function(){

            var value        = $('#value').val();
            var cntrl        = $('#cntrl').val();
            var func         = $('#func').val();
            var state_id     = $('#state_id').val();
            var city_id      = $('#city_id').val();
            var zone_id      = $('#zone_id').val();
            var outlet_id    = $('#outlet_id').val();
            var vendor_id    = $('#vendor_id').val();
            var start_date   = $('#start_date').val();
            var end_date     = $('#end_date').val();
            var category_id  = $('#category_id').val();
            var sub_cat      = $('#sub_cat_id').val();

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'state_id'     : state_id,
                    'city_id'      : city_id,
                    'zone_id'      : zone_id,
                    'outlet_id'    : outlet_id,
                    'vendor_id'    : vendor_id,
                    'start_date'   : start_date,
                    'end_date'     : end_date,
                    'sub_cat'      : sub_cat,
                    'category_val' : category_id,
                    'method'       : 'getProductList',
                },
                dataType: 'json',
            }).done(function (response)
            {
                // _TAR();

                if(response['status'] == 1)
                {
                    $('.loyalty_value').removeClass('hide').addClass('show');
                    $('#loyalty_error').removeClass('show').addClass('hide');
                    $('#getLoyaltyList').empty().html(response['data']);
                    $('.submit_btn').removeClass('hide').addClass('show');
                    

                    var title = "";
                    var text  = response['message'];
                    _success(title,text);
                }
                else
                {
                    $('.loyalty_value').removeClass('show').addClass('hide');
                    $('#loyalty_error').removeClass('hide').addClass('show');
                    $('#getLoyaltyList').empty().html('');

                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });

        });
    }

    $("#check_month").click(function(){
        if($("#check_month").is(':checked') ){
            $("#month_id > option").attr("selected","selected");
            $("#month_id").trigger("change");
        }else{
            $("#month_id > option").removeAttr("selected");
            $("#month_id").trigger("change");
        }
    });

    $("#check_employee").click(function(){
        if($("#check_employee").is(':checked') ){
            $("#employee_id > option").attr("selected","selected");
            $("#employee_id").trigger("change");
        }else{
            $("#employee_id > option").removeAttr("selected");
            $("#employee_id").trigger("change");
        }
    });

    $("#check_category").click(function(){
        if($("#check_category").is(':checked') ){
            $("#category_id > option").attr("selected","selected");
            $("#category_id").trigger("change");
        }else{
            $("#category_id > option").removeAttr("selected");
            $("#category_id").trigger("change");
        }
    });
    $("#check_sub").click(function(){
        if($("#check_sub").is(':checked') ){
            $("#sub_cat_id > option").attr("selected","selected");
            $("#sub_cat_id").trigger("change");
        }else{
            $("#sub_cat_id > option").removeAttr("selected");
            $("#sub_cat_id").trigger("change");
        }
    });

    $("#check_product").click(function(){
        if($("#check_product").is(':checked') ){
            $("#product_id > option").attr("selected","selected");
            $("#product_id").trigger("change");
        }else{
            $("#product_id > option").removeAttr("selected");
            $("#product_id").trigger("change");
        }
    });

    $("#check_type").click(function(){
        if($("#check_type").is(':checked') ){
            $("#type_id > option").attr("selected","selected");
            $("#type_id").trigger("change");
        }else{
            $("#type_id > option").removeAttr("selected");
            $("#type_id").trigger("change");
        }
    });

    $("#check_city").click(function(){
        if($("#check_city").is(':checked') ){
            $("#city_id > option").attr("selected","selected");
            $("#city_id").trigger("change");
        }else{
            $("#city_id > option").removeAttr("selected");
            $("#city_id").trigger("change");
        }
    });
    $("#check_astate").click(function(){
        if($("#check_astate").is(':checked') ){
            $("#astate_id > option").attr("selected","selected");
            $("#astate_id").trigger("change");
        }else{
            $("#astate_id > option").removeAttr("selected");
            $("#astate_id").trigger("change");
        }
    });
    $("#check_acity").click(function(){
        if($("#check_acity").is(':checked') ){
            $("#acity_id > option").attr("selected","selected");
            $("#acity_id").trigger("change");
        }else{
            $("#acity_id > option").removeAttr("selected");
            $("#acity_id").trigger("change");
        }
    });

    $("#check_mzone").click(function(){
        if($("#check_mzone").is(':checked') ){
            $("#mzone > option").attr("selected","selected");
            $("#mzone").trigger("change");
        }else{
            $("#mzone > option").removeAttr("selected");
            $("#mzone").trigger("change");
        }
    });



    $("#check_beat").click(function(){
        if($("#check_beat").is(':checked') ){
            $("#zone_id > option").attr("selected","selected");
            $("#zone_id").trigger("change");
        }else{
            $("#zone_id > option").removeAttr("selected");
            $("#zone_id").trigger("change");
        }
    });


    $(document).on('change', '.yearr_id, .monthh_id', function () {
        var month_id = $('.monthh_id').val();
        var year_id  = $('.yearr_id').val();
        var value    = $('#value').val();
        var cntrl    = $('#cntrl').val();
        var func     = $('#func').val();

        if(month_id != '')
        {   
            $.ajax({
                method: 'POST',
                data: {
                    'month_id' : month_id,
                    'year_id'  : year_id,
                    'method'   : '_getEmployeeList',
                },
                url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                dataType: 'json',
            }).done(function (response)
            {
                if(response['status'] == 1)
                {
                    $('#employee_id').empty('').html(response['data']);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);
                }
            });
        }
        else
        {
            var title = "";
            var text  = 'Please Select The Month Name';
            _error(title,text);
        }
    });
   
    // MIS Report 

    // Beat Outlet Report Report
    if($(".misReport_submit").length)
    {
        $('.misReport_submit').on('click',function(){
               
            var month_id    = $('#month_id').val();
            var year_id     = $('#year_id').val();
            var employee_id = $('#employee_id').val();
            var month_name  = $('#month_name').val();
            var year_name   = $('#year_name').val();
            var emp_name    = $('#emp_name').val();
            var method      = $('#method').val();
            var value       = $(this).data('value');
            var cntrl       = $(this).data('cntrl');
            var func        = $(this).data('func');

            _RPTL();

            $.ajax({
                type : 'POST',
                url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func,
                data : {
                    'month_id'    : month_id,
                    'year_id'     : year_id,
                    'employee_id' : employee_id,
                    'month_name'  : month_name,
                    'year_name'   : year_name,
                    'emp_name'    : emp_name,
                    'method'      : method,
                },
                dataType: 'json',
            }).done(function (response)
            {
                _RPTR();
                
                if(response['status'] == 1)
                {
                    var title = "";
                    var text  = response['message'];
                    _success(title,text);

                    $('.result_value').removeClass('hide').addClass('show');
                    $('#page_error').removeClass('show').addClass('hide');
                    $('#getDataList').empty().html(response['data']);

                    $('.excel_val').empty().html(response['excel_btn']);
                    $('.pdf_val').empty().html(response['pdf_btn']);
                }
                else
                {
                    var title = "";
                    var text  = response['message'];
                    _error(title,text);

                    $('.result_value').removeClass('show').addClass('hide');
                    $('#getDataList').empty();
                    $('#page_error').removeClass('hide').addClass('show');
                    // $('.excel_val').empty();
                    // $('.pdf_val').empty();
                }
            });
        });
    }
    $(document).on('click', '.customer_view', function () {
        var id = $(this).data('id');
        var value = $(this).data('value');
        var cntrl = $(this).data('cntrl');
        var func  = $(this).data('func');
        $.ajax({
            type: 'POST',
            data: {
                'att_id': id,
                
            },
            url: baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/get_img',
            dataType: 'json',
        }).done(function (response)
        {
            

            if (response['status'] == 1)
            {
                
                $('.modal-body').html(response['data']);
            } 
            else
            {
               
                $('.modal-body').html("No Data Found");
            }
        });
    });

    $(document).on('change','.mis_month_id',function(){
        var selectedText = $(this).find("option:selected").text();
        $('#month_name').empty().val(selectedText);
    });

    $(document).on('change','.mis_year_id',function(){
        var selectedText = $(this).find("option:selected").text();
        $('#year_name').empty().val(selectedText);
    });

    $(document).on('change','.mis_employee_id',function(){
        var selectedText = $(this).find("option:selected").text();
        $('#emp_name').empty().val(selectedText);
    }); 

    $(document).on('change','.attendance_status',function(){
        var type_val = $('select.attendance_status option:selected').val();

        if(type_val == 2)
        {
            $('.required').removeClass('contents').addClass('hide');
        }
        else
        {
            $('.required').removeClass('hide').addClass('contents');
        }
    });

    $(document).on('change','.e_inv_status',function(){
        var type_val = $('select.e_inv_status option:selected').val();

        if(type_val == 1)
        {
            $('.eway_status').removeClass('hide').addClass('contents');
        }
        else
        {
            $('.eway_status').removeClass('contents').addClass('hide');   
        }
    });
    
    $(document).on('change','.e_way_status',function(){
        var type_val = $('select.e_way_status option:selected').val();

        if(type_val == 1)
        {
            $('.eway_value').removeClass('hide').addClass('contents');
        }
        else
        {
            $('.eway_value').removeClass('contents').addClass('hide');   
        }
    });

    $(document).on('keyup','.product_qty',function(){
        var value     = $('#value').val();
        var cntrl     = $('#cntrl').val();
        var func      = $('#func').val();
        var cclass    = $(this).data("te");
        var _qty      = $('.product_qty'+cclass).val();
        var _packQty  = $('.pack_qty'+cclass).val();
        var _stockChk = $('.stock_check'+cclass).val();


        $.ajax({
            type : 'POST',
            url  : baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/_qtyCheck',
            data : {
                '_qty'      : _qty,
                '_packQty'  : _packQty,
                '_stockChk' : _stockChk,
            },
            dataType: 'json',
        }).done(function (response)
        {    
            if(response['status'] == 1)
            {
                $('.product_qty'+cclass).empty().val('');

                var title = "";
                var text  = "Invalid Quantity";
                _error(title,text);
            }
        });
    });

    $(document).on('change','.loyalty_type',function(){
        var cclass       = $(this).data("te");
        var _loyaltyType = $('.loyalty_type_'+cclass).val();
        var _pdtPrice    = $('.pdt_price_'+cclass).val();

        if(_loyaltyType == 1)
        {
            $(".pdt_price_"+cclass).attr('maxlength','2');
        }
        else
        {
            $(".pdt_price_"+cclass).attr('maxlength','7');
        }
    });
});
    var accountUserImage = $('.uploadedAvatar'), accountUploadImg = $('#account-upload-img'), accountResetBtn = $('#account-reset');
    if (accountUserImage.length) {
        
        var resetImage = accountUserImage.attr('src');
        $('#account-upload').on('change', function (e) {
           
            var reader = new FileReader(),
            files = e.target.files;
            reader.onload = function () {
            if (accountUploadImg) {
                accountUploadImg.attr('src', reader.result);
            }
            };
            reader.readAsDataURL(files[0]);
        });

        accountResetBtn.on('click', function () {
            accountUserImage.attr('src', resetImage);
        });
    }

    var accountUserImage_gst = $('.uploadedGst'), accountUploadImg_gst = $('#gst-upload-img'), accountResetBtn_gst = $('#account-reset');
    if (accountUserImage_gst.length) {
        var resetImage = accountUserImage_gst.attr('src');
        $('#gst-upload').on('change', function (e) {
            console.log('123');
            var reader = new FileReader(),
            files = e.target.files;
            reader.onload = function () {
            if (accountUploadImg_gst) {
                accountUploadImg_gst.attr('src', reader.result);
            }
            };
            reader.readAsDataURL(files[0]);
        });

        accountResetBtn_gst.on('click', function () {
            accountUserImage_gst.attr('src', resetImage);
        });
    }


    var accountUser_logo = $('.uploadedLogo'), accountUpload_logo = $('#upload_logo_img'), accountResetBtn_gst = $('#logo-reset');
    if (accountUser_logo.length) {
        var resetImage = accountUser_logo.attr('src');
        $('#upload_logo').on('change', function (e) {
            var reader = new FileReader(),
            files = e.target.files;
            reader.onload = function () {
            if (accountUpload_logo) {
                accountUpload_logo.attr('src', reader.result);
            }
            };
            reader.readAsDataURL(files[0]);
        });
        accountResetBtn_gst.on('click', function () {
            accountUser_logo.attr('src', resetImage);
        });
    }

    var accountUser_qr = $('.uploadedQr'), accountUpload_qr = $('#upload_qr_img'), accountResetBtn_gst = $('#logo-reset');
    if (accountUser_qr.length) {
        var resetImage = accountUser_qr.attr('src');
        $('#upload_qr').on('change', function (e) {
            var reader = new FileReader(),
            files = e.target.files;
            reader.onload = function () {
            if (accountUpload_qr) {
                accountUpload_qr.attr('src', reader.result);
            }
            };
            reader.readAsDataURL(files[0]);
        });
        accountResetBtn_gst.on('click', function () {
            accountUser_qr.attr('src', resetImage);
        });
    }


    $(document).on('click', '.openModalBtn', function () {   
        var baseurl = $('.geturl').val();
        var att_id   = $(this).data('row');
        var value = $(this).data('value');
        var cntrl = $(this).data('cntrl');
        var func  = $(this).data('func');
         
    $.ajax({
        type: 'POST',
            data: {
                'att_id': att_id,
            },
            url : baseurl+'index.php/'+value+'/'+cntrl+'/'+func+'/get_img',
            dataType: 'json',
        }).done(function (response)
        {    
            if(response['status'] == 1)
            {
                $('#largeModal').modal('show');
                var carouselIndicators = $('.carousel-indicators');
                var carouselInner = $('.carousel-inner');

                // Append each image to the carousel indicators and inner divs
                $.each(response['data'], function(index, imageUrl) {
                var indicatorItem = $('<li>').attr('data-target', '#carouselExampleIndicators').attr('data-slide-to', index);
                carouselIndicators.append(indicatorItem);

                var carouselItem = $('<div>').addClass('carousel-item');
                var imageElement = $('<img>').attr('src', imageUrl).addClass('d-block w-100 img-size');
                carouselItem.append(imageElement);

                if (index === 0) {
                    carouselItem.addClass('active');
                    indicatorItem.addClass('active');
                }

                carouselInner.append(carouselItem);
                });
            }else{
                // alert('Failed to fetch images.');
                $('#largeModal').modal('show');
                var carouselIndicators = $('.carousel-indicators');
                var carouselInner = $('.carousel-inner');

                // Append each image to the carousel indicators and inner divs
                $.each(response['data'], function(index, imageUrl) {
                var indicatorItem = $('<li>').attr('data-target', '#carouselExampleIndicators').attr('data-slide-to', index);
                carouselIndicators.append(indicatorItem);

                var carouselItem = $('<div>').addClass('carousel-item');
                var imageElement = $('<img>').attr('src', imageUrl).addClass('d-block w-100 img-size');
                carouselItem.append(imageElement);

                if (index === 0) {
                    carouselItem.addClass('active');
                    indicatorItem.addClass('active');
                }

                carouselInner.append(carouselItem);
                }); 
            }
        });
   
    });

    $(document).on('click','.meeting_res',function(e){
        var view_val = $(this).attr("data-te");
        var result   = '0';
        if ($(this).is(':checked')) {
            result = '1';
        } else {
            result = '0';
        }

        $('.meeting_val_'+view_val).empty('').val(result);
    });
 