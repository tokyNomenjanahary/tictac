@extends('proprietaire.index')

@section('file-input')
  <!-- if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below -->
  {{-- <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css"> --}}

  <!-- bootstrap 5.x or 4.x is supported. You can also use the bootstrap css 3.3.x versions -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">

  <!-- default icons used in the plugin are from Bootstrap 5.x icon library (which can be enabled by loading CSS below) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">

  <!-- alternatively you can use the font awesome icon library if using with `fas` theme (or Bootstrap 4.x) by uncommenting below. -->
  <!-- link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" crossorigin="anonymous" -->

  <!-- the fileinput plugin styling CSS file -->
  <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <style>
    .kv-file-rotate, .kv-file-upload {
      display: none !important;
    }
    .nav-tabs .nav-item .nav-link:not(.active) {
      background-color: rgb(250, 250, 250);
    }
    .nav-tabs .nav-item .nav-link.active  {
      border-top: 3px solid blue !important;
      border-bottom: 0 !important;
    }
    .nav-tabs .nav-item .nav-link   {
      color: blue !important;
      font-size: 13px !important;
    }
    .nav-tabs .nav-item .nav-link.active {
      border-top: 3px solid blue !important;
      border-bottom: 3px solid white !important;
    }
    .nav-align-top .nav-tabs .nav-item .nav-link {
      border-top-right-radius: 0 !important;
    }
  </style>
@endsection

@push('css')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
  integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@section('contenue')
<!-- Content wrapper -->
<div class="content-wrapper" id="scTop">
  <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <!-- HEADER -->
      <div class="mb-4">
        <div>
            @if (isset($a))
              <h3 class="page-header page-header-top m-0">Nouvel état des lieux</h3>

            @else
                @if (isset($etat_lieu))
                <h3 class="page-header page-header-top m-0">Modifier état des lieux</h3>
                @else
                <h3 class="page-header page-header-top m-0">Nouvel état des lieux</h3>
                @endif
            @endif
        </div>

      </div>
      <!-- END HEADER -->
      <div class="col-12">
        <div class="nav-align-top mb-4">
          <ul class="nav nav-tabs" role="tablist" id="nav-id">
            <li class="nav-item stepHa">
              <button type="button" class="nav-link active text-uppercase" id="info-gen-id" data-id="info-gen-id" role="tab" data-bs-toggle="tab" data-bs-target="#navs-info-gen" aria-controls="navs-info-gen" aria-selected="true">
                Informations générales
              </button>
            </li>
            @if (isset($etat_lieu) && !$etat_lieu->etat_pieces->isEmpty())
              @foreach ($etat_lieu->etat_pieces as $piece)
              @php
                $mytime = Carbon\Carbon::now()->timestamp + rand();
                $id_arr[] = $mytime
              @endphp
              <li class="nav-item stepHa" id="{{ 'piece-nav-' . $mytime }}">
                <button type="button" id="{{ 'nav-tab-' . $mytime }}" data-id="{{ 'nav-tab-' . $mytime }}" class="nav-link text-uppercase" role="tab" data-bs-toggle="tab" data-bs-target="{{ '#nav-nouvelle-piece-' . $mytime }}" aria-controls="{{ 'nav-nouvelle-piece-' . $mytime }}" aria-selected="true">
                  {{ $piece->identifiant }}
                </button>
              </li>
              @endforeach
            @endif


            <li class="nav-item stepHa">
              <button type="button" class="nav-link text-uppercase" id="observer" data-id="observer" role="tab" data-bs-toggle="tab" data-bs-target="#navs-observer" aria-controls="navs-observer" aria-selected="true">
                Observation
              </button>
            </li>
            <li class="nav-item stepHa" id="nouv">
              <button type="button" class="nav-link text-uppercase">
                nouvelle pièce
              </button>
            </li>
          </ul>
          <div class="tab-content" id="tab-id">
            <div class="tab-pane fade active show" id="navs-info-gen" role="tabpanel">
              <!-- CARD FILTER -->
              <form action="{{ route('proprietaire.enregistrer-etat') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                  <div class="card h-100">
                    {{-- INFO GENERAL --}}
                    @if(isset($a))
                        @include('proprietaire.etat-info-general')
                        {{-- FIN INFO GENERAL --}}

                        <!-- Relevés des compteurs eau, gaz... -->
                        @include('proprietaire.etat-compteur-eau')
                    @else
                        @include('proprietaire.etat-info-general')
                        {{-- FIN INFO GENERAL --}}

                        <!-- Relevés des compteurs eau, gaz... -->
                        @include('proprietaire.etat-compteur-eau')
                        @include('proprietaire.etat-compteur-electrique')
                        <!-- fin Relevé compteur électrique. -->

                        <!-- Type de chauffage. -->
                        @include('proprietaire.etat-type-chauffage')
                        <!-- fin type chauffage. -->

                        <!-- Production d’eau chaude -->
                        @include('proprietaire.etat-production-eau-chaude')
                        <!-- fin Production d’eau chaude -->

                        <!-- Remise des clés -->
                        @include('proprietaire.etat-remise-cle')
                        <div class="row p-3 align-items-end">
                            <label for="etat-files-update">
                              <div class="file-loading">
                                <input id="etat-files-update" name="etat-files-update[]" type="file" multiple>
                              </div>
                            </label>
                        </div>
                    @endif
                    <!-- fin Relevés des compteurs eau, gaz... -->

                    <!-- Relevé compteur électrique. -->

                    <!-- fin remise des clés -->

                  </div>
                </div>
              </form>
              <!-- END CARD FILTER -->
            </div>
            <div class="tab-pane fade" id="navs-observer" role="tabpanel">
              <!-- CARD FILTER -->
              <div>
                <label for="observerText" class="form-label">Observation</label>
                <textarea class="form-control" id="observerText" rows="3">@if (isset($etat_lieu)){{ $etat_lieu->observation }}@endif</textarea>
              </div>

              <!-- END CARD FILTER -->
            </div>
            @if (isset($etat_lieu) && !$etat_lieu->etat_pieces->isEmpty())
              @php
                $i = 0
              @endphp
              @foreach ($etat_lieu->etat_pieces as $piece)
                <div class="tab-pane fade mb-3 all-piece" id="{{ 'nav-nouvelle-piece-' . $id_arr[$i] }}" role="tabpanel">
                  <div class="card h-100">
                    <div>
                      <div class="card-header border-bottom py-2 px-3 mb-3">
                        <div class="card-title mb-0 row align-items-center">
                          <div class="w-auto">
                            <h5 class="m-0 me-2 w-auto">Pièce</h5>
                          </div>
                          <div class="w-auto">
                            <span class="btn-sm btn-danger btn-remove-piece get-pi" data-pi-id="{{ $piece->id }}" data-id="{{ 'nav-nouvelle-piece-' . $id_arr[$i] }}" data-nav="{{ 'piece-nav-' . $id_arr[$i] }}">
                              <i class="fa-solid fa-trash-can"></i> Supprimer
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="" id="">
                        <div class="card-body p-0">
                          <div class="p-3 border-bottom">
                            <div class="mb-3 row">
                              <label for="etat_name" class="col-md-2 col-form-label text-end">Nom de la pièce</label>
                              <div class="col-lg-5">
                                <input class="{{ 'form-control cls-piece-' . $id_arr[$i] }}" id="{{ 'piece-id-' . $id_arr[$i] }}" data-input="{{ 'piece-id-' . $id_arr[$i] }}" type="text" placeholder="Identifiant" value='{{ $piece->identifiant }}'>
                                <input type="number" hidden data-input="{{ 'piece-id-modif-' . $id_arr[$i] }}" value="{{ $piece->id }}">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-header border-bottom py-2 px-3 mb-3">
                        <div class="card-title mb-0">
                          <h5 class="m-0 me-2 w-auto">Murs, plafond, sol</h5>
                        </div>
                      </div>
                      <div id="{{ 'mur-plafond-sol-' . $id_arr[$i] }}">
                            {{-- @php
                                dd($etat_lieu->compteur_eaux);
                            @endphp --}}
                           @if (!$piece->properties->isEmpty())
                          @foreach ($piece->properties as $property)
                          <div class="row p-3 align-items-end">
                            <div class="col-md-1 text-end"></div>
                            <input type="number" hidden data-input="{{ 'mur-val-modif-mur-plafond-sol-' . $id_arr[$i] }}" value="{{ $property->id }}">
                            <div class="col-lg-2">
                              <div>
                                <label class="col-form-label text-end">Element</label>
                              </div>
                              @if(isset($a))
                                <input class="{{ 'form-control cls-piece-' . $id_arr[$i] }}" type="text" data-input="{{ 'mur-val-mur-plafond-sol-' . $id_arr[$i] }}" value="{{ $property->identifiant }}">
                              @else
                                <input class="{{ 'form-control cls-piece-' . $id_arr[$i] }}" type="text" data-input="{{ 'mur-val-mur-plafond-sol-' . $id_arr[$i] }}" value="{{ $property->element }}">
                              @endif
                            </div>
                            <div class="col-lg-2">
                              <div>
                                <label class="col-form-label text-end">revêtement</label>
                              </div>
                              <input class="{{ 'form-control cls-piece-' . $id_arr[$i] }}" type="text" data-input="{{ 'mur-revetement-mur-plafond-sol-' . $id_arr[$i] }}" value="{{ $property->revetement }}">
                            </div>
                            <div class="col-lg-2">
                              <div>
                                <label class="col-form-label text-end">etat d'usure</label>
                              </div>
                              <select class="form-select cls-piece- . $id_arr[$i]" data-input="{{ 'mur-usure-mur-plafond-sol-' . $id_arr[$i] }}">
                                <option value="">Non vérifié</option>
                                @foreach ($etat_usures as $etat_usure)
                                  <option value="{{ $etat_usure->id }}" @if ($property->etat_usure_id == $etat_usure->id) selected @endif>{{ $etat_usure->name }}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-lg">
                              <div>
                                <label class="col-form-label text-end">Commentaire</label>
                              </div>
                              <input class="{{ 'form-control cls-piece-' . $id_arr[$i] }}" type="text" placeholder="Description" data-input="{{ 'mur-commentaire-mur-plafond-sol-' . $id_arr[$i] }}" value="{{ $property->commentaire }}">
                            </div>
                          </div>
                          @endforeach
                        @endif
                      </div>
                      <div class="row border-bottom g-0">
                        <div class="col-md-1"></div>
                        <div class="col ps-3 mb-3">
                          <span class="btn btn-primary" data-cls="{{ 'cls-piece-' . $id_arr[$i] }}" data-id="{{ 'mur-plafond-sol-' . $id_arr[$i] }}" id="{{ 'add-wall-' . $id_arr[$i] }}">Ajouter un autre champ</span>
                        </div>
                      </div>


                      @if(isset($a))

                      @else
                      <div class="card-header border-bottom py-2 px-3 mb-3">
                        <div class="card-title mb-0">
                          <h5 class="m-0 me-2 w-auto">Equipement</h5>
                        </div>
                      </div>
                      <div id="{{ 'piece-equipement-' . $id_arr[$i] }}">
                        @if (!$piece->equipements->isEmpty())
                          @foreach ($piece->equipements as $equipement)
                          <div class="row p-3 align-items-end">
                            <div class="col-md-1 text-end"></div>
                            <input type="number" hidden data-input="{{ 'equip-element-modif-piece-equipement-' . $id_arr[$i]}}" value="{{ $equipement->id }}">
                            <div class="col-lg-2">
                              <div>
                                <label class="col-form-label text-end">Element</label>
                              </div>
                              <input class="{{ 'form-control cls-piece-' . $id_arr[$i] }}" type="text" data-input="{{ 'equip-element-piece-equipement-' . $id_arr[$i] }}" value="{{ $equipement->element }}">
                            </div>
                            <div class="col-lg-2">
                              <div>
                                <label class="col-form-label text-end">matériaux</label>
                              </div>
                              <input class="{{ 'form-control cls-piece-' . $id_arr[$i] }}" type="text" data-input="{{ 'materiaux-piece-equipement-' . $id_arr[$i] }}" value="{{ $equipement->materiaux }}">
                            </div>
                            <div class="col-lg-2">
                              <div>
                                <label class="col-form-label text-end">etat d'usure</label>
                              </div>
                              <select class="{{ 'form-select cls-piece-' . $id_arr[$i] }}" data-input="{{ 'equipe-usure-piece-equipement-' . $id_arr[$i] }}">
                                <option value="" selected>Non vérifié</option>
                                @foreach ($etat_usures as $etat_usure)
                                  <option value="{{ $etat_usure->id }}" @if ($equipement->etat_usure_id == $etat_usure->id) selected @endif>{{ $etat_usure->name }}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-lg-2">
                              <div>
                                <label class="col-form-label text-end">Fonctionnement</label>
                              </div>
                              <select class="{{ 'form-select cls-piece-' . $id_arr[$i] }}" data-input="{{ 'equip-fonction-piece-equipement-' . $id_arr[$i] }}">
                                <option value="" selected>Choisir</option>
                                @foreach ($fonctionnements as $fonctionnement)
                                  <option value="{{ $fonctionnement->id }}" @if ($equipement->fonctionnement_id == $fonctionnement->id) selected @endif>{{ $fonctionnement->name }}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-lg">
                              <div>
                                <label class="col-form-label text-end">Commentaire</label>
                              </div>
                              <input class="{{ 'form-control cls-piece-' . $id_arr[$i] }}" type="text" placeholder="Description" data-input="{{ 'commentaire-piece-equipement-' . $id_arr[$i] }}" value="{{ $equipement->commentaire }}">
                            </div>
                          </div>
                          @endforeach
                        @endif
                      </div>
                      <div class="row border-bottom g-0">
                        <div class="col-md-1"></div>
                        <div class="col ps-3 mb-3">
                          <span class="btn btn-primary" data-id="{{ 'piece-equipement-' . $id_arr[$i] }}" data-cls="{{ 'cls-piece-' . $id_arr[$i] }}" id="{{ 'add-equipement-' . $id_arr[$i] }}">Ajouter un autre champ</span>
                        </div>
                      </div>
                      @endif




                    </div>
                  </div>
                </div>
                <script>
                  $(document).ready(function () {
                    var date = {!! json_encode($id_arr[$i]) !!};
                    $('#add-wall-'+date).on('click',function () {
                      let mur = $(this).attr('data-id');
                      let cls = $(this).attr('data-cls')
                      let child = `
                                  <div class="row p-3 align-items-end">
                                    <div class="col-md-1 text-end"><span class="btn btn-danger btn-remove"><i class="fa-solid fa-trash-can"></i></span></div>
                                    <div class="col-lg-2">
                                      <input type="number" hidden data-input="mur-val-modif-${mur}">
                                      <div>
                                        <label class="col-form-label text-end">Element</label>
                                      </div>
                                      <input class="form-control ${cls}" type="text" data-input="mur-val-${mur}" name="elem[]">
                                    </div>
                                    <div class="col-lg-2">
                                      <div>
                                        <label class="col-form-label text-end">revêtement</label>
                                      </div>
                                      <input class="form-control ${cls}" type="text" data-input="mur-revetement-${mur}">
                                    </div>
                                    <div class="col-lg-2">
                                      <div>
                                        <label for="fonction-eau" class="col-form-label text-end">etat d'usure</label>
                                      </div>
                                      <select class="form-select ${cls}" data-input="mur-usure-${mur}">
                                        <option value="" selected>Non vérifié</option>
                                        @foreach ($etat_usures as $etat_usure)
                                          <option value="{{ $etat_usure->id }}">{{ $etat_usure->name }}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <div class="col-lg">
                                      <div>
                                        <label class="col-form-label text-end">Commentaire</label>
                                      </div>
                                      <input class="form-control ${cls}" type="text" placeholder="Description" data-input="mur-commentaire-${mur}">
                                    </div>
                                  </div>
                                `
                      $("#" + mur).append(child)
                      $("span.btn-remove").on('click', function (e) {
                        $(this).closest('.row.p-3.align-items-end').remove()
                      })
                    })

                    $('#add-equipement-'+date).on('click',function () {
                      let equ = $(this).attr('data-id');
                      let cls = $(this).attr('data-cls')
                      let child = `
                                  <div class="row p-3 align-items-end">
                                    <div class="col-md-1 text-end"><span class="btn btn-danger btn-remove"><i class="fa-solid fa-trash-can"></i></span></div>
                                    <input type="number" hidden data-input="equip-element-modif-${equ}">
                                    <div class="col-lg-2">
                                      <div>
                                        <label class="col-form-label text-end">Element</label>
                                      </div>
                                      <input class="form-control ${cls}" type="text" data-input="equip-element-${equ}">
                                    </div>
                                    <div class="col-lg-2">
                                      <div>
                                        <label class="col-form-label text-end">matériaux</label>
                                      </div>
                                      <input class="form-control ${cls}" type="text" data-input="materiaux-${equ}">
                                    </div>
                                    <div class="col-lg-2">
                                      <div>
                                        <label for="fonction-eau" class="col-form-label text-end">etat d'usure</label>
                                      </div>
                                      <select id="fonction-eau" class="form-select ${cls}" data-input="equipe-usure-${equ}">
                                        <option value="" selected>Non vérifié</option>
                                        @foreach ($etat_usures as $etat_usure)
                                          <option value="{{ $etat_usure->id }}">{{ $etat_usure->name }}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <div class="col-lg-2">
                                      <div>
                                        <label for="fonction-eau" class="col-form-label text-end">Fonctionnement</label>
                                      </div>
                                      <select id="fonction-eau" class="form-select ${cls}" data-input="equip-fonction-${equ}">
                                        <option value="" selected>Choisir</option>
                                        @foreach ($fonctionnements as $fonctionnement)
                                          <option value="{{ $fonctionnement->id }}">{{ $fonctionnement->name }}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <div class="col-lg">
                                      <div>
                                        <label class="col-form-label text-end">Commentaire</label>
                                      </div>
                                      <input class="form-control ${cls}" type="text" placeholder="Description" data-input="commentaire-${equ}">
                                    </div>
                                  </div>
                                  `
                      $("#" + equ).append(child)
                      $("span.btn-remove").on('click', function (e) {
                        $(this).closest('.row.p-3.align-items-end').remove()
                      })
                    })
                  })
                </script>
                @php
                $i++
                @endphp
                @endforeach
            @endif
          </div>
          {{-- end file input --}}
          <div class="card-body p-0">
            <div class="row align-items-center">
              <label class="col-md-2 col-form-label text-end"></label>
              <div class="col-lg-8 text-end">
                @if(isset($a))
                <button class="btn btn-success d-none" id="prec-etat">Précedent</button>
                <button class="btn btn-warning" id="enregistrer-inv" data-id="{{ $etat_lieu->id }}">Enregister le transfer</button>
                <button class="btn btn-success" id="suiv-etat">Suivant</button>
                @else
                    @if (isset($etat_lieu))
                        <button class="btn btn-warning" id="enregistrer-etat" data-id="{{ $etat_lieu->id }}">Valider les modifications</button>
                    @else
                        <button class="btn btn-success d-none" id="prec-etat">Précedent</button>
                        <button class="btn btn-primary" id="enregistrer-etat">Sauvegarder</button>
                        <button class="btn btn-success" id="suiv-etat">Suivant</button>
                    @endif
                @endif

              </div>
            </div>
          </div>
          {{-- FIN buttons --}}
        </div>
      </div>
    </div>
  <!-- / Content -->
  <div class="content-backdrop fade"></div>
</div>
@endsection

@push('script')
  <!-- buffer.min.js and filetype.min.js are necessary in the order listed for advanced mime type parsing and more correct
  preview. This is a feature available since v5.5.0 and is needed if you want to ensure file mime type is parsed
  correctly even if the local file's extension is named incorrectly. This will ensure more correct preview of the
  selected file (note: this will involve a small processing overhead in scanning of file contents locally). If you
  do not load these scripts then the mime type parsing will largely be derived using the extension in the filename
  and some basic file content parsing signatures. -->
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/buffer.min.js" type="text/javascript"></script>
  {{-- <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/filetype.min.js" type="text/javascript"></script> --}}

  <!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you
      wish to resize images before upload. This must be loaded before fileinput.min.js -->
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/piexif.min.js" type="text/javascript"></script>

  <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
      This must be loaded before fileinput.min.js -->
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/sortable.min.js" type="text/javascript"></script>

  <!-- bootstrap.bundle.min.js below is needed if you wish to zoom and preview file content in a detail modal
      dialog. bootstrap 5.x or 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
  {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> --}}

  <!-- the main fileinput plugin script JS file -->
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/fileinput.min.js"></script>

  <!-- following theme script is needed to use the Font Awesome 5.x theme (`fa5`). Uncomment if needed. -->
  <!-- script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/themes/fa5/theme.min.js"></script -->

  <!-- optionally if you need translation for your language then include the locale file as mentioned below (replace LANG.js with your language locale) -->
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/locales/LANG.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.5.0/js/locales/fr.js"
  integrity="sha512-ncBK/c/2Y7C/dQ94Ye0QDDBeMrFY7yCb3KGod1KVRQR4nSlXKAXoCzpwHysH5BPMS/hXAe5xaw4/bYyuMjTY4A==" crossorigin="anonymous"
  referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script>
    $(document).ready(function () {
      $('input[type="number"]').on('input', function() {
                var value = parseFloat($(this).val());
                if (value < 0  ) {
                $(this).addClass('is-invalid');
                $(this).next('.error-message').remove();
                $(this).after('<span class="error-message text-danger" style="font-size:10px;">Veuillez saisir un nombre positif</span>');
                } else if (value === '') {
                value = 0;
                } else {
                $(this).removeClass('is-invalid');
                $(this).next('.error-message').remove();
                }
                });
        $("#enregistrer-inv").on("click", function (e) {
            e.preventDefault()
            let id = $(this).attr("data-id")
            let url = "{{ route('proprietaire.enregistrer-etat-inventaire') }}"
            if (typeof(id) == 'string') {
            url = "{{ route('proprietaire.enregistrer-etat-inventaire', ":id") }}"
            url = url.replace(':id', id);
            }
            $("#myLoader").removeClass("d-none")
            $(".form-control").removeClass('is-invalid')
            $(".form-select").removeClass('is-invalid')
            $(".no-error").text('')
            // COMPTEUR EAU
            let name_eaux = $('input[name="name_eau[]"]').map(function(){
                            return this.value;
                        }).get();

            let numero_eaux = $('input[name="numero_eau[]"]').map(function(){
                            return this.value;
                        }).get();

            let volume_eaux = $('input[name="volume_eau[]"]').map(function(){
                            return this.value;
                        }).get();

            let fontion_eaux = $('select[name="fontion_eau[]"]').map(function(){
                            return this.value;
                        }).get();

            let observation_eaux = $('input[name="observation_eau[]"]').map(function(){
                            return this.value
                        }).get();

            // COMPTEUR ELECTRIQUES
            let name_electriques = $('input[name="name_electrique[]"]').map(function(){
                            return this.value;
                        }).get();

            let numero_electriques = $('input[name="numero_electrique[]"]').map(function(){
                            return this.value;
                        }).get();

            let volume_electriques = $('input[name="volume_electrique[]"]').map(function(){
                            return this.value;
                        }).get();

            let fonction_electriques = $('select[name="fonction_electrique[]"]').map(function(){
                            return this.value;
                        }).get();

            let observartion_electriques = $('input[name="observartion_electrique[]"]').map(function(){
                            return this.value;
                        }).get();

            // TYPE DE CHAUFFAGE
            let name_chauffages = $('input[name="name_chauffage[]"]').map(function(){
                            return this.value;
                        }).get();

            let numero_chauffages = $('input[name="numero_chauffage[]"]').map(function(){
                            return this.value;
                        }).get();

            let volume_chauffages = $('input[name="volume_chauffage[]"]').map(function(){
                            return this.value;
                        }).get();

            let fonction_chauffages = $('select[name="fonction_chauffage[]"]').map(function(){
                            return this.value;
                        }).get();

            let observation_chauffages = $('input[name="observation_chauffage[]"]').map(function(){
                            return this.value;
                        }).get();

            // PRODUCTION EAU CHAUDE

            let name_production_eaux = $('input[name="name_production_eau[]"]').map(function(){
                            return this.value;
                        }).get();

            let fonction_production_eaux = $('select[name="fonction_production_eau[]"]').map(function(){
                            return this.value;
                        }).get();

            let observation_production_eaux = $('input[name="observation_production_eau[]"]').map(function(){
                            return this.value;
                        }).get();

            // MODIFICATION

            let compteur_eaux_modif = $('input[name="compteur_eau_modif[]"]').map(function(){
                            return this.value;
                        }).get();

            let compteur_electriques_modif = $('input[name="compteur_electriques_modif[]"]').map(function(){
                            return this.value;
                        }).get();

            let type_chauffage_modif = $('input[name="type_chauffage_modif[]"]').map(function(){
                            return this.value;
                        }).get();

            let production_eau_chaude_modif = $('input[name="production_eau_chaude_modif[]"]').map(function(){
                            return this.value;
                        }).get();

            // Piece
            var dataPiMur = []
            var dataPiRev = []
            var dataPiUsure = []
            var dataPiCommentaire = []
            var dataEqiEl = []
            var dataEqiMat = []
            var dataEqiUsure = []
            var dataEqiFonc = []
            var dataEqiCom = []
            var dataPiTitl = []
            var dataPiTitlId = []
            var dataPropId = []
            var dataEquipId = []
            var piTitl
            var piTitlId
            var propId
            var equipId
            var mur_val
            var mur_revetement
            var mur_usure
            var mur_commentaire
            var equi_el
            var equi_mat
            var equi_usure
            var equi_fonc
            var equi_com

            var formData = new FormData();
            $('.all-piece').each(function(index, element) {
            let idKey = $(element).attr('id').split("-").pop()
            piTitl = $('input[data-input="piece-id-'+idKey+'"]').val()
            piTitlId = $('input[data-input="piece-id-modif-'+idKey+'"]').val()
            $(".cls-piece-"+idKey).each(function (i, el) {
                propId = $('input[data-input="mur-val-modif-mur-plafond-sol-'+idKey+'"]').map(function(){
                            return this.value;
                        }).get();
                equipId = $('input[data-input="equip-element-modif-piece-equipement-'+idKey+'"]').map(function(){
                            return this.value;
                        }).get();
                mur_val = $('input[data-input="mur-val-mur-plafond-sol-'+idKey+'"]').map(function(){
                            return this.value;
                        }).get();
                mur_revetement = $('input[data-input="mur-revetement-mur-plafond-sol-'+idKey+'"]').map(function(){
                            return this.value;
                        }).get();
                mur_usure = $('select[data-input="mur-usure-mur-plafond-sol-'+idKey+'"]').map(function(){
                            return this.value;
                        }).get();
                mur_commentaire = $('input[data-input="mur-commentaire-mur-plafond-sol-'+idKey+'"]').map(function(){
                            return this.value;
                        }).get();
                equi_el = $('input[data-input="equip-element-piece-equipement-'+idKey+'"]').map(function(){
                            return this.value;
                        }).get();
                equi_mat = $('input[data-input="materiaux-piece-equipement-'+idKey+'"]').map(function(){
                            return this.value;
                        }).get();
                equi_usure = $('select[data-input="equipe-usure-piece-equipement-'+idKey+'"]').map(function(){
                            return this.value;
                        }).get();
                equi_fonc = $('select[data-input="equip-fonction-piece-equipement-'+idKey+'"]').map(function(){
                            return this.value;
                        }).get();
                equi_com = $('input[data-input="commentaire-piece-equipement-'+idKey+'"]').map(function(){
                            return this.value;
                        }).get();

            })
            dataPiMur.push(mur_val)
            dataPiRev.push(mur_revetement)
            dataPiUsure.push(mur_usure)
            dataPiCommentaire.push(mur_commentaire)
            dataEqiEl.push(equi_el)
            dataEqiMat.push(equi_mat)
            dataEqiUsure.push(equi_usure)
            dataEqiFonc.push(equi_fonc)
            dataEqiCom.push(equi_com)
            dataPiTitl.push(piTitl)
            dataPiTitlId.push(piTitlId)
            dataPropId.push(propId)
            dataEquipId.push(equipId)

            });
            for (let index = 0; index < dataPiMur.length; index++) {
            formData.append('prop_id[]', JSON.stringify(dataPropId[index]))
            formData.append('mur_val[]', JSON.stringify(dataPiMur[index]))
            formData.append('mur_revetement[]', JSON.stringify(dataPiRev[index]))
            formData.append('mur_usure[]', JSON.stringify(dataPiUsure[index]))
            formData.append('mur_commentaire[]', JSON.stringify(dataPiCommentaire[index]))
            }
            for (let index = 0; index < dataEqiEl.length; index++) {
            formData.append('equip_id[]', JSON.stringify(dataEquipId[index]))
            formData.append('equi_el[]', JSON.stringify(dataEqiEl[index]))
            formData.append('equi_mat[]', JSON.stringify(dataEqiMat[index]))
            formData.append('equi_usure[]', JSON.stringify(dataEqiUsure[index]))
            formData.append('equi_fonc[]', JSON.stringify(dataEqiFonc[index]))
            formData.append('equi_com[]', JSON.stringify(dataEqiCom[index]))
            }
            for (let index = 0; index < dataPiTitl.length; index++) {
            formData.append('pi_titl_id[]', dataPiTitlId[index])
            formData.append('piTitl[]', dataPiTitl[index])
            }

            formData.append('etat_name', $('input[name="etat_name"]').val());
            formData.append('etat_obs', 'hfsjdkhgkjsdfhk');
            formData.append('type_etat_id', $('select[name="type_etat_id"]').val());
            formData.append('etat_location_id',$('select[name="etat_location_id"]').val());
            for (let index = 0; index < name_eaux.length; index++) {
            formData.append('name_eaux[]', name_eaux[index]);
            formData.append('numero_eaux[]', numero_eaux[index]);
            formData.append('volume_eaux[]', volume_eaux[index]);
            formData.append('fontion_eaux[]', fontion_eaux[index]);
            formData.append('observation_eaux[]', observation_eaux[index]);
            formData.append('compteur_eaux_modif[]', compteur_eaux_modif[index]);
            }
            for (let index = 0; index < name_electriques.length; index++) {
            formData.append('name_electriques[]', name_electriques[index]);
            formData.append('numero_electriques[]', numero_electriques[index]);
            formData.append('volume_electriques[]', volume_electriques[index]);
            formData.append('fonction_electriques[]', fonction_electriques[index]);
            formData.append('observartion_electriques[]', observartion_electriques[index]);
            formData.append('compteur_electriques_modif[]', compteur_electriques_modif[index]);
            }

            for (let index = 0; index < name_chauffages.length; index++) {
            formData.append('name_chauffages[]', name_chauffages[index]);
            formData.append('numero_chauffages[]', numero_chauffages[index]);
            formData.append('volume_chauffages[]', volume_chauffages[index]);
            formData.append('fonction_chauffages[]', fonction_chauffages[index]);
            formData.append('observation_chauffages[]', observation_chauffages[index]);
            formData.append('type_chauffage_modif[]', type_chauffage_modif[index]);
            }
            for (let index = 0; index < name_production_eaux.length; index++) {
            formData.append('name_production_eaux[]', name_production_eaux[index]);
            formData.append('fonction_production_eaux[]', fonction_production_eaux[index]);
            formData.append('observation_production_eaux[]', observation_production_eaux[index]);
            formData.append('production_eau_chaude_modif[]', production_eau_chaude_modif[index]);
            }
            $.ajax({
                type:'POST',
                url: url,
                contentType: false,
                processData: false,
                data: formData,
                success:function(){
                location.href = "{{ route('proprietaire.etat-des-lieux') }}"
                },
                error: function(data) {
                let errors = data.responseJSON.errors
                $.each( errors, function( key, value ) {
                    $("#" + key).addClass("is-invalid")
                    if ($("#" + key).hasClass("is-invalid")) {
                        $("#" + "err_" + key).text("Ce champ est obligatoire")
                    }
                });
                $("#info-gen-id").tab("show")
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#" + Object.keys(errors)[0]).offset().top - 50
                }, 100);
                $("#myLoader").addClass("d-none")
                }
            });
      })
      $("#add-eau").on('click', function () {
        let child = `
                      <div class="row p-3 align-items-start">
                        <div class="col-md-1 text-end"><span class="btn btn-danger btn-remove"><i class="fa-solid fa-trash-can"></i></span></div>
                        <div class="col-lg-2">
                          <input type="number" name="compteur_eau_modif[]" hidden>
                          <div>
                            <label class="col-form-label text-end">Type de releve</label>
                          </div>
                          <input class="form-control" type="text" name="name_eau[]">
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">n° de serie</label>
                          </div>
                          <input class="form-control" type="text" name="numero_eau[]">
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">M3</label>
                          </div>
                          <input class="form-control check-minus" type="number" name="volume_eau[]">
                          <span class="error-message d-none text-danger">Veuillez saisir un nombre positif</span>
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label for="fonction-eau" class="col-form-label text-end">Fonctionnement</label>
                          </div>
                          <select id="fonction-eau" class="form-select" name="fontion_eau[]">
                            <option value="" selected>Choisir</option>
                            @foreach ($fonctionnements as $fonctionnement)
                              <option value="{{ $fonctionnement->id }}">{{ $fonctionnement->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-lg">
                          <div>
                            <label class="col-form-label text-end">Observation</label>
                          </div>
                          <input class="form-control" type="text" placeholder="Description" name="observation_eau[]">
                        </div>
                      </div>
                    `
        $("#releve-compteur-eau").append(child)
        TesteP()
        $("span.btn-remove").on('click', function (e) {
          $(this).closest('.row.p-3.align-items-start').remove()
        })
      })

      $("#add-elec").on('click', function () {
        let child = `
                      <div class="row p-3 align-items-start">
                        <div class="col-md-1 text-end"><span class="btn btn-danger btn-remove">
                        <i class="fa-solid fa-trash-can"></i></span></div>
                        <div class="col-lg-2">
                          <input type="number" hidden name="compteur_electriques_modif[]">
                          <div>
                            <label class="col-form-label text-end">Type de releve</label>
                          </div>
                          <input class="form-control" type="text" name="name_electrique[]" >
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">n° de serie</label>
                          </div>
                          <input class="form-control" type="text" name="numero_electrique[]">
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">kwh</label>
                          </div>
                          <input class="form-control check-minus" type="number" name="volume_electrique[]">
                          <span class="error-message d-none text-danger">Veuillez saisir un nombre positif</span>
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">Fonctionnement</label>
                          </div>
                          <select class="form-select" name="fonction_electrique[]">
                            <option value="" selected>Choisir</option>
                            @foreach ($fonctionnements as $fonctionnement)
                              <option value="{{ $fonctionnement->id }}">{{ $fonctionnement->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-lg">
                          <div>
                            <label class="col-form-label text-end">Observation</label>
                          </div>
                          <input class="form-control" type="text" placeholder="Description" name="observartion_electrique[]">
                        </div>
                      </div>
                    `
        $("#releve-compteur-electrique").append(child)
        TesteP()
        $("span.btn-remove").on('click', function (e) {
          $(this).closest('.row.p-3.align-items-start').remove()
        })
      })

      $("#add-chauffage").on('click', function () {
        let child = `
                      <div class="row p-3 align-items-start">
                        <div class="col-md-1 text-end"><span class="btn btn-danger btn-remove"><i class="fa-solid fa-trash-can"></i></span></div>
                        <div class="col-lg-2">
                          <input type="number" name="type_chauffage_modif[]" hidden>
                          <div>
                            <label class="col-form-label text-end">Type de releve</label>
                          </div>
                          <input class="form-control" type="text" name="name_chauffage[]">
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">n° de serie</label>
                          </div>
                          <input class="form-control" type="text" name="numero_chauffage[]">
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">M3/kwh</label>
                          </div>
                          <input class="form-control check-minus" type="number" name="volume_chauffage[]">
                          <span class="error-message d-none text-danger">Veuillez saisir un nombre positif</span>
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">Fonctionnement</label>
                          </div>
                          <select class="form-select" name="fonction_chauffage[]">
                            <option value="" selected>Choisir</option>
                            @foreach ($fonctionnements as $fonctionnement)
                              <option value="{{ $fonctionnement->id }}">{{ $fonctionnement->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-lg">
                          <div>
                            <label class="col-form-label text-end">Observation</label>
                          </div>
                          <input class="form-control" type="text" placeholder="Description" name="observation_chauffage[]">
                        </div>
                      </div>
                    `
          $("#type-chauffage").append(child)
          TesteP()
          $("span.btn-remove").on('click', function (e) {
            $(this).closest('.row.p-3.align-items-start').remove()
          })
      })

      $("#add-eau-chaude").on('click', function () {
        let child = `
                      <div class="row p-3 align-items-end">
                        <div class="col-md-1 text-end"><span class="btn btn-danger btn-remove"><i class="fa-solid fa-trash-can"></i></span></div>
                        <div class="col-lg-2">
                          <input type="number" name="production_eau_chaude_modif[]" hidden>
                          <div>
                            <label class="col-form-label text-end">Type de production</label>
                          </div>
                          <input class="form-control" type="text" name="name_production_eau[]">
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">Fonctionnement</label>
                          </div>
                          <select class="form-select"  name="fonction_production_eau[]">
                            <option selected value="">Choisir</option>
                            @foreach ($fonctionnements as $fonctionnement)
                              <option value="{{ $fonctionnement->id }}">{{ $fonctionnement->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-lg-4">
                          <div>
                            <label class="col-form-label text-end">Observation</label>
                          </div>
                          <input class="form-control" type="text" placeholder="Description" name="observation_production_eau[]">
                        </div>
                      </div>
                    `
        $("#eau-chaude").append(child)
        TesteP()
        $("span.btn-remove").on('click', function (e) {
          $(this).closest('.row.p-3.align-items-end').remove()
        })
      })

    })
  </script>
  <script>
    var clsePi = function() {
      document.querySelectorAll("span.btn-remove-piece").forEach(function(element) {
        element.addEventListener("click", function(e) {
          let id = this.getAttribute('data-id');
          let nav = this.getAttribute('data-nav');
          let searArr = document.querySelector('#' + nav).parentElement.children;
          let activeBefore = Array.prototype.indexOf.call(searArr, document.querySelector('#' + nav));
          document.querySelector("#nav-id li:nth-child(" + activeBefore + ")");
          let tac = document.querySelector("#nav-id li:nth-child(" + activeBefore + ")").children[0].getAttribute("data-id");
          this.closest('#' + id).remove();
          document.querySelector('#' + nav).remove();
          $("#"+tac).tab("show")
        });
      });
    }
    clsePi()
    var TesteP = function() {
             $('input[type="number"]').on('input', function() {
                var value = parseFloat($(this).val());
                if (value < 0  ) {
                $(this).addClass('is-invalid');
                $(this).next('.error-message').remove();
                $(this).after('<span class="error-message text-danger" style="font-size:10px;">Veuillez saisir un nombre positif</span>');
                } else if (value === '') {
                value = 0;
                } else {
                $(this).removeClass('is-invalid');
                $(this).next('.error-message').remove();
                }
                });
        }
        // TesteP()
    var stepHandlerOnTabs = function() {
      $("ul#nav-id li").on("click", function () {
        let active = $(this)
        let index = $("ul#nav-id").children().index(active)
        let tab = $("ul#nav-id li").length - 2
        //console.log(index)
        $("#prec-etat").removeClass("d-none")
        $("#suiv-etat").removeClass("d-none")

        if (index === 0) {
          $("#prec-etat").addClass("d-none")
          $("#suiv-etat").removeClass("d-none")
        } else if ( index === tab ) {
          $("#suiv-etat").addClass("d-none")
          $("#prec-etat").removeClass("d-none")

        }
      })
    }
    stepHandlerOnTabs()
    function updateNamePiece(date) {
        var inputValuePiece = document.getElementById("piece-id-"+date).value;
        if(inputValuePiece == ""){
            inputValuePiece.innerHTML = "Piece"
        }else{
            document.getElementById("nav-tab-"+date).innerHTML = inputValuePiece;
        }

    }

    $(document).ready(function() {
      var date

      var nbr = 1
      $("#nouv").on('click', function name() {
        var nbr_piece = document.querySelectorAll('.nbr-piece').length;
        if(nbr_piece == 0){
          nbr = 1
        }else{
          nbr = nbr_piece + 1
        }

        $("#suiv-etat").removeClass("d-none")
        $("#prec-etat").removeClass("d-none")
        date = Date.now()
        let pieceTab = `
            <li class="nav-item stepHa nbr-piece" id="piece-nav-${date}">
              <button type="button" id="nav-tab-${date}" data-id="nav-tab-${date}" class="nav-link text-uppercase" role="tab" data-bs-toggle="tab" data-bs-target="#nav-nouvelle-piece-${date}" aria-controls="nav-nouvelle-piece-${date}" aria-selected="false">
                piece ${nbr}
              </button>
            </li>
        `
        let piece = `
                    <div class="tab-pane fade mb-3 all-piece" id="nav-nouvelle-piece-${date}" role="tabpanel">
                      <div class="card h-100">
                        <div>
                          <div class="card-header border-bottom py-2 px-3 mb-3">
                            <div class="card-title mb-0 row  align-items-center">
                              <div class="w-auto">
                                <h5 class="m-0 me-2 w-auto">Pièce</h5>
                              </div>
                              <div class="w-auto">
                                <span class="btn-sm btn-danger btn-remove-piece" data-id="nav-nouvelle-piece-${date}" data-nav="piece-nav-${date}"><i class="fa-solid fa-trash-can"></i> Supprimer</span>
                              </div>
                            </div>
                          </div>
                          <div class="" id="">
                            <div class="card-body p-0">
                              <div class="p-3 border-bottom">
                                <div class="mb-3 row">
                                  <label for="etat_name" class="col-md-2 col-form-label text-end">Nom de la pièce</label>
                                  <div class="col-lg-5">
                                    <input class="form-control cls-piece-${date}" id="piece-id-${date}" data-input="piece-id-${date}" type="text" placeholder="Identifiant" value='piece ${nbr}' onkeyup="updateNamePiece(${date})">
                                    <input type="number" hidden data-input="piece-id-modif-${date}">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card-header border-bottom py-2 px-3 mb-3">
                            <div class="card-title mb-0">
                              <h5 class="m-0 me-2 w-auto">Murs, plafond, sol</h5>
                            </div>
                          </div>
                          <div id="mur-plafond-sol-${date}">
                            <div class="row p-3 align-items-end">
                              <div class="col-md-1 text-end"></div>
                              <input type="number" hidden data-input="mur-val-modif-mur-plafond-sol-${date}">
                              <div class="col-lg-2">
                                <div>
                                  <label class="col-form-label text-end">Element</label>
                                </div>
                                <input class="form-control cls-piece-${date}" type="text" data-input="mur-val-mur-plafond-sol-${date}">
                              </div>
                              <div class="col-lg-2">
                                <div>
                                  <label class="col-form-label text-end">revêtement</label>
                                </div>
                                <input class="form-control cls-piece-${date}" type="text" data-input="mur-revetement-mur-plafond-sol-${date}">
                              </div>
                              <div class="col-lg-2">
                                <div>
                                  <label class="col-form-label text-end">etat d'usure</label>
                                </div>
                                <select class="form-select cls-piece-${date}" data-input="mur-usure-mur-plafond-sol-${date}">
                                  <option value="" selected>Non vérifié</option>
                                  @foreach ($etat_usures as $etat_usure)
                                    <option value="{{ $etat_usure->id }}">{{ $etat_usure->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-lg">
                                <div>
                                  <label class="col-form-label text-end">Commentaire</label>
                                </div>
                                <input class="form-control cls-piece-${date}" type="text" placeholder="Description" data-input="mur-commentaire-mur-plafond-sol-${date}">
                              </div>
                            </div>
                          </div>
                          <div class="row border-bottom g-0">
                            <div class="col-md-1"></div>
                            <div class="col ps-3 mb-3">
                              <span class="btn btn-primary" data-cls="cls-piece-${date}" data-id="mur-plafond-sol-${date}" id="add-wall-${date}">Ajouter un autre champ</span>
                            </div>
                          </div>

                          <div class="card-header border-bottom py-2 px-3 mb-3">
                            <div class="card-title mb-0">
                              <h5 class="m-0 me-2 w-auto">Equipement</h5>
                            </div>
                          </div>

                          <div id="piece-equipement-${date}">
                            <div class="row p-3 align-items-end">
                              <div class="col-md-1 text-end"></div>
                              <input type="number" hidden data-input="equip-element-modif-piece-equipement-${date}">
                              <div class="col-lg-2">
                                <div>
                                  <label class="col-form-label text-end">Element</label>
                                </div>
                                <input class="form-control cls-piece-${date}" type="text" data-input="equip-element-piece-equipement-${date}">
                              </div>
                              <div class="col-lg-2">
                                <div>
                                  <label class="col-form-label text-end">matériaux</label>
                                </div>
                                <input class="form-control cls-piece-${date}" type="text" data-input="materiaux-piece-equipement-${date}">
                              </div>
                              <div class="col-lg-2">
                                <div>
                                  <label for="fonction-eau" class="col-form-label text-end">etat d'usure</label>
                                </div>
                                <select id="fonction-eau" class="form-select cls-piece-${date}" data-input="equipe-usure-piece-equipement-${date}">
                                  <option value="" selected>Non vérifié</option>
                                  @foreach ($etat_usures as $etat_usure)
                                    <option value="{{ $etat_usure->id }}">{{ $etat_usure->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-lg-2">
                                <div>
                                  <label for="fonction-eau" class="col-form-label text-end">Fonctionnement</label>
                                </div>
                                <select id="fonction-eau" class="form-select cls-piece-${date}" data-input="equip-fonction-piece-equipement-${date}">
                                  <option value="" selected>Choisir</option>
                                  @foreach ($fonctionnements as $fonctionnement)
                                    <option value="{{ $fonctionnement->id }}">{{ $fonctionnement->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-lg">
                                <div>
                                  <label class="col-form-label text-end">Commentaire</label>
                                </div>
                                <input class="form-control cls-piece-${date}" type="text" placeholder="Description" data-input="commentaire-piece-equipement-${date}">
                              </div>
                            </div>
                          </div>

                          <div class="row border-bottom g-0">
                            <div class="col-md-1"></div>
                            <div class="col ps-3 mb-3">
                              <span class="btn btn-primary" data-id="piece-equipement-${date}" data-cls="cls-piece-${date}" id="add-equipement-${date}">Ajouter un autre champ</span>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>
                    `
        $("#nav-id .nav-item:nth-last-child(2)").before(pieceTab)
        $("#tab-id").append(piece)
        // ${date}
        $("#nav-tab-" + date).tab("show")
        clsePi()
        stepHandlerOnTabs()
        $('#add-wall-'+date).on('click',function () {
          let mur = $(this).attr('data-id');
          let cls = $(this).attr('data-cls')
          let child = `
                      <div class="row p-3 align-items-end">
                        <div class="col-md-1 text-end"><span class="btn btn-danger btn-remove"><i class="fa-solid fa-trash-can"></i></span></div>
                        <div class="col-lg-2">
                          <input type="number" hidden data-input="mur-val-modif-${mur}">
                          <div>
                            <label class="col-form-label text-end">Element</label>
                          </div>
                          <input class="form-control ${cls}" type="text" data-input="mur-val-${mur}" name="elem[]">
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">revêtement</label>
                          </div>
                          <input class="form-control ${cls}" type="text" data-input="mur-revetement-${mur}">
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label for="fonction-eau" class="col-form-label text-end">etat d'usure</label>
                          </div>
                          <select class="form-select ${cls}" data-input="mur-usure-${mur}">
                            <option value="" selected>Non vérifié</option>
                            @foreach ($etat_usures as $etat_usure)
                              <option value="{{ $etat_usure->id }}">{{ $etat_usure->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-lg">
                          <div>
                            <label class="col-form-label text-end">Commentaire</label>
                          </div>
                          <input class="form-control ${cls}" type="text" placeholder="Description" data-input="mur-commentaire-${mur}">
                        </div>
                      </div>
                    `
          $("#" + mur).append(child)
          $("span.btn-remove").on('click', function (e) {
            $(this).closest('.row.p-3.align-items-end').remove()
          })
        })

        $('#add-equipement-'+date).on('click',function () {
          let equ = $(this).attr('data-id');
          let cls = $(this).attr('data-cls')
          let child = `
                      <div class="row p-3 align-items-end">
                        <div class="col-md-1 text-end"><span class="btn btn-danger btn-remove"><i class="fa-solid fa-trash-can"></i></span></div>
                        <input type="number" hidden data-input="equip-element-modif-${equ}">
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">Element</label>
                          </div>
                          <input class="form-control ${cls}" type="text" data-input="equip-element-${equ}">
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label class="col-form-label text-end">matériaux</label>
                          </div>
                          <input class="form-control ${cls}" type="text" data-input="materiaux-${equ}">
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label for="fonction-eau" class="col-form-label text-end">etat d'usure</label>
                          </div>
                          <select id="fonction-eau" class="form-select ${cls}" data-input="equipe-usure-${equ}">
                            <option value="" selected>Non vérifié</option>
                            @foreach ($etat_usures as $etat_usure)
                              <option value="{{ $etat_usure->id }}">{{ $etat_usure->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-lg-2">
                          <div>
                            <label for="fonction-eau" class="col-form-label text-end">Fonctionnement</label>
                          </div>
                          <select id="fonction-eau" class="form-select ${cls}" data-input="equip-fonction-${equ}">
                            <option value="" selected>Choisir</option>
                            @foreach ($fonctionnements as $fonctionnement)
                              <option value="{{ $fonctionnement->id }}">{{ $fonctionnement->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-lg">
                          <div>
                            <label class="col-form-label text-end">Commentaire</label>
                          </div>
                          <input class="form-control ${cls}" type="text" placeholder="Description" data-input="commentaire-${equ}">
                        </div>
                      </div>
                      `
          $("#" + equ).append(child)
          $("span.btn-remove").on('click', function (e) {
            $(this).closest('.row.p-3.align-items-end').remove()
          })
        })
        nbr++
      })
    })
  </script>
  <script>

    var stepHandler = function (i) {
      window.scrollTo(0, 0)
      let active = $("ul#nav-id li").find("button.active").closest("li")
      let index = $("ul#nav-id").children().index(active) + i //suivant
      let countChild = $("#nav-id").children().length
      let number = $("ul#nav-id li:nth-child("+ index +")").children().attr("id")
      $("#" + number).tab("show")
      return { index: index, countChild: countChild };
    }

    $("#suiv-etat").on('click', function (e) {
      const { countChild, index } = stepHandler(2)
      limit = countChild - 1
      if ( limit === index ) {
        $(this).addClass("d-none")
      }
      $("#prec-etat").removeClass("d-none")
    })

    $("#prec-etat").on('click', function (e) {
      const { index } = stepHandler(0)
      limit = index - 1
      if ( limit === 0 ) {
        $(this).addClass("d-none")
      }
      $("#suiv-etat").removeClass("d-none")
    })
  </script>
  <script>
    $(document).ready(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
          }
      });
      var plugin
      let initialPreviewArray = []
      let initialPreviewConfigArray = []
      @if (isset($etat_lieu))
        var etatFiles = {!! json_encode($etat_lieu->etat_files) !!};
        var etatId = {!! json_encode($etat_lieu->id) !!};
        for (let i = 0; i < etatFiles.length; i++) {
          initialPreviewArray.push('/storage/' + etatFiles[i].file_name)
          initialPreviewConfigArray.push({caption:etatFiles[i].alt, size:etatFiles[i].size, width:'120px', url:"{{ route('delete-etat-lieux-files') }}", id: 0, extra : {id:etatFiles[i].id, type:"deleted", file_name: etatFiles[i].file_name, etat: etatId}});
        }
      @endif
      let test = $("#etat-files-update").fileinput({
          language: "fr",
          initialPreview: initialPreviewArray,
          initialPreviewAsData: true,
          initialPreviewConfig: initialPreviewConfigArray,
          uploadUrl: "{{ route('upload-etat-lieux-files') }}",
          deleteUrl: "{{ route('delete-etat-lieux-files') }}",
          altId: "etat-files-update",
          overwriteInitial: false,
          allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif'],
          maxFileCount: 8,
          showUpload: false,
          uploadAsync: true,
          showRemove: false,
          showCancel: false,
          showDrag: false,
          maxFileSize: 5120,
      })

      test.on("filebatchselected", function (event, files) {
        $("#etat-files-update").fileinput("upload");
      });
      test.on("filebatchuploadcomplete", function (event) {
        plugin = $('#etat-files-update').data('fileinput');
      });

      var getPi = []
      $(".get-pi").on('click', function () {
        getPi.push($(this).attr('data-pi-id'))
      })
      $("#enregistrer-etat").on("click", function (e) {
        e.preventDefault()
        let id = $(this).attr("data-id")
        let url = "{{ route('proprietaire.enregistrer-etat') }}"
        if (typeof(id) == 'string') {
          url = "{{ route('proprietaire.enregistrer-etat', ":id") }}"
          url = url.replace(':id', id);
        }
        $("#myLoader").removeClass("d-none")
        $(".form-control").removeClass('is-invalid')
        $(".form-select").removeClass('is-invalid')
        $(".no-error").text('')
        // COMPTEUR EAU
        let name_eaux = $('input[name="name_eau[]"]').map(function(){
                        return this.value;
                    }).get();

        let numero_eaux = $('input[name="numero_eau[]"]').map(function(){
                        return this.value;
                    }).get();

        let volume_eaux = $('input[name="volume_eau[]"]').map(function(){
                        return this.value;
                    }).get();

        let fontion_eaux = $('select[name="fontion_eau[]"]').map(function(){
                        return this.value;
                    }).get();

        let observation_eaux = $('input[name="observation_eau[]"]').map(function(){
                        return this.value
                    }).get();

        // COMPTEUR ELECTRIQUES
        let name_electriques = $('input[name="name_electrique[]"]').map(function(){
                        return this.value;
                    }).get();

        let numero_electriques = $('input[name="numero_electrique[]"]').map(function(){
                        return this.value;
                    }).get();

        let volume_electriques = $('input[name="volume_electrique[]"]').map(function(){
                        return this.value;
                    }).get();

        let fonction_electriques = $('select[name="fonction_electrique[]"]').map(function(){
                        return this.value;
                    }).get();

        let observartion_electriques = $('input[name="observartion_electrique[]"]').map(function(){
                        return this.value;
                    }).get();

        // TYPE DE CHAUFFAGE
        let name_chauffages = $('input[name="name_chauffage[]"]').map(function(){
                        return this.value;
                    }).get();

        let numero_chauffages = $('input[name="numero_chauffage[]"]').map(function(){
                        return this.value;
                    }).get();

        let volume_chauffages = $('input[name="volume_chauffage[]"]').map(function(){
                        return this.value;
                    }).get();

        let fonction_chauffages = $('select[name="fonction_chauffage[]"]').map(function(){
                        return this.value;
                    }).get();

        let observation_chauffages = $('input[name="observation_chauffage[]"]').map(function(){
                        return this.value;
                    }).get();

        // PRODUCTION EAU CHAUDE

        let name_production_eaux = $('input[name="name_production_eau[]"]').map(function(){
                        return this.value;
                    }).get();

        let fonction_production_eaux = $('select[name="fonction_production_eau[]"]').map(function(){
                        return this.value;
                    }).get();

        let observation_production_eaux = $('input[name="observation_production_eau[]"]').map(function(){
                        return this.value;
                    }).get();

        // MODIFICATION

        let compteur_eaux_modif = $('input[name="compteur_eau_modif[]"]').map(function(){
                        return this.value;
                    }).get();

        let compteur_electriques_modif = $('input[name="compteur_electriques_modif[]"]').map(function(){
                        return this.value;
                    }).get();

        let type_chauffage_modif = $('input[name="type_chauffage_modif[]"]').map(function(){
                        return this.value;
                    }).get();

        let production_eau_chaude_modif = $('input[name="production_eau_chaude_modif[]"]').map(function(){
                        return this.value;
                    }).get();

        // Piece
        var dataPiMur = []
        var dataPiRev = []
        var dataPiUsure = []
        var dataPiCommentaire = []
        var dataEqiEl = []
        var dataEqiMat = []
        var dataEqiUsure = []
        var dataEqiFonc = []
        var dataEqiCom = []
        var dataPiTitl = []
        var dataPiTitlId = []
        var dataPropId = []
        var dataEquipId = []
        var piTitl
        var piTitlId
        var propId
        var equipId
        var mur_val
        var mur_revetement
        var mur_usure
        var mur_commentaire
        var equi_el
        var equi_mat
        var equi_usure
        var equi_fonc
        var equi_com

        var formData = new FormData();
        $('.all-piece').each(function(index, element) {
          let idKey = $(element).attr('id').split("-").pop()
          piTitl = $('input[data-input="piece-id-'+idKey+'"]').val()
          piTitlId = $('input[data-input="piece-id-modif-'+idKey+'"]').val()
          $(".cls-piece-"+idKey).each(function (i, el) {
            propId = $('input[data-input="mur-val-modif-mur-plafond-sol-'+idKey+'"]').map(function(){
                          return this.value;
                      }).get();
            equipId = $('input[data-input="equip-element-modif-piece-equipement-'+idKey+'"]').map(function(){
                          return this.value;
                      }).get();
            mur_val = $('input[data-input="mur-val-mur-plafond-sol-'+idKey+'"]').map(function(){
                          return this.value;
                      }).get();
            mur_revetement = $('input[data-input="mur-revetement-mur-plafond-sol-'+idKey+'"]').map(function(){
                          return this.value;
                      }).get();
            mur_usure = $('select[data-input="mur-usure-mur-plafond-sol-'+idKey+'"]').map(function(){
                          return this.value;
                      }).get();
            mur_commentaire = $('input[data-input="mur-commentaire-mur-plafond-sol-'+idKey+'"]').map(function(){
                          return this.value;
                      }).get();
            equi_el = $('input[data-input="equip-element-piece-equipement-'+idKey+'"]').map(function(){
                          return this.value;
                      }).get();
            equi_mat = $('input[data-input="materiaux-piece-equipement-'+idKey+'"]').map(function(){
                          return this.value;
                      }).get();
            equi_usure = $('select[data-input="equipe-usure-piece-equipement-'+idKey+'"]').map(function(){
                          return this.value;
                      }).get();
            equi_fonc = $('select[data-input="equip-fonction-piece-equipement-'+idKey+'"]').map(function(){
                          return this.value;
                      }).get();
            equi_com = $('input[data-input="commentaire-piece-equipement-'+idKey+'"]').map(function(){
                          return this.value;
                      }).get();

          })
          dataPiMur.push(mur_val)
          dataPiRev.push(mur_revetement)
          dataPiUsure.push(mur_usure)
          dataPiCommentaire.push(mur_commentaire)
          dataEqiEl.push(equi_el)
          dataEqiMat.push(equi_mat)
          dataEqiUsure.push(equi_usure)
          dataEqiFonc.push(equi_fonc)
          dataEqiCom.push(equi_com)
          dataPiTitl.push(piTitl)
          dataPiTitlId.push(piTitlId)
          dataPropId.push(propId)
          dataEquipId.push(equipId)

        });
        for (let index = 0; index < dataPiMur.length; index++) {
          formData.append('prop_id[]', JSON.stringify(dataPropId[index]))
          formData.append('mur_val[]', JSON.stringify(dataPiMur[index]))
          formData.append('mur_revetement[]', JSON.stringify(dataPiRev[index]))
          formData.append('mur_usure[]', JSON.stringify(dataPiUsure[index]))
          formData.append('mur_commentaire[]', JSON.stringify(dataPiCommentaire[index]))
        }
        for (let index = 0; index < dataEqiEl.length; index++) {
          formData.append('equip_id[]', JSON.stringify(dataEquipId[index]))
          formData.append('equi_el[]', JSON.stringify(dataEqiEl[index]))
          formData.append('equi_mat[]', JSON.stringify(dataEqiMat[index]))
          formData.append('equi_usure[]', JSON.stringify(dataEqiUsure[index]))
          formData.append('equi_fonc[]', JSON.stringify(dataEqiFonc[index]))
          formData.append('equi_com[]', JSON.stringify(dataEqiCom[index]))
        }
        for (let index = 0; index < dataPiTitl.length; index++) {
          formData.append('pi_titl_id[]', dataPiTitlId[index])
          formData.append('piTitl[]', dataPiTitl[index])
        }

        formData.append('etat_name', $('input[name="etat_name"]').val());
        formData.append('etat_obs', $('textarea#observerText').val());
        formData.append('type_etat_id', $('select[name="type_etat_id"]').val());
        formData.append('etat_location_id', $('select[name="etat_location_id"]').val());
        for (let index = 0; index < name_eaux.length; index++) {
          formData.append('name_eaux[]', name_eaux[index]);
          formData.append('numero_eaux[]', numero_eaux[index]);
          formData.append('volume_eaux[]', volume_eaux[index]);
          formData.append('fontion_eaux[]', fontion_eaux[index]);
          formData.append('observation_eaux[]', observation_eaux[index]);
          formData.append('compteur_eaux_modif[]', compteur_eaux_modif[index]);
        }
        for (let index = 0; index < name_electriques.length; index++) {
          formData.append('name_electriques[]', name_electriques[index]);
          formData.append('numero_electriques[]', numero_electriques[index]);
          formData.append('volume_electriques[]', volume_electriques[index]);
          formData.append('fonction_electriques[]', fonction_electriques[index]);
          formData.append('observartion_electriques[]', observartion_electriques[index]);
          formData.append('compteur_electriques_modif[]', compteur_electriques_modif[index]);
        }

        for (let index = 0; index < name_chauffages.length; index++) {
          formData.append('name_chauffages[]', name_chauffages[index]);
          formData.append('numero_chauffages[]', numero_chauffages[index]);
          formData.append('volume_chauffages[]', volume_chauffages[index]);
          formData.append('fonction_chauffages[]', fonction_chauffages[index]);
          formData.append('observation_chauffages[]', observation_chauffages[index]);
          formData.append('type_chauffage_modif[]', type_chauffage_modif[index]);
        }
        for (let index = 0; index < name_production_eaux.length; index++) {
          formData.append('name_production_eaux[]', name_production_eaux[index]);
          formData.append('fonction_production_eaux[]', fonction_production_eaux[index]);
          formData.append('observation_production_eaux[]', observation_production_eaux[index]);
          formData.append('production_eau_chaude_modif[]', production_eau_chaude_modif[index]);
        }
        if (getPi.length) {
          for (let index = 0; index < getPi.length; index++) {
            formData.append('get_pi[]', getPi[index]);
          }
        }

        formData.append('name_cle', $('input[name="name_cle"]').val());
        formData.append('nombre_cle', $('input[name="nombre_cle"]').val());
        formData.append('date_cle', $('input[name="date_cle"]').val());
        formData.append('commentaire_cle', $('input[name="commentaire_cle"]').val());
        formData.append('cle_modif', $('input[name="cle_modif"]').val());
        if (plugin) {
          for (let index = 0; index < plugin.initialPreviewConfig.length; index++) {
            if (plugin.initialPreviewConfig[index].id != "0") {
              formData.append('etat_files[]', plugin.initialPreviewConfig[index].id);
            }
          }
        }
        $.ajax({
            type:'POST',
            url: url,
            contentType: false,
            processData: false,
            data: formData,
            success:function(){

              location.href = "{{ route('proprietaire.etat-des-lieux') }}"
            },
            error: function(data) {
              let errors = data.responseJSON.errors
              $.each( errors, function( key, value ) {
                  $("#" + key).addClass("is-invalid")
                  if ($("#" + key).hasClass("is-invalid")) {
                    $("#" + "err_" + key).text("Ce champ est obligatoire")
                  }
              });
              $("#info-gen-id").tab("show")
              $([document.documentElement, document.body]).animate({
                  scrollTop: $("#" + Object.keys(errors)[0]).offset().top - 50
              }, 100);
              $("#myLoader").addClass("d-none")
            }
        });
      })

    })
  </script>
@endPush
