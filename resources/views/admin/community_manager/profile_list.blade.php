<option value="">Profiles</option>
@foreach($profiles as $key => $profile)
<option value="{{$profile->id}}" @if($key == 0) selected @endif>{{$profile->login}}</option>
@endforeach