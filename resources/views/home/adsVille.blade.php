@if(!empty($featured_room_mates_ville) && count($featured_room_mates_ville) > 0 )
<div id="featured_room_mates" class="collocataire recherchant clearfix">
    <div class="inner">
        <h3>
            {{ __("acceuil.offre_colocataire") }} {{$ville}}
        </h3>
        <div class="recherchant-holder list_coloc">
            @foreach($featured_room_mates_ville as $featured_room_mate)
            <a  href="{{ adUrl($featured_room_mate->id)  }}">
            <div itemscope itemtype="{{ adUrl($featured_room_mate->id)  }}" class="block-items publ-room-mate">
                <span itemprop="{{ __('acceuil.longement_title') }}" class="item-prop-hidden">{{$featured_room_mate->title}}</span>
                <img itemprop="Image" width="80" height="80" class="profile-image" @if(File::exists(storage_path('/uploads/profile_pics/' . $featured_room_mate->profile_pic)) && !empty($featured_room_mate->profile_pic))  src="{{'/uploads/profile_pics/' . $featured_room_mate->profile_pic}}" @else  src="/images/profile_avatar.jpeg" @endif>
                <div class="holder-big">
                    <div class="nom-age">
                        <span itemprop="Nom du colocataire"  class="nom">@if(!empty($featured_room_mate->first_name)){{getFirstWord($featured_room_mate->first_name)}}@endif</span>
                        <span itemprop="Age du colocataire" class="age">@if(!empty($featured_room_mate->birth_date) 
                        && !empty($featured_room_mate->birth_date)) {{Age(date('d-m-Y', strtotime($featured_room_mate->birth_date)))}}  {{__("acceuil.age_label")}} @endif</span>
                    </div>
                    <p itemprop="Description">@if(!empty($featured_room_mate) && !empty($featured_room_mate->description)){{substr($featured_room_mate->description, 0, 130)}} @if(strlen($featured_room_mate->description) >= 130)...@endif @endif</p>
                </div>
                <span itemprop="Date de publication" class="date-roommate"> {{translateDuration($featured_room_mate->created_at)}}</span>
            </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif
@if(!empty($featured_rooms_ville) && count($featured_rooms_ville) > 0 )
<div class="collocataire recherchant logements clearfix">
    <div class="inner">
        <h3>
            {{ __("acceuil.offre_colocation") }} {{$ville}}
        </h3>
        <div class="publication">
            @foreach($featured_rooms_ville as $featured_room)
                <a itemscope itemtype="{{ adUrl($featured_room->id)  }}" href="{{ adUrl($featured_room->id)  }}" class="publ">
                    <span itemprop="Prix" class="prix">
                    @if(!empty($featured_room) && !empty($featured_room->min_rent)) {{'&euro;'.$featured_room->min_rent}} @endif/ {{__("acceuil.mois")}}</span>
                    @if(!empty($featured_room->ad_files) && count($featured_room->ad_files) > 0)
                    <img itemprop="image" width="1024" height="683" class="ad-image active-custom-carousel" src="{{'/uploads/images_annonces/' . $featured_room->ad_files[0]->filename }}">
                    @else
                    <img itemprop="image" width="1024" height="683" class="ad-image active-custom-carousel" @if(!empty($featured_room->ad_files) && count($featured_room->ad_files) > 0)  src="{{'/uploads/images_annonces/' . $featured_room->ad_files[0]->filename }}" @else  src="/images/room_no_pic.png" @endif>
                    @endif
                    <div class="items-publ">
                        <img itemprop="Photo du propriétaire"  width="80" height="80"class="profile-image" @if(File::exists(storage_path('/uploads/profile_pics/' . $featured_room->profile_pic)) && !empty($featured_room->profile_pic))  src="{{'/uploads/profile_pics/' . $featured_room->profile_pic}}" @else  src="/images/profile_avatar.jpeg"  @endif>
                        <h5 itemprop="Adresse">@if(!empty($featured_room) && !empty($featured_room->address)) {{$featured_room->address}} @endif</h5>
                        <span itemprop="Titre de la publication" class="logement">@if(!empty($featured_room) && !empty($featured_room->title)) {{$featured_room->title}} @endif</span>
                        <p itemprop="Description" > @if(!empty($featured_room) && !empty($featured_room->description)){{substr($featured_room->description, 0, 130)}} @if(strlen($featured_room->description) > 130)...@endif @endif</p>
                        <span itemprop="Date de publication" class="date"> {{translateDuration($featured_room->updated_at)}}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif