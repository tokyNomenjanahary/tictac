@extends('layouts.adminappinner')

<script>
    var appSettings = {};
    @if(!empty($user->user_profiles) && !empty($user->user_profiles->profile_pic))
        filesize = {{filesize(storage_path('uploads/profile_pics/' . $user->user_profiles->profile_pic))}}
        appSettings['profile_pic'] = ["{{$user->user_profiles->profile_pic}}", filesize];
    @endif
</script>

@include('admin.user.user_profile_form')