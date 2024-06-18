@extends('proprietaire.index')

@section('contenue')
    <div class="container">
        <div class="card mt-5">
            <h4 class="card-title" style="padding: 10px;background-color: #F2F5F6;">Notification</h4>
            <div class="card-body">

                @if ($ticket_non_lus > 0)
                    <div class="row">
                        <div class="alert  m-b-0 m-l-10 m-r-10"
                            style="background-color: #F2DEDE; border-left: 4px solid rgb(58,135,173);">
                            <span class="label m-r-2"
                                style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">{{ __('ticket.nouveau_ticket')}}</span>
                            <p style="margin-top:10px;font-size:12px;">

                                {{ str_replace('x_ticket',count($ticket_non_lus),__('texte_global.ticket_non_lu')) }}
                                <a  href="{{ route('ticket.index') }}">...{{ __('texte_global.voir_ticket')}}</a>
                            </p>
                          
                        </div>
                    </div>
                @endif
                @if (count($loyerEnretard) > 0)
                    <div class="row">
                        <div class="alert  m-b-0 m-l-10 m-r-10"
                            style="background-color: #F2DEDE; border-left: 4px solid rgb(58,135,173);">
                            <span class="label m-r-2"
                                style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">{{ __('texte_global.loyer_en_retard')}}
                                </span>
                            <p style="margin-top:10px;font-size:12px;">
                               {{ __('texte_global.loyer_non_paye')}}<a  href="{{ route('proprietaire.finance') }}">...{{ __('texte_global.voir_detail')}}</a>
                            </p>
                            <ul>
                                @foreach ($loyerEnretard->unique('identifiant') as $item)
                                    <li>{{ $item->Location->identifiant }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @if (count($revenue_en_attente) > 0)
                    <div class="row">
                        <div class="alert  m-b-0 m-l-10 m-r-10"
                            style="background-color: #F2DEDE; border-left: 4px solid rgb(58,135,173);">
                            <span class="label m-r-2"
                                style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">{{ __('texte_global.revenu_entete_attente') }}</span>
                            <p style="margin-top:10px;font-size:12px;">
                                {{ str_replace('x_revenu',count($revenue_en_attente),__('texte_global.revenu_attente')) }}
                                <a  href="{{ route('proprietaire.finance') }}">...{{ __('texte_global.voir_detail')}}</a>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
