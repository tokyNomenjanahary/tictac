<ul class="nav nav-tabs d-none" role="tablist">
    <li class="nav-item">
        <button type="button" class="nav-link" id="revenuFoncierTab" role="tab" data-bs-toggle="tab" data-bs-target="#revenuFoncier" aria-controls="revenuFoncier" aria-selected="false">

        </button>
    </li>
    <li class="nav-item">
        <button type="button" class="nav-link" id="LmnpReelTab" role="tab" data-bs-toggle="tab" data-bs-target="#LmnpReel" aria-controls="LmnpReel" aria-selected="false">

        </button>
    </li>
    <li class="nav-item">
        <button type="button" class="nav-link" id="MicroBicTab" role="tab" data-bs-toggle="tab" data-bs-target="#MicroBic" aria-controls="MicroBic" aria-selected="false">
        </button>
    </li>
    <li class="nav-item">
        <button type="button" class="nav-link" id="MicroFoncierTab" role="tab" data-bs-toggle="tab" data-bs-target="#MicroFoncier" aria-controls="MicroFoncier" aria-selected="false">

        </button>
    </li>
</ul>
<div class="tab-content p-0">
    <div class="tab-pane fade" id="revenuFoncier" role="tabpanel">
        <table class="table table-hover table-bordered" id="bilan">
            <tbody>
                <tr>
                    <th style="cursor:default;">Recettes Année Fiscale <span class="annee" >{{ now()->year }}</span> </th>
                    <th style="cursor:default;">Montant</th>
                </tr>
                <tr>
                    <td><strong>Recettes</strong> : Loyers bruts encaissés (sans charges locatives)
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="La somme des encaissements des loyers nus (sans les provisions pour charges locatives) encaissés pendant l'année. Si vous êtes assujetti à la TVA, les recettes doivent être déclarées pour leur montant hors TVA."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td class="sommeHC">{{$sommeHC}} €</td>
                </tr>
                <tr>
                    <td><strong>Recettes</strong> : Autres
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Autre revenu taxable (pris en compte dans l'aide à la déclaration)"><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td>TOTAL RECETTES</td>
                    <td><strong class="sommeHC">{{$sommeHC}} €</strong></td>
                </tr>
                <tr>
                    <th style="cursor:default;">Charges Année Fiscale  <span class="annee" >{{ now()->year }}</span> </th>
                    <th style="cursor:default;">Montant</th>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Frais de gestion et d’administration
                        (rémunérations des gardes et concierges inclus)
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="La somme des frais de gestion et d'administration payés pendant l'année (rémunérations des gardes et concierges, rémunérations, honoraires et commissions versés à un tiers, frais de procédure...). "><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Autres frais de gestion (montant fixé par
                        l'administration fiscale à 20 € par local)
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Ces frais de gestion sont déductibles pour un montant forfaitaire de 20 € par local."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>20.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Assurance
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="L’ensemble des primes d’assurance que vous avez souscrites sont déductibles y compris les primes d’assurance souscrites dans le cadre d’un contrat de groupe."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Réparation, entretien et amélioration
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Les dépenses de réparation, d'entretien ou d'amélioration acquittées par vos soins au cours de l'année sont déductibles, à l'exclusion des dépenses correspondant à des travaux de construction, de reconstruction ou d'agrandissement."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Charges récupérables non récupérées au départ du
                        locataire
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Il s'agit des dépenses incombant normalement au locataire, que vous avez payées pour son compte (frais de chauffage ou d'éclairage, entretien des ascenseurs, taxes de balayage et d'enlèvement des ordures ménagères, location de compteur, etc.) et dont vous n'avez pu obtenir le remboursement au 31 décembre de l'année de son départ. Ces charges peuvent avoir été engagées au titre de l'année de départ du locataire comme au titre des autres années depuis son entrée dans le logement."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Frais d’éviction et de relogement
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="L'indemnité d'éviction versée par le propriétaire est déductible lorsqu'elle a pour objet de libérer les locaux en vue de les relouer dans de meilleures conditions."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Taxes et impôts
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Les impôts perçus au profit des collectivités locales et d'organismes divers sur les propriétés dont les revenus sont déclarés. La taxe d'enlèvement des ordures ménagères n'est pas déductible des revenus fonciers. En effet, il s'agit d'une charge récupérable par les bailleurs auprès de leurs locataires."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Intérêts d’emprunt (assurance crédit incluse) <a
                            href="#bilan" target="_blank"><i class="icon-info hidden-phone" data-toggle="tooltip"
                                title=""
                                data-original-title="Il s’agit des intérêts payés pendant l’année au titre des emprunts contractés pour l’acquisition, la conservation, la (re)construction, l’agrandissement, la réparation ou l’amélioration des immeubles donnés en location. Le remboursement du principal ne doit pas être inclus."></i></a>

                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Amortissements
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Autres
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="A utiliser pour les cas particuliers (déductions spécifiques, particularités SCI...), puis à reporter dans la ou les lignes ad'hoc de la déclaration.
                    "><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Autres</td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Syndic : Provisions pour charge
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Si vous êtes copropriétaires bailleurs, indiquez le montant des provisions pour charges de copropriété que vous avez versées pendant l'année à votre syndic de copropriété. Seules les provisions pour dépenses sont déductibles."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Syndic : Provisions pour charge exceptionnelles</td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Syndic de copropriété : Régularisation des provisions pour charges
                        déduites au titre de 2022
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Si vous êtes copropriétaires bailleurs, la régularisation des provisions pour charges de copropriété que vous avez déduites au titre de l'année passée = ( &quot;Syndic de copropriété : Provisions pour charge&quot; versée de l'année passée ) - ( &quot;Syndic de copropriété : Arrêté des comptes : Charges déductibles&quot; de l'année passée)."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td>TOTAL DES FRAIS ET CHARGES</td>
                    <td><strong>20.00 €</strong></td>
                </tr>
                <tr>
                    <td><strong>REVENUS FONCIERS TAXABLES <span class="annee" >{{ now()->year }}</span></strong></td>
                    <td><strong class="somme-avec">{{$sommeAvec}} €</strong></td>
                </tr>
            </tbody>

        </table>
    </div>
    <div class="tab-pane fade" id="LmnpReel" role="tabpanel">
        <table class="table table-hover table-bordered">
            <tbody>
                <tr>
                    <th style="cursor:default;">Recettes Année Fiscale <span class="annee" >{{ now()->year }}</span> </th>
                    <th style="cursor:default;">Montant</th>
                </tr>
                <tr>
                    <td><strong>Recettes</strong> : Loyers bruts encaissés (charges comprises)
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="La somme des encaissements des loyers nus (sans les provisions pour charges locatives) encaissés pendant l'année. Si vous êtes assujetti à la TVA, les recettes doivent être déclarées pour leur montant hors TVA."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td class="totals">{{$totals}} €</td>
                </tr>
                <tr>
                    <td><strong>Recettes</strong> : Autres
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Autre revenu taxable (pris en compte dans l'aide à la déclaration)"><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td>TOTAL RECETTES</td>
                    <td><strong class="totals">{{$totals}} €</strong></td>
                </tr>
                <tr>
                    <th style="cursor:default;">Charges Année Fiscale <span class="annee" >{{ now()->year }}</span> </th>
                    <th style="cursor:default;">Montant</th>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Frais de gestion et d’administration (rémunérations des gardes et concierges inclus)
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="La somme des frais de gestion et d'administration payés pendant l'année (rémunérations des gardes et concierges, rémunérations, honoraires et commissions versés à un tiers, frais de procédure...). "><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Assurance
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="L’ensemble des primes d’assurance que vous avez souscrites sont déductibles y compris les primes d’assurance souscrites dans le cadre d’un contrat de groupe."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Réparation, entretien et amélioration
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Les dépenses de réparation, d'entretien ou d'amélioration acquittées par vos soins au cours de l'année sont déductibles, à l'exclusion des dépenses correspondant à des travaux de construction, de reconstruction ou d'agrandissement."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Charges récupérables non récupérées au départ du
                        locataire
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Il s'agit des dépenses incombant normalement au locataire, que vous avez payées pour son compte (frais de chauffage ou d'éclairage, entretien des ascenseurs, taxes de balayage et d'enlèvement des ordures ménagères, location de compteur, etc.) et dont vous n'avez pu obtenir le remboursement au 31 décembre de l'année de son départ. Ces charges peuvent avoir été engagées au titre de l'année de départ du locataire comme au titre des autres années depuis son entrée dans le logement."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Frais d’éviction et de relogement
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="L'indemnité d'éviction versée par le propriétaire est déductible lorsqu'elle a pour objet de libérer les locaux en vue de les relouer dans de meilleures conditions."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Taxes et impôts
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Les impôts perçus au profit des collectivités locales et d'organismes divers sur les propriétés dont les revenus sont déclarés. La taxe d'enlèvement des ordures ménagères n'est pas déductible des revenus fonciers. En effet, il s'agit d'une charge récupérable par les bailleurs auprès de leurs locataires."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Intérêts d’emprunt (assurance crédit incluse) <a
                            href="#bilan" target="_blank"><i class="icon-info hidden-phone" data-toggle="tooltip"
                                title=""
                                data-original-title="Il s’agit des intérêts payés pendant l’année au titre des emprunts contractés pour l’acquisition, la conservation, la (re)construction, l’agrandissement, la réparation ou l’amélioration des immeubles donnés en location. Le remboursement du principal ne doit pas être inclus."></i></a>

                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Amortissements
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Charge déductible : Autres
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="A utiliser pour les cas particuliers (déductions spécifiques, particularités SCI...), puis à reporter dans la ou les lignes ad'hoc de la déclaration.
                    "><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Autres</td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Syndic : Provisions pour charge
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Si vous êtes copropriétaires bailleurs, indiquez le montant des provisions pour charges de copropriété que vous avez versées pendant l'année à votre syndic de copropriété. Seules les provisions pour dépenses sont déductibles."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Syndic : Provisions pour charge exceptionnelles</td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td><strong>Frais et Charges</strong> : Syndic de copropriété : Régularisation des provisions pour charges
                        déduites au titre de 2022
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Si vous êtes copropriétaires bailleurs, la régularisation des provisions pour charges de copropriété que vous avez déduites au titre de l'année passée = ( &quot;Syndic de copropriété : Provisions pour charge&quot; versée de l'année passée ) - ( &quot;Syndic de copropriété : Arrêté des comptes : Charges déductibles&quot; de l'année passée)."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td>TOTAL DES FRAIS ET CHARGES</td>
                    <td><strong>00.00 €</strong></td>
                </tr>
                <tr>
                    <td><strong>REVENUS FONCIERS TAXABLES <span class="annee" >{{ now()->year }}</span></strong></td>
                    <td><strong class="totals">{{$totals}} €</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="MicroBic" role="tabpanel">
        <table class="table table-hover table-bordered">
            <tbody>
                <tr>
                    <th style="cursor:default;">Recettes Année Fiscale <span class="annee" >{{ now()->year }}</span> </th>
                    <th style="cursor:default;">Montant</th>
                </tr>
                <tr>
                    <td><strong>Recettes</strong> : Loyers bruts encaissés (charges comprises)
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="La somme des encaissements des loyers nus (sans les provisions pour charges locatives) encaissés pendant l'année. Si vous êtes assujetti à la TVA, les recettes doivent être déclarées pour leur montant hors TVA."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td class="totals">{{$totals}} €</td>
                </tr>
                <tr>
                    <td><strong>Recettes</strong> : Autres
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Autre revenu taxable (pris en compte dans l'aide à la déclaration)"><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td>TOTAL RECETTES</td>
                    <td><strong class="totals">{{$totals}} €</strong></td>
                </tr>
                <tr>
                    <td><strong>REVENUS FONCIERS TAXABLES <span class="annee" >{{ now()->year }}</span></strong></td>
                    <td><strong class="totals">{{$totals}} €</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="MicroFoncier" role="tabpanel">
        <table class="table table-hover table-bordered">
            <tbody>
                <tr>
                    <th style="cursor:default;">Recettes Année Fiscale <span class="annee" >{{ now()->year }}</span> </th>
                    <th style="cursor:default;">Montant</th>
                </tr>
                <tr>
                    <td><strong>Recettes</strong> : Loyers bruts encaissés (charges comprises)
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="La somme des encaissements des loyers nus (sans les provisions pour charges locatives) encaissés pendant l'année. Si vous êtes assujetti à la TVA, les recettes doivent être déclarées pour leur montant hors TVA."><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td class="sommeHC">{{$sommeHC}} €</td>
                </tr>
                <tr>
                    <td><strong>Recettes</strong> : Autres
                        <a href="#bilan" title="" data-toggle="tooltip" style="cursor: help; text-decoration:none;"
                            data-original-title="Autre revenu taxable (pris en compte dans l'aide à la déclaration)"><span
                                class="icon-info hidden-phone"> </span></a>
                    </td>
                    <td>0.00 €</td>
                </tr>
                <tr>
                    <td>TOTAL RECETTES</td>
                    <td><strong class="sommeHC">{{$sommeHC}} €</strong></td>
                </tr>
                <tr>
                    <td><strong>REVENUS FONCIERS TAXABLES <span class="annee" >{{ now()->year }}</span></strong></td>
                    <td><strong class="sommeHC">{{$sommeHC}} €</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
 </div>
@push('script')
<script>
    $(document).ready(function () {
        $("#revenuFoncierTab").tab("show")
        $('#bilanFoncier').on('change', function () {
            $('#'+$(this).val()).tab('show')
        })

        $('#export_bilan_fiscal').on('click', function(){
            var annee = $('#annee').val();
            var bilanFoncier = $('#bilanFoncier').val();
            var bien = $('#bien').val();
            location.href = '/bilan/downloadExelBilanFiscal/'+annee+'/'+bilanFoncier+'/'+bien;
        });
    })
 </script>
@endpush
