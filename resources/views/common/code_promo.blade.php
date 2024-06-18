<div id="codePromoModal" class="modal fade alert-modal" role="dialog">
    <div class="modal-dialog">
        <a href="javascript:" class="closeModalBtn" data-dismiss="modal"><span>x</span></a>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="alrt-modal-body">
                <h3>{{ __('addetails.saisir_code') }}</h3>
                <div class="alert alert-danger fade in alert-dismissable error-promo" style="margin-top:20px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                     <div id="error-body-promo"></div>
                </div>
                <div class="alert alert-success fade in alert-dismissable status-promo" style="margin-top:20px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                     <div id="status-body-promo"></div>
                </div>
                <form class="code-form" id="code-form" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="button_clicked" id="button_clicked_promo">
                <div class="form-group">
                        <label class="control-label" for="note">{{ __('addetails.code') }}</label>
                        <input type="text" name="code" id="code"/>
                    </div>
                <div class="porject-btn-1 project-btn-green" style="margin-left : initial;">
                    <a href="javascript:" id="submit_code_promo">{{__("addetails.submit")}}</a>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .status-promo
    {
        display: none;
    }

    .error-promo
    {
        display: none;
    }
     .error
    {
        color : red;
    }
</style>

<script type="text/javascript">
    $('document').ready(function(){
        jQuery.extend(jQuery.validator.messages, {
            required: "{{__('validator.required')}}",
            number: "{{__('validator.number')}}"
          });
        jQuery("#code-form").validate({
            rules: {
                 "code":{
                    "required": true
                 }
            }                
        });
         $("#code-form").validate();
        $('#submit_code_promo').on("click", function(){
            if(!$("#code-form").valid()) {
                return;
            }
            var code = $('#code').val();
            submitCodePromo(code);

        });

        $('#code_promo_header').on('click', function(){
            $('#codePromoModal').modal("show");
        });
    });
    function submitCodePromo(code)
    {
        $.ajax({
            url: '/submit_code_promo',
            type: "post",
            dataType: "json",
            data: {'code': code, "button_clicked" : $('#button_clicked_promo').val()}
        }).done(function (data){
            if(data.error == "yes") {

               $("#error-body-promo").text(data.message);
               showAnimate($(".error-promo"));
            } else {

               $("#status-body-promo").text(data.message);
                showAnimate($(".status-promo"), data.redirect_url);
            }

        }).fail(function (jqXHR, ajaxOptions, thrownError){
            alert('No response from server');
        });
    }

    function showAnimate(elem, href = null)
    {
        elem.show();
        setTimeout(function(){ 
            elem.hide(); 
            if(href != null) location.href = href;
        }, 3000);
    }
</script>