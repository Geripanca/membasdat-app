// Reset form saat modal ditutup (Add & Edit)
$(".cancelModalAddMateri, .cancelModalEditMateri").on("click", function () {
    $(".modalAdminMateri")[0].reset();
    $("#formModalAdminMateri #title, #formModalAdminMateri #file, #formModalAdminMateri #video, #formModalAdminMateri #category, #formEditModalAdminMateri #titleEdit, #formEditModalAdminMateri #fileEdit, #formEditModalAdminMateri #videoEdit, #formEditModalAdminMateri #categoryEdit")
        .removeClass("is-invalid");
});

// Tombol Edit Materi
$(".buttonEditMateri").on("click", function () {
    const code = $(this).data("code-materi");
    const title = $(this).data("title-materi");
    const category = $(this).data("category-materi");
    const video = $(this).data("video-materi");
    const url = $(this).data("url-materi");

    // isi form modal edit
    $(".codeMateri").val(code);
    $("#titleEdit").val(title);
    $("#categoryEdit").val(category).trigger("change");
    $("#videoEdit").val(video);
    $("#urlEdit").val(url);



    // buka modal edit
    $("#formEditModalAdminMateri").modal("show");
});

// Tombol Delete Materi
$(".buttonDeleteMateri").on("click", function () {
    const data = $(this).data("title-materi");
    const code = $(this).data("code-materi");

    $(".materiMessagesDelete").html(
        "Anda yakin ingin menghapus materi dengan nama <strong>'" +
            data +
            "'</strong> ?"
    );
    $(".codeMateri").val(code);

    $("#deleteMateriConfirm").modal("show");
});
