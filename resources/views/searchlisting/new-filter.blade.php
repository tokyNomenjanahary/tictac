@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/places.js@1.16.1"></script> --}}
    {{--<script src="/js/new-filter.min.js"></script>--}}
    <script src="/js/new-filter.js"></script>


    <script type="text/javascript">

        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });
        // This example adds a search box to a map, using the Google Place Autocomplete
        // feature. People can enter geographical searches. The search box will return a
        // pick list containing a mix of places and predicted search terms.

        // This example requires the Places library. Include the libraries=places
        // parameter when you first load the API. For example:
        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
        var map;
        var infowindow;
        var marker;
        var key_to_use=null
        var page_name='search_listing_premium'
        //console.log(appSettings);

        // $(document).ready(function (){
        //     initAutocomplete();
        //     placesAutocomplete.setVal('')
        //
        //     $("#address").keyup(function () {
        //         if($.trim($('#address').val()) == ''){
        //             $("#latitude").val('');
        //             $("#longitude").val('');
        //             $("#actual_address").val('');
        //             $('#nearByFacilities').html('');
        //         }
        //     });
        // });


        // function initAutocomplete() {
        //     var latitude = parseFloat($('#latitude').val());
        //     var longitude = parseFloat($('#longitude').val());
        //     var placesAutocomplete = places({
        //         appId: 'pl8GAWX9U3QF',
        //         apiKey: 'e80df4d22a0beeeef730bb02a6602b51',
        //         container: document.querySelector('#address')
        //     });
        //
        //     placesAutocomplete.on('change', function(e) {
        //         $('#latitude').val(e.suggestion.latlng.lat);
        //         $('#longitude').val(e.suggestion.latlng.lng);
        //         //$address.textContent = e.suggestion.value
        //         /*var service = new google.maps.places.PlacesService($('#results').get(0));
        //         var nearByFacilityType = ['train_station', 'subway_station', 'bus_station', 'university', 'school'];
        //
        //         $.each(nearByFacilityType, function (index, item) {
        //             service.nearbySearch({
        //                 location: {lat: e.suggestion.latlng.lat, lng: e.suggestion.latlng.lng},
        //                 radius: 5000,
        //                 type: item
        //             }, function(results, status){
        //                 callback(results, status, item);
        //             });
        //         });*/
        //     });
        //     placesAutocomplete.setVal();
        //
        //
        //     /*if(latitude != "") {
        //         var service = new google.maps.places.PlacesService($('#results').get(0));
        //         var nearByFacilityType = ['train_station', 'subway_station', 'bus_station', 'university', 'school'];
        //         $.each(nearByFacilityType, function (index, item) {
        //             service.nearbySearch({
        //                 location: {lat: latitude, lng: longitude},
        //                 radius: 5000,
        //                 type: item
        //             }, function(results, status){
        //                 callback(results, status, item);
        //             });
        //         });
        //     }*/
        // }

        function getKey(page)
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.get(`/geoapify_key/${page}`)
            .then((response)=>{
                key_to_use=response
            })
            .catch((error)=>{
                //ajout de clé par defaut pour que l'api reste fonctionne
                key_to_use='bd5911089b114d6fa4fd3b0d17b1ac50'
                //send error ici
            })

        }

        function callback(results, status, item) {
            if (results != null) {
                if (item == 'train_station') {
                    optGroupLabel = 'Train Stations';
                } else if (item == 'subway_station') {
                    optGroupLabel = 'Subway Stations';
                } else if (item == 'bus_station') {
                    optGroupLabel = 'Bus Stations';
                } else if (item == 'university') {
                    optGroupLabel = 'Universities';
                } else if (item == 'school') {
                    optGroupLabel = 'Schools';
                }
                var $select = $('#nearByFacilities'), $optgroup;
                $optgroup = $('<optgroup/>');
                $optgroup.attr('label', optGroupLabel);
                $optgroup.appendTo($select);

                for (var i = 0; i < results.length; i++) {
                    val = results[i].geometry.location.lat() + "#" + results[i].geometry.location.lng() + "#" + results[i].name + "#" + item;
                    $select.append('<option value="' + val + '">' + results[i].name + '</option>');
                }
            }
        }

        function getFacilities() {
            var facilities = $('#nearByFacilities').val();
            var result = new Array();
            for (var i = 0; i < facilities.length; i++) {
                var temp = facilities[i].split('#');
                result.push({lat: temp[0], lng: temp[1], name: temp[2], type: temp[3]});
            }
            return result;
        }


        /*
        The addressAutocomplete takes as parameters:
      - a container element (div)
      - callback to notify about address selection
      - geocoder options:
           - placeholder - placeholder text for an input element
         - type - location type
    */
        function addressAutocomplete(containerElement, callback, options) {
            getKey(page_name)
            // create input element
            var inputElement = document.getElementById("address");
            inputElement.setAttribute("value",'')
            // inputElement.setAttribute("type", "text");
            // inputElement.setAttribute("placeholder", options.placeholder);
            containerElement.appendChild(inputElement);

            // add input field clear button
            var clearButton = document.createElement("div");
            clearButton.classList.add("clear-button");
            addIcon(clearButton);
            clearButton.addEventListener("click", (e) => {
                address
                e.stopPropagation();
                inputElement.value = '';
                callback(null);
                clearButton.classList.remove("visible");
                closeDropDownList();
            });
            containerElement.appendChild(clearButton);

            /* Current autocomplete items data (GeoJSON.Feature) */
            var currentItems;

            /* Active request promise reject function. To be able to cancel the promise when a new request comes */
            var currentPromiseReject;

            /* Focused item in the autocomplete list. This variable is used to navigate with buttons */
            var focusedItemIndex;

            /* Execute a function when someone writes in the text field: */
            inputElement.addEventListener("input", function (e) {
                var currentValue = this.value;

                /* Close any already open dropdown list */
                closeDropDownList();

                // Cancel previous request promise
                if (currentPromiseReject) {
                    currentPromiseReject({
                        canceled: true
                    });
                }

                if (!currentValue) {
                    clearButton.classList.remove("visible");
                    return false;
                }

                // Show clearButton when there is a text
                clearButton.classList.add("visible");

                /* Create a new promise and send geocoding request */
                var promise = new Promise((resolve, reject) => {
                    currentPromiseReject = reject;

                    var apiKey = key_to_use;
                    var url = `https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(currentValue)}&limit=5&apiKey=${apiKey}`;

                    if (options.type) {
                        url += `&type=${options.type}`;
                    }

                    fetch(url)
                        .then(response => {
                            // check if the call was successful
                            $.get(`/update_gestion_geoapify/${page_name}`,`url=${window.location.href}`)

                            if (response.ok) {
                                response.json().then(data => resolve(data));
                            } else {
                                response.json().then(data => reject(data));
                            }
                        });
                });

                promise.then((data) => {
                    currentItems = data.features;

                    /*create a DIV element that will contain the items (values):*/
                    var autocompleteItemsElement = document.createElement("div");
                    autocompleteItemsElement.setAttribute("class", "autocomplete-items");
                    containerElement.appendChild(autocompleteItemsElement);

            //insertion de multiple ville
              var i = 0;
              i = $(".loglat").length;
              $('.autocomplete-items').on('click', function(){
                i++;
                var latitude = $('#latitude').val();
                var longitude = $('#longitude').val();
                var address = $('#address').val();

                $('#address').val('');

                $( "#home-search-sc2" ).append($('<input type="hidden" class="loglat" id="itemLat_'+i+'" name="itemLat['+i+'][lat]" value="'+latitude+'">'));
                $( "#home-search-sc2" ).append($('<input type="hidden" id="itemLog_'+i+'" name="itemLat['+i+'][log]" value="'+longitude+'">'));
                $( "#home-search-sc2" ).append($('<input type="hidden" id="itemAdd_'+i+'" name="itemLat['+i+'][add]" value="'+address+'">'));

                $( "#champ-recherche" ).append($('<div class="XaYpq _1GwGp oydxI" id="'+i+'"><div class="_1DJWB"><div class="_1f4ib"><span class="_137P- P4PEa _3j0OU"> '+ address + '</span></div></div><div class="_3hxfS _3Cn9F"><svg viewBox="0 0 24 24" class="sc-bdVaJa src___StyledBox-fochin-0 cSECIV"><use xlink:href="#SvgMore"><svg id="SvgMore"><path d="M12 0a12 12 0 1012 12A12 12 0 0012 0zm4.8 13.2h-3.6v3.6a1.2 1.2 0 01-2.4 0v-3.6H7.2a1.2 1.2 0 110-2.4h3.6V7.2a1.2 1.2 0 112.4 0v3.6h3.6a1.2 1.2 0 010 2.4z"></path></svg></use></svg></div></div>'));



            $("button.ap-input-icon.ap-icon-clear").click();
              });

                    /* For each item in the results */
                    data.features.forEach((feature, index) => {
                        /* Create a DIV element for each element: */
                        var itemElement = document.createElement("DIV");
                        /* Set formatted address as item value */
                        itemElement.innerHTML = feature.properties.formatted;

                        /* Set the value for the autocomplete text field and notify: */
                        itemElement.addEventListener("click", function (e) {
                            inputElement.value = currentItems[index].properties.formatted;

                            callback(currentItems[index]);

                            /* Close the list of autocompleted values: */
                            closeDropDownList();
                        });

                        autocompleteItemsElement.appendChild(itemElement);
                    });
                }, (err) => {
                    if (!err.canceled) {
                        console.log(err);
                    }
                });
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
                inputElement.value = currentItems[index].properties.formatted;
                callback(currentItems[index]);
            }

            function closeDropDownList() {
                var autocompleteItemsElement = containerElement.querySelector(".autocomplete-items");
                if (autocompleteItemsElement) {
                    containerElement.removeChild(autocompleteItemsElement);
                }

                focusedItemIndex = -1;
            }

            function addIcon(buttonElement) {
                var svgElement = document.createElementNS("http://www.w3.org/2000/svg", 'svg');
                svgElement.setAttribute('viewBox', "0 0 24 24");
                svgElement.setAttribute('height', "24");

                var iconElement = document.createElementNS("http://www.w3.org/2000/svg", 'path');
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

        addressAutocomplete(document.getElementById("autocomplete-container"), (data) => {
            initInputAutoComplete("address", data);

            console.log("Selected option: ");
            console.log(data);
        }, {
            placeholder: "Enter un lieu"
        });


        function initInputAutoComplete(id, data) {
            // console.log(data);
            $('#latitude').val("");
            $('#longitude').val("");

            $('#latitude').val(data.properties.lat);
            $('#longitude').val(data.properties.lon);
            // const val = document.getElementById('latitude').value;
//   console.log(val);
            //$address.textContent = e.suggestion.value


        }


    </script>
@endpush

@push('styles')
    <style>
        #champ-recherche {
        background-color: #f4f6f7;
        border-radius: 4px;
        width: 100%;
        cursor: pointer;
        font-size: 0;
        width: 95%;
        margin-bottom: 11px;
        padding: 2px;
        height: 100% !important;
        padding: 7px;
        min-height: 51px;
    }

    .XaYpq {
    border: 1px solid #cad1d9;
    border-radius: 16px;
    display: inline-block;
    white-space: nowrap;
    height: 3.2rem;
    transition: background-color .2s ease-in-out,border .2s ease-in-out;
    cursor: default;
    background: #fff;
    position: relative;
    margin: 3px 2px;
    max-width: 132px;

}


.XaYpq ._1DJWB {
    position: relative;
    width: 100%;
    color: #1a1a1a;
    font-size: 0;
    line-height: 0;
    padding: 0 1.6rem;
    white-space: normal;
}
.XaYpq {
    min-width: 137px;
}
.XaYpq.oydxI ._1DJWB {
    width: calc(100% - 2.3rem + 1px);
}
.XaYpq.oydxI ._1DJWB {
    padding-right: .6rem;
}
.XaYpq ._1DJWB ._1f4ib {
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.XaYpq ._1DJWB ._1f4ib, .XaYpq ._1DJWB ._3qFDa {
    position: relative;
    display: inline-block;
    line-height: calc(3.2rem - 2px);
    height: calc(3.2rem - 2px);
    font-size: 1.4rem;
    font-weight: 400;
    vertical-align: top;
}
.P4PEa {
    line-height: 1.9rem;
}

.XaYpq ._3hxfS._3Cn9F {
    text-align: left;
}
.XaYpq ._3hxfS {
    width: 2.3rem;
    padding-top: .6rem;
    text-align: right;
    overflow: hidden;
}
.XaYpq>:not(._3hxfS), .XaYpq>:not(._30xoP) {
    white-space: normal;
}
.XaYpq ._1DJWB, .XaYpq ._3hxfS, .XaYpq ._30xoP {
    display: inline-block;
    vertical-align: top;
    height: calc(3.2rem - 2px);
    line-height: calc(3.2rem - 2px);
    font-size: 0;
}
.XaYpq ._3hxfS._3Cn9F svg {
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
}
svg:not(:root) {
    overflow: hidden;
}
.cSECIV {
    width: 1.6rem;
    height: 1.6rem;
    min-width: 1.6rem;
    fill: rgb(168, 180, 192);
    color: rgb(168, 180, 192);
}
.cSECIV {
    width: 1.6rem;
    height: 1.6rem;
    min-width: 1.6rem;
    fill: rgb(168, 180, 192);
    color: rgb(168, 180, 192);
}
.XaYpq ._3hxfS._3Cn9F {
    text-align: left;
    cursor: pointer;
}



        .autocomplete-container {
            /*the container must be positioned relative:*/
            position: relative;

            margin-bottom: 20px;
        }

        .autocomplete-container input {
            width: calc(100% - 43px);
            outline: none;

            border: 1px solid rgba(0, 0, 0, 0.2);
            padding: 10px;
            padding-right: 31px;
            font-size: 16px;
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0px 2px 10px 2px rgba(0, 0, 0, 0.1);
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: calc(100% + 34px);
            left: 0;
            right: 0;

            background-color: #fff;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
        }

        .autocomplete-items div:hover {
            /*when hovering an item:*/
            background-color: rgba(0, 0, 0, 0.1);
        }

        .autocomplete-items .autocomplete-active {
            /*when navigating through the items using the arrow keys:*/
            background-color: rgba(0, 0, 0, 0.1);
        }

        .clear-button {
            color: rgba(0, 0, 0, 0.4);
            cursor: pointer;

            position: absolute;
            right: 13px;
            top: 17px;
            z-index: 100;
            height: 100%;
            display: none;
            align-items: center;
        }

        .clear-button.visible {
            display: flex;
        }


        .clear-button:hover {
            color: rgba(0, 0, 0, 0.6);
        }
    </style>
@endpush
<input type="hidden" value="{{getSearchFilters('page')}}" id="page-index" name="">
<div id="top-filter-container" class="top-filter-container row-fluid full-width no-gutter" align="center"
     style="margin-top:0px;"> <!-- inner-filter-wrap -->
    <input type="hidden" id="type-design" value="premium" name="">
    <form id="form-filter-container" class="form-inline no-gutter d-flex">
        <a class="filter-item-left d-flex flex-column" href="{{ url()->previous() }}">
            <i class="fa fa-arrow-left"></i>
            <span>{{ __('searchlisting.retour') }}</span>
        </a>

        <div class="filter-item-center flex-one d-flex justify-content-between">
            <div class="filter-div" id="filter-scenario-search">
                <a href="javascript:" left-position="0" id="scenario-search"
                   class="filter-parent filter-top-button web-filter">
                    <!-- <i class="fi fi-home filter-icon"></i> -->
                    <img src="/img/icon-colocation.png"/>
                    <div class="filter-title filter-title-scenario">
                           <span>
                            @if(isset($scenario_id) && $scenario_id == 3)
                                   {{ __('searchlisting.locataire') }}
                               @endif
                               @if(isset($scenario_id) && $scenario_id == 4)
                                   {{ __('searchlisting.colocataire') }}
                               @endif
                               @if(isset($scenario_id) && $scenario_id == 1)
                                   {{ __('searchlisting.rent_property') }}
                               @endif
                               @if(isset($scenario_id) && $scenario_id == 2)
                                   {{ __('searchlisting.colocation') }}
                               @endif
                               @if(isset($scenario_id) && $scenario_id == 5)
                                   {{ __('searchlisting.monter_colocation') }}
                               @endif
                            </span>
                    </div>
                </a>
                <div class="text-bottom overflow-ellipsis"><span class="bolder">+</span><span class="bolder"
                                                                                              id="first-scenari-filter">0</span>&nbsp;<span
                        class="bolder text-light">filtres</span><span class="text-light"> {{ __('searchlisting.utilise') }},</span>
                </div>
                <div id="filter-div-container" class="filter-div-content">
                    <div class="entete-search-mobile helper-search-mobile-top">
                        <div>
                            <div class="filter-title-mobile-filter">{{ __('searchlisting.votre_recherche') }}</div>
                            <div class="mobile-menu-container">
                                <a href="javascript:" data-id="div-scenario-search"
                                   class="mobile-menu active menu-filter-on-mobile">
                                    <i class="fi fi-home filter-icon"></i>
                                </a>
                                <a href="javascript:" data-id="div-map-search"
                                   class="mobile-menu menu-filter-on-mobile">
                                    <i class="fi fi-map-marker-alt filter-icon"></i>
                                </a>
                                <a href="javascript:" data-id="div-budget-search"
                                   class="mobile-menu menu-filter-on-mobile">
                                    <i class="fi fi-euro filter-icon"></i>
                                </a>
                                @if($scenario_id!=4)
                                    <a href="javascript:" data-id="div-pieces-search"
                                       class="mobile-menu menu-filter-on-mobile">
                                        <i class="fi fi-room filter-icon"></i>
                                    </a>
                                @endif
                                <a href="javascript:" data-id="div-colocataire-search"
                                   class="mobile-menu menu-filter-on-mobile">
                                    <i class="fi @if($scenario_id!=1)fi-persons @else fi-heart @endif filter-icon"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="footer-search-mobile helper-search-mobile">
                        <div>
                            <div class="content-footer-search-mobile">
                                <div class="btn-appliquer-mobile">
                                    <button type="button" id="button-appliquer-filter" class="btn button-appliquer">
                                        {{ __('searchlisting.appliquer') }}
                                    </button>
                                </div>
                                <div class="btn-annuler-filter-mobile">
                                    <i class="fi fi-close filter-icon-close"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="cache-shadow-scenario" class="content-body">
                        <div id="div-scenario-search"
                             {{-- class="filter-content @if(isset($map)) map-scenario-filter @endif active"> --}}
                             class="filter-content active">
                            <div class="cache-shadow cache-shadow-left"></div>
                            <div class="row content-left">
                                    <span class="filter-child-title filter-child">
                                        {{ __('searchlisting.je_cherche') }}
                                    </span>
                                <div class="filter-child-content radio-box-filter">
                                    <a href="javascript:"
                                       class="scenario_id_ajax logement_entier type-scenario-filter new-filtre-scen @if(isset($scenario_id) && $scenario_id == 1) active @endif"
                                       value="1">{{ __('searchlisting.rent_property') }}</a>
                                    <a href="javascript:"
                                       class="scenario_id_ajax type-scenario-filter new-filtre-scen @if(isset($scenario_id) && $scenario_id == 2) active @endif"
                                       value="2">{{ __('searchlisting.colocation') }}</a>
                                    {{-- @if(!isset($map)) --}}
                                        <a href="javascript:"
                                           class="@if(isset($scenario_id) && $scenario_id == 3) active @endif scenario_id_ajax type-scenario-filter type-scenario-filter-first new-filtre-scen"
                                           value="3">{{ __('searchlisting.locataire') }}</a>
                                        <a href="javascript:"
                                           class="scenario_id_ajax type-scenario-filter-last new-filtre-scen @if(isset($scenario_id) && $scenario_id == 4) active @endif"
                                           value="4">{{ __('searchlisting.colocataire') }}</a>
                                        <a href="javascript:"
                                           class=" box-monter-colocation scenario_id_ajax type-scenario-filter-last new-filtre-scen @if(isset($scenario_id) && $scenario_id == 5) active @endif"
                                           value="5">{{ __('searchlisting.monter_colocation') }}</a>
                                    {{-- @endif --}}
                                </div>
                                <div>
                                    <ul class="filter-check-listing">
                                        <li>
                                            <input class="custom-checkbox" id="urgent-chkbox" type="checkbox"
                                                   @if(!is_null(getSearchFilters('urgent')) &&  getSearchFilters('urgent') == 1) checked
                                                   @endif value="1" name="prop_urgent[]">
                                            <label for="urgent-chkbox">
                                                <?php echo __('searchlisting.urgent_ads'); ?>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @if($scenario_id == 1 ||$scenario_id ==2)
                                <div class="row content-left div-type-location">
                                    <span class="filter-child-title filter-child">
                                        {{ __('property.type_location') }}
                                    </span>
                                    <div>
                                        <div class="custom-selectbx">
                                            <?php $sous_type_loc = getTypeLocation(); ?>
                                            <select id="sous_loc_type" name="sous_loc_type"
                                                    title="{{__('filters.no_selected')}}" class="selectpicker">
                                                <option value="0"></option>
                                                @foreach($sous_type_loc as $data)
                                                    <option
                                                        value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row content-left">
                                    <span class="filter-child-title filter-child">
                                        {{ __('searchlisting.apercu') }}
                                    </span>
                                <div>
                                    <ul class="filter-check-listing">
                                        <li>
                                            <input class="custom-checkbox"
                                                   @if(!is_null(getSearchFilters('with_image')) &&  getSearchFilters('with_image') == 1) checked
                                                   @endif id="image-chkbox" type="checkbox" value="1"
                                                   name="prop_image[]">
                                            <label for="image-chkbox">{{ __('searchlisting.with_image') }}</label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row content-left">
                                    <span class="filter-child-title filter-child">
                                        {{ __('searchlisting.quel_property') }}
                                    </span>
                                @if(!empty($propertyTypes) && count($propertyTypes) > 0)
                                    <div>
                                        <div class="form-group">
                                            <div class="custom-selectbx">
                                                <select class="sport-sumo-select sumo-select"
                                                        placeholder="{{__('filters.no_selected')}}" name="prop_type[]"
                                                        id="prop_type" multiple="">
                                                    @foreach($propertyTypes as $propertyType)
                                                        <option
                                                            @if(!is_null(getSearchFilters('property_types')) &&  is_int(array_search($propertyType->id, explode(',',getSearchFilters('property_types'))))) selected
                                                            @endif value="{{$propertyType->id}}">{{traduct_info_bdd($propertyType->property_type)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div id="div-map-search" class="filter-content">
                            <div class="cache-shadow cache-shadow-left"></div>
                            @if(!isUserSubscribed())
                                <div class="row content-left">
                                    <div class="div-cadenas">
                                        <img class="img-cadenas" src="/img/cadenas.png"/>
                                        <p>{{ __('searchlisting.benefice_filtre') }}</p>
                                        <a class="btn-more-detail" style="width : 150px;"
                                           href="/subscription_plan?type=filtre">Passer en Premium</a>
                                    </div>
                                </div>
                            @endif
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.ou_cherchez_vous') }}
                                </span>

                                <div id="champ-recherche" style="height: 37px;">
                                    @if(!empty($itemLat))
                                    @foreach($itemLat as $key=>$itemLa)
                                        <div class="XaYpq _1GwGp oydxI" id="{{ $key }}">
                                            <div class="_1DJWB">
                                                <div class="_1f4ib">

                                                 <span class="_137P- P4PEa _3j0OU">{{ $itemLa["add"] }}</span>

                                                </div>
                                            </div>
                                            <div class="_3hxfS _3Cn9F">
                                            <svg viewBox="0 0 24 24" class="sc-bdVaJa src___StyledBox-fochin-0 cSECIV">
                                            <use xlink:href="#SvgMore">
                                            <svg id="SvgMore"><path d="M12 0a12 12 0 1012 12A12 12 0 0012 0zm4.8 13.2h-3.6v3.6a1.2 1.2 0 01-2.4 0v-3.6H7.2a1.2 1.2 0 110-2.4h3.6V7.2a1.2 1.2 0 112.4 0v3.6h3.6a1.2 1.2 0 010 2.4z"></path></svg>

                                            </use>
                                            </svg>

                                            </div>
                                        </div>
                                    @endforeach
                                 @endif
                                </div>

                                {{-- eto --}}
                                <div>
                                    <div class="form-group div-search-map-input">
                                        <button type="button" id="btn-recherche-lieux" class="btn btn-recherche-lieux">
                                            <i class="fi fi-search"></i>
                                        </button>
                                        <div class="input-group">
                                            <div class="autocomplete-container" id="autocomplete-container"></div>
                                            <input id="address" name="address" class="form-control" type="text"
                                                   placeholder="{{ __('filters.enter_location') }}"
                                                   @if(isset($address)) value="{{$address}}" @endif>
                                            <input type="hidden" id="latitude" name="latitude"
                                                   @if(isset($lat)) value="{{$lat}}" @endif>
                                            <input type="hidden" id="longitude" name="longitude"
                                                   @if(isset($long)) value="{{$long}}" @endif>
                                            <input id="actual_address" name="actual_address" type="hidden"
                                                   @if(isset($address)) value="{{$address}}" @endif>
                                        </div>
                                        <label class="error-address">{{__('filters.error_adress_valide')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-left">
                                    <span class="filter-child-title filter-child">
                                        {{ __('property.point_proximity') }}
                                    </span>
                                <div>
                                    <div class="form-group div-search-map-input">
                                        <div class="input-group">
                                            <select id="proximity" placeholder="{{__('filters.no_selected')}}"
                                                    name="proximity[]" class="sumo-select" multiple="">
                                                @if(count($proximities)>0)
                                                    @foreach($proximities as $proximity)
                                                        <option @if(!isUserSubscribed()) disabled=""
                                                                @endif value="{{$proximity->id}}">{{ $proximity->title }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="premium_only"><span>* {{ __('searchlisting.only_premium') }}</span>
                                    </div>
                                    <label class="error-address">{{__('filters.error_adress_valide')}}</label>
                                </div>
                            </div>
                            @if($scenario_id==1 || $scenario_id==2)
                                <?php $lines = getAllMetroLines($address);?>
                                @if(count($lines) > 0)
                                    <div class="row content-left">
                                    <span class="filter-child-title filter-child">
                                        {{ __('filters.metro_lines') }}
                                    </span>
                                        <div>
                                            <div class="custom-selectbx">
                                                <select class="selectpicker" title="{{__('filters.no_selected')}}"
                                                        name="metro_line" id="metro_line">
                                                    <option value=""></option>
                                                    @foreach($lines as $key => $value)
                                                        <option value="{{$value}}">{{$value}}</option>

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <div class="row content-left">
                                    <span class="filter-child-title filter-child">
                                        Rayon
                                        <!-- {{ __('searchlisting.zone_recherche') }} -->
                                    </span>

                                <div class="div-menu-search-area" id="div-radius">
                                    <div class="custom-range-slider">
                                        <div class="range-slider-value" id="ex6CurrentSliderValLabel"><h6
                                                id="ex6SliderVal">@if(!is_null(getSearchFilters('radius')))
                                                    {{getSearchFilters('radius')}}
                                                @else
                                                    {{ $radius??'40' }}
                                                @endif</h6>{{ __('searchlisting.km') }}</div>
                                        <input id="ex6" type="text" data-slider-min="1" data-slider-max="40"
                                               data-slider-step="1"
                                               data-slider-value="@if(!is_null(getSearchFilters('radius'))) {{getSearchFilters('radius')}} @else {{ $radius??'40' }} @endif"/>
                                    </div>
                                </div>
                                @if(isset($map))
                                    <div class="div-menu-search-area div-menu-search-area-hidden" id="div-travel-time">

                                        <div>
                                            {{__('searchlisting.travel_message', array("scenario" => __("searchlisting.travel_scenario_" . $scenario_id)))}}
                                        </div>
                                        <div>
                                            <div class="div-select div-padd-other">
                                                <select title="{{__('filters.no_selected')}}"
                                                        class="selectpicker travel-input" name="travel_time"
                                                        id="travel_time">
                                                    <option value="600">10 {{__('searchlisting.min')}}</option>
                                                    <option value="1200">20 {{__('searchlisting.min')}}</option>
                                                    <option value="1800">30 {{__('searchlisting.min')}}</option>
                                                    <option value="2200">45 {{__('searchlisting.min')}}</option>
                                                    <option value="3600">1 {{__('searchlisting.hour')}}</option>
                                                    <option value="5400">1 {{__('searchlisting.hour')}}
                                                        30 {{__('searchlisting.min')}}</option>
                                                    <option value="7200">2 {{__('searchlisting.hour')}}</option>
                                                </select>
                                            </div>
                                            <div class="div-select div-padd-other">
                                                <select title="{{__('filters.no_selected')}}"
                                                        class="selectpicker travel-input" name="travel_mode"
                                                        id="travel_mode">
                                                    <option value="walking">{{__("searchlisting.a_pied")}}</option>
                                                    <option value="cycling">{{__("searchlisting.a_velo")}}</option>
                                                    <option selected
                                                            value="driving">{{__("searchlisting.en_voiture")}}</option>
                                                    <option
                                                        value="public_transport">{{__("searchlisting.public_transport")}}</option>

                                                </select>
                                            </div>
                                            <div class="div-select div-padd">
                                                <span>{{ __('searchlisting.de') }}</span>
                                                <select title="{{__('filters.no_selected')}}"
                                                        class="selectpicker travel-input" name="travel_type_place"
                                                        id="travel_type_place">
                                                    <option selected value="0">{{__("searchlisting.my_work")}}</option>
                                                    <option value="1">{{__("searchlisting.my_school")}}</option>
                                                    <option value="2">{{__("searchlisting.other_place")}}</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="div-select div-select-address">
                                            <span>{{ __('searchlisting.situe') }} : </span>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                                <input id="address-travel" name="address_travel" class="form-control"
                                                       type="text" placeholder="{{ __('searchlisting.adress_exact') }}">
                                                <input type="hidden" id="latitude-travel" name="latitude_travel">
                                                <input type="hidden" id="longitude-travel" name="longitude_travel">
                                            </div>
                                            <span
                                                class="exemple-address">{{__("searchlisting.travel_address_ex")}}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div id="div-budget-search" class="filter-content">
                            <div class="cache-shadow cache-shadow-budget cache-shadow-left"></div>
                            <div class="row content-left">
                                <i class="filter-icon-mobile fi fi-euro filter-icon-blue filter-icon-blue-modal"></i>
                                <span class="filter-child-title filter-child">
                                        {{ __('searchlisting.votre_budget') }}
                                    </span>
                                <div class="filter-child-content">
                                    <input type="text" class="form-control input-rent" name="min_rent" id="min_rent"
                                           placeholder="{{__('searchlisting.min_loyer')}}"
                                           @if(!is_null(getSearchFilters('min_rent'))) value="{{getSearchFilters('min_rent')}}" @endif>
                                    <input type="text" class="form-control input-rent" name="max_rent" id="max_rent"
                                           @if(!is_null(getSearchFilters('max_rent'))) value="{{getSearchFilters('max_rent')}}"
                                           @endif placeholder="{{__('searchlisting.max_loyer')}}">
                                </div>
                                <span id="error-budget" class=" filter-child filter-error">
                                    </span>
                            </div>
                        </div>
                        <div id="div-pieces-search" class="filter-content">
                            <div class="cache-shadow cache-shadow-budget cache-shadow-pieces cache-shadow-right"></div>
                            <div class="row content-left">

                                    <span class="filter-child-title filter-child">
                                        {{ __('searchlisting.surface_habitable') }}
                                    </span>
                                <div class="filter-child-content">
                                    <input type="text" class="form-control input-rent" name="min_area" id="min_area"
                                           @if(!is_null(getSearchFilters('min_area'))) value="{{getSearchFilters('min_area')}}"
                                           @endif placeholder="{{__('searchlisting.min_surface')}}">
                                    <input type="text" class="form-control input-rent" name="max_area" id="max_area"
                                           @if(!is_null(getSearchFilters('max_area'))) value="{{getSearchFilters('max_area')}}"
                                           @endif placeholder="{{__('searchlisting.max_surface')}}">
                                </div>
                                <span id="error-surface" class="filter-child-title filter-child filter-error">
                                    </span>
                            </div>
                            <div class="row content-left">
                                    <span class="filter-child-title filter-child">
                                        {{ __('searchlisting.is_meuble') }}
                                    </span>
                                <div class="filter-radio-listing">
                                    <div class="custom-radio">
                                        <input type="radio" id="f-furnished-op1"
                                               @if(!is_null(getSearchFilters('furnished')) &&  getSearchFilters('furnished') == 0) checked
                                               @endif name="furnished" value="0"/>
                                        <label for="f-furnished-op1">{{ __('searchlisting.yes') }}</label>
                                    </div>
                                    <div class="custom-radio">
                                        <input type="radio" id="f-furnished-op2"
                                               @if(!is_null(getSearchFilters('furnished')) &&  getSearchFilters('furnished') == 1) checked
                                               @endif name="furnished" value="1"/>
                                        <label for="f-furnished-op2">{{ __('searchlisting.no') }}</label>
                                    </div>
                                    <div class="custom-radio custom-radio-big">
                                        <input type="radio" id="f-furnished-op3" name="furnished"
                                               @if(!is_null(getSearchFilters('furnished')) &&  getSearchFilters('furnished') != 0 && getSearchFilters('furnished') != 1) checked
                                               @endif value=""/>
                                        <label for="f-furnished-op3">{{ __('searchlisting.dont_matter') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-left">
                                    <span class="filter-child-title filter-child">
                                        {{ __('searchlisting.combien_bedroom') }}
                                    </span>
                                <div class="filter-radio-listing">
                                    <div class="custom-radio">
                                        <input class="bedroom_check" type="radio"
                                               @if(is_null(getSearchFilters('bedrooms')) || getSearchFilters('bedrooms') == 0 ) checked
                                               @endif id="bedroom-op1" name="bedrooms" checked="checked" value="0"/>
                                        <label for="bedroom-op1">{{ __('searchlisting.any') }}</label>
                                    </div>
                                    <div class="custom-radio">
                                        <input class="bedroom_check" type="radio"
                                               @if(!is_null(getSearchFilters('bedrooms')) &&  getSearchFilters('bedrooms') == 1) checked
                                               @endif id="bedroom-op2" name="bedrooms" value="1"/>
                                        <label for="bedroom-op2">1+</label>
                                    </div>
                                    <div class="custom-radio">
                                        <input class="bedroom_check" type="radio"
                                               @if(!is_null(getSearchFilters('bedrooms')) &&  getSearchFilters('bedrooms') == 2) checked
                                               @endif id="bedroom-op3" name="bedrooms" value="2"/>
                                        <label for="bedroom-op3">2+</label>
                                    </div>
                                    <div class="custom-radio">
                                        <input class="bedroom_check" type="radio"
                                               @if(!is_null(getSearchFilters('bedrooms')) &&  getSearchFilters('bedrooms') == 4) checked
                                               @endif id="bedroom-op4" name="bedrooms" value="3"/>
                                        <label for="bedroom-op4">3+</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-left">
                                    <span class="filter-child-title filter-child">
                                        {{ __('searchlisting.combien_bathroom') }}
                                    </span>
                                <div class="filter-child-content radio-box-filter">
                                    <div class="filter-radio-listing">
                                        <div class="custom-radio">
                                            <input class="bathroom_check" type="radio"
                                                   @if(!is_null(getSearchFilters('bathrooms')) &&  getSearchFilters('bedrooms') == 0) checked
                                                   @endif id="bathroom-op1" name="bathrooms" checked="checked"
                                                   value="0"/>
                                            <label for="bathroom-op1">{{ __('searchlisting.any') }}</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input class="bathroom_check" type="radio"
                                                   @if(!is_null(getSearchFilters('bathrooms')) &&  getSearchFilters('bathrooms') == 1) checked
                                                   @endif id="bathroom-op2" name="bathrooms" value="1"/>
                                            <label for="bathroom-op2">1+</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input class="bathroom_check" type="radio"
                                                   @if(!is_null(getSearchFilters('bathrooms')) &&  getSearchFilters('bathrooms') == 2) checked
                                                   @endif id="bathroom-op3" name="bathrooms" value="2"/>
                                            <label for="bathroom-op3">2+</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input class="bathroom_check" type="radio"
                                                   @if(!is_null(getSearchFilters('bathrooms')) &&  getSearchFilters('bathrooms') == 3) checked
                                                   @endif id="bathroom-op4" name="bathrooms" value="3"/>
                                            <label for="bathroom-op4">3+</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-left">
                                    <span class="filter-child-title filter-child">
                                        {{ __('searchlisting.is_separate_kitchen') }}
                                    </span>
                                <div class="filter-radio-listing">
                                    <div class="custom-radio">
                                        <input class="kitchen_check" type="radio"
                                               @if(!is_null(getSearchFilters('kitchen')) &&  getSearchFilters('kitchen') == 1) checked
                                               @endif  id="kitchen-op1" name="kitchen" value="1"/>
                                        <label for="kitchen-op1">{{ __('searchlisting.yes') }}</label>
                                    </div>
                                    <div class="custom-radio">
                                        <input class="kitchen_check" type="radio"
                                               @if(!is_null(getSearchFilters('kitchen')) &&  getSearchFilters('kitchen') == 0) checked
                                               @endif id="kitchen-op2" name="kitchen" value="0"/>
                                        <label for="kitchen-op2">{{ __('searchlisting.no') }}</label>
                                    </div>
                                    <div class="custom-radio custom-radio-big">
                                        <input class="kitchen_check" type="radio" id="kitchen-op3"
                                               @if(is_null(getSearchFilters('kitchen')) ||  empty(getSearchFilters('kitchen'))) checked
                                               @endif name="kitchen" value=""/>
                                        <label for="kitchen-op3">{{ __('searchlisting.dont_matter') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="div-colocataire-search"
                             class="filter-content @if($scenario_id==1) div-scenario-1 @endif">
                            <div class="cache-shadow cache-shadow-right"></div>
                            @if(!isUserSubscribed())
                                <div class="row content-left">
                                    <div class="div-cadenas">
                                        <img class="img-cadenas" src="/img/cadenas.png"/>
                                        <p>{{ __('searchlisting.benefice_filtre') }}</p>
                                        <a class="btn-more-detail" style="width : 150px;"
                                           href="/subscription_plan?type=filtre">{{ __('searchlisting.passe_premium') }}</a>
                                    </div>
                                </div>
                            @endif
                            @if($scenario_id!=1)
                                <div class="row content-left">
                                    <span class="filter-child-title filter-child">
                                        {{ __('searchlisting.quel_genre') }}
                                    </span>
                                    <div>
                                        <div class="form-group">
                                            <div class="custom-selectbx">
                                                <select @if(!isUserSubscribed()) disabled=""
                                                        @endif  class="sport-sumo-select sumo-select"
                                                        placeholder="{{__('filters.no_selected')}}" name="gender[]"
                                                        id="gender" multiple="">
                                                    <option
                                                        @if(!is_null(getSearchFilters('gender', true)) &&  is_int(array_search(0, getSearchFilters('gender', true)))) selected=""
                                                        @endif value="0">{{ __('searchlisting.men') }}</option>
                                                    <option
                                                        @if(!is_null(getSearchFilters('gender', true)) &&  is_int(array_search(1, getSearchFilters('gender', true)))) selected=""
                                                        @endif value="1">{{ __('searchlisting.women') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($scenario_id==1 || $scenario_id == 3)
                                <div class="row content-left">
                                    @if($scenario_id==1)
                                        <span class="filter-child-title filter-child">
                                        {{ __('filters.garantie_demande') }}
                                    </span>
                                    @endif
                                    @if($scenario_id==3)
                                        <span class="filter-child-title filter-child">
                                        {{ __('filters.garantie') }}
                                    </span>
                                    @endif
                                    <div>
                                        <div class="custom-selectbx">
                                            <select @if(!isUserSubscribed()) disabled=""
                                                    @endif  class="sport-sumo-select sumo-select"
                                                    placeholder="{{__('filters.no_selected')}}" name="garanty[]"
                                                    id="garanty" multiple="">
                                                <?php $guarAsked = getUsersGaranties(); ?>
                                                @foreach($guarAsked as $data)
                                                    <option
                                                        @if(!is_null(getSearchFilters('garanty', true)) &&  is_int(array_search($data->id, getSearchFilters('garanty', true)))) checked
                                                        @endif value="{{$data->id}}">{{traduct_info_bdd($data->guarantee)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($scenario_id==4 || $scenario_id == 2 || $scenario_id == 5)
                                @if(Auth::check() && Auth::user()->provider=="facebook")
                                    <div class="row content-left">
                                        <i style="font-size : 1.5em;color:rgb(66,103,178);;"
                                           class="fa fa-facebook-official"></i>
                                        <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.avec_amis_en_commun') }}
                                    <a data-toggle="tooltip" data-placement="top"
                                       title="{{ __('searchlisting.trouver_amis_commun') }}" href="javascript:"
                                       class="info-sign-fb">
                                        <span class="glyphicon glyphicon-question-sign"></span>
                                            </a>
                                        </span>
                                        <div>
                                            <ul class="filter-check-listing">
                                                <li>
                                                    <input class="custom-checkbox friend_check"
                                                           @if(!is_null(getSearchFilters('common_friend')) &&  getSearchFilters('common_friend') == 2) checked
                                                           @endif id="fb-checkbox-1" type="checkbox" value="2">
                                                    <label for="fb-checkbox-1">{{ __('filters.with_common_friend') }}</label>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="row content-left">
                                            <span class="filter-child-title filter-child">
                                                {{ __('searchlisting.quel_occupation') }}
                                            </span>
                                            <div>
                                                <div class="form-group">
                                                    <div class="custom-selectbx">
                                                        <select @if(!isUserSubscribed()) disabled=""
                                                                @endif  class="sport-sumo-select sumo-select"
                                                                placeholder="{{__('filters.no_selected')}}"
                                                                name="occupation[]" id="occupation" multiple="">
                                                            <option
                                                                @if(!is_null(getSearchFilters('occupation', true)) &&  is_int(array_search(0, getSearchFilters('occupation', true)))) selected=""
                                                                @endif value="0">{{ __('profile.student') }}</option>
                                                            <option
                                                                @if(!is_null(getSearchFilters('occupation', true)) &&  is_int(array_search(1, getSearchFilters('occupation', true)))) selected=""
                                                                @endif  value="1">{{ __('profile.freelancer') }}</option>
                                                            <option
                                                                @if(!is_null(getSearchFilters('occupation', true)) &&  is_int(array_search(2, getSearchFilters('occupation', true)))) selected=""
                                                                @endif value="2">{{ __('profile.salaried') }}</option>
                                                            <option
                                                                @if(!is_null(getSearchFilters('occupation', true)) &&  is_int(array_search(3, getSearchFilters('occupation', true)))) selected=""
                                                                @endif value="3">{{ __('profile.cadre') }}</option>
                                                            <option
                                                                @if(!is_null(getSearchFilters('occupation', true)) &&  is_int(array_search(4, getSearchFilters('occupation', true)))) selected=""
                                                                @endif value="4">{{ __('profile.retraite') }}</option>
                                                            <option
                                                                @if(!is_null(getSearchFilters('occupation', true)) &&  is_int(array_search(5, getSearchFilters('occupation', true)))) selected=""
                                                                @endif value="5">{{ __('profile.chomage') }}</option>
                                                            <option
                                                                @if(!is_null(getSearchFilters('occupation', true)) &&  is_int(array_search(6, getSearchFilters('occupation', true)))) selected=""
                                                                @endif value="6">{{ __('profile.rentier') }}</option>
                                                            <option
                                                                @if(!is_null(getSearchFilters('occupation', true)) &&  is_int(array_search(7, getSearchFilters('occupation', true)))) selected=""
                                                                @endif value="7">{{ __('profile.situationPro7') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row content-left">
                                            <span class="filter-child-title filter-child">
                                                {{ __('filters.profession') }}
                                            </span>
                                            <div>
                                                <div class="custom-selectbx">
                                                    <select @if(!isUserSubscribed()) disabled=""
                                                            @endif class="selectpicker"
                                                            title="{{__('filters.no_selected')}}" name="profession"
                                                            id="profession">
                                                        <?php $profs = getUsersProfessions(); ?>
                                                        <option value=""></option>
                                                        @foreach($profs as $key => $value)
                                                            <option
                                                                @if(!is_null(getSearchFilters('profession')) && getSearchFilters('profession') == $value->profession) selected=""
                                                                @endif value="{{$value->profession}}">{{$value->profession}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row content-left">
                                            <span class="filter-child-title filter-child">
                                                {{ __('filters.quel_school') }}
                                            </span>
                                            <div>
                                                <div class="custom-selectbx">
                                                    <select @if(!isUserSubscribed()) disabled=""
                                                            @endif class="selectpicker"
                                                            title="{{__('filters.no_selected')}}" name="school_name"
                                                            id="school_name">
                                                        <?php $schools = getUsersSchools(); ?>
                                                        <option value=""></option>
                                                        @foreach($schools as $key => $value)
                                                            <option
                                                                @if(!is_null(getSearchFilters('school_name')) && getSearchFilters('school_name') == $value->school) selected=""
                                                                @endif value="{{$value->school}}">{{$value->school}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row content-left">
                                            <span class="filter-child-title filter-child">
                                                {{ __('searchlisting.est_il_fumeur') }}
                                            </span>
                                            <div>
                                                <div class="custom-selectbx">
                                                    <select @if(!isUserSubscribed()) disabled=""
                                                            @endif class="selectpicker"
                                                            title="{{__('filters.no_selected')}}" name="smoker"
                                                            id="smoker">
                                                        <option
                                                            @if(!is_null(getSearchFilters('smoker')) &&  getSearchFilters('smoker') == 2) selected
                                                            @endif selected
                                                            value="2">{{ __('filters.smoker_indifferent') }}</option>
                                                        <option
                                                            @if(!is_null(getSearchFilters('smoker')) &&  getSearchFilters('smoker') == 0) selected
                                                            @endif value="0">{{ __('filters.smoker_no') }}</option>
                                                        <option
                                                            @if(!is_null(getSearchFilters('smoker')) &&  getSearchFilters('smoker') == 1) selected
                                                            @endif value="1">{{ __('filters.smoker_yes') }}</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row content-left">
                                            <span class="filter-child-title filter-child">
                                                {{ __('filters.est_il_alcool') }}
                                            </span>
                                            <div>
                                                <div class="custom-selectbx">
                                                    <select class="selectpicker" @if(!isUserSubscribed()) disabled=""
                                                            @endif title="{{__('filters.no_selected')}}" name="alcool"
                                                            id="alcool">
                                                        <option
                                                            @if(!is_null(getSearchFilters('smoker')) &&  getSearchFilters('smoker') == 2) selected
                                                            @endif value="2">{{ __('filters.smoker_indifferent') }}</option>
                                                        <option
                                                            @if(!is_null(getSearchFilters('alcool')) &&  getSearchFilters('alcool') == 0) selected
                                                            @endif value="0">{{ __('filters.smoker_no') }}</option>
                                                        <option
                                                            @if(!is_null(getSearchFilters('alcool')) &&  getSearchFilters('alcool') == 1) selected
                                                            @endif value="1">{{ __('filters.smoker_yes') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row content-left">
                                            <span class="filter-child-title filter-child">
                                                {{ __('searchlisting.quel_interet') }}
                                            </span>
                                            @if(!empty($socialInterests) && count($socialInterests) > 0)
                                                <div>
                                                    <div class="form-group">
                                                        <div class="custom-selectbx">
                                                            <select @if(!isUserSubscribed()) disabled=""
                                                                    @endif class="sport-sumo-select sumo-select"
                                                                    placeholder="{{__('filters.no_selected')}}"
                                                                    name="social_interest[]" id="social_interest"
                                                                    multiple="">
                                                                @foreach($socialInterests as $i => $data)
                                                                    <option
                                                                        @if(!is_null(getSearchFilters('social_interest', true)) &&  is_int(array_search($data->id, getSearchFilters('social_interest', true)))) checked
                                                                        @endif value="{{$data->id}}">{{$data->interest_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="row content-left">
                                            <span class="filter-child-title filter-child">
                                                {{ __('filters.sport_preferes') }}
                                            </span>
                                            @if(!empty($socialInterests) && count($socialInterests) > 0)
                                                <div>
                                                    <div class="form-group">
                                                        <div class="custom-selectbx">
                                                            <select disabled="" class="sport-sumo-select sumo-select"
                                                                    placeholder="{{__('filters.no_selected')}}"
                                                                    name="social_interest[]" id="social_interest"
                                                                    multiple="">
                                                                @foreach($socialInterests as $i => $data)
                                                                    <option
                                                                        value="{{$data->id}}">{{$data->interest_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="row content-left">
                                            <span class="filter-child-title filter-child">
                                                {{ __('searchlisting.quel_music') }}
                                            </span>
                                            @if(!empty($typeMusics) && count($typeMusics) > 0)
                                                <div>
                                                    <div class="form-group">
                                                        <div class="custom-selectbx">
                                                            <select @if(!isUserSubscribed()) disabled=""
                                                                    @endif class="sport-sumo-select sumo-select"
                                                                    placeholder="{{__('filters.no_selected')}}"
                                                                    name="musics[]" id="musics" multiple="">
                                                                @foreach($typeMusics as $i => $data)
                                                                    <option
                                                                        @if(!is_null(getSearchFilters('social_interest', true)) &&  is_int(array_search($data->id, getSearchFilters('musics', true)))) checked
                                                                        @endif value="{{$data->id}}">{{$data->label}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>
                    <!-- <div class="content-footer">
                        <div class="btn-appliquer">
                            <button type="button" class="btn button-appliquer">Appliquer</button>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="filter-div web-filter" id="filter-div-map">
                <a href="javascript:" @if($scenario_id==4) left-position="250" @else left-position="204"
                   @endif left-position-medium="126" id="map-search" class="filter-parent filter-top-button web-filter">
                    <!--  left-position="204" -->
                    <img src="/img/pointer_home.png"/>
                    <div class="filter-title filter-title-map">
                           <span class="overflow-ellipsis">
                            {{$address}}
                            </span>
                    </div>
                </a>
                <div class="text-bottom overflow-ellipsis">+ <span
                        class="text-light">{{ __('searchlisting.rayon_de') }}</span> <span class="bolder"
                                                                                           id="ex6SliderValTextBottom">{{ $radius??'40' }}</span><span
                        class="bolder">Km</span><span class="text-light">,</span></div>
            </div>
            <div class="filter-div web-filter" id="filter-div-budget">
                <a href="javascript:" id="budget-search" @if($scenario_id==4) left-position="484"
                   @elseif($scenario_id==2) left-position="383" @else left-position="413"
                   @endif left-position-medium="256" class="filter-parent  filter-parent-budget filter-top-button">
                    <!-- left-position="407" -->
                    <img src="/img/icon-budget.png"/>
                    <div class="filter-title">
                            <span class="">
                                {{ __('searchlisting.budget') }}
                            </span>
                    </div>
                </a>
                <div class="text-bottom overflow-ellipsis d-flex justify-content-center">
                    <div id="min_rent_text_bottom" class="hidden">
                        <span class="bolder text-light">+</span><span class="bolder" id="min_rent_text_bottom_value"> 200</span>&nbsp;
                    </div>
                    <div id="max_rent_text_bottom" class="hidden">
                        <span class="text-light">à</span><span class="mobile-only"> - </span> <span class="bolder"
                                                                                                    id="max_rent_text_bottom_value">450</span><span
                            class="text-light"><img src="/img/icon-budget.png"/>,</span>
                    </div>
                </div>
            </div>
            @if($scenario_id!=4)
                <div class="filter-div web-filter" id="filter-div-pieces">
                    <a href="javascript:" id="pieces-search" @if($scenario_id==2) left-position="310"
                       @else left-position="360" @endif left-position-medium="135"
                       class="filter-parent filter-top-button">
                        <!-- left-position="330" -->
                        <img src="/img/piece.png"/>
                        <div class="filter-title">
                            <span class="">{{__('searchlisting.pieces')}}</span>
                        </div>
                    </a>
                    <div class="text-bottom overflow-ellipsis"><span class="bolder">+ </span><span class="bolder"
                                                                                                   id="last-scenari-filter">0</span>&nbsp;<span
                            class="bolder text-light">{{ __('searchlisting.filters') }}</span><span class="text-light"> {{ __('searchlisting.utilise') }},</span>
                    </div>
                </div>
            @endif


            @if($scenario_id!=1)
                <div class="filter-div web-filter" id="filter-colocataire-search">
                    <a href="javascript:" id="colocataire-search" top-position-medium="140"
                       @if($scenario_id==4) left-position="540" left-position-medium="170"
                       @elseif($scenario_id==3) left-position="572" left-position-medium="273"
                       @elseif($scenario_id==5) left-position="582" left-position-medium="273" @else left-position="512"
                       left-position-medium="273" @endif  class="filter-parent filter-top-button last-filter-div">
                        <i class="fi fi-persons"></i> <!-- filter-icon-retour -->
                        @if($scenario_id!=3)
                            <div class="filter-title">
                                <span>{{__('searchlisting.colocataire')}} {{__('searchlisting.ideal')}}</span>
                            </div>
                        @endif
                        @if($scenario_id==3)
                            <div class="filter-title">
                                <span>{{__('searchlisting.locataire')}} {{__('searchlisting.ideal')}}</span>
                            </div>
                        @endif
                    </a>
                </div>
            @endif
            @if($scenario_id==1)
                <div class="filter-div web-filter" id="filter-colocataire-search">
                    <a href="javascript:" id="colocataire-search" top-position-medium="140" left-position="496"
                       left-position-medium="223" class="filter-parent filter-top-button last-filter-div">
                        @if($scenario_id==1)
                            <i class="fi fi-heart"></i>
                            <div class="filter-title">
                                <span>{{__('filters.plus')}}</span>
                            </div>
                        @endif
                    </a>
                </div>
            @endif

            <div class="gird-options-icon-outer">
                    <span class="listing-view-icon option-mobile active">
                        <i class="fa-option fa fa-bars" aria-hidden="true"></i><span
                            class="text">{{__("searchlisting.liste")}}</span></span>
                @if($scenario_id == 1 || $scenario_id == 2)
                    <span class="grid-view-icon option-mobile search-option-middle"><i class="fa-option fa fa-th"
                                                                                       aria-hidden="true"></i><span
                            class="text">{{__("searchlisting.grid")}}</span></span>
                    <span class="listing-view-icon listing-view-icon-map"><a
                            href="{{enleveInscriptionPath() . '?map=true'}}@if($id!=0)&id={{$id}}@endif"><i
                                class="fa-option fas fa-map-marked-alt" aria-hidden="true"></i><span
                                class="text">{{__("searchlisting.carte")}}</span></a></span>
                @else
                    <span class="grid-view-icon option-mobile search-option-middle"><i class="fa-option fa fa-th"
                                                                                       aria-hidden="true"></i><span
                            class="text">{{__("searchlisting.grid")}}</span></span>
                @endif
            </div>
        </div>
        <div class="filter-item-right d-flex">
            <!-- <div class="listing-grid-option-outer text-right"> -->

            <div class="custom-selectbx search-dropdown-filter-top flex-one">
                <select id="sort" name="sort" class="selectpicker">
                    <option value="1">{{ __('searchlisting.recent') }}</option>
                    <option value="2">{{ __('searchlisting.increase_rent') }}</option>
                    <option value="3">{{ __('searchlisting.decrease_rent') }}</option>
                </select>
            </div>
            <!-- </div> -->
        </div>
    </form>
    <form class="form-inline no-gutter col-lg-12 mobile-filter">
        <a href="/choisir-design/0">
            <button type="button" id="btn-recherche-mobile" class="btn btn-recherche-mobile">
                <i class="fi fi-filter"></i>
                {{__('searchlisting.ma_recherche')}}
            </button>
        </a>
    </form>

</div>
<input type="hidden" id="premium" value="true" name="">
<style>
    .occup-none {
        display: none;
    }

    .error-address {
        color: red;
        display: none;
    }
</style>

@include("profile.profile-recherche")
