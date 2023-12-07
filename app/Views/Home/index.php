<!-- app/Views/Home/index.php -->

<?= $this->extend('template/main'); ?>

<?= $this->section('header'); ?>
<?= view('Home/header') ?>
<?= $this->endSection(); ?>

<?= $this->section('sidebar'); ?>
<?= view('Home/sidebar') ?>
<?= $this->endSection(); ?>





<?= $this->section('content'); ?>
<?= view('Home/content') ?>

<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="card-body">
                    <div class="d-flex align-items-end row">
                        <div class="card-body">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" onclick="openModal('add');">
                                Tambah
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <!-- Konten modal yang sama untuk menambah dan mengedit -->
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel1">Modal title</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Isi formulir untuk menambah dan mengedit data -->
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" id="username" class="form-control" placeholder="Enter Username" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="text" id="password" class="form-control" placeholder="Enter Password" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="nama" class="form-label">Nama</label>
                                                    <input type="text" id="nama" class="form-control" placeholder="Enter Name" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="role" class="form-label">Role</label>
                                                    <input type="text" id="role" class="form-control" placeholder="Enter Role" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="button" class="btn btn-primary" onclick="saveData();">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <table class="table" id="table-datatable">
                                <thead>
                                    <tr>
                                        <th> No. </th>
                                        <th> Username </th>
                                        <th> Password </th>
                                        <th> Nama </th>
                                        <th> Role </th>
                                        <th> Aksi </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var tabel = null;

    $(document).ready(function() {
        tabel = $('#table-datatable').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "ordering": true, // Set true agar bisa di sorting
            "order": [
                [0, 'asc']
            ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "ajax": {
                "url": "<?= base_url('public/Home/grid'); ?>", // URL file untuk proses select datanya
                "type": "POST",

            },
            "deferRender": true,
            "aLengthMenu": [
                [15, 25, 50],
                [15, 25, 50]
            ], // Combobox Limit
            "columns": [{
                    "data": 'username',
                    "sortable": false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "username"
                },
                {
                    "data": "password",
                    "render": function(data, type, row, meta) {
                        return '[hidden]';
                    }
                },
                {
                    "data": "nama",
                    "render": function(data, type, row, meta) {
                        return data + " " + row.role;
                    }
                },
                {
                    "data": "role"
                },
                {
                    "data": "username",
                    "render": function(data, type, row, meta) {
                        var editButton = "<button class='btn btn-primary' data-username='" + data + "' data-nama='" + row.nama + "' data-password='" + row.password + "' data-role='" + row.role + "' onclick='prosesEdit(this)'>EDIT</button>";
                        var deleteButton = "<button class='btn btn-danger' onclick='prosesDelete(\"" + data + "\")'>DELETE</button>";
                        return editButton + " " + deleteButton;
                    }
                },
            ],
        });
    });

    function openModal(action, username = '', nama = '', password = '', role = '') {
        // Reset formulir dan status modal
        $("#username").val(username);
        $("#nama").val(nama);
        $("#password").val(password);
        $("#role").val(role);

        // Kosongkan kolom password saat dalam mode edit
        if (action === 'edit') {
            $("#password").val('');
        } else {
            $("#password").val(password);
        }

        $("#role").val(role);

        resetModalStatus();

        // Atur judul modal berdasarkan tindakan
        var modalTitle = action === 'add' ? 'Tambah Data' : 'Edit Data';
        $("#exampleModalLabel1").text(modalTitle);

        // Tampilkan modal
        $("#basicModal").modal("show");

        // Menyembunyikan elemen yang tidak diperlukan saat menambah data
        if (action === 'add') {
            // Tidak ada yang perlu diubah saat menambah data
        } else {
            // Men-disable kolom tertentu saat melakukan edit
            $("#username").prop("readonly", true);
            $("#role").prop("readonly", true);
            // Misalnya, jika ingin men-disable kolom username
            // $("#username").prop("disabled", true);

            // Menyembunyikan atau menampilkan kembali elemen lain jika perlu
            // $("#elemenLain").show(); // Menampilkan kembali elemen lain jika perlu
        }
    }

    function saveData() {
        var action = $("#exampleModalLabel1").text().toLowerCase().includes('tambah') ? 'add' : 'edit';

        $.ajax({
            method: "POST",
            url: "<?= base_url(); ?>public/Home/" + (action === 'add' ? 'simpan' : 'edit'),
            data: {
                username: $("#username").val(),
                password: $("#password").val(),
                nama: $("#nama").val(),
                role: $("#role").val(),
            },
            success: function(result) {
                console.log("Save Data Result:", result);
                if (result == "success") {
                    alert("Berhasil disimpan");
                    tabel.ajax.reload();
                    $("#basicModal").modal("hide");
                } else {
                    alert("Gagal menyimpan data");
                }
            },
        });
    }

    function prosesEdit(button) {
        var username = $(button).data('username');
        var nama = $(button).data('nama');
        var password = $(button).data('password');
        var role = $(button).data('role');

        // Selanjutnya, lakukan apa yang diperlukan dengan data tersebut, seperti membuka modal
        openModal('edit', username, nama, password, role);
    }


    function prosesDelete(data) {
        var id_delete = data;
        $.ajax({
            method: "POST",
            url: "<?= base_url(); ?>public/Home/delete",
            data: {
                id: id_delete
            },
            success: function(result) {
                if (result == "success") {
                    alert("Berhasil dihapus");
                    tabel.ajax.reload();
                }
            }
        });
    }

    // Fungsi untuk mengatur status modal ke keadaan awal
    function resetModalStatus() {
        $("#username").prop("readonly", false);
        $("#role").prop("readonly", false);
        $("#password").attr("placeholder", "Enter Password");
        // Setel nilai lainnya ke keadaan awal jika diperlukan
    }
</script>
<?= $this->endSection(); ?>




























<?= $this->section('footer'); ?>
<?= view('Home/footer') ?>
<?= $this->endSection(); ?>