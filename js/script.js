$(document).ready(function () {
  // hilangkan tombol cari
  $("#tombol-cari").hide();

  // event ketika keyword ditulis
  $("#keyword").on("keyup", function () {
    // munculkan icon loading
    $(".loader").show();
    $(".paginationLinks").hide();

    // ajax menggunakan load
    // $('#container').load('ajax/mahasiswa.php?keyword=' + $('#keyword').val());

    // $.get()
    $.get("ajax/roti.php?keyword=" + $("#keyword").val(), function (data) {
      $("#container").html(data);
      $(".loader").hide();
    });
  });
});

// document.addEventListener("DOMContentLoaded", function () {
//   var keywordInput = document.getElementById("keyword");
//   var container = document.getElementById("container");
//   var paginationLinks = document.getElementById("paginationLinks");

//   keywordInput.addEventListener("keyup", function () {
//     var keyword = keywordInput.value;
//     var xhr = new XMLHttpRequest();

//     xhr.onreadystatechange = function () {
//       if (xhr.readyState == 4 && xhr.status == 200) {
//         var response = xhr.responseText.split('<div class="pagination">');
//         container.innerHTML = response[0];
//         paginationLinks.innerHTML = response[1] || "";
//       }
//     };

//     xhr.open(
//       "GET",
//       "ajax/roti.php?keyword=" + encodeURIComponent(keyword),
//       true
//     );
//     xhr.send();
//   });
// });
