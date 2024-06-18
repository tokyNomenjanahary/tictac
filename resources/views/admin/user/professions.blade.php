@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
<!--    <link href="{{ asset('css/admin/datatables.net-bs/dataTables.bootstrap.min.css') }}" rel="stylesheet">-->
@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
<!--    <script src="{{ asset('js/admin/datatables.net/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/admin/datatables.net-bs/dataTables.bootstrap.min.js') }}"></script>-->

<script src="{{ asset('js/admin/manageusers.js') }}"></script>
<script src="{{ asset('js/admin/manageads.js') }}"></script>
<script src="/js/metier_autocompletion.js"></script>
<script src="/js/school_autocomplete.js"></script>
<script src="/js/easyautocomplete/jquery.easy-autocomplete.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.reload-page').on('click', function(){
        document.location.reload();
    });
});
</script>
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Professions & School
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        @if ($message = Session::get('error'))
        <div class="alert alert-danger fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $message }}
        </div>

        @endif
        @if ($message = Session::get('status'))
        <div class="alert alert-success fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $message }}
        </div>
        @endif
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Manage Professions & Schools</h3>
                        
                        <!-- <div class="box-tools">
                            <div clas="col-md-6">
                            <form id="searchForm" method="GET">
                                <input type="hidden" id="treaty" name="treaty" value="0">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search_name" value="" class="form-control pull-right" placeholder="Search">
                                           <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>

                                </div>
                                
                                
                            </form>
                            </div>
                        </div> -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>User</th>
                                <th>Catégorie Pro.</th>
                                <th>
                                    Profession
                                </th>
                                <th>Ecole</th>
                                <th>Date d'inscription</th>
                                <th>Revenus</th>
                                <th>Alcool</th>
                                <th>Fumeur</th>
                                <th>Gay</th>
                                <th>Type de sorties</th>
                                <th>Sports</th>
                                <th>Type de musics</th>
                                <th>Scenarios</th>
                                <th>Adresse</th>
                            </tr>
                            @if(!empty($users))
                            @foreach($users as $key => $user)
                            <tr>
                                <td>{{$user->first_name}} {{$user->last_name}}</td>
                                <td>
                                @if(!is_null($user->user_profiles) && !is_null($user->user_profiles->professional_category))
                                {{__('profile.situationPro' . $user->user_profiles->professional_category)}}
                                @endif
                                </td>
                                <td>
                                @if(!is_null($user->user_profiles)) 
                                {{$user->user_profiles->profession}} 
                                @if(!empty($user->user_profiles->profession) && !isExistProfession($user->user_profiles->profession))
                                <a title="Modifier" class="action-toggle-on edit-prof" href="javascript:" data-id="{{$user->id}}"><i class="fa fa-pencil"></i></a>
                                <a title="Valider" class="action-toggle-on active-prof" href="{{route('admin.active_data')}}?type=profession&id={{$user->id}}"><i class="fa fa-check"></i></a>
                                @endif
                                @endif
                                </td>
                                <td>
                                @if(!is_null($user->user_profiles)) 
                                {{$user->user_profiles->school}}
                                @if(!empty($user->user_profiles->school) && !isExistSchool($user->user_profiles->school))
                                <a title="Modifier" class="action-toggle-on edit-school" href="javascript:" data-id="{{$user->id}}"><i class="fa fa-pencil"></i></a>
                                <a title="Valider" class="action-toggle-on active-school" href="{{route('admin.active_data')}}?type=school&id={{$user->id}}"><i class="fa fa-check"></i></a>
                                @endif 
                                @endif
                                </td>
                                <td>
                                {{adjust_gmt($user->created_at)}}
                                </td>
                                <td>
                                @if(!is_null($user->user_profiles))
                                {{$user->user_profiles->revenus}}
                                @endif
                                </td>
                                <td>
                                @if(!is_null($user->user_profiles) && !is_null($user->user_profiles->alcool))
                                @if($user->user_profiles->alcool == 0)
                                Oui
                                @else 
                                Non
                                @endif
                                @endif
                                </td>
                                <td>
                                @if(!is_null($user->user_profiles) && !is_null($user->user_profiles->smoker))
                                @if($user->user_profiles->smoker == 0)
                                Oui
                                @else 
                                Non
                                @endif
                                @endif
                                </td>
                                <td>
                                @if(!is_null($user->user_profiles) && !is_null($user->user_profiles->gay))
                                @if($user->user_profiles->gay == 0)
                                Oui
                                @else 
                                Non
                                @endif
                                @endif
                                </td>
                                <td>{{getTypeDeSorties($user->id)}}</td>
                                <td>{{getSports($user->id)}}</td>
                                <td>{{getTypeMusics($user->id)}}</td>
                                <td>{{getScenario($user->scenario_register)}}</td>
                                <td>{{$user->address_register}}</td>

                                
                            </tr>
                            @endforeach
                            @else
                            <tr>{{'No record found'}}</tr>
                            @endif
                        </table>
                    </div>
                    <div class="pull-right">
                        @if($users) 
                        {{ $users->links('vendor.pagination.bootstrap-4') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div> 

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete ad?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modal-delete-btn-yes">Yes</button>
                <button type="button" class="btn btn-default" id="modal-delete-btn-no">No</button>
            </div>
        </div>
    </div>
</div> 


<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modify-profession-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modifier ?</h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="formModifProfession" enctype="multipart/form-data" action="{{ route('modify-profession') }}">
                {{ csrf_field() }}
                    <div class="div-form-modal">
                       <div class="row">
                           <div class="col-md-12 col-xs-12">
                                <input type="text" style="width: 100%;" name="profession" id="profession">
                                <input type="hidden" id="profession_user_id" name="user_id">
                           </div>
                       </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="modal-save-profession">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modify-school-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modifier ?</h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="formModifSchool" enctype="multipart/form-data" action="{{ route('modify-school') }}">
                {{ csrf_field() }}
                    <div class="div-form-modal">
                       <div class="row">
                           <div class="col-md-12 col-xs-12">
                                <input type="text" style="width: 100%;" name="school" id="school">
                                <input type="hidden" id="school_user_id" name="user_id">
                           </div>
                       </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="modal-save-school">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .div-form-modal
    {
        margin-bottom: 15px;
    }

    #autre-reason
    {
        display: none;
    }

    .easy-autocomplete.eac-square
    {
        width: 90% !important;
    }

    .easy-autocomplete
    {
        position: relative;
    }

    .easy-autocomplete-container
    {
        width: 100% !important;
        box-shadow: 0 10px 30px 0 rgba(50,50,50,.35);
        z-index: 99999;
    }

    .easy-autocomplete-container li
    {
        padding: 5px;
        color: black;
        background-color: white;
        border-bottom: solid #d2d1d1 0.1px;
        cursor: pointer;
        list-style: none;
    }

    .easy-autocomplete-container li:hover
    {
        background-color: rgb(239,239,239);
    }

</style>
@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('.edit-prof').on('click', function() {
            var id=$(this).attr('data-id');
            $('#profession_user_id').val(id);
            $('#modify-profession-modal').modal('show');
            
        });

        $('#modal-save-profession').on('click', function() {
            $('#formModifProfession').submit();
        });

        $('.edit-school').on('click', function() {
            var id=$(this).attr('data-id');
            $('#school_user_id').val(id);
            $('#modify-school-modal').modal('show');
            
        });

        $('#modal-save-school').on('click', function() {
            $('#formModifSchool').submit();
        });
    });
</script>   
@endpush
@endsection
