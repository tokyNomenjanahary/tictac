@extends('proprietaire.index')
@section('contenue')
<style>
        .rating {
            font-size: 3rem;
            cursor: pointer;
        }

        .star {
            display: inline-block;
            margin-right: 5px;
            color: gray;
        }

        .star.selected {
            color: orange; /* Couleur des étoiles sélectionnées */
        }
</style>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
      <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="col-12 col-lg-4 col-sm-12 col-md-3 mbr-3 btn-new-state btn-new-state-lg">
                <h3 class="page-header page-header-top m-0">Notes et avis laissés</h3>
            </div>
        </div>  
        <div class="card p-3">
          <div>
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Locataire</th>
                    <th scope="col">Location</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($ratings as $rating)  
                    <tr>
                        <td>{{$rating->locataire->first_name}}</td>
                        <th scope="row">{{$rating->location->identifiant}}</th>
                        <td>{{$rating->Note .'/5'}}</td>
                        <td>
                            <div class="dropdown handle-event">
                                <button class="btn p-0 dropdown-toggle hide-arrow stop-prop mx-4 my-2" data-bs-toggle="dropdown">
                                  <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                  <a class="dropdown-item show-rating-detail" data-rating="{{$rating->Note}}"
                                    data-review="{{$rating->Avis}}"
                                    data-rating-id="{{$rating->id}}"
                                    ><i class="fa fa-pencil me-1"></i> Modifier</a>
                                </div>
                            </div>    
                        </td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
        </div>
      </div>
</div>

<div class="modal fade" id="EditNote" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #F5F5F9">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <form action="{{route('note.update')}}" method="post">
                    @csrf
                    @method('put')
                    <input type="hidden" name="rating_id" id="rating_id">
                    <div class="modal-body">
                        <div class="row" >
                            <div class="col-md-2 col-sm-10 mt-4 ">
                                {{__('location.note')}}
                            </div>
                            <div class="col-md-10 col-sm-10">
                                <input hidden type="text" name="note_star" id="note_star" value="0">
                                <div class="rating">
                                    <span class="star" data-value="1">&#9733;</span>
                                    <span class="star" data-value="2">&#9733;</span>
                                    <span class="star" data-value="3">&#9733;</span>
                                    <span class="star" data-value="4">&#9733;</span>
                                    <span class="star" data-value="5">&#9733;</span>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-10 mt-4 ">
                                {{__('location.avis')}}
                            </div>
                            <div class="col-md-10 col-sm-10">
                                <textarea required name="avis" id="review" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary" id="save_note">Mettre a jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    
<script>
    $(document).ready(function () {
        $('.star').click(function() {
            var ratingValue = $(this).data('value');
            $('#note_star').val(ratingValue);
            $('.star').removeClass('selected');
            $(this).prevAll('.star').addBack().addClass('selected');
        });

        $('.show-rating-detail').click(function (e) {
            e.preventDefault()
            $('.rating span').removeClass('selected');
            let rate = $(this).attr('data-rating')
            let avis = $(this).attr('data-review')
            let id =  $(this).attr('data-rating-id')
            let premiersSpans = $('.rating span').slice(0, rate);
            $('#note_star').val(rate);
            $('#review').val(avis)
            $('#rating_id').val(id)
            premiersSpans.addClass('selected');
            $('#EditNote').modal('show');
        })
    })
</script>

@endpush