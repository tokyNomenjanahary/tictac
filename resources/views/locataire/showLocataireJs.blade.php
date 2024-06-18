@push('js')
    <script defer>
        $(document).ready(function() {
            if ($("#showLocataireArchiver").find("tr.empty").length !== 1) {
                var tableArchive = $('#showLocataireArchiver').DataTable({
                    "pageLength": 10,
                    "language": {
                        "paginate": {
                            "previous": "&lt;", // Remplacer "previous" par "<"
                            "next": "&gt;" // Remplacer "next" par ">"
                        },
                        "lengthMenu": "Filtre _MENU_ ",
                        "zeroRecords": "Pas de recherche corespondant",
                        "info": "Affichage _PAGE_ sur _PAGES_",
                        "infoEmpty": "Pas de recherche corespondant",
                        "infoFiltered": "(filtered from _MAX_ total records)"
                    },
                    ordering: false,
                });
                $('#ArchivesCounts').text(tableArchive.rows().count())
                $('#searchColocataireActif').on('input', function() {
                    tableArchive.search($(this).val()).draw();
                });
                $('#recherche').on('keyup', function() {
                    tableArchive.search(this.value).draw();
                });

                $('#filter-select-type').on('change', function() {
                    var selectedValue = this.value;
                    if (selectedValue === 'All') {
                        tableArchive.search('').columns().search('').draw();
                    } else {
                        tableArchive.column(0).search(selectedValue).draw();
                    }
                });
                $('#filter-select-bien').on('change', function() {
                    var selectedValue = this.value;
                    if (selectedValue === 'All') {
                        tableArchive.search('').columns().search('').draw();
                    } else {
                        tableArchive.column(3).search(selectedValue).draw();
                    }
                });
                $('#filter-select-location').on('change', function() {
                    var selectedValue = this.value;
                    if (selectedValue === 'All') {
                        tableArchive.search('').columns().search('').draw();
                    } else {
                        tableArchive.column(2).search(selectedValue).draw();
                    }
                });
                $('#filter-select-etat').on('change', function() {
                    var selectedValue = this.value;
                    if (selectedValue === 'All') {
                        tableArchive.search('').columns().search('').draw();
                    } else {
                        tableArchive.column(4).search(selectedValue).draw();
                    }
                });

                $('#rec').on('keyup', function() {
                    tableArchive.search(this.value).draw();
                });
            }
            if ($("#showLocataire").find("tr.empty").length !== 1) {
                var table = $('#showLocataire').DataTable({
                    "pageLength": 10,
                    "language": {
                        "paginate": {
                            "previous": "&lt;", // Remplacer "previous" par "<"
                            "next": "&gt;" // Remplacer "next" par ">"
                        },
                        "lengthMenu": "Filtre _MENU_ ",
                        "zeroRecords": "Pas de recherche corespondant",
                        "info": "Affichage _PAGE_ sur _PAGES_",
                        "infoEmpty": "Pas de recherche corespondant",
                        "infoFiltered": "(filtered from _MAX_ total records)"
                    },
                    ordering: false,
                });
                $('#ActifsCounts').text(table.rows().count())
                $('#searchColocataireActif').on('input', function() {
                    table.search($(this).val()).draw();
                });
                $('#recherche').on('keyup', function() {
                    table.search(this.value).draw();
                });
                $('#filter-select-type').on('change', function() {
                    var selectedValue = this.value;
                    if (selectedValue === 'All') {
                        table.search('').columns().search('').draw();
                    } else {
                        table.column(0).search(selectedValue).draw();
                    }
                });
                $('#filter-select-bien').on('change', function() {
                    var selectedValue = this.value;
                    if (selectedValue === 'All') {
                        table.search('').columns().search('').draw();
                    } else {
                        table.column(3).search(selectedValue).draw();
                    }
                });
                $('#filter-select-location').on('change', function() {
                    var selectedValue = this.value;
                    if (selectedValue === 'All') {
                        table.search('').columns().search('').draw();
                    } else {
                        table.column(2).search(selectedValue).draw();
                    }
                });
                $('#filter-select-etat').on('change', function() {
                    var selectedValue = this.value;
                    if (selectedValue === 'All') {
                        table.search('').columns().search('').draw();
                    } else {
                        table.column(4).search(selectedValue).draw();
                    }
                });
                $('#rec').on('keyup', function() {
                    table.search(this.value).draw();
                });
            }

            $('.dropdown-item.archive-link').on('click', (function(e) {
                $("#myLoader").removeClass("d-none")
                e.preventDefault();
                let trId = ('#' + $(this).data("id"))
                let aId = $(this).data("archive")
                $.get($(this).data("href"), function(result) {
                    if (aId === 1) {
                        // Récupération de la ligne à déplacer
                        var row = tableArchive.row(trId);
                        var data = row.data();
                        console.log('archiive');
                        $("#myLoader").addClass("d-none")
                        location.reload();
                        // Ajout des données à la nouvelle table
                        table.row.add(data).draw();
                        // Suppression de la ligne d'origine
                        row.remove().draw();
                        $('#ArchivesCounts').text(tableArchive.rows().count())
                        $('#ActifsCounts').text(table.rows().count())
                    } else {
                        // Récupération de la ligne à déplacer
                        var row1 = table.row(trId);
                        var data1 = row1.data();
                        // Ajout des données à la nouvelle table
                        console.log('desarchiive');
                        $("#myLoader").addClass("d-none")
                        location.reload();
                        tableArchive.row.add(data1).draw();

                        // Suppression de la ligne d'origine
                        row1.remove().draw();
                        $('#ArchivesCounts').text(tableArchive.rows().count())
                        $('#ActifsCounts').text(table.rows().count())
                        // $("#myLoader").addClass("d-none")
                    }
                    // Find the row you want to delete

                });
            }));


            let ids = ["#navs-top-actif", "#navs-top-archive"]
            $.each(ids, function(key, value) {
                $(value + " .select_all_state").on("change", function() {

                    if (value == "#navs-top-actif") {
                        table.draw()
                    } else {
                        tableArchive.draw()
                    }
                    for (let i = 0; i < $(value + " .checkbox").length; i++) {
                        if (this.checked) {
                            $(value + " .checkbox")[i].checked = true
                            $("#export").removeClass("d-none")
                        } else {
                            $(value + " .checkbox")[i].checked = false
                            $("#export").addClass("d-none")
                        }
                    }
                })

                $(value + " .checkbox").on("change", function(e) {
                    e.stopPropagation()


                    if ($(value + " .checkbox:checked").length > 0) {
                        $("#export").removeClass("d-none")
                    } else {
                        $("#export").addClass("d-none")
                    }
                    if ($(value + " .checkbox:checked").length == $(value + " .checkbox").length) {
                        $(value + " .select_all_state").prop("checked", true);
                    } else {
                        $(value + " .select_all_state").prop("checked", false);
                    }
                });
            });

            $('#navs-top-actif-tab, #navs-top-archive-tab').on('click', function() {
                let id = $(this).attr('id');
                if(id == "navs-top-actif-tab"){
                    $('#archive_data').html("{{ __('documents.Archiver') }}");
                }
                else{
                    $('#archive_data').html("{{ __('texte_global.desarchiver') }}");
                }
                for (let i = 0; i < $(".checkbox").length; i++) {
                    $(".checkbox")[i].checked = false
                }
                $(".select_all_state").prop("checked", false);
                $("#export").addClass("d-none")
            })

            $('#navs-top-archive-tab').on('click', function() {
                $("#filter-b").addClass('d-none')
            })
            $('#navs-top-actif-tab').on('click', function() {
                $("#filter-b").removeClass('d-none')
            })

            // $(".sub_chk").change(function() {
            //     if ($(".sub_chk:checked").length > 0) {

            //         document.getElementById('export').style.display = "block"
            //     } else {
            //         document.getElementById('export').style.display = "none"
            //     }


            //     $("#master").prop("checked", false);


            //     if ($(".sub_chk:checked").length == $(".checkbox").length) {
            //         $("#master").prop("checked", true);
            //     }
            // });


            $("#master").change(function() {
                if (this.checked) {
                    $(".sub_chk").prop("checked", true);;
                    document.getElementById('export').style.display = "block"
                } else {
                    $(".sub_chk").prop("checked", false);
                    document.getElementById('export').style.display = "none"
                }
            });
        });
    </script>
@endpush
