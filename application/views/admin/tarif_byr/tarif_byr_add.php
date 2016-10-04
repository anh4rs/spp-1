<?php
$this->load->view('admin/tinymce_init');
$this->load->view('admin/datepicker');
if (isset($tarif_byr)) {
    $id = $tarif_byr['tarif_byr_id'];
    $inputThn = $tarif_byr['thn_ajaran_thn_ajaran_id'];
    $inputBulan = $tarif_byr['bulan_bulan_id'];
    $inputJns = $tarif_byr['jns_byr_jns_byr_id'];
    $inputKategori = $tarif_byr['tarif_byr_kategori'];

} else {

    $inputKategori = set_value('tarif_byr_kategori');
    $inputThn = set_value('thn_ajaran_id');
    $inputBulan = set_value('bulan_id');
    $inputJns = set_value('jns_byr_id');
    $inputKategori = set_value('tarif_byr_kategori');
}
?>
<?php echo isset($alert) ? ' ' . $alert : null; ?>
<?php echo validation_errors(); ?>
<div class="col-md-12 col-sm-12 col-xs-12 main post-inherit">
    <div class="x_panel post-inherit">
        <div class="col-lg-12">
            <h3><?php echo $operation ?> Tarif Pembayaran</h3>
            <br>
        </div>
        <!-- /.col-lg-12 -->

        <?php echo form_open_multipart(current_url()); ?>
        <div class="col-md-12">
            <div class="col-sm-8 col-md-6">
                <?php if (isset($tarif_byr)): ?>
                    <input type="hidden" name="tarif_byr_id" value="<?php echo $tarif_byr['tarif_byr_id'] ?>" />
                <?php endif; ?>     
                <label >Pilih Jenis Pembayaran *</label>
                <select name="jns_byr_id" class="form-control">
                    <option value="">-- Pilih --</option>
                    <?php
                    foreach ($jns_byr as $row):
                        ?>
                    <option value="<?php echo $row['jns_byr_id']; ?>"> <?php echo $row['jns_byr_ket']; ?></option>

                    <?php
                    endforeach;
                    ?>
                </select><br>
                <label >Pilih Tahun Ajaran *</label>
                <select name="thn_ajaran_id" class="form-control">
                    <option value="">-- Pilih --</option>
                    <?php
                    foreach ($thn_ajaran as $row):
                        ?>
                    <option value="<?php echo $row['thn_ajaran_id']; ?>"> <?php echo $row['thn_ajaran_ket']; ?></option>

                    <?php
                    endforeach;
                    ?>
                </select><br>
                 <label >Tarif Pembayaran *</label>
                <input name="tarif_byr_kategori" placeholder="Tarif" type="text" class="form-control" onkeypress="validate(event)" value="<?php echo $inputKategori; ?>"><br>
                    <p style="color:#9C9C9C;margin-top: 5px"><i>*) Wajib diisi</i></p>
                </div>
                <div class="col-sm-12 col-xs-12 col-md-3">
                    <div class="form-group">
                    <br>
                    <button name="action" type="submit" value="save" class="btn btn-success btn-form"><i class="fa fa-check"></i> Simpan</button><br>
                    <a href="<?php echo site_url('admin/tarif_byr'); ?>" class="btn btn-info btn-form"><i class="fa fa-arrow-left"></i> Batal</a><br>
                    <?php if (isset($tarif_byr)): ?>
                        <?php if ($this->session->userdata('tarif_byr_id') != $id) {
                            ?>
                            <a href="<?php echo site_url('admin/tarif_byr/delete/' . $tarif_byr['tarif_byr_id']); ?>" class="btn btn-danger btn-form"><i class="fa fa-trash"></i> Hapus</a><br>
                            <?php } ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <?php if (isset($tarif_byr)): ?>
        <!-- Delete Confirmation -->
        <div class="modal fade" id="confirm-del">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><b>Konfirmasi Penghapusan</b></h4>
                    </div>
                    <div class="modal-body">
                        <p>Data yang dipilih akan dihapus oleh sistem, apakah anda yakin?;</p>
                    </div>
                    <?php echo form_open('admin/tarif_byr/delete/' . $tarif_byr['tarif_byr_id']); ?>
                    <div class="modal-footer">
                        <a><button style="float: right;margin-left: 10px" type="button" class="btn btn-default" data-dismiss="modal">Tidak</button></a>
                        <input type="hidden" name="del_id" value="<?php echo $tarif_byr['tarif_byr_id'] ?>" />
                        <input type="hidden" name="del_name" value="<?php echo $tarif_byr['tarif_byr_kateegori'] ?>" />
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </div>
                    <?php echo form_close(); ?>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <?php if ($this->session->flashdata('delete')) { ?>
            <script type = "text/javascript">
                $(window).load(function() {
                    $('#confirm-del').modal('show');
                });
            </script>
            <?php }
            ?>
        <?php endif; ?>

        <script type="text/javascript">
            function validate(evt) {
              var theEvent = evt || window.event;
              var key = theEvent.keyCode || theEvent.which;
              key = String.fromCharCode( key );
              var regex = /[0-9]|\./;
              if( !regex.test(key) ) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        }
    </script>