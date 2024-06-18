
@if(!empty($featured_room_mates) && count($featured_room_mates) > 0 )
<div id="featured_room_mates" class="collocataire recherchant clearfix">
    <div class="inner">
        <h3>
            {{ __("acceuil.coloc_searcher") }} @if(isset($ville)) {{__("acceuil.a_ville", ["ville" => $ville])}} @endif
        </h3>
        <div class="recherchant-holder list_coloc">
            @foreach($featured_room_mates as $featured_room_mate)
            <a  href="{{ adUrl($featured_room_mate->id)  }}">
            <div itemscope itemtype="{{ adUrl($featured_room_mate->id)  }}" class="block-items publ-room-mate">
                <span itemprop="Titre du logement" class="item-prop-hidden">{{$featured_room_mate->title}}</span>
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