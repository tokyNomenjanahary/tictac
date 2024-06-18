$(document).ready(function () {

  $(".stop-prop").on("click", function (e) {
    e.stopPropagation()
  });
  $('.handle-event').on("click", function (e) {
    e.stopPropagation();
    $("body").trigger("click");
  })

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
    },
    cache: false
  });

  var myTable = $("#etat-des-lieux").DataTable({
    "responsive": true,
    "pageLength": 15,
    "language": {
      "lengthMenu": "Filtre _MENU_ ",
      "zeroRecords": "Pas de recherche corespondant",
      "info": "Affichage _PAGE_ sur _PAGES_",
      "infoEmpty": "Pas de recherche corespondant",
      "infoFiltered": "(filtré à partir de _MAX_ enregistrements au total)",
      "search": "Rechercher:",
      "paginate": {
        first: '«',
        previous: '‹',
        next: '›',
        last: '»'
      },
    }
  })
  var myTableArc = $("#etat-des-lieux-arc").DataTable({
    "responsive": true,
    "pageLength": 15,
    "language": {
      "lengthMenu": "Filtre _MENU_ ",
      "zeroRecords": "Pas de recherche corespondant",
      "info": "Affichage _PAGE_ sur _PAGES_",
      "infoEmpty": "Pas de recherche corespondant",
      "infoFiltered": "(filtré à partir de _MAX_ enregistrements au total)",
      "search": "Rechercher:",
      "paginate": {
        first: '«',
        previous: '‹',
        next: '›',
        last: '»'
      },
    }
  })
  $('#rec').on('keyup', function() {
    myTableArc.search(this.value).draw();
  });
  $('#rec').on('keyup', function() {
    myTable.search(this.value).draw();
  });
  $(".select_all_state").on("change", function () {
    myTable.draw()
    for (let i = 0; i < $(".check-etat").length; i++) {
      if (this.checked) {
        $(".check-etat")[i].checked = true
        $("#action_btn").removeClass("d-none")
      } else {
        $(".check-etat")[i].checked = false
        $("#action_btn").addClass("d-none")
      }
    }
  })

  $(".select_all_state_arc").on("change", function () {
    myTableArc.draw()
    for (let i = 0; i < $(".check-etat-arc").length; i++) {
      if (this.checked) {
        $(".check-etat-arc")[i].checked = true
        $("#action_btn_arc").removeClass("d-none")
      } else {
        $(".check-etat-arc")[i].checked = false
        $("#action_btn_arc").addClass("d-none")
      }
    }
  })

  $(".select_all_state").prop("checked", false);
  $(".select_all_state_arc").prop("checked", false);
  $(".check-etat").on("change", function (e) {
    e.stopPropagation()
    if ($(".check-etat:checked").length > 0) {
      $("#action_btn").removeClass("d-none")
    } else {
      $("#action_btn").addClass("d-none")
    }
    if ($(".check-etat:checked").length == $(".check-etat").length) {
      $(".select_all_state").prop("checked", true);
    } else {
      $(".select_all_state").prop("checked", false);
    }
  });

  $(".check-etat-arc").on("change", function (e) {
    e.stopPropagation()
    if ($(".check-etat-arc:checked").length > 0) {
      $("#action_btn_arc").removeClass("d-none")
    } else {
      $("#action_btn_arc").addClass("d-none")
    }
    if ($(".check-etat-arc:checked").length == $(".check-etat-arc").length) {
      $(".select_all_state_arc").prop("checked", true);
    } else {
      $(".select_all_state_arc").prop("checked", false);
    }
  });

  var etat_ids = []
  $(".handle-click").on('click', function () {
    etat_ids = $(".check-etat:checked").map(function () {
      return this.value;
    }).get();
  })

  $(".handle-click-arc").on('click', function () {
    etat_ids = $(".check-etat-arc:checked").map(function () {
      return this.value;
    }).get();
  })

  $(".delete-etat").on("click", function (e) {
    e.preventDefault()
    $("#myLoader").removeClass("d-none")
    $.ajax({
      type: 'GET',
      url: "/etats-des-lieux/delete-etat",
      data: {
        etat_ids: etat_ids,
      },
      success: function () {
        location.reload();
      },
      error: function (data) {
        // $("#myLoader").addClass("d-none")
      }
    });
  })

  $(".delete-etat-one").on("click", function (e) {
    e.preventDefault()
    e.stopPropagation()
    etat_ids = [$(this).attr('data-id')]
  })
  $("#trashed").on("click", function(e) {
    e.preventDefault()
    e.stopPropagation()
      $("#myLoader").removeClass("d-none")
      $.ajax({
        type: 'GET',
        url: "/etats-des-lieux/delete-etat",
        data: {
          etat_ids: etat_ids,
        },
        success: function () {
            location.reload();
        },
        error: function (data) {
            // $("#myLoader").addClass("d-none")
        }
      });
  });


  $(".archive-etat").on("click", function (e) {
    e.preventDefault()
    $("#myLoader").removeClass("d-none")
    $.ajax({
      type: 'GET',
      url: "/etats-des-lieux/archiver",
      data: {
        etat_ids: etat_ids,
      },
      success: function () {
        location.reload();
      },
      error: function (data) {
        // $("#myLoader").addClass("d-none")
      }
    });
  })


  $(".archive-etat-one").on("click", function (e) {
    e.preventDefault()
    e.stopPropagation()
    $("#myLoader").removeClass("d-none")
    etat_ids = [$(this).attr('data-id')]
    $.ajax({
      type: 'GET',
      url: "/etats-des-lieux/archiver",
      data: {
        etat_ids: etat_ids,
      },
      success: function () {
        location.reload();
      },
      error: function (data) {
        // $("#myLoader").addClass("d-none")
      }
    });
  })

})
