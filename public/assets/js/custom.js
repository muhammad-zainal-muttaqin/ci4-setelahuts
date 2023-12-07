function showSuccessModal() {
    // Menambahkan delay 2 detik (2000 milidetik) sebelum menampilkan modal
    setTimeout(function() {
        // Tampilkan modal
        $("#successModal").css("display", "block");

        // Animasi loading bar
        var loadingBar = $("#loading-bar");
        loadingBar.css("width", "0%");
        loadingBar.animate({ width: "100%" }, 2000, function() {
            // Animasi selesai, sembunyikan modal
            $("#successModal").css("display", "none");
        });
    }, 2000); // Delay 2 detik
}