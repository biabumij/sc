    <script type="text/javascript">
        var form_control = '';
    </script>
    <script type="text/javascript">
    $('input.numberformat').number( true, 2,',','.' );
    $('.dtpicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });
    $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY'));
        // table.ajax.reload();
    });

    $('.dtpickerange').daterangepicker({
        autoUpdateInput: false,
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
        },
        showDropdowns: true,
    });

    var table = $('#on-site-table').DataTable({
        ajax: {
            processing: true,
            serverSide: true,
            url: '<?php echo site_url('pmm/table_remaining_material'); ?>',
            type: 'POST',
            data: function(d) {
                d.material_id = $('#filter_tag').val();
                d.filter_date = $('#filter_date').val();
            }
        },
        columns: [{
                "data": "no"
            },
            {
                "data": "date"
            },
            {
                "data": "material_id"
            },
            {
                "data": "measure"
            },
            {
                "data": "volume"
            },
            {
                "data": "notes"
            },
            {
                "data": "admin_name"
            },
            {
                "data": "created_on"
            },
            {
                "data": "delete"
            },
        ],
        responsive: true,
        "columnDefs": [{
            "targets": [0, 1, 3, 4],
            "className": 'text-center',
        },
        {
			"targets": [2],
			"className": 'text-left',
        },
        {
			"targets": [7, 8],
			"className": 'text-right',
         }
		]
    });

    $('#filter_tag').change(function() {
        table.ajax.reload();
    });

    $('.dtpickerange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table.ajax.reload();
    });

    function OpenForm(id = '') {
        $("#modalForm form").trigger("reset");
        $('#modalForm').modal('show');
        $('#id').val('');
        // table_detail.ajax.reload();
        if (id !== '') {
            $('#id').val(id);
            getData(id);
        }
    }

    function modalDetail(title, id) {
        $('#modalDetail').modal('show');
        $('#modalDetail .modal-title').text(title);
        $('#modalDetail .modal-body').html('');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('pmm/get_remaining_price'); ?>/" + Math.random(),
            dataType: 'html',
            data: {
                id: id
            },
            success: function(result) {
                $('#modalDetail .modal-body').html(result);
            }
        });
    }

    $('#modalForm form').submit(function(event) {
        $('#btn-form').button('loading');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('pmm/form_remaining_material'); ?>/" + Math.random(),
            dataType: 'json',
            data: $(this).serialize(),
            success: function(result) {
                $('#btn-form').button('reset');
                if (result.output) {
                    $("#modalForm form").trigger("reset");
                    table.ajax.reload();
                    $('#modalForm').modal('hide');
                } else if (result.err) {
                    bootbox.alert(result.err);
                }
            }
        });

        event.preventDefault();

    });

    function getData(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('pmm/get_remaining_material'); ?>",
            dataType: 'json',
            data: {
                id: id
            },
            success: function(result) {
                if (result.output) {
                    $('#id').val(result.output.id);
                    $('#date').val(result.output.date);
                    $('#material_id').val(result.output.material_id);
                    $('#measure_id').val(result.output.measure);
                    $('#volume').val(result.output.volume);
                    $('#notes').val(result.output.notes);
                    $('#status').val(result.output.status);
                } else if (result.err) {
                    bootbox.alert(result.err);
                }
            }
        });
    }


    function DeleteData(id) {
        bootbox.confirm("Are you sure to delete this data ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/delete_remaining_material'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table.ajax.reload();
                            bootbox.alert('Berhasil menghapus!!');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }
        });
    }

    $('#material_id').change(function() {
        var measure = $(this).find(':selected').data('measure');
        $('#measure_id').val(measure);
    });
    </script>