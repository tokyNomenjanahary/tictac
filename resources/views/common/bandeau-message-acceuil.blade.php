<link href="/css/bandeau-message.min.css" rel="stylesheet">
@push("scripts")
<script src="/js/bandeau-message.min.js"></script>
@endpush
<div class="bandeau-message bandeau-message-acceuil">
    <!--<div class="message-demande">{{__("bandeau.message")}}<a href="javascript:" class="button-close-bandeau" class="btn">x</a></div>-->
    <div class="message-demande">
    <div class="lancez_vous">
    	{{ __('acceuil.lancez_vous') }}
    </div>
    <div>
    	{{ __('acceuil.message_acceuil') }}
    </div>
    <div>
	    <div class="div-standard-post standard-post-acceuil"><a class="standard-post-button buttonSearchButton bandeau" href="javascript:">{{__('acceuil.je_cherche')}}</a></div>
	    <div class="div-standard-post standard-post-acceuil"><a class="standard-post-button post_an_ad" href="/publiez-annonce?Bandeau=oui">{{__('acceuil.publiez_annonce')}}</a></div>
    </div>
    <input type="hidden" id="isShownBandeau" value="false" name="">
    <input type="hidden" id="bandeau-closed" name="">
    <a href="javascript:" class="button-close-bandeau" class="btn">x</a>
    </div>
</div>

