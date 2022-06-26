<script type="text/javascript">
    $('#dtpickerange').daterangepicker({
        autoUpdateInput: false,
        showDropdowns: true,
        locale: {
            format: 'DD-MM-YYYY'
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $('#filter_date_u').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        TableDateMatUse();
    });


    function TableDate() {
        $('#table-date').show();
        $('#loader-table').fadeIn('fast');
        $('#table-date tbody').html('');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('pmm/receipt_material/table_matuse'); ?>/" + Math.random(),
            dataType: 'json',
            data: {
                purchase_order_no: $('#filter_po_id').val(),
                supplier_id: $('#filter_supplier_id').val(),
                filter_date: $('#filter_date').val(),
                filter_material: $('#filter_material').val(),
            },
            success: function(result) {
                if (result.data) {
                    $('#table-date tbody').html('');

                    if (result.data.length > 0) {
                        $.each(result.data, function(i, val) {
                            $('#table-date tbody').append('<tr class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="2">' + val.name + '</td><td class="text-center">' + val.measure + '</td><td class="text-center">' + val.real + '</td><td class="text-center">' + val.convert_value + '</td><td class="text-center">' + val.total_convert + '</td><td class="text-right"><span class="pull-left">Rp. </span>' + val.total_price + '</td></tr>');
                            $.each(val.mats, function(a, row) {
                                var a_no = a + 1;
                                $('#table-date tbody').append('<tr  class="mats-' + val.no + '"><td class="text-center">' + val.no + '.' + a_no + '</td><td></td><td class="text-left">' + row.material_name + '</td><td class="text-center">' + row.measure + '</td><td class="text-center">' + row.real + '</td><td class="text-center">' + row.convert_value + '</td><td class="text-center">' + row.total_convert + '</td><td class="text-right"><span class="pull-left">Rp. </span>' + row.total_price + '</td></tr>');
                            });
                            $('#table-date tbody').append('<tr><td colspan="8" height="20px"></td></tr>');

                        });
                        $('#table-date tbody').append('<tr><td class="text-right" colspan="5"><b>TOTAL</b></td><td></td><td class="text-center" ><b>' + result.total_convert + '</b></td><td class="text-right" ><b><span class="pull-left">Rp. </span>' + result.total + '</b></td></tr>');
                    } else {
                        $('#table-date tbody').append('<tr><td class="text-center" colspan="6"><b>No Data</b></td></tr>');
                    }
                    $('#loader-table').fadeOut('fast');
                } else if (result.err) {
                    bootbox.alert(result.err);
                }
            }
        });
    }

    function TableDateMatUse() {
        $('#loader-table-u').fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('pmm/reports/data_material_usage'); ?>/" + Math.random(),
            data: {
                supplier_id: $('#filter_supplier_id_u').val(),
                filter_date: $('#filter_date_u').val(),
                filter_material: $('#filter_material_u').val(),
            },
            success: function(result) {
                $('#loader-table-u').fadeOut('fast');
                $('#table-u').html(result);
            }
        });
    }

    $('#filter_material_u').change(function() {
        TableDateMatUse();
    });

    function NextShow(id) {
        $('.mats-' + id).slideToggle();
    }


    $('.custom_date').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });
    $('.custom_date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY'));
    });
    // TableDate();

    function GetPO() {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('pmm/receipt_material/get_po_by_supp'); ?>/" + Math.random(),
            dataType: 'json',
            data: {
                supplier_id: $('#filter_supplier_id').val(),
            },
            success: function(result) {
                if (result.data) {
                    $('#filter_po_id').empty();
                    $('#filter_po_id').select2({
                        data: result.data
                    });
                    $('#filter_po_id').trigger('change');
                } else if (result.err) {
                    bootbox.alert(result.err);
                }
            }
        });
    }

    $('#filter_supplier_id').change(function() {
        TableDate();
        GetPO();
    });
    $('#filter_po_id').change(function() {
        TableDate();
    });

    $('#filter_material').change(function() {
        TableDate();
    });
</script>