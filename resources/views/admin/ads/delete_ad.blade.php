<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Supprimer annonce?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary contact_annonceur"  @if(isset($ad)) data-id="{{$ad->id}}" data-type="{{route('delete-ad-comunity', [$ad->id])}}" @endif id="modal-delete-btn-yes">Oui</button>
                <button type="button" data-dismiss="modal" class="btn btn-default" id="modal-delete-btn-no">Non</button>
            </div>
        </div>
    </div>
</div>