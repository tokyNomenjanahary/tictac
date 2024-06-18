<div class="card" style="margin-top: 5px">
    <div class="row">
        <div class="col-md-12" style="padding: 15px;">
            <div class="float-start">
                <a class="btn btn-dark"  id="previous-tab" style="color:white;float: left;">{{__('location.precedent')}}</a>
            </div>
            <div class="float-end">
                <button  id="lasa" class="btn btn-primary"> {{__('location.enregistrer')}} </button>
                <a class="btn btn-dark" id="next-tab" style="color:white">{{__('location.suivant')}}</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
    let activeTab = $('.nav-tabs > .nav-item > .active');
    let activeTabId = activeTab.attr('id');
    if (activeTabId == "home-tab"){
        $("#previous-tab").hide();
    }


    $('.nav-link').on('click', function () {
        // Mettre à jour l'affichage des boutons en fonction de l'onglet actif
        let activeTab = $('.nav-tabs > .nav-item > .active');
        let activeTabId = activeTab.attr('id');
        if (activeTabId === "home-tab") {
        $("#previous-tab").hide();
        $("#next-tab").show();
        } else if (activeTabId === "document-tab") {
        $("#previous-tab").show();
        $("#next-tab").hide();
        } else {
        $("#previous-tab").show();
        $("#next-tab").show();
        }
  });


    $('#next-tab').click(function () {
        let currentTab = $('.nav-tabs > .nav-item > .active');
        let nextTab = $(currentTab).parent().next().find('.nav-link');

        if (nextTab.length) {
            nextTab.tab('show');
        }
        let activeTab = $('.nav-tabs > .nav-item > .active');
        let activeTabId = activeTab.attr('id');
        if(activeTabId == "profile-tab" ){
            $("#previous-tab").show();
        }
        if(activeTabId == "document-tab" ){
            $("#next-tab").hide();
        }
    });


    $('#previous-tab').click(function (e) {
        e.preventDefault()
        let activeTabe = $('.nav-tabs > .nav-item > .active');
        let activeTabIde = activeTabe.attr('id');
        if(activeTabIde == "profile-tab"){
            $("#previous-tab").hide();
            $("#next-tab").show();
        }
        if(activeTabIde == "document-tab"){
            $("#next-tab").show();
        }

        let currentTab = $('.nav-tabs > .nav-item > .active');
        let previousTab = $(currentTab).parent().prev().find('.nav-link');
        if (previousTab.length) {
                previousTab.tab('show');
            }
        });

    });
</script>
