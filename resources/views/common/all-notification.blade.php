<!-- Modal -->
<div class="modal fade" id="alertModalAllNotification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content-notif">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">{{__('notification.modal_notif_title')}}
                 </h4>

            </div>
            <div class="modal-body">
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li id="liMessageTab" role="presentation" class="active tabNotif"><a href="#messageTab" aria-controls="messageTab" role="tab" data-toggle="tab"><img id="img-foudre" width="30" height="20" src="/img/icons/contacter-blue.png">
                        <!-- <span class="message-title tab-title-notif">{{__('messages.messages')}}</span> -->
                        </a>
                        <span id="count-glyph-all-message" class="count-glyph-all-notif count-glyph-all-notif-message">1</span>
                        </li>
                        <li id="liFoudreTab" role="presentation"><a href="#foudreTab" aria-controls="foudreTab" role="tab" data-toggle="tab">
                         <img id="img-foudre" width="30" height="20" src="/img/icons/toctoc-blue.png"><!-- <span class="tab-title-notif ">{{__('notif.coup_de_foudre_title')}} </span> -->
                        </a>
                        <span id="count-glyph-coup-de-foudre" class="count-glyph-all-notif count-glyph-all-notif-message">1</span>
                        </li>
                        <li id="liVisitRequestTab" role="presentation"><a href="#visitTab" aria-controls="visitTab" role="tab" data-toggle="tab">
                        <i class="glyph-image glyphicon glyphicon-calendar" aria-hidden="true"></i>
                        <!-- <span class="message-title tab-title-notif">{{__('notification.demande_visite')}}</span> -->
                        </a>
                        <span id="count-glyph-visit-request" class="count-glyph-all-notif count-glyph-all-notif-message">1</span>
                        </li>
                        
                        <li id="liCandidatureTab" role="presentation"><a href="#CandidatureTab" aria-controls="CandidatureTab" role="tab" data-toggle="tab">
                         <img id="img-foudre" width="30" height="20" src="/img/list-candidature-annonce.png"><!-- <span class="tab-title-notif ">{{__('notif.coup_de_foudre_title')}} </span> -->
                        </a>
                        <span id="count-glyph-candidature" class="count-glyph-all-notif count-glyph-all-notif-message">1</span>
                        </li>

                        <li id="liCommentTab" role="presentation"><a href="#CommentTab" aria-controls="CommentTab" role="tab" data-toggle="tab">
                         <img id="img-foudre" width="30" height="20" src="/img/icon-comment.png"><!-- <span class="tab-title-notif ">{{__('notif.coup_de_foudre_title')}} </span> -->
                        </a>
                        <span id="count-glyph-comment" class="count-glyph-all-notif count-glyph-all-notif-message">1</span>
                        </li>

                        
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active custom-tab-pane" id="messageTab">
                            
                        </div>
                        <div role="tabpanel" class="tab-pane custom-tab-pane" id="foudreTab">
                            
                        </div>
                        <div role="tabpanel" class="tab-pane custom-tab-pane" id="visitTab">
                            
                        </div>
                        <div role="tabpanel" class="tab-pane custom-tab-pane" id="CandidatureTab">
                            
                        </div>

                        <div role="tabpanel" class="tab-pane custom-tab-pane" id="CommentTab">
                            
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stack('custom-scripts')
{{-- <script src="/js/all-notif.min.js"></script> --}}

<script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650702466/Bailti/js/all-notif.min_mel9yt.js"></script>