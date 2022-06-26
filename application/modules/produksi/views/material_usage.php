<?php
$arr_po = $this->db->select('id,no_po')->get_where('pmm_purchase_order', array('status' => 'PUBLISH'))->result_array();
$suppliers = $this->db->order_by('name', 'asc')->get_where('pmm_supplier', array('status' => 'PUBLISH'))->result_array();

// $this->db->where_in('tag_type',array('MATERIAL','EQUIPMENT'));
$tags_material = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH','bahanbaku'=>1))->result_array();
?>

<form action="<?php echo site_url('pmm/reports/material_usage_prod_print'); ?>" target="_blank">
    <div class="row">
        <div class="col-sm-5">
            <input type="text" id="filter_date_u" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
        </div>
        <div class="col-sm-5">
            <select id="filter_material_u" name="filter_material" class="form-control">
                <option value="">Pilih Material</option>
                <?php
                foreach ($tags_material as $key => $mats) {
                ?>
                    <option value="<?php echo $mats['id']; ?>"><?php echo $mats['nama_produk']; ?></option>
                <?php
                }
                ?>
               
            </select>
        </div>
        <div class="col-sm-1 text-right">
            <button class="btn btn-info" type="submit" id="btn-print-u"><i class="fa fa-print"></i> Print</button>
        </div>

    </div>
</form>

<div>
    <br />
    <div id="loader-table-u" class="text-center" style="display:none">
        <img src="<?php echo base_url(); ?>assets/back/theme/images/loader.gif">
        <div>
            Please Wait
        </div>
    </div>
    <div id="table-u">

    </div>

</div>