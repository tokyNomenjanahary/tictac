<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <title>Document</title>
    <style>
        h3{
            padding: 3px auto ;
            border: 1px solid black;
            font-size: 12px;
        }
        p{
            font-size:12px;
            line-height: 3px;
        }
    </style>
</head>
<body>
        <div class="text-center">
            <h1 style="text-align: center">Contrat de location {{ __('location.'.$location->typelocation->description)}}</h1>
        </div>
        <div class="">
            <p style="font-size: 9px;margin-top:-10px;text-align: center;line-height: 12px">SOUMIS AU TITRE IER BIS DE LA LOI DU 6 JUILLET
               TENDANT À AMÉLIORER LES RAPPORTS LOCATIFS ET
            PORTANT MODIFICATION DE LA LOI N° 86-1290 DU 23 DÉCEMBRE 1986</p>
        </div>
        <div>
            <h3>DÉSIGNATION DES PARTIES</h3>
            <p>Le présent contrat est conclu entre les soussignés :</p>
            <p><b>LE BAILLEUR :</b></p>
            <p><b>Nom :</b> {{$proprio->first_name . ' ' . $proprio->last_name}}</p>
            <p><b>Adresse :</b>{{$proprio->address_register}}</p>
            {{-- <p><b>Télephone :</b>{{$proprio->address_register}}</p> --}}
        </div>
        <div>
            <p>Dénommé(s) ci-après <b>« LE BAILLEUR »</b> , (au singulier)</p>
        </div>
        <div>
            <p><b>LE(S) LOCATAIRE(S) :</b></p>
            <p><b>Nom :</b> {{$location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName}}</p>
            <p><b>Adresse :</b>{{ ($location->Locataire->TenantAddress) ? $location->Locataire->TenantAddress : ' Neant' }}</p>
            <p><b>Télephone :</b> {{ ($location->Locataire->TenantMobilePhone) ? $location->Locataire->TenantMobilePhone : ' Neant' }}</p>
            <p><b>Né(e) le :</b>  {{ ($location->Locataire->TenantBirthDate) ? $location->Locataire->TenantBirthDate . ' ' . $location->Locataire->TenantBirthPlace  : ' Neant' }}</p>
        </div>
        <div>
            <p>Dénommé(s) ci-après <b>« LE LOCATAIRE »</b> , (au singulier)</p>
        </div>
        <div>
            <p>Ensemble dénommés les <b>« Parties »</b> </p>
        </div>
        <div>
            <p>Il a été convenu ce qui suit :</p>
        </div>
        <div>
            <h3>CONDITIONS FINANCIÈRES</h3>
        </div>
        <div>
            <p>Les Parties conviennent des conditions financières suivantes :</p>
        </div>
        <div>
            <p><b>MONTANT DES PAIEMENTS :</b></p>
            <p><b>Loyer hors charges : </b>&nbsp;{{$location->loyer_HC}} € <b> dont complément de loyer :  </b> &nbsp;{{$location->charge}} €<b> justifié par les caractéristiques suivantes : </b>  &nbsp;{{$location->identifiant}}</p>
            {{-- <p><b>Provision sur charges : </b> 46.00 €</p>
            <p><b>Proident deserunt a : </b> 46.00 €</p>
            <p><b>Total Quadrimestriel : </b> 46.00 €</p> --}}
            <p><b>Dépôt de garantie : </b> &nbsp;{{($location->garantie) ? $location->garantie : '0'}} €</p>
        </div>
        <div>
            <h3>DÉSIGNATION DES LOCAUX</h3>
        </div>
        <div>
            <p>Le présent contrat a pour objet la location d'un logement ainsi déterminé :</p>
        </div>
        <div>
            <p><b>SITUATION, DESIGNATION ET CONSISTANCE DES LOCAUX :</b></p>
            <p>{{$location->Logement->typeLogement->property_type}} </p>
            <p>Nombre de pièces : {{($location->Logement->nbr_piece) ? $location->Logement->nbr_piece : '0'}}</p>
            <p>Surface habitable :{{($location->Logement->superficie) ? $location->Logement->superficie . ' m²' : 'Neant'}}</p>
            <p>Adresse : {{$location->Logement->adresse}}</p>
            <p>Bâtiment : {{($location->Logement->batiment) ? $location->Logement->batiment : 'Neant' }}</p>
            <p>Escalier : {{($location->Logement->escalier) ? $location->Logement->escalier : 'Neant' }}</p>
            <p>Etage : {{($location->Logement->etage) ? $location->Logement->etage : 'Neant' }}</p>
            {{-- <p>Porte :  {{($location->Logement->etage) ? $location->Logement->etage : 'Neant' }}</p> --}}
            <p>Cave : Néant</p>
            <p>Parking n° : Néant</p>
            <p>Période de construction : {{($location->Logement->annee_construction !== '0000-00-00') ? $location->Logement->annee_construction : 'Neant' }}</p>
            <p>Description :  {{($location->Logement->description) ? $location->Logement->description : 'Neant' }}</p>
        </div>

        <div>
            <p><b>DESIGNATION DES PARTIES ET DES EQUIPEMENTS :</b></p>
        </div>
        <div>
            <p style="line-height: 12px">Tels que ces locaux existent et tels que le LOCATAIRE déclare parfaitement les connaître pour les avoir vus et visités dès avant ce
                jour. Il reconnaît en outre, qu'ils sont en bon état d'usage et d'entretien et s'engage à les rendre comme tels en fin de jouissance.</p>
        </div>
        {{-- <div>
            <p><b>DESTINATION EXCLUSIVE DES LOCAUX LOUES – OCCUPATION </b></p>
        </div>
        <div>
            <p>Loué(e) à usage de ☒ résidence principale ☐ résidence secondaire ☐ mixte (habitation et professionnel pour la profession libérale
                de : )</p>
        </div>
        <div>
            <p style="line-height: 15px">Le LOCATAIRE s'interdit notamment d'exercer dans les locaux loués toute activité commerciale, industrielle ou artisanale. En cas
                d'usage mixte professionnel et habitation, il fera son affaire personnelle de toute prescription relative à l'exercice de sa profession, en
                sorte que le BAILLEUR ne puisse, en aucun cas, être recherché ni inquiété à ce sujet par l'administration, les occupants de l'immeuble
                ou les voisins. Il ne pourra céder son bail ou sous-louer le logement sans l'accord écrit du BAILLEUR.</p>
        </div> --}}
        <div>
            <h3>DURÉE ET RENOUVELLEMENT</h3>
        </div>
        <div>
            <p style="line-height: 12px"><b>Durée de contrat :</b>{{$location->dure}}</p>
            <p><b>Date de début du bail : </b>{{\Carbon\Carbon::parse($location->debut)->format('d.m.Y')}}</p>
            <p><b>Date de fin du bail : </b>{{\Carbon\Carbon::parse($location->fin)->format('d.m.Y')}}</p>
        </div>
        <div>
            <p style="line-height: 12px;">
                <b>Reconduction ou renouvellement :</b> A défaut de congé ou de proposition de renouvellement, le contrat de bail sera tacitement
                reconduit à son terme pour une durée de 3 ans si le BAILLEUR est une personne physique ou pour une durée de 6 ans si le BAILLEUR
                est une personne morale. Le LOCATAIRE peut mettre fin au bail à tout moment, après avoir donné congé. Le BAILLEUR, quant à lui,
                peut mettre fin au bail à son échéance et après avoir donné congé, soit pour reprendre le logement en vue de l'occuper lui-même ou par
                une personne de sa famille, soit pour le vendre, soit pour un motif légitime et sérieux (Cf. rubrique 3 de la Notice d'information).
            </p>
        </div>
        <div>
            <h3>ASSUREUR MULTIRISQUE HABITATION</h3>
        </div>
        <div>
            <p style="line-height: 12px">Le LOCATAIRE est tenu de s'assurer contre les risques locatifs et d'en justifier à la remise des clés puis chaque année à la demande
                du BAILLEUR (Cf. Notice d'information rubrique 2.2.).</p>
        </div>
        <div>
            <p style="line-height: 12px">S'il ne le fait pas, le BAILLEUR peut demander la résiliation du bail ou souscrire une assurance à la place du LOCATAIRE en lui
                répercutant le montant de la prime.</p>
        </div>
        <div>
            <p><b>Assureur :</b></p>
            <p><b>Date de souscription :</b></p>
            <p><b>Valable jusqu'au :</b></p>
        </div>
        <div>
            <p style="line-height: 12px"><b>EN CAS DE COLOCATION,</b> les Parties peuvent convenir dès la conclusion du contrat de bail de la souscription par le BAILLEUR de
                cette assurance pour le compte des colocataires</p>
        </div>
        <div>
            <p style="line-height: 12px">
                Le BAILLEUR doit remettre une copie du contrat d'assurance au LOCATAIRE lors de sa souscription et de chacun de ses
                renouvellements.
            </p>
        </div>
        <div>
            <p style="line-height: 12px">
                Au cours du bail, le LOCATAIRE peut provoquer la résiliation de l'assurance souscrite par le bailleur pour son compte dans les
                conditions prévues par l'article 7g de la loi du 06/07/1989, en lui fournissant une attestation d'assurance. Le BAILLEUR s'engage alors à
                résilier le contrat souscrit pour le compte du LOCATAIRE dans le délai le plus bref permis par la législation en vigueur. La prime ou la
                fraction de prime exigible dans ce délai au titre de la garantie souscrite par le BAILLEUR demeure récupérable auprès du LOCATAIRE.
            </p>
        </div>
        <div>
            <h3>GARANTS SOLIDAIRES</h3>
            <p>La présente location est garantie par les personnes désignées ci-dessous en qualité de caution :</p>
            @foreach ($garants as $garant)
                -{{$garant->nom . ' ' . $garant->prenom}} <br>
            @endforeach
        </div>
        <div>
            <p style="line-height: 12px">
                La caution se porte caution solidaire du locataire et renonce aux bénéfices de discussion et de division pour le paiement des loyers et
                des charges pour une durée de 9 ans, à compter de la date de signature du bail. Le montant du loyer mensuel s'élevant à la somme de
                (voir Condition financières) euros.
            </p>
        </div>
        <div>
            <p>Un exemplaire de l'engagement de la caution est annexé au présent contrat de bail.</p>
        </div>
        <div>
            <h3>LE LOYER - REVISION</h3>
        </div>
        <div>
            <p><b>LE LOYER :</b></p>
            <p>Il est payable d'avance le 24.</p>
        </div>
        <div>
            <p>Le loyer est de (voir Condition financières) HORS CHARGES</p>
        </div>
        <div>
            <p><b>LA REVISION DU LOYER :</b></p>
            <p>Indice de référence pour la révision du loyer : 4ème trimestre 2022 Valeur : 126.6600 (ILAT)</p>
            <p>Le loyer sera indexé chaque année, à la date anniversaire du présent contrat</p>
        </div>
        <div>
            <p style="line-height: 12px">
                L'indice de référence à prendre en compte est celui du trimestre qui figure dans le bail ou, à défaut, le dernier indice publié à la date de
                signature du contrat. Il est à comparer avec l'indice du même trimestre connu à la date de révision
            </p>
        </div>
        <div>
            <p style="line-height: 12px;">
                A défaut de manifester sa volonté d'appliquer la révision du loyer dans un délai d'un an suivant sa date de prise d'effet, le BAILLEUR
                est réputé avoir renoncé au bénéfice de cette clause pour l'année écoulée. Si le BAILLEUR manifeste sa volonté de réviser le loyer
                dans le délai d'un an sus-indiqué, cette révision de loyer prendra effet à compter de sa demande.
            </p>
        </div>
        <div>
            <h3>LES CHARGES</h3>
        </div>
        <div>
            <p style="line-height: 12px">
                En même temps et de la même façon que le loyer principal, le LOCATAIRE s'oblige à acquitter les charges, prestations et impositions
                récupérables mises à sa charge et découlant de la législation en vigueur et du présent bail, au prorata des millièmes de copropriété s'il
                existe un règlement de copropriété de l'immeuble dans lequel se trouvent les locaux loués, ou selon les modalités définies par un
                règlement intérieur dudit immeuble, ou tout autre état de répartition conforme au principe de répartition des copropriétés.
            </p>
            <p style="line-height: 12px">
                Le paiement de ces charges donnera lieu au paiement de provisions ou forfait mensuelles justifiées par les résultats constatés par
                l'année précédente ou par l'état prévisionnel des dépenses pour l'année en cours.
            </p>
            <p style="line-height: 12px">
                Le montant provisionnel des charges mensuelles à la date de ce jour est (voir Condition financières). Ce montant sera modifié et
                réajusté en fonction de l'évolution réelle du coût des charges.
            </p>
            <p style="line-height: 12px">
                La régularisation s'opèrera chaque année, dans les conditions prévues à l'article 23 de la loi du 6 juillet 89. Les charges sont
                récupérables jusqu'à 3 ans en arrière.
            </p>
            <p>En cas de charges forfaitaires, ce forfait est révisé dans les mêmes conditions que le loyer principal.</p>
        </div>
        <div>
            <h3>DEPOT DE GARANTIE</h3>
        </div>
        <div>
            <p  style="line-height: 12px">A titre de garantie de l'entière exécution de ses obligations le LOCATAIRE verse, ce jour, un dépôt de garantie correspondant à UN
                MOIS de loyer hors charges</p>
        </div>
        <div>
            <p>Ce dépôt ne dispensera en aucun cas le LOCATAIRE du paiement du loyer et des charges aux dates fixées au présent contrat.</p>
        </div>
        <div>
            <p>Cette somme sera restituée sans intérêt au LOCATAIRE en fin de bail et au plus tard dans un délai de :</p>
            <p>- 1 mois si l'état des lieux de sortie est conforme à l'état des lieux d'entrée,</p>
            <p>- 2 mois si l'état des lieux de sortie révèle des différences avec l'état des lieux d'entrée</p>
            <p style="line-height: 12px">
                de la remise des clés, déduction faite, le cas échéant, des sommes restant dues au BAILLEUR ou dont celui-ci pourrait être tenu ou
                responsable, sous réserve de leur justification. Pour le cas où les locaux loués se situeraient dans un immeuble en copropriété, le
                BAILLEUR pourra conserver une provision maximale de 20% du dépôt de garantie pour couvrir des charges en attendant leur
                liquidation ; le solde restant dû au LOCATAIRE est majoré d'une somme égale à 10 % du loyer mensuel, pour chaque mois de retard
                commencé. Cette majoration n'est pas due lorsque l'origine du défaut de restitution dans les délais résulte de l'absence de transmission
                par le LOCATAIRE de sa nouvelle adresse.
            </p>
        </div>
        <div>
            <p style="line-height: 12px">Le LOCATAIRE devra justifier en fin de bail de sa nouvelle domiciliation et du paiement de toute somme dont le BAILLEUR pourrait être
                tenu en ses lieux et place.</p>
        </div>
        <div>
            <p style="line-height: 12px">
                Le départ s'entend après complet déménagement et établissement de l'état des lieux contradictoire de sortie, résiliation des
                abonnements électricité, gaz, téléphone, exécution des réparations locatives et remise des clefs.
            </p>
        </div>
        <div>
            <h3>
                RESILIATION DU CONTRAT
            </h3>
        </div>
        <div>
            <p>Le présent contrat pourra être résilié :</p>
        </div>
        <div>
            <p style="line-height: 12px">
                - Par le LOCATAIRE à tout moment, moyennant un délai de trois mois (partant de la date de réception de l'acte). Ce délai est réduit
                à un mois lorsque le logement est situé dans des « zones de tension du marché locatif » ou lorsque le locataire justifie d'une des
                situations suivantes : obtention d'un premier emploi, de mutation, de perte d'emploi ou de nouvel emploi consécutif à une perte
                d'emploi, état de santé justifiant un changement de domicile et constaté par un certificat médical, bénéfice du revenu de solidarité
                active ou de l'allocation adulte handicapé, attribution d'un logement social. Le locataire doit alors préciser le motif de son départ et
                le justifier à l'occasion de la notification de congé. À défaut, le préavis de trois mois s'applique
            </p>
            <p style="line-height: 12px">
                - Par le BAILLEUR, à l'expiration du bail ou de chacun de ses renouvellements, moyennant un délai de préavis de six mois (partant
                de la date de réception de l'acte).
            </p>
        </div>
        <div>
            <p style="line-height: 12px">
                Le congé devra être signifié à l'autre partie par lettre recommandée avec accusé de réception, remise en main propre contre
                émargement ou par acte d'huissier. La notification de résiliation ou de fin de bail vaudra engagement formel de partir et renonciation à
                tout maintien dans les lieux, sans qu'il soit besoin de ne recourir à aucune formalité. Faute de libérer les lieux à la date convenue, la
                clause pénale incluse au présent contrat sera immédiatement applicable.
            </p>
        </div>
        <div>
            <h3>
                OBLIGATIONS DES PARTIES
            </h3>
        </div>
        <div>
            <p style="line-height: 12px">
                Outre toutes les obligations prévues par la loi du 6 juillet 1989 rappelées dans la Notice d'information annexée au présent contrat,
                auxquelles sont tenus le BAILLEUR (Cf. Notice d'information rubrique 2.1.) et le LOCATAIRE (Cf. Notice d'information rubrique 2.2.) :
            </p>
            <p>Le BAILLEUR est également tenu :</p>
            <p style="line-height: 12px">
                - De transmettre, gratuitement, une quittance au LOCATAIRE lorsque ce dernier en fait la demande ; <br>
                - Si le logement est situé dans un immeuble collectif dont le permis de construire a été délivré avant le 01/07/1997, de tenir à la
                disposition du LOCATAIRE, sur simple demande, le dossier amiante.
            </p>
        </div>
        <div>
            <p style="line-height: 12px">
                Le LOCATAIRE est également tenu :
                - De laisser visiter, aussitôt le congé donné ou reçu, ou en cas de mise en vente, les locaux loués, deux heures par jour les jours
                ouvrables (du lundi au samedi) ;<br>
                - Si le logement est équipé d'une chaudière individuelle, de souscrire un contrat d'entretien annuel de celle-ci auprès d'un
                professionnel qualifié et d'en justifier chaque année à première demande du BAILLEUR ;<br>
                - De ne pas sous-louer le logement sauf accord préalable et écrit du BAILLEUR, y compris sur le prix du loyer
            </p>
        </div>
        <div>
            <p style="line-height: 12px">
                Les Parties conviennent d'établir un état des lieux contradictoire dans les conditions de l'article 3-2 de la loi du 6 juillet 1989 rappelées
                dans la Notice d'information (Cf. Notice d'information rubrique 1.5.), et de l'annexer au présent contrat de bail
            </p>
        </div>
        <div>
            <p style="line-height: 12px">
                Pour donner congé, les Parties s'obligent à respecter les règles légales rappelées ci-avant (au titre de la résiliation du contrat) ainsi que
                dans la Notice d'information (Cf. Notice d'information rubrique 3.1.)
            </p>
        </div>
        <div>
            <h3>
                TRAVAUX REALISES PAR LE LOCATAIRE
            </h3>
        </div>
        <div>
            <p>{{ !empty($location->travauxProprietaire->description) ? $location->travauxProprietaire->description : ''}}</p>
            <p><b>Montant des travaux : </b>{{ !empty($location->travauxProprietaire->Montant) ? $location->travauxProprietaire->Montant : '0'}} €</p>
        </div>
        <div>
            <h3>
                TRAVAUX REALISES PAR LE BAILLEUR
            </h3>
        </div>
        <div>
            <p>{{ !empty($location->travauxLocataire->description) ? $location->travauxLocataire->description : ''}}</p>
            <p><b>Montant des travaux : </b>{{ !empty($location->travauxLocataire->Montant) ? $location->travauxLocataire->Montant : '0'}} €</p>
        </div>
        <div>
            <h3>CLAUSE DE SOLIDARITE</h3>
        </div>
        <div>
            <p style="line-height: 12px">
                Il y aura solidarité et indivisibilité entre les parties désignées sous le nom de LOCATAIRE, et leurs ayants causes, pour le paiement de
                toutes les sommes dues en application du présent bail.
            </p>
        </div>
        <div>
            <p style="line-height: 12px">
                Dans le cas d'une colocation, le colocataire partant, et sa caution, n'est plus tenu au paiement solidaire des loyers à la fin du congé qu'il
                a régulièrement délivré en cas d'arrivée d'un nouveau colocataire. A défaut, la solidarité cesse au plus tard six mois après la date d'effet
                du congé.
            </p>
        </div>
        <div>
            <h3>CLAUSE RESOLUTOIRE</h3>
        </div>
        <div>
            <p style="line-height: 12px">
                Il est expressément convenu qu'à défaut de paiement au terme convenu de tout ou partie du loyer, des charges ou encore du dépôt de
                garantie, et deux mois après un commandement de payer demeuré infructueux, le présent contrat sera résilié de plein droit si bon
                semble au bailleur, sans aucune formalité judiciaire.
            </p>
        </div>
        <div>
            <p style="line-height: 12px">
                Un commandement visant le défaut d'assurance des risques locatifs, ou encore le non-respect de l'obligation d'user paisiblement des
locaux loués, résultant de troubles de voisinage constatés par une décision de justice passée en force de chose jugée, aura les mêmes
effets passé le délai d'un mois.
            </p>
        </div>
        <div>
            <p style="line-height: 12px">
                Une fois le bénéfice de la clause résolutoire acquis au BAILLEUR, le LOCATAIRE devra libérer immédiatement les lieux ; s'il refuse, le
                BAILLEUR pourra alors l'y contraindre par simple ordonnance de référé.
            </p>
        </div>
        <div>
            <p style="line-height: 12px">
                Il est bien entendu qu'en cas de paiement par chèque, le loyer ne sera considéré comme réglé qu'après encaissement du chèque, la
présente clause résolutoire pouvant être appliquée par le BAILLEUR au cas où le chèque serait sans provision.
            </p>
        </div>
        <div>
            <p style="line-height: 12px">
                Toute offre de paiement ou d'exécution intervenant après l'expiration du délai ci-dessus ne pourra faire obstacle à la résolution du
contrat de location acquise au BAILLEUR.
            </p>
        </div>
        <div>
            <p style="line-height: 12px">
                Le coût des commandements, sommations ou significations ci-dessus, y compris le droit proportionnel et les frais d'huissiers, d'avoués
et d'avocats sera à la charge du locataire qui devra les rembourser dans les huit jours de la demande qui lui en sera faite.
De plus, le BAILLEUR se réserve le droit de réclamer des dommages et intérêts supplémentaire s'il était contraint de saisir le tribunal
pour faire valoir ses droits.
            </p>
        </div>
        <div>
            <h3>CLAUSE DE COMMUNICATION PAR VOIE ÉLECTRONIQUE</h3>
        </div>
        <div>
            <p style="line-height: 12px">
                Le LOCATAIRE accepte de recevoir sa quittance de loyer par voie électronique ainsi que toute autre communication par lettre
recommandée électronique.
            </p>
        </div>
        <div>
            <h3>CONVENTION DE PREUVE SIGNATURE ÉLECTRONIQUE</h3>
        </div>
        <div>
            <p style="line-height: 12px">
                Conformément aux articles 1363 et suivants du Code civil, les Parties fixent les règles de preuve recevables entre elles lorsqu'il est fait
recours à un procédé de signature électronique pour conclure ce contrat. <br>
Les Parties acceptent que chaque Partie manifeste son consentement par tout moyen, notamment OTP, SMS ou clic, signature
manuscrite, au moment de la signature électronique de ce contrat.
            </p>
        </div>
        <div>
            <p  style="line-height: 12px">Ces procédés sont admissibles devant les tribunaux et font preuve des données et des éléments qu'ils matérialisent ainsi que des
                signatures qu'ils expriment conformément aux exigences de l'article 1367 du Code civil.</p>
            <p>Les Parties acceptent que :</p>
        </div>
        <div>
            <p style="line-height: 12px">
                - les éléments d'identification utilisés dans le cadre de ce procédé, et
                - les éléments d'horodatage, et
                - les contrats signés et archivés électroniquement, les courriers électroniques, les accusés de réception échangés entre elles,
                - soient admissibles devant les tribunaux et fassent preuve des données et des éléments qu'ils contiennent ainsi que des procédés
                d'authentification qu'ils expriment.
            </p>
            <p style="line-height: 12px">Les Parties reconnaissent que ce contrat signé sous forme électronique aura la même valeur probante qu'un écrit signé sur support
                papier</p>
            <p style="line-height: 12px">Les Parties acceptent que les contrats papiers numérisés soient considérés comme des copies fidèles, durables et fassent preuve des
                données et des éléments qu'ils contiennent.</p>
            <p style="line-height: 12px">Dans le cadre de la relation entre les Parties, la preuve des connexions, des enregistrements informatiques et d'autres éléments
                d'identification sera établie autant que de besoin à l'appui des journaux de connexion tenus à jour par les Parties, sous réserve de la
                preuve contraire.</p>
        </div>
        <div>
            <h3>TOLERANCES</h3>
        </div>
        <div>
            <p style="line-height: 12px">
                Il est formellement convenu que toutes les tolérances de la part du BAILLEUR, relatives aux conditions énoncées ci-dessus, quelles
qu'en aient été la fréquence et la durée, ne pourront en aucun cas être considérés comme apportant une modification ou suppression
de ces conditions, ni génératrices d'un droit quelconque. Le BAILLEUR pourra toujours y mettre fin après notification au LOCATAIRE
par lettre recommandée A.R. en respectant un délai suffisant permettant à ce dernier de se mettre en conformité avec ladite obligation
            </p>
        </div>
        <div>
            <h3>ELECTION DE DOMICILE</h3>
        </div>
        <div>
            <p style="line-height: 12px">Pour l'exécution des présentes, et notamment pour la signification de tout acte de poursuites, les parties font élection de domicile, le
                BAILLEUR en son domicile ou en celui de son mandataire et le LOCATAIRE dans les lieux loués.</p>
        </div>
        <div>
            <h3>
                PIECES ANNEXEES AU CONTRAT
            </h3>
        </div>
        <div>
            <p>Les annexes font parties intégrantes du présent contrat :</p>
            <p  style="line-height: 12px">
                - État des lieux établis contradictoirement (ou par huissier) lors de la remise des clefs au LOCATAIRE. <br>
                - Le cas échéant, Acte de caution solidaire.<br>
                - Décret n° 87-713 du 26 août 1987 fixant la liste des charges récupérables.<br>
                - Décret n°87-712 du 26 août 1987 fixant la liste des réparations locatives.<br>
                - Dossier de diagnostic technique (comprenant un DPE établi par un diagnostiqueur certifié, et, pour les biens concernés un état des
                risques naturels, miniers et technologiques, un état de l'installation intérieure d'électricité et de gaz, une copie de l'état d'amiante
                et/ou un constat de risque d'exposition au plomb).<br>
                - Attestation d'assurance contre les risques locatifs souscrite par le LOCATAIRE.<br>
                - Notice d'information relative aux droits et obligations des locataires et des bailleurs établis par l'arrêté du 29 mai 2015.<br>
                - Le cas échéant, Une autorisation préalable de mise en location.<br>
                - Le cas échéant, Extrait du règlement de copropriété concernant la destination de l'immeuble, la jouissance et l'usage des parties
                privatives et communes et précisant la quote-part afférente au lot loué dans chacune des catégories de charges.<br>
                - Le cas échéant, Les références aux loyers habituellement constatés dans le voisinage pour des logements comparables (si la
                détermination du loyer est la conséquence d'une procédure liée au fait que le loyer précédemment appliqué était manifestement
                sous-évalué).
            </p>
            <p>Fait le</p>
        </div>
        <div style="margin-top: 100px">
            <p><b>LE(S) BAILLEUR(S)</b></p>
            @if(!empty($signaturePro))
                <p>
                    <img
                        style="height: {{$signaturePro['desiredHeight']}};width: {{$signaturePro['desiredWidth']}}; margin-top: 5px; margin-left: -30px"
                        src={{$signaturePro['path']}}>
                </p>
            @endif
        </div>
        <div style="margin-left:500px;@if(!empty($signaturePro)) margin-top: -130px; @else margin-top: -15px; @endif  ">
            <p><b>LE(S) LOCATAIRE(S)</b></p>
            @if(!empty($signatureLoc))
                <p>
                    <img
                        style="height: {{$signatureLoc['desiredHeight']}};width: {{$signatureLoc['desiredWidth']}}; margin-top: 5px"
                        src={{$signatureLoc['path']}}>
                </p>
            @endif
        </div>
</body>
</html>
