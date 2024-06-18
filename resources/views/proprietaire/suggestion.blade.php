<div class="row">
    <form action="{{route('proprietaire.suggestion')}}" method="post">
        @csrf
        <div class="card" style="margin-top: 30px">
            <p style="color:black;padding:10px;margin-top:20px;border-radius:0px;font-size:25px;line-height: 22px;">
                {{ __('finance.ameliorer') }}</p>
            <div class="card-body">
                <div>
                    <label for="" class="form-label">{{ __('finance.note') }}</label>
                    <textarea class="form-control" resize="none" cols="20" rows="5" placeholder={{ __('finance.suggestion') }} name="suggestion" required></textarea>
                </div>
                <br>
                <button class="btn btn-primary" type="submit">{{ __('finance.envoyer_vos_suggestions') }}</button>
            </div>
        </div>
    </form>
</div>
