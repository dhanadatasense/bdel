$(document).ready(function () {
    var baseurl = $('.geturl').val();
    var apiurl = $('.apiurl').val();

    function gototop() {
        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    }

    var dateToday = new Date();
    var end = new Date(new Date().setYear(dateToday.getFullYear() - 18));
    var st_date = new Date(new Date().setYear(dateToday.getFullYear() - 60));
    $('.datepicker').datepicker({
        startDate: "+0d",
        format: 'dd-mm-yyyy',
        ignoreReadonly: true,
        autoclose: true
    });

    $('.dob').datepicker({
        startDate: st_date,
        endDate: end,
        format: 'dd-mm-yyyy',
        ignoreReadonly: true,
        autoclose: true
    });




    $('.dateselecter').datepicker({
        maxDate: new Date(),
        format: 'dd-mm-yyyy',
        ignoreReadonly: true,
        autoclose: true
    });

    $('.dates').datepicker({
        endDate: dateToday,
        format: 'dd-mm-yyyy',
        ignoreReadonly: true,
        autoclose: true
    });
    $('.atdates').datepicker({
        maxDate: new Date(),
        format: 'dd-mm-yyyy',
        ignoreReadonly: true,
        autoclose: true
    });

    var _success = function (title, text) {
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

    var _error = function (title, text) {
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

    var _LO = function () {
        $(".data_submit").attr("disabled", "disabled").button('refresh');
        $('.first_btn').removeClass('show').addClass('hide');
        $('.span_btn').removeClass('hide').addClass('show');
    }

    var _LH = function () {
        $(".data_submit").removeAttr("disabled").button('refresh');
        $('.first_btn').removeClass('hide').addClass('show');
        $('.span_btn').removeClass('show').addClass('hide');
    }

    var _TO = function () {
        $('.table_load').removeClass('hide').addClass('show');
        $('.table_value').removeClass('show').addClass('hide');
        $('#error').removeClass('show').addClass('hide');
    }

    var _TH = function () {
        $('.table_load').removeClass('show').addClass('hide');
        $('.table_value').removeClass('hide').addClass('show');
    }

    if ($(".js-select1-multi").length) {
        $(".js-select1-multi").select2({
            // placeholder: "Select",
            placeholder: function () {
                $(this).attr('placeholder');
            }
        });
    }

    if ($(".js-example-placeholder-multiple").length) {
        $(".js-example-placeholder-multiple").select2({
            placeholder: "Select",
            tags: false,
        });
    }

    // Active Financial Year
    $('.active_academic_id').on('change', function () {
        var active_acad = $(this).val();
        $.ajax({
            type: 'POST',
            url: baseurl + 'index.php/welcome/active_academic',
            data: { 'active_acad': active_acad, 'method': 'add' },
            dataType: 'json',
        }).done(function (response) {
            if (response['status'] == 1) {
                var title = "Success";
                var text = response['message'];
                _success(title, text);
                location.reload();
            }
            else {
                var title = "Error";
                var text = response['message'];
                _error(title, text);
            }
        });
    });

    // dis_product_qty
    // function zerovalidtion(){

    // }
    // Data List
    function loadInCartitems(page, search, load_data, value, cntrl, func, limitval, filterval, random_val, page_type) {

        _TO();
        var npage;
        var sort_type, sort_column;
        var sort_type_ = $('.sorting.active').attr("data-sort");
        var sort_column_ = $('.sorting.active').attr("data-item");
        if (sort_type_) {
            sort_type = sort_type_;
        }
        else {
            sort_type = '1';
        }

        if (sort_column_) {
            sort_column = sort_column_;
        }
        else {
            sort_column = '';
        }
        if (page) {
            npage = page;
        }
        else {
            npage = 1;
        }

        if (filterval) {
            nfilter = filterval;
        }
        else {
            nfilter = 0;
        }

        $.ajax({
            method: 'POST',
            data: {
                'load_data': load_data,
                'page': npage,
                'search': search,
                'limitval': limitval,
                'filterval': nfilter,
                'random_val': random_val,
                'sort_column': sort_column,
                'sort_type': sort_type,
            },
            url: baseurl + 'index.php/' + value + '/' + cntrl + '/' + func + '/data_list',
            dataType: 'json',
            beforeSend: function (x) {
                $('.loading').css('visibility', 'visible');
            }
        }).done(function (response) {

            _TH();

            if (response['status'] == 1) {
                $('#error').removeClass('show').addClass('hide');
                $('.table_value').removeClass('hide').addClass('show');
                $('#getCategory').empty('').html(response['result']);
                $('#button_view').empty('').html(response['button']);

                $('.pagnext').empty().html(response['next']);
                $('.pagprev').empty().html(response['prev']);
            }
            else {
                $('#error').removeClass('hide').addClass('show');
                $('.error_msg').empty('').html(response['message']);
                $('.table_value').removeClass('show').addClass('hide');
            }
        });
    }

    $(document).on('click', '.sorting', function () {
        $('.sorting').removeClass('active');
        $(this).addClass('active');
        $('.sorting .up, .sorting .down').removeClass('show-inline').addClass('hide');
        var _sort_column = $(this).attr('data-item');
        var _finder = $(this).closest('.sorting');
        if ($(this).attr('data-sort') == 1) {
            // 1 up
            _finder.find('.up').removeClass('show-inline').addClass('hide')
            _finder.find('.down').removeClass('hide').addClass('show-inline');
            $(this).attr('data-sort', 2);
        }
        else {
            // 2 down
            _finder.find('.down').addClass('hide').removeClass('show-inline');
            _finder.find('.up').removeClass('hide').addClass('show-inline');
            $(this).attr('data-sort', 1);
        }

        var search = $('#searchval').val();
        var page_type = $('#page_type').val();
        var load_data = $('#load_data').val();
        var limitval = $('.getlimitval').val();
        var value = $('#value').val();
        var cntrl = $('#cntrl').val();
        var func = $('#func').val();
        var filterval = $('.getfilterval').val();
        var random_val = $('#random_val').val();
        console.log(func);
        loadInCartitems(1, search, load_data, value, cntrl, func, limitval, filterval, random_val, page_type);
    });


    $(document).on('click', '.searchdata', function () {
        var search = $('#searchval').val();
        var load_data = $('#load_data').val();
        var value = $('#value').val();
        var cntrl = $('#cntrl').val();
        var func = $('#func').val();
        var filterval = $('.getfilterval').val();
        var limitval = $('.getlimitval').val();
        var random_val = $('#random_val').val();
        var page_type = $('#page_type').val();
        loadInCartitems(1, search, load_data, value, cntrl, func, limitval, filterval, random_val, page_type);
        gototop();
    });

    $(document).on('click', '.pages', function () {
        var page = $(this).data('page');
        var search = $('#searchval').val();
        var load_data = $('#load_data').val();
        var value = $('#value').val();
        var cntrl = $('#cntrl').val();
        var func = $('#func').val();
        var filterval = $('.getfilterval').val();
        var limitval = $('.getlimitval').val();
        var random_val = $('#random_val').val();
        var page_type = $('#page_type').val();
        loadInCartitems(page, search, load_data, value, cntrl, func, limitval, filterval, random_val, page_type);
        gototop();
    });

    $(document).on('change', '.getlimitval', function () {
        var search = $('#searchval').val();
        var load_data = $('#load_data').val();
        var value = $('#value').val();
        var cntrl = $('#cntrl').val();
        var func = $('#func').val();
        var filterval = $('.getfilterval').val();
        var limitval = $('.getlimitval').val();
        var random_val = $('#random_val').val();
        var page_type = $('#page_type').val();
        loadInCartitems(1, search, load_data, value, cntrl, func, limitval, filterval, random_val, page_type);
        gototop();
    });

    $(document).on('change', '.getfilterval', function () {
        var search = $('#searchval').val();
        var load_data = $('#load_data').val();
        var value = $('#value').val();
        var cntrl = $('#cntrl').val();
        var func = $('#func').val();
        var filterval = $('.getfilterval').val();
        var limitval = $('.getlimitval').val();
        var page_type = $('#page_type').val();
        var random_val = $('#random_val').val();
        loadInCartitems(1, search, load_data, value, cntrl, func, limitval, filterval, random_val, page_type);
        gototop();
    });

    var search = $('#searchval').val();
    var load_data = $('#load_data').val();
    var value = $('#value').val();
    var cntrl = $('#cntrl').val();
    var func = $('#func').val();
    var filterval = $('.getfilterval').val();
    var limitval = $('.getlimitval').val();
    var random_val = $('#random_val').val();
    var page_type = $('#page_type').val();
    loadInCartitems(1, search, load_data, value, cntrl, func, limitval, filterval, random_val, page_type);



    //  $(document).on('click', '.searchdata', function () {
    //         var search    = $('#searchval').val();
    //         var page_type = $('#page_type').val();
    //         var load_data = $('#load_data').val();
    //         var limitval  = $('.getlimitval').val();
    //         loadInDataitems(1, search, load_data, limitval, page_type);    
    //     });

    //     $(document).on('change', '.getlimitval', function () {
    //         var search    = $('#searchval').val();
    //         var page_type = $('#page_type').val();
    //         var load_data = $('#load_data').val();
    //         var limitval  = $('.getlimitval').val();
    //         loadInDataitems(1, search, load_data, limitval, page_type);    
    //     });

    //     $(document).on('click', '.pages', function () {
    //         var page      = $(this).data('page');
    //         var search    = $('#searchval').val();
    //         var page_type = $('#page_type').val();
    //         var load_data = $('#load_data').val();
    //         var limitval  = $('.getlimitval').val();
    //         loadInDataitems(page, search, load_data, limitval, page_type);    
    //     });

    //     var search    = $('#searchval').val();
    //     var page_type = $('#page_type').val();
    //     var load_data = $('#load_data').val();
    //     var limitval  = $('.getlimitval').val();
    //     loadInDataitems(1, search, load_data, limitval, page_type);  

    // Payment List
    function loadInPaymentitems(page, search, load_data, value, cntrl, func, limitval, random_val) {
        _TO();
        var npage,
            nsort;
        if (page) {
            npage = page;
        }
        else {
            npage = 1;
        }

        $.ajax({
            method: 'POST',
            data: {
                'load_data': load_data,
                'page': npage,
                'search': search,
                'limitval': limitval,
                'random_val': random_val
            },
            url: baseurl + 'index.php/' + value + '/' + cntrl + '/' + func + '/payment_value',
            dataType: 'json',
            beforeSend: function (x) {
                $('.loading').css('visibility', 'visible');
            }
        }).done(function (response) {

            _TH();

            if (response['status'] == 1) {
                $('#error').removeClass('show').addClass('hide');
                $('.table_value').removeClass('hide').addClass('show');
                $('#getPayment').empty('').html(response['result']);
                $('#button_view').empty('').html(response['button']);

                $('.pagnext').empty().html(response['next']);
                $('.pagprev').empty().html(response['prev']);
            }
            else {
                $('#error').removeClass('hide').addClass('show');
                $('.table_value').removeClass('show').addClass('hide');
            }
        });
    }

    $(document).on('click', '.searchdata', function () {
        var search = $('#searchval').val();
        var load_data = $('#load_data').val();
        var value = $('#value').val();
        var cntrl = $('#cntrl').val();
        var func = $('#func').val();
        var random_val = $('#random_val').val();
        var limitval = $('.getlimitval').val();
        loadInPaymentitems(1, search, load_data, value, cntrl, func, limitval, random_val);
        gototop();
    });

    $(document).on('click', '.pages', function () {
        var page = $(this).data('page');
        var search = $('#searchval').val();
        var load_data = $('#load_data').val();
        var value = $('#value').val();
        var cntrl = $('#cntrl').val();
        var func = $('#func').val();
        var random_val = $('#random_val').val();
        var limitval = $('.getlimitval').val();
        loadInPaymentitems(page, search, load_data, value, cntrl, func, limitval, random_val);
        gototop();
    });

    $(document).on('change', '.getlimitval', function () {
        var search = $('#searchval').val();
        var load_data = $('#load_data').val();
        var value = $('#value').val();
        var cntrl = $('#cntrl').val();
        var func = $('#func').val();
        var random_val = $('#random_val').val();
        var limitval = $('.getlimitval').val();
        loadInPaymentitems(1, search, load_data, value, cntrl, func, limitval, random_val);
        gototop();
    });

    var search = $('#searchval').val();
    var load_data = $('#load_data').val();
    var value = $('#value').val();
    var cntrl = $('#cntrl').val();
    var func = $('#func').val();
    var random_val = $('#random_val').val();
    var limitval = $('.getlimitval').val();
    loadInPaymentitems(1, search, load_data, value, cntrl, func, limitval, random_val);


    // Data Store
    if ($('.form_submit').length) {
        $('.form_data').on('submit', function (e) {
            // _LO(); 

            e.preventDefault();
            var pre_menu = $('#pre_menu').val();
            var value = $('#value').val();
            var cntrl = $('#cntrl').val();
            var func = $('#func').val();
            var submit_url = $('#submit_url').val();

            $.ajax({
                type: 'POST',
                url: apiurl + submit_url,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
            }).done(function (response) {

                // _LH();
                if (response['status'] == 1) {
                    var title = "";
                    var text = response['message'];
                    _success(title, text);
                    location.reload();
                }
                else {
                    var title = "";
                    var text = response['message'];
                    _error(title, text);
                }
            });
        });
    }


    // Data Delete
    $(document).on('click', '.delete-btn', function () {

        var row = $(this).data('row');
        var id = $(this).data('id');
        var value = $(this).data('value');
        var cntrl = $(this).data('cntrl');
        var func = $(this).data('func');

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
                        'id': id,
                    },
                    url: baseurl + 'index.php/' + value + '/' + cntrl + '/' + func + '/delete',
                    dataType: 'json',
                }).done(function (response) {
                    if (response['status'] == 1) {
                        $('.row_' + row).remove();

                        var title = "";
                        var text = response['message'];
                        _success(title, text);
                        location.reload();
                    }
                    else {
                        var title = "";
                        var text = response['message'];
                        _error(title, text);
                        location.reload();
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your data is safe :)',
                    type: 'error',
                    confirmButtonClass: 'btn btn-success',
                })
            }
        })
    });
    $(document).on('click', '.approve-btn', function () {

        var row = $(this).data('row');
        var status = $(this).data('status');
        var id = $(this).data('id');
        var value = $(this).data('value');
        var cntrl = $(this).data('cntrl');
        var func = $(this).data('func');

        Swal.fire({
            title: 'Are you sure?',
            text: "You wan't be able to revert this!",
            type: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve it!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {

                $.ajax({
                    method: 'POST',
                    data: {
                        'id': id,
                        'status': status,
                    },
                    url: baseurl + 'index.php/' + value + '/' + cntrl + '/' + func + '/approve',
                    dataType: 'json',
                }).done(function (response) {
                    if (response['status'] == 1) {
                        $('.row_' + row).remove();

                        var title = "";
                        var text = response['message'];
                        _success(title, text);
                        location.reload();
                    }
                    else {
                        var title = "";
                        var text = response['message'];
                        _error(title, text);
                        location.reload();
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your data is safe :)',
                    type: 'error',
                    confirmButtonClass: 'btn btn-success',
                })
            }
        })
    });

    $(document).on('click', '.uploadedAvatar1', function () {

        var row = $(this).data('id');

        Swal.fire({
            imageUrl: row,
            // imageHeight: 300,
            // imageWidth: 1000,
            imageAlt: 'A tall image'
        })
        // Swal.fire({
        //     title: 'Image Preview',
        //     html: '<img src="'+row+'" class="preview-image">',
        //     showCloseButton: true,
        //     showConfirmButton: false,
        //     customClass: {
        //       popup: 'preview-modal',
        //       closeButton: 'preview-close-button',
        //     },
        //   });

    });
    // Data Delete
    $(document).on('click', '.single-del', function () {

        var row = $(this).data('row');
        var id = $(this).data('id');
        var value = $(this).data('value');
        var cntrl = $(this).data('cntrl');
        var func = $(this).data('func');

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
                        'id': id,
                    },
                    url: baseurl + 'index.php/' + value + '/' + cntrl + '/' + func + '/single_delete',
                    dataType: 'json',
                }).done(function (response) {
                    if (response['status'] == 1) {
                        $('.row_' + row).remove();

                        var title = "";
                        var text = response['message'];
                        _success(title, text);
                    }
                    else {
                        var title = "";
                        var text = response['message'];
                        _error(title, text);
                    }
                });

            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your data is safe :)',
                    type: 'error',
                    confirmButtonClass: 'btn btn-success',
                })
            }
        })
    });

    // Data Delete
    $(document).on('click', '.update-btn', function () {

        var row = $(this).data('row');
        var id = $(this).data('id');
        var value = $(this).data('value');
        var cntrl = $(this).data('cntrl');
        var func = $(this).data('func');

        Swal.fire({
            title: 'Are you sure?',
            text: "You wan't be able to update this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!',
            confirmButtonClass: 'btn btn-warning',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {

                $.ajax({
                    method: 'POST',
                    data: {
                        'id': id,
                    },
                    url: baseurl + 'index.php/' + value + '/' + cntrl + '/' + func + '/payment_update',
                    dataType: 'json',
                }).done(function (response) {
                    if (response['status'] == 1) {
                        location.reload();

                        var title = "";
                        var text = response['message'];
                        _success(title, text);
                    }
                    else {
                        var title = "";
                        var text = response['message'];
                        _error(title, text);
                    }
                });

            }
        })
    });


    // Data Delete
    $(document).on('click', '.del_bth', function () {

        var row = $(this).data('row');
        var id = $(this).data('id');
        var value = $(this).data('value');
        var cntrl = $(this).data('cntrl');
        var func = $(this).data('func');
        var method = $(this).data('method');

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
                        'order_id': id,
                        'method': method,
                    },
                    url: baseurl + 'index.php/' + value + '/' + cntrl + '/' + func,
                    dataType: 'json',
                }).done(function (response) {
                    if (response['status'] == 1) {
                        $('.row_' + row).remove();

                        var title = "";
                        var text = response['message'];
                        _success(title, text);

                        location.reload();
                    }
                    else {
                        var title = "";
                        var text = response['message'];
                        _error(title, text);
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your data is safe :)',
                    type: 'error',
                    confirmButtonClass: 'btn btn-success',
                })
            }
        })
    });

    // Status change
    $(document).on('click', '.status-btn', function () {

        var row = $(this).data('row');
        var id = $(this).data('id');
        var value = $(this).data('value');
        var cntrl = $(this).data('cntrl');
        var func = $(this).data('func');
        var status = $(this).data('status');

        Swal.fire({
            title: 'Are you sure?',
            text: "You wan't be able to update this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!',
            confirmButtonClass: 'btn btn-warning',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {

                $.ajax({
                    method: 'POST',
                    data: {
                        'id': id,
                        'status': status,
                    },
                    url: baseurl + 'index.php/' + value + '/' + cntrl + '/' + func + '/status_update',
                    dataType: 'json',
                }).done(function (response) {
                    if (response['status'] == 1) {
                        // $('.row_'+row).remove();
                        if (status == 1) {
                            $('.enable_btn_' + row).data('status', '2');
                            $('.enable_btn_' + row).empty().html('<i class="ft-edit"></i> Disable');
                            $('.enable_btn_' + row).removeClass('btn-success').addClass('btn-danger');
                            $('.list_' + row).removeClass('enable_btn_' + row).addClass('disable_btn_' + row);
                        }
                        else {
                            $('.disable_btn_' + row).data('status', '1');
                            $('.disable_btn_' + row).empty().html('<i class="ft-edit"></i> Enable');
                            $('.disable_btn_' + row).removeClass('btn-danger').addClass('btn-success');
                            $('.list_' + row).removeClass('disable_btn_' + row).addClass('enable_btn_' + row);
                        }

                        var title = "";
                        var text = response['message'];
                        _success(title, text);
                    }
                    else {
                        var title = "";
                        var text = response['message'];
                        _error(title, text);
                    }
                });

            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your data is safe :)',
                    type: 'error',
                    confirmButtonClass: 'btn btn-success',
                })
            }
        })
    });

    // Modal Popup View
    $(document).on('click', '.modal-btn', function () {

        var id = $(this).data('id');
        var value = $(this).data('value');
        var cntrl = $(this).data('cntrl');
        var func = $(this).data('func');

        $.ajax({
            method: 'POST',
            data: {
                'id': id,
            },
            url: baseurl + 'index.php/' + value + '/' + cntrl + '/' + func + '/model_view',
            dataType: 'json',
        }).done(function (response) {
            if (response['status'] == 1) {
                $('.modal-title').empty('').html(response['title']);
                $('.modal-body').empty('').html(response['data']);
            }
            else {
                $('.modal-title').empty('').html(response['title']);
                $('.modal-body').empty('').html(response['data']);
            }
        });
    });

    // Data Store
    if ($('.data_submit').length) {
        $('.data_form').on('submit', function (e) {

            // _LO();
            e.preventDefault();
            var pre_menu = $('#pre_menu').val();
            var value = $('#value').val();
            var cntrl = $('#cntrl').val();
            var func = $('#func').val();

            $.ajax({
                type: 'POST',
                url: baseurl + 'index.php/' + value + '/' + cntrl + '/' + func,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
            }).done(function (response) {
                // _LH();
                if (response['status'] == 1) {
                    var title = "";
                    var text = response['message'];
                    _success(title, text);
                    location.reload();
                }
                else {
                    var title = "";
                    var text = response['message'];
                    _error(title, text);
                }
            });
        });
    }

    $(document).on('keyup', '.int_value', function () {
        this.value = this.value.replace(/^0+/g, '');

    });

    function isNumberKey(txt, evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 46) {
            //Check if the text already contains the . character
            if (txt.value.indexOf('.') === -1) {
                return true;
            } else {
                return false;
            }
        } else {
            if (charCode > 31 &&
                (charCode < 48 || charCode > 57))
                return false;
        }
        return true;
    }

    function isNumberKey_new(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        console.log(charCode);
        if (charCode != 46 && charCode != 45 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

});