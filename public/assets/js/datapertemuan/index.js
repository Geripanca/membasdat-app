$(document).on("click", ".buttonEditMeeting", function () {
    let id = $(this).data("id");
    let judul = $(this).data("judul");
    let deskripsi = $(this).data("deskripsi");

    $(".idMeeting").val(id);
    $("#judulEdit").val(judul);
    $("#deskripsiEdit").val(deskripsi);

    // Replace :id dengan ID yang benar
    let action = $("#formEditMeeting").attr("action").replace(':id', id);
    $("#formEditMeeting").attr("action", action);

    $("#formEditModalMeeting").modal("show");
});
