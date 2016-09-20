$(document).ready(function() {
      $('#bloc-datatables').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "dom":'<"row"<"search col-sm-6"f><"top col-sm-6 text-right"l>>rt<"pull-left"i>p<"clear">',
        language: {
          searchPlaceholder: "Search",
          search: "_INPUT_",
          "lengthMenu": "_MENU_"
        }
      });
    });