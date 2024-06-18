
<style>
    label{
        color: black !important;
        margin-top: 12px;
    }
    input{
        border-radius: none !important;
    }
    .card-header{
        color:#4C8DCB;
        padding:10px;
        background-color:F5F5F9;
        margin-top:20px;
        border-radius:0px;
    }
    .card-body{
        margin-top:20px;
    }

    .nav-tabs .nav-item .nav-link:not(.active) {
        background-color: rgb(250, 250, 250);
    }
    .nav-tabs .nav-item .nav-link.active  {
        border-top: 3px solid blue !important;
        border-bottom: 3px solid white !important;
    }
    .nav-tabs .nav-item .nav-link   {
        color: blue !important;
        font-size: 13px !important;

    }
</style>

@if($notif = Session::get('error'))
    <div class="alert alert-danger" role="alert" style="margin-top: 15px;">
        {{ $notif }}
    </div>
@endif


    <div class="card">
        <div class="card-header" style="color: #4C8DCB">
            Informations générales :
        </div>
        <div class="card-body">
            <div class="row align-middle">
                <div class="col-md-1 align-middle ">
                <label for="" class="form-label">TYPE :</label>
                </div>
                <div class="col-md-6 align-middle">
                    <select class="form-select" name="property_type_id" id="" aria-label="">
                        @if (isset($logementMere))
                            <option value="2" >Chambre</option>
                        @else
                            @foreach ($logementType as $type)
                                @if (isset($detailLogement->property_type_id))
                                    @if ($detailLogement->property_type_id == $type->id )
                                        <option value="{{ $type->id }}" selected>{{ $type->property_type }}</option>
                                    @else
                                        <option value="{{ $type->id }}" >{{ $type->property_type }}</option>
                                    @endif
                                @else
                                    <option value="{{ $type->id }}" >{{ $type->property_type }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body" style="margin-top: -40px">
            <div class="row align-middle">
                <div class="col-md-1 align-middle ">
                <label for="" class="form-label">Identifiant:</label>
                </div>
                <div class="col-md-6 align-middle">
                    <input type="text" class="form-control info-general" id="identifiant" name="identifiant"  @if(isset($detailLogement->identifiant)) value="{{ $detailLogement->identifiant }} @endif">
                    <span style="color: red;" id="err_0"></span>
                    @error('identifiant')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                    <div class="invalid-feedback">
                        Veuillez remplir l'identifiant de votre logement svp!
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-1">
        <div class="card-header" style="color: #4C8DCB">
            Localisation :
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card-body" style="margin-top: -20px">
                    <div class="autocomplete-container mb-1" id="autocompletess">
                        <label for="" class="form-label">Adresse :</label>
                        @if (isset($logementMere))
                            <input class="form-control info-general"  type="text" readonly value="{{ $logementMere->adresse }}">
                            <input type="hidden" id="address" name="adresse" class="form-control info-general"  type="text" placeholder="{{ __('property.placeholder_adress') }}" value="{{ $logementMere->adresse }}">
                            <input type="hidden" id="latitude" class="info-general" name="latitude" value="{{$logementMere->latitude}}">
                            <input type="hidden" id="longitude" class="info-general" name="longitude" value="{{$logementMere->longitude}}">
                        @elseif (isset($detailLogement))
                            <input id="address" name="adresse" class="form-control info-general"  type="text" placeholder="{{ __('property.placeholder_adress') }}" value="{{ $detailLogement->adresse }}">
                            <input type="hidden" id="latitude" class="info-general" name="latitude" value="{{$detailLogement->latitude}}">
                            <input type="hidden" id="longitude" class="info-general" name="longitude" value="{{$detailLogement->longitude}}">
                        @else
                            <input id="address" name="adresse" class="form-control info-general"  type="text" placeholder="{{ __('property.placeholder_adress') }}" value="">
                            <input type="hidden" id="latitude" class="info-general" name="latitude" value="">
                            <input type="hidden" id="longitude" class="info-general" name="longitude" value="">
                        @endif
                    </div>
                    <span style="color: red;" id="err_3"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-body" style="margin-top: -20px">
                    <div class="">
                        <label for="" class="form-label">Batiment :</label>
                        @if (isset($logementMere))
                            <input type="text" readonly name="batiment" id="" class="form-control" placeholder="" aria-describedby="helpId" value="{{ $logementMere->batiment }}">
                        @elseif (isset($detailLogement))
                            <input type="text" name="batiment" id="" class="form-control" placeholder="" aria-describedby="helpId" value="{{ $detailLogement->batiment }}">
                        @else
                            <input type="text" name="batiment" id="" class="form-control" placeholder="" aria-describedby="helpId" value="">
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-body" style="margin-top: -40px">
                    <div class="">
                        <label for="" class="form-label">Escalier :</label>
                        <input type="number" name="escalier" id="escalier" class="form-control info-general-p" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->escalier)) value="{{ $detailLogement->escalier }}" @endif>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-body" style="margin-top: -40px">
                    <div class="">
                        <label for="" class="form-label">Etage :</label>
                        <input type="number" name="etage" id="etage" class="form-control info-general-p" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->etage)) value="{{ $detailLogement->etage }}" @endif>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-body" style="margin-top: -40px">
                    <div class="">
                        <label for="" class="form-label">Numeros :</label>
                        <input type="number" name="numero" id="numero" class="form-control info-general-p" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->numero)) value="{{ $detailLogement->numero }}" @endif>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 5px">
        <div class="card-header" style="color: #4C8DCB">
            Informations locatives :
        </div>
        <div class="card-body">
            <div class="">
                <label for="" class="form-label">Type de location proposée :</label>
                <select class="form-select" name="type_location" id="" aria-label="">
                    {{-- @foreach ($logementType as $type) --}}
                        <option value="0">Meublée</option>
                        <option value="1">Vide</option>
                    {{-- @endforeach --}}
                </select>
            </div>
        </div>
        <div class="card-body" style="margin-top: -40px">
            <div class="">
                <label for="" class="form-label">Loyer hors charge (€) :</label>
                <input type="number" min="0" name="loyer" id="loyer" class="form-control info-general info-general-p" placeholder="" aria-describedby="helpId"  @if(isset($detailLogement->loyer)) value="{{ $detailLogement->loyer }}" @endif>
            </div>
        </div>
        <div class="card-body" style="margin-top: -40px">
            <div class="">
                <label for="" class="form-label">Charge (€) :</label>
                <input type="number" name="charge" id="charge" class="form-control info-general-p" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->charge)) value="{{ $detailLogement->charge }}" @endif>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 5px">
        <div class="card-header" style="color: #4C8DCB">
            Description :
        </div>
        <div class="card-body">
            <div class="">
                <label for="" class="form-label">Superficie (M²):</label>
                <input type="number" required name="superficie" id="superficie" class="form-control info-general info-general-p" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->superficie)) value="{{ $detailLogement->superficie }}" @endif>
                <span style="color: red;" id="err_5"></span>
            </div>
        </div>
        <div class="card-body" style="margin-top: -40px">
            <div class="">
                <label for="" class="form-label">Nombre de pièce :</label>
                <input type="number" name="nbr_piece" id="nbr_piece" class="form-control info-general-p" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->nbr_piece)) value="{{ $detailLogement->nbr_piece }}" @endif>
            </div>
        </div>
        @if (!isset($logementMere))
            <div class="card-body" style="margin-top: -40px">
                <div class="">
                    <label for="" class="form-label">Nombre de chambre:</label>
                    <input type="number" name="nbr_chambre" id="nbr_chambre" class="form-control info-general-p" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->nbr_chambre)) value="{{ $detailLogement->nbr_chambre }}" @endif>
                </div>
            </div>
        @else
            <input type="hidden" name="logement_mere_id" value="{{$logementMere->id}}">
        @endif

        <div class="card-body" style="margin-top: -40px">
            <div class="">
                <label for="" class="form-label">Nombre de salle de bain :</label>
                <input type="number" name="salle_bain" id="salle_bain" class="form-control info-general-p" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->salle_bain)) value="{{ $detailLogement->salle_bain }}" @endif>
            </div>
        </div>
        <div class="card-body" style="margin-top: -40px">
            <div class="">
                <label for="" class="form-label">Année de construction :</label>
                @if (isset($logementMere))
                    <input type="number" name="annee_construction" id="annee_construction" class="form-control info-general-p" aria-describedby="helpId" readonly value="{{$logementMere->annee_construction}}">
                @elseif (isset($detailLogement))
                    <input type="number" min="1500" max="{{ date('Y') }}" step="1" name="annee_construction" id="annee_construction" class="form-control info-general-p" placeholder="ex: 1752" aria-describedby="helpId" value="{{$detailLogement->annee_construction}}">
                @else
                    <input type="number" min="1500" max="{{ date('Y') }}" step="1" name="annee_construction" id="annee_construction" class="form-control info-general-p" placeholder="ex: 1752" aria-describedby="helpId">
                @endif
            </div>
        </div>
        <div class="card-body" style="margin-top: -40px">
            <div class="">
                <label for="" class="form-label">Description :</label>
                <input type="text" name="description_logement" id="description_logement" class="form-control" aria-describedby="helpId" @if(isset($detailLogement->description)) value="{{ $detailLogement->description }}" @endif>
            </div>
        </div>
        <div class="card-body" style="margin-top: -40px">
            <div class="">
                <label for="" class="form-label">Note privée :</label>
                <input type="text" name="note_privee" id="" class="form-control" placeholder="" aria-describedby="helpId" @if(isset($detailLogement->note_privee)) value="{{ $detailLogement->note_privee }}" @endif>
            </div>
        </div>
    </div>
    <div class="card" style="margin-top: 5px">
        <div class="card-body" style="margin-top: -5px">
            <div class="row">
                <div class="col-md-12">
                    <div class="float-start">

                    </div>
                    <div class="float-end">
                        <button type="button" class="btn btn-primary suivantInfoGeneral" id="suivantInfoGeneral"> Suivant </button>
                    </div>
                </div>
            </div>
        </div>
    </div>



