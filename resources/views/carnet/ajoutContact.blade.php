<?php
if (!isTenant()) {
    $entete = 'proprietaire.index';
    $contenue = 'contenue';
}else{
    $entete = 'espace_locataire.index';
    $contenue = 'locataire-contenue';
}
?>

@extends($entete)
@section($contenue)
    <div class="container mt-3">
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ajouter une contact</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST"
                            @if (isset($contact)) action="{{ route('carnet.saveContact', $contact->id) }}" @else action="{{ route('carnet.saveConctact') }}" @endif>
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">CATEGORIE</label>
                                        <select name="categorie" id="civilite" class="form-select">
                                            @foreach ($categorieContact as $categorie)
                                                <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <label class="form-label" for="basic-default-fullname">Full Name</label>
                                    <input type="text" class="form-control" id="basic-default-fullname" placeholder="John Doe"> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label for="" class="form-label">TYPE</label>
                                    <select name="type" id="civilite" class="form-select">
                                        <option value="0">Particulier</option>
                                        <option value="1">Société/autre</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label for="" class="form-label">NOM <span
                                            style="color: #dc3545;">*</span></label>
                                    <input type="text" name="name" class="form-control control" placeholder=""
                                        aria-describedby="helpId"
                                        @if (isset($contact->name)) value="{{ $contact->name }}" @endif>
                                    @error('name')
                                        <span style="color: #dc3545;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label for="" class="form-label">PRÉNOM</label>
                                    <input type="text" name="first_name" class="form-control" placeholder=""
                                        aria-describedby="helpId"
                                        @if (isset($contact->first_name)) value="{{ $contact->first_name }}" @endif>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label for="" class="form-label">E-MAIL <span
                                            style="color: #dc3545;">*</span></label>
                                    <input type="email" name="email" class="form-control control" placeholder=""
                                        aria-describedby="helpId"
                                        @if (isset($contact->email)) value="{{ $contact->email }}" @endif>
                                    @error('email')
                                        <span style="color: #dc3545;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label for="" class="form-label">MOBILE <span
                                            style="color: #dc3545;">*</span></label>
                                    <input type="number" name="mobile" class="form-control control" placeholder=""
                                        aria-describedby="helpId"
                                        @if (isset($contact->mobile)) value="{{ $contact->mobile }}" @endif>
                                    @error('mobile')
                                        <span style="color: #dc3545;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label for="" class="form-label">ADRESSE <span
                                            style="color: #dc3545;">*</span></label>
                                    <input type="text" name="adress" class="form-control control" placeholder=""
                                        aria-describedby="helpId"
                                        @if (isset($contact->adress)) value="{{ $contact->adress }}" @endif>
                                    @error('adress')
                                        <span style="color: #dc3545;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label for="" class="form-label">VILLE</label>
                                    <input type="text" name="ville" class="form-control control" placeholder=""
                                        aria-describedby="helpId"
                                        @if (isset($contact->ville)) value="{{ $contact->ville }}" @endif>
                                    <span style="color: #dc3545;"></span>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label for="" class="form-label">CODE POSTAL</label>
                                    <input type="mail" name="code_postal" class="form-control" placeholder=""
                                        aria-describedby="helpId"
                                        @if (isset($contact->code_postal)) value="{{ $contact->code_postal }}" @endif>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label for="" class="form-label">PAYS</label>
                                    <input type="text" name="pays" class="form-control" placeholder=""
                                        aria-describedby="helpId"
                                        @if (isset($contact->pays)) value="{{ $contact->pays }}" @endif>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">COMENTAIRE</label>
                                        <textarea id="basic-default-message" name="comment" class="form-control"
                                            placeholder="Hi, Do you have a moment to talk Joe?">
                                            @if (isset($contact->comment))
                                            {{ $contact->comment }}
                                            @endif
                                            </textarea>
                                    </div>
                                </div>
                                <div class="card mt-2" style="margin-top: 5px;box-shadow:none ">
                                    <div class="card-header"
                                        style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                                        Partage
                                    </div>
                                    <div class="card-body mt-3 ">
                                        <div class="row" >
                                            <div class="col-md-2 text-md-end">
                                                <label for="" style="margin-top:8px;">PARTAGE</label>
                                            </div>
                                            <div class="col-md-4 col-sm-10 mt-1" >
                                                <input type="checkbox" @if (isset( $contact->partage ) && $contact->partage=='oui') @checked(true) @endif name="partage" {{ old('partage') ? 'checked' : '' }}>
                                                <p style="margin-top: 5px;">Partager le document avec votre locataire</p>
                                            </div>
                                        </div>
                                    </div>
                
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Sauvegarder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('input[type="number"]').on('input', function() {
                var value = parseFloat($(this).val());
                if (value < 0) {
                    $(this).addClass('is-invalid');
                    $(this).next('.error-message').remove();
                    $(this).after(
                        '<span class="error-message text-danger" style="font-size:10px;">Veuillez saisir un nombre positif</span>'
                    );
                } else if (value === '') {
                    value = 0;
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.error-message').remove();
                }
            });
        });
    </script>
@endpush
 