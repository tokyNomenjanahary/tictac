<?php
if (!isTenant()) {
    $entete = 'proprietaire.index';
    $contenue = 'contenue';
} else {
    $entete = 'espace_locataire.index';
    $contenue = 'locataire-contenue';
}
?>

@extends($entete)

@push('style')
    <style>
        #chat3 .form-control {
            border-color: transparent;
        }

        #chat3 .form-control:focus {
            border-color: transparent;
            box-shadow: inset 0px 0px 0px 1px transparent;
        }

        .badge-dot {
            border-radius: 50%;
            height: 10px;
            width: 10px;
            margin-left: 2.9rem;
            margin-top: -.75rem;
        }

        .ellipsis {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endpush

@section($contenue)
    <div class="content-wrapper">
        <section style="">
            <div class="container py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card" id="chat3" style="border-radius: 15px;">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col">
                                        <form method="POST"  id="data_message" action="{{ route('espaceMessage.saveNewMessage') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="sujet" class="form-label">Sujet</label>
                                                <input required type="text" name="sujet" class="form-control" id="sujet" value="{{ old('sujet') }}" placeholder="Sujet de discution">
                                            </div>
                                            <div class="mb-3">
                                                <label for="user_receiver" class="form-label">Selectionné le déstinateur</label>
                                                <select required id="user_receiver" name="id_user_receiver" class="form-select">
                                                    <option>Default select</option>
                                                    @if (!isTenant())
                                                        @foreach ($data_user_receiver as $user_receiver)
                                                            <option value="{{$user_receiver->user_account_id}}">{{$user_receiver->TenantFirstName}} {{$user_receiver->TenantLastName}}</option>
                                                        @endforeach
                                                    @else
                                                        @foreach ($data_user_receiver as $user_receiver)
                                                            <option value="{{$user_receiver->user_id}}">{{$user_receiver->first_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div>
                                                <label for="message" class="form-label">Message</label>
                                                <textarea required name="message" class="form-control" id="message" rows="1">{{ old('message') }}</textarea>
                                              </div>
                                            <div class="d-flex justify-content-center align-items-center pt-3 mt-2">
                                                <button type="button" class="btn rounded-pill btn-dark ml-3" style="margin-right: 2rem">Annuler</button>
                                                <button type="submit" class="btn rounded-pill btn-primary">Envoyer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>
    @push('js')
        <script>
        </script>
    @endpush
@endsection