@push('script')
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
            })
    })()

    $(document).ready(function () {
        var table = $('#example').DataTable({
            "language": {
                "lengthMenu": "Filtre _MENU_ ",
                "zeroRecords": "Pas de recherche corespondant",// __("logement.pas-recherche-correspondant")
                "info": "Affichage _PAGE_ sur _PAGES_",
                "infoEmpty": "Pas de recherche corespondant",// __("logement.pas-recherche-correspondant")
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        }
        );
        var dep = $('#departement').DataTable({
            "language": {
                "lengthMenu": "Filtre _MENU_ ",
                "zeroRecords": "Pas de recherche corespondant",// __("logement.pas-recherche-correspondant")
                "info": "Affichage _PAGE_ sur _PAGES_",
                "infoEmpty": "Pas de recherche corespondant",// __("logement.pas-recherche-correspondant")
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        }
        );
        $('#myInput').on( 'keyup', function () {
            table.search( this.value ).draw();
            dep.search( this.value ).draw();
        });


    var key = null
    var url_page = window.location.pathname;
    var page = url_page.slice(1)
    var position = page.search("/");

    function getKey(page) {
        //fixer le format de l'url
        if (position > 0) {
            key = 'bd5911089b114d6fa4fd3b0d17b1ac50'
            return
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.get(`/geoapify_key/${page}`)
            .then((response) => {
                key = response

            })
            .catch((error) => {
                key = 'bd5911089b114d6fa4fd3b0d17b1ac50'
                //send error ici
            })

    }

    getKey(page)

    function addressAutocomplete(containerElement, callback, options) {

        const MIN_ADDRESS_LENGTH = 3;
        const DEBOUNCE_DELAY = 100;

        // create container for input element
        const inputContainerElement = document.createElement("div");
        inputContainerElement.setAttribute("class", "input-container");
        containerElement.appendChild(inputContainerElement);

        // create input element
        const inputElement = document.getElementById("address");
        inputContainerElement.appendChild(inputElement);

        // add input field clear button
        const clearButton = document.createElement("div");
        clearButton.classList.add("clear-button");
        addIcon(clearButton);
        clearButton.addEventListener("click", (e) => {
            e.stopPropagation();
            inputElement.value = '';
            callback(null);
            clearButton.classList.remove("visible");
            closeDropDownList();
        });
        inputContainerElement.appendChild(clearButton);

        /* We will call the API with a timeout to prevent unneccessary API activity.*/
        let currentTimeout;

        /* Save the current request promise reject function. To be able to cancel the promise when a new request comes */
        let currentPromiseReject;

        /* Focused item in the autocomplete list. This variable is used to navigate with buttons */
        let focusedItemIndex;

        /* Process a user input: */
        inputElement.addEventListener("input", function (e) {
            const currentValue = this.value;

            /* Close any already open dropdown list */
            closeDropDownList();


            // Cancel previous timeout
            if (currentTimeout) {
                clearTimeout(currentTimeout);
            }

            // Cancel previous request promise
            if (currentPromiseReject) {
                currentPromiseReject({
                    canceled: true
                });
            }

            if (!currentValue) {
                clearButton.classList.remove("visible");
            }

            // Show clearButton when there is a text
            clearButton.classList.add("visible");

            // Skip empty or short address strings
            if (!currentValue || currentValue.length < MIN_ADDRESS_LENGTH) {
                return false;
            }

            /* Call the Address Autocomplete API with a delay */
            currentTimeout = setTimeout(() => {
                currentTimeout = null;

                /* Create a new promise and send geocoding request */
                const promise = new Promise((resolve, reject) => {
                    currentPromiseReject = reject;
                    var pathname = window.location.pathname;

                    // The API Key provided is restricted to JSFiddle website
                    // Get your own API Key on https://myprojects.geoapify.com
                    apiKey = key

                    var url = `https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(currentValue)}&format=json&limit=5&apiKey=${apiKey}`;

                    fetch(url)
                        .then(response => {
                            currentPromiseReject = null;
                            $.get(`/update_gestion_geoapify/${page}`,`url=${window.location.href}`)
                            // check if the call was successful
                            if (response.ok) {
                                response.json().then(data => resolve(data));
                            } else {
                                response.json().then(data => reject(data));
                            }
                        });
                });

                promise.then((data) => {
                    // here we get address suggestions
                    currentItems = data.results;

                    /*create a DIV element that will contain the items (values):*/
                    const autocompleteItemsElement = document.createElement("div");
                    autocompleteItemsElement.setAttribute("class", "autocomplete-items");
                    inputContainerElement.appendChild(autocompleteItemsElement);

                    /* For each item in the results */
                    data.results.forEach((result, index) => {
                        /* Create a DIV element for each element: */
                        const itemElement = document.createElement("div");
                        /* Set formatted address as item value */
                        itemElement.innerHTML = result.formatted;
                        autocompleteItemsElement.appendChild(itemElement);

                        /* Set the value for the autocomplete text field and notify: */
                        itemElement.addEventListener("click", function (e) {
                            inputElement.value = currentItems[index].formatted;
                            callback(currentItems[index]);
                            /* Close the list of autocompleted values: */
                            closeDropDownList();
                        });
                    });

                }, (err) => {
                    if (!err.canceled) {
                        console.log(err);
                        // $.ajaxSetup({
                        //     headers: {
                        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        //     }
                        // });
                        // $.ajax({
                        //     type: "POST",
                        //     url: '/email-error-autocomplete-geoapify',
                        //     //data: err,
                        //     //dataType: 'json',
                        //     success: function (data) {
                        //         console.log('email envoyé');
                        //     }
                        // });
                    }
                });
            }, DEBOUNCE_DELAY);
        });

        /* Add support for keyboard navigation */
        inputElement.addEventListener("keydown", function (e) {
            var autocompleteItemsElement = containerElement.querySelector(".autocomplete-items");
            if (autocompleteItemsElement) {
                var itemElements = autocompleteItemsElement.getElementsByTagName("div");
                if (e.keyCode == 40) {
                    e.preventDefault();
                    /*If the arrow DOWN key is pressed, increase the focusedItemIndex variable:*/
                    focusedItemIndex = focusedItemIndex !== itemElements.length - 1 ? focusedItemIndex + 1 : 0;
                    /*and and make the current item more visible:*/
                    setActive(itemElements, focusedItemIndex);
                } else if (e.keyCode == 38) {
                    e.preventDefault();

                    /*If the arrow UP key is pressed, decrease the focusedItemIndex variable:*/
                    focusedItemIndex = focusedItemIndex !== 0 ? focusedItemIndex - 1 : focusedItemIndex = (itemElements.length - 1);
                    /*and and make the current item more visible:*/
                    setActive(itemElements, focusedItemIndex);
                } else if (e.keyCode == 13) {
                    /* If the ENTER key is pressed and value as selected, close the list*/
                    e.preventDefault();
                    if (focusedItemIndex > -1) {
                        closeDropDownList();
                    }
                }
            } else {
                if (e.keyCode == 40) {
                    /* Open dropdown list again */
                    var event = document.createEvent('Event');
                    event.initEvent('input', true, true);
                    inputElement.dispatchEvent(event);
                }
            }
        });

        function setActive(items, index) {
            if (!items || !items.length) return false;

            for (var i = 0; i < items.length; i++) {
                items[i].classList.remove("autocomplete-active");
            }

            /* Add class "autocomplete-active" to the active element*/
            items[index].classList.add("autocomplete-active");

            // Change input value and notify
            inputElement.value = currentItems[index].formatted;
            callback(currentItems[index]);
        }

        function closeDropDownList() {
            const autocompleteItemsElement = inputContainerElement.querySelector(".autocomplete-items");
            if (autocompleteItemsElement) {
                inputContainerElement.removeChild(autocompleteItemsElement);
            }

            focusedItemIndex = -1;
        }

        function addIcon(buttonElement) {
            const svgElement = document.createElementNS("http://www.w3.org/2000/svg", 'svg');
            svgElement.setAttribute('viewBox', "0 0 24 24");
            svgElement.setAttribute('height', "24");

            const iconElement = document.createElementNS("http://www.w3.org/2000/svg", 'path');
            iconElement.setAttribute("d", "M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z");
            iconElement.setAttribute('fill', 'currentColor');
            svgElement.appendChild(iconElement);
            buttonElement.appendChild(svgElement);
        }

        /* Close the autocomplete dropdown when the document is clicked.
        Skip, when a user clicks on the input field */
        document.addEventListener("click", function (e) {
            if (e.target !== inputElement) {
                closeDropDownList();
            } else if (!containerElement.querySelector(".autocomplete-items")) {
                // open dropdown list again
                var event = document.createEvent('Event');
                event.initEvent('input', true, true);
                inputElement.dispatchEvent(event);
            }
        });
    }

    addressAutocomplete(document.getElementById("autocompletess"), (data) => {

        console.log(data);

        $('#latitude').val(data['lat']);
        $('#longitude').val(data['lon']);

    }, {
        placeholder: "Enter an address here",
    });


    });
</script>
@endpush

