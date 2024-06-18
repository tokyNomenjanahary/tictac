<?php

use App\Finance;

use App\Http\Controllers\MergeController;
use App\Logement;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Proprietaire;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TotocController;
use App\Http\Controllers\CarnetController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\revenuController;
use App\Http\Controllers\TicketController;
use Illuminate\Routing\RouteFileRegistrar;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\GeopifyController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\documentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CorbeilleController;
use App\Http\Controllers\LocataireController;
use App\Http\Controllers\quittanceController;
use App\Http\Controllers\inventaireController;
use App\Http\Controllers\EtatDesLieuController;
use App\Http\Controllers\UploadLocationController;
use App\Http\Controllers\Admin\CommunityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\EspaceLocataireController;
use App\Http\Controllers\EspaceMessageController;
use App\Http\Controllers\RatingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/quittance', function () {
//    return view('locataire.quittance');
// });
// Route::get('finance',function(){
//     return view('proprietaire.finance');
// })->name('proprietaire.finance');
//Auth::routes();

// Authentication Routes...
// Route::get('/logement',function(){
//     return view('proprietaire.teste');
// })->name('proprietaire.logement');

Route::get('/merge', [MergeController::class, 'index'])->name('merge.view');
Route::post('/mergePost', [MergeController::class, 'merge'])->name('merge');
Route::post('/chmodwecoco', [MergeController::class, 'chmodWecoco'])->name('chmodwecoco');
Route::post('/cacheClear', [MergeController::class, 'cacheClear'])->name('cacheClear');
Route::post('/getGitBranches', [MergeController::class, 'getGitBranches'])->name('getGitBranches');


Route::any('/Etat_partiel', [FinanceController::class, 'Etat_partiel'])->middleware('InscriptionControle')->name('Etat_partiel');
Route::post('/Etat', [FinanceController::class, 'Changer_Etat'])->middleware('InscriptionControle')->name('Changer_Etat');
Route::get('/Enregistrer-paiement/{id}/{type}', [FinanceController::class, 'Enregistrer_paiement'])->middleware('InscriptionControle')->name('Enregistrer-paiement');
Route::post('/paiement', [FinanceController::class, 'paiement_sauver'])->middleware('InscriptionControle')->name('paiement.sauver');
Route::get('/finance', [FinanceController::class, 'index'])->middleware('InscriptionControle')->name('proprietaire.finance');
Route::any('delete_finance/{id}', [FinanceController::class, 'delete'])->middleware('InscriptionControle')->name('loyer.delete');
Route::any('/suppMultiple', [FinanceController::class, 'suppMultiple']);
Route::any('/getfinance', [FinanceController::class, 'getfinance']);
Route::get('/Exporter', [FinanceController::class, 'exporter'])->name('exporter');
Route::get('/exportation', [FinanceController::class, 'exportation'])->name('exportation');
Route::get('quittance/{id}', [quittanceController::class, 'index'])->middleware('InscriptionControle')->name('locataire.quittances');
Route::get('telecharger/{id}', [quittanceController::class, 'voirPdf'])->middleware('InscriptionControle')->name('quittance.telechargement');
Route::get('envoi/{id}', [quittanceController::class, 'send'])->middleware('InscriptionControle')->name('quittance.send');
Route::get('echeance/{id}', [quittanceController::class, 'avisEcheance'])->middleware('InscriptionControle')->name('echeance.show');
Route::get('telechargerEcheance/{id}', [quittanceController::class, 'showEcheance'])->middleware('InscriptionControle')->name('echeance.telechargement');
Route::get('invite/{email}', [quittanceController::class, 'inviteLocataire'])->middleware('InscriptionControle')->name('locataire.invite');
Route::get('/bilan', [FinanceController::class, 'bilan'])->middleware('InscriptionControle')->name('proprietaire.bilan');
Route::get('/bilan/downloadExelBilanFiscal/{annee}/{bilanFoncier}/{bien?}', [FinanceController::class, 'downloadExelBilanFiscal'])->middleware('InscriptionControle')->name('proprietaire.downloadExelBilanFiscal');
Route::get('/filtrer-bilan', [FinanceController::class, 'filtrebilan'])->middleware('InscriptionControle')->name('proprietaire.filtre_bilan');
Route::post('/suggestion', [FinanceController::class, 'suggestion'])->middleware(['limit_requests_per_day','InscriptionControle', 'throttle:5,1'])->name('proprietaire.suggestion');
Route::get('/documents', [documentController::class, 'index'])->middleware('InscriptionControle')->name('documents.index');
Route::get('/documents/modeles-documents', [documentController::class, 'listeModeleDocument'])->middleware('InscriptionControle')->name('documents.modeles');
Route::get('/documents/modeles-document/download/{id}', [documentController::class, 'telechargerModeleDocument'])->middleware('InscriptionControle')->name('documents.telecharger_modele');
Route::get('/subscription_documents', [documentController::class, 'subscriptionDocuments'])->middleware('InscriptionControle')->name('documents.subscription_documents');
Route::get('/confirmation-stockage-subscription/{id}', 'SubscriptionController@confirmationStockageSubscription')->name('package.confirmation_subscription_documents')->middleware('InscriptionControle');
// Route::get('/documents/recherche-modele-document', [documentController::class, 'rechercheModele'])->middleware('InscriptionControle')->name('documents.recherche_modele');
Route::get('/modifier_documents/{id}', [documentController::class, 'modifier'])->middleware('InscriptionControle')->name('documents.modifier');
Route::post('/modifier_documents', [documentController::class, 'saveModification'])->middleware('InscriptionControle')->name('documents.modification_document');
Route::get('/telecharger_documents/{id}', [documentController::class, 'telecharger'])->middleware('InscriptionControle')->name('documents.telecharger');
Route::get('/archiver_documents/{id}', [documentController::class, 'archiver'])->middleware('InscriptionControle')->name('documents.archiver');
Route::get('/desarchiver_documents/{id}', [documentController::class, 'desarchive'])->middleware('InscriptionControle')->name('documents.desarchiver');
Route::get('/supprimer_documents/{id}', [documentController::class, 'supprimer'])->middleware('InscriptionControle')->name('documents.supprimer');
Route::get('/downloadE', [documentController::class, 'downloadE'])->middleware('InscriptionControle')->name('downloadE');
Route::get('/nouveau_documents', [documentController::class, 'nouveau'])->middleware('InscriptionControle')->name('documents.nouveau');
Route::any('/suppdoc', [documentController::class, 'deleteMultiple']);
Route::get('/inventaire', [inventaireController::class, 'index'])->middleware('InscriptionControle')->name('inventaire.index');
Route::get('/nouveau_inventaire', [inventaireController::class, 'nouveau'])->middleware('InscriptionControle')->name('inventaire.nouveau');
Route::get('/inventaire/edit/{inventaire_id}', [inventaireController::class, 'updateInventaire'])->middleware('InscriptionControle')->name('inventaire.edit');
Route::post('/inventaire/saveUpdate/{inventaire_id}', [inventaireController::class, 'saveUpdateInventaire'])->middleware('InscriptionControle')->name('inventaire.saveUpdateInventaire');
Route::any('/sauvegarderI', [inventaireController::class, 'sauvegarder'])->middleware('InscriptionControle')->name('inventaire.sauvegarder');
Route::any('/inventaire/sauvegarder', [inventaireController::class, 'enregistrer'])->middleware('InscriptionControle')->name('inventaire.enregistrer_inventaire');
Route::get('/inventaire/recherche-bien-location', [documentController::class, 'rechercherBienLocation'])->middleware('InscriptionControle')->name('inventaire.recherche_bien_location');
Route::any('/downloadExcel', [CarnetController::class, 'downloadExcel'])->middleware('InscriptionControle')->name('carnet.downloadExcel');
Route::any('/importercontact', [CarnetController::class, 'importercontact'])->middleware('InscriptionControle')->name('carnet.importercontact');
Route::post('/impordonnecontact', [CarnetController::class, 'impordonnecontact'])->middleware('InscriptionControle')->name('carnet.impordonne');
Route::any('/contact', [CarnetController::class, 'index'])->middleware('InscriptionControle')->name('carnet.index');
Route::get('/deleteContact/{id}', [CarnetController::class, 'delete'])->middleware('InscriptionControle')->name('carnet.delete');
Route::any('/noveau_contact', [CarnetController::class, 'nouveau'])->middleware('InscriptionControle')->name('carnet.nouveau');
Route::get('/newContact', [CarnetController::class, 'newContact'])->middleware('InscriptionControle')->name('carnet.newContact');
Route::post('/saveConctact', [CarnetController::class, 'saveConctact'])->middleware('InscriptionControle')->name('carnet.saveConctact');
Route::get('/editContact/{id}', [CarnetController::class, 'editContact'])->middleware('InscriptionControle')->name('carnet.editContact');
Route::post('/saveContact/{id}', [CarnetController::class, 'saveContact'])->middleware('InscriptionControle')->name('carnet.saveContact');
Route::get('/export-contact', [CarnetController::class, 'exportContact'])->middleware('InscriptionControle')->name('carnet.export_contact');

/*** Espace message ***/
Route::middleware('InscriptionControle')->group(function(){
    Route::resource('/agenda',AgendaController::class);
    Route::get('/ajoutRendez',[AgendaController::class,'ajout']);
    Route::get('/modifierAgenda',[AgendaController::class,'Modifier']);
    Route::get('/deleteAgenda',[AgendaController::class,'supprimer']);
    Route::get('/updateStatusAgenda',[AgendaController::class,'accepter']);
    Route::get('/updateStatusAgendaRefuser',[AgendaController::class,'refuser']);
    Route::get('/getAgenda',[AgendaController::class,'getAgenda']);
    Route::get('/list-message',[EspaceMessageController::class, 'boiteMessage'])->name('message.index');
    Route::get('/delete_message/{id}',[EspaceMessageController::class, 'supprimer'])->name('message.supression');
    Route::resource('/espaceMessage', EspaceMessageController::class);
    Route::post('/espaceMessage/store', [EspaceMessageController::class, 'store']);
    Route::get('/espaceMessage/showConversation/{id}', [EspaceMessageController::class, 'showConversation'])->name('espaceMessage.showConversation');
    Route::get('/espaceMessage/getResponseMessage/{id}', [EspaceMessageController::class, 'getResponseMessage']);
    Route::post('/espaceMessage/saveNewMessage', [EspaceMessageController::class, 'saveNewMessage'])->name('espaceMessage.saveNewMessage');
});

Route::any('/annuler', [FinanceController::class, 'annuler'])->middleware('InscriptionControle')->name('finance.annuler');
Route::put('/toggle-role', [RoleController::class, 'update'])->middleware(['InscriptionControle'])->name('user.toggle');
Route::middleware(['InscriptionControle', 'IsTenant'])->group(function(){
    Route::get('/locataire/dashboard', [EspaceLocataireController::class, 'index'])->name('espaceLocataire.dashboard');
    Route::get('/locataire/quittance', [EspaceLocataireController::class, 'quittance'])->name('locataire.quittance');
    Route::get('/supprimerquittance/{id}', [EspaceLocataireController::class, 'suppression'])->name('quittance.supprimerquittance');
    Route::get('/telecharge/{id}', [EspaceLocataireController::class, 'download'])->name('quittance.download');
    Route::get('/visualiser/{id}', [EspaceLocataireController::class, 'visualiser'])->name('quittance.visualiser');
    Route::get('/visualiser_recu/{id}', [EspaceLocataireController::class, 'visualiserrecu'])->name('quittance.recue');
    Route::get('/info', [EspaceLocataireController::class, 'mesinfo'])->name('espaceLocataire.mesinfo');
    Route::get('/mesinfo/{id}', [EspaceLocataireController::class, 'infoplus'])->name('espaceLocataire.infoplus');
    Route::get('/proprio', [EspaceLocataireController::class, 'mesproprio'])->name('espaceLocataire.mesproprio');
    Route::get('/location_locataire', [LocationController::class,'liste_locataire'])->name('location.liste_locataire');
    Route::get('/location_detaille/{encoded_id}', [LocationController::class,'detail_location_locataire'])->name('location.detail_loc');
    Route::get('locataire/notification', [EspaceLocataireController::class, 'mynotification'])->name('locataire.notification');

});


Route::middleware('InscriptionControle', 'IsOwner')->group(function(){
    Route::get('/logement/nouveauxLot', [Proprietaire::class, 'nouveauLogement'])->name('proprietaire.nouveaux');
    Route::get('/logement/importerBien', [Proprietaire::class, 'importerBien'])->name('proprietaire.importerBien');
    Route::post('/logement/saveImporterBien', [Proprietaire::class, 'saveImporterBien'])->name('proprietaire.saveImporterBien');
    Route::get('/logement/downloadExempleImportBien', [Proprietaire::class, 'downloadExempleImportBien'])->name('proprietaire.downloadExempleImportBien');
    Route::get('/logement/downloadExempleImportBienOds', [Proprietaire::class, 'downloadExempleImportBienOds'])->name('proprietaire.downloadExempleImportBienOds');
    Route::get('/logement/downloadExempleImportBienCsv', [Proprietaire::class, 'downloadExempleImportBienCsv'])->name('proprietaire.downloadExempleImportBienCsv');
    Route::get('/detail/{logementId}', [Proprietaire::class, 'detailLogement'])->name('proprietaire.detail');
    Route::get('/getcharge', [Proprietaire::class, 'chargeList'])->name('charge.logement');
    Route::get('/logement', [Proprietaire::class, 'logementList'])->name('proprietaire.logement');
    Route::get('/deleteLogement/{idLogement}', [Proprietaire::class, 'deleteLogement'])->name('proprietaire.deleteLogement');
    Route::get('/delete_logement_multiple', [Proprietaire::class, 'deleteLogementMultiple'])->name('proprietaire.delete_logement_multiple');
    Route::post('/save_logement', [Proprietaire::class, 'saveLogement'])->name('proprietaire.save_logement');
    Route::get('/editLogement/{idLogement}', [Proprietaire::class, 'editlogement'])->name('proprietaire.editlogement');
    Route::post('/saveEditLogement/{idLogement}', [Proprietaire::class, 'updateLogement'])->name('proprietaire.saveEditLogement');
    Route::get('/genererAnonceLogement/{idLogement}', [Proprietaire::class, 'genererAnnonceLogement'])->name('proprietaire.genererAnonceLog');
    Route::post('/deleteImageLogement', [Proprietaire::class, 'deleteImage'])->name('proprietaire.deleleteImageLogement');
    Route::post('/uploadImageLogement', [Proprietaire::class, 'uploadImageLogement'])->name('proprietaire.uploadImageLogement');
    Route::post('/saveContactLogement', [Proprietaire::class, 'saveContactLogement'])->name('proprietaire.saveContactLogement');
    Route::get('/deleteContactLogement/{idContactLogement}', [Proprietaire::class, 'deleteContactLogement'])->name('proprietaire.deleteContactLogement');
    Route::post('/updateContactLogement', [Proprietaire::class, 'updateContactLogement'])->name('proprietaire.updateContactLogement');
    Route::get('/getListContactInSession', [Proprietaire::class, 'getListContactInSession'])->name('proprietaire.getListContactInSession');
    Route::post('/saveContratDiagnostic/{id?}', [Proprietaire::class, 'saveContratDiagnostic'])->name('proprietaire.saveContratDiagnostic');
    Route::get('/deleteContratDiagnostic/{idContratDiagnostic}', [Proprietaire::class, 'deleteContratDiagnostic'])->name('proprietaire.deleteContratDiagnostic');
    Route::get('/exportInfoLogement', [Proprietaire::class, 'exportInfoLogement'])->name('proprietaire.exportInfoLogement');
    Route::get('/archive_logement/{idLogement}', [Proprietaire::class, 'archiveLogementMultiple'])->name('proprietaire.archiveLogement');
    Route::get('/archive_multiple_logement/{idLogement}', [Proprietaire::class, 'archiveLogementMultiple'])->name('proprietaire.archive_multiple_logement');
    Route::get('/downloadExelLogement/{data_id}', [Proprietaire::class, 'downloadExelLogement'])->name('proprietaire.downloadExelLogement');
    Route::get('/chambreInLogement', [Proprietaire::class, 'chambreInLogement'])->name('proprietaire.chambreInLogement');
    Route::get('/logement/listChambre', [Proprietaire::class, 'listChambre'])->name('proprietaire.listChambre');
    Route::get('/logement/list/{propertyType}/{idPropertyType}', [Proprietaire::class, 'listLogements'])->name('proprietaire.listLogements');
    Route::get('/addLogementEnfant/{idLogementMere}', [Proprietaire::class, 'addLogementEnfant'])->name('proprietaire.addLogementEnfant');
    Route::post('/saveLogementEnfant/{idLogementMere}', [Proprietaire::class, 'saveLogementEnfant'])->name('proprietaire.saveLogementEnfant');
    Route::get('/deleteLogementEnfant/{idLogement}', [Proprietaire::class, 'deleteLogementEnfant'])->name('proprietaire.deleteLogementEnfant');
    Route::get('/logement/add-chambre-in-logement/{idLogementMere}', [Proprietaire::class, 'creatChambreInLogement'])->name('proprietaire.creatChambreInLogement');
    Route::any('/searchContact', [Proprietaire::class, 'searchContact'])->name('proprietaire.searchContact');
    Route::any('/getsearchContact/{id}', [Proprietaire::class, 'getsearchContact'])->name('proprietaire.getsearchContact');
    Route::get('recu/{id}', [FinanceController::class, 'telecharge_recu'])->name('telecharge-recu');
    Route::get('recu_telecharge/{id}', [FinanceController::class, 'voirPdf_recu'])->name('recu.telechargement');
    Route::get('send/{id}', [FinanceController::class, 'sendrecu'])->name('recu.send');
    Route::get('/proprietaire', [Proprietaire::class, 'index'])->name('proprietaire.bureau');
    Route::get('/get-ready', [Proprietaire::class, 'ready'])->name('proprietaire.ready');

    Route::get('/locataires', [LocataireController::class,'locataireList'])->name('locataire.locataire');
    Route::get('/deleteLocataire/{id}', [LocataireController::class,'delete_locataire'])->name('locataire.delete');
    Route::get('/ficheLocataire/{id}', [LocataireController::class,'fiche_locataire'])->name('locataire.fiche');

    /*** Gestion de dossier ***/
    Route::get('/gestion-dossier', [documentController::class, 'gestionDossier'])->name('documents.gestionDossier');
    Route::post('/saveDossier', [documentController::class, 'saveDossier'])->name('documents.saveDossier');
    Route::get('/containedDossier/{dossier_id}', [documentController::class, 'containedDossier'])->name('documents.containedDossier');
    Route::get('/removeDocumentDossier/{document_id}', [documentController::class, 'removeDocumentDossier'])->name('documents.removeDocumentDossier');
    Route::get('/addDocumentDossier/{document_id}', [documentController::class, 'addDocumentDossier'])->name('documents.addDocumentDossier');
    Route::post('/saveDocumentDossier', [documentController::class, 'saveDocumentDossier'])->name('documents.saveDocumentDossier');

    /*** Gestion de signature ***/
    Route::get('/gestion-signature', [documentController::class, 'gestionSignature'])->name('documents.gestionSignature');
    Route::post('/uploadGestionSignature', [documentController::class, 'uploadGestionSignature'])->name('documents.uploadGestionSignature');
    Route::post('/uploadGestionSignatureNopad', [documentController::class, 'uploadGestionSignatureNopad'])->name('documents.uploadGestionSignatureNopad');


    //Add
    Route::post('/locataireG', [LocataireController::class,'locataireInfoGenerale'])->name('locataire.info_generale');
    Route::post('/locataireC', [LocataireController::class,'locataireInfoComplementaire'])->name('locataire.info_complementaire');
    Route::post('/locataireGarants', [LocataireController::class,'locataireInfoGarants'])->name('locataire.info_garants');
    Route::post('/locataireUrgence', [LocataireController::class,'locataireInfoUrgence'])->name('locataire.info_urgence');
    Route::any('/suppphotos/{id}', [LocataireController::class,'suppphotos'])->name('locataire.suppphotos');
    Route::any('/suppsignature/{id}', [LocataireController::class,'suppsignature'])->name('locataire.suppsignature');
    Route::any('/suppcard/{id}', [LocataireController::class,'suppcard'])->name('locataire.suppcard');
    //Archive
    Route::get('/locataireArchive/{id}', [LocataireController::class,'locataireArchive'])->name('locataire.archive');
    //Edit
    Route::get('/exportLoctaire', [LocataireController::class,'exportLoctaire'])->name('locataire.exportLoctaire');
    Route::get('/exportexportLoctaireODS', [LocataireController::class,'exportexportLoctaireODS'])->name('locataire.exportexportLoctaireODS');

    Route::get('/locataireEdit/{id}', [LocataireController::class,'locataireEdit'])->name('locataire.edit');
    Route::post('/EditlocataireG', [LocataireController::class,'editLocataireInfoGenerale'])->name('locataire.info_generale.edit');
    Route::post('/EditlocataireC', [LocataireController::class,'editLocataireInfoComplementaire'])->name('locataire.info_complementaire.edit');
    Route::post('/EditlocataireGarants', [LocataireController::class,'editLocataireInfoGarants'])->name('locataire.info_garants.edit');
    Route::any('/suppGarantsEditLocataire/{id}', [LocataireController::class,'suppGarantsEditLocataire'])->name('locataire.suppGarantsEditLocataire');
    Route::any('/suppUrgenceEditLocataire/{id}', [LocataireController::class,'suppUrgenceEditLocataire'])->name('locataire.suppUrgenceEditLocataire');
    Route::post('/EditlocataireUrgence', [LocataireController::class,'editlocataireInfoUrgence'])->name('locataire.info_urgence.edit');
    //envoi invitation par mail
    Route::post('/inviteMail', [LocataireController::class,'inviteMail'])->name('locataire.inviteMail');
    Route::any('/supp-tempGar/{id}', [LocationController::class,'supp_tempGar']);
    Route::any('/garant/{id}', [LocationController::class,'getgarantById']);
    Route::any('/getGarants', [LocationController::class,'getgarant']);
    Route::any('/deleteGarants', [LocationController::class,'deleteGarants']);

    /* notification */
    Route::get('proprietaire/notification', [Proprietaire::class, 'mynotification'])->name('proprietaire.notification');
    Route::get('proprietaire/agenda', [AgendaController::class, 'agenda'])->name('proprietaire.agenda');
    Route::get('proprietaire/Newagenda', [AgendaController::class, 'nouveau'])->name('proprietaire.nouveauAgenda');
    Route::any('/sauver_rdv', [AgendaController::class, 'saveorupdate'])->name('proprietaire.saveAgenda');
    Route::get('/getNomLocataire', [AgendaController::class, 'locataire'])->name('proprietaire.getLocataire');
    Route::get('/editAgenda/{id}', [AgendaController::class, 'editer'])->name('agenda.edit');
    Route::post('/agenda/status', [AgendaController::class, 'status'])->name('agenda.status');


});

Route::middleware('InscriptionControle')->group(function () {
    Route::resource('/ticket',TicketController::class);
    Route::get('/ticket/depense/{id}',[TicketController::class, 'TickectDepense'])->name('ticket.depense');
    Route::post('/ticket/saveDepenseTicket',[TicketController::class, 'saveDepenseTicket'])->name('ticket.saveDepenseTicket');
    Route::get('/ticketdetails/{id}',[TicketController::class, 'Tickectdetails'])->name('ticket.details');
    Route::get('/deleteTicketMultiple',[TicketController::class,'deleteMultiple']);
    Route::get('/archiveTicketMultiple',[TicketController::class,'archiveMultiple']);
    Route::get('/archive-Ticket/{id}',[TicketController::class,'archive'])->name('ticket.archive');
    Route::get('/formulaire/creation-ticket/{location?}', [TicketController::class, 'formulaire'])->name('ticket.formulaire');
    Route::get('/supprime/{id}', [TicketController::class, 'suppression'])->name('ticket.suppression');
    Route::get('/reouvir/{id}', [TicketController::class, 'reouvrir'])->name('ticket.reouvrir');
    Route::get('/modif/{id}', [TicketController::class, 'modification'])->name('ticket.modification');
    Route::post('/sauvermodif', [TicketController::class, 'sauvermodif'])->name('ticket.sauvermodif');
    Route::post('/uploadTicketFiles', [TicketController::class, 'uploadTicketFiles'])->name('ticket.uploadTicketFiles');
    Route::post('/DeleteTicketFiles', [TicketController::class, 'DeleteTicketFiles'])->name('ticket.DeleteTicketFiles');
    Route::get('/changeStatusTicket',[TicketController::class, 'changeStatus']);
    Route::get('/get-location/{locataire_id}/{user_proprietiare_id}',[TicketController::class, 'renderSelectView'])->name('ticket.getLocation');
    Route::post('/enregistrementDoc',[documentController::class,'enregistrement'])->name('document.nouvaux');
    Route::get('/attestation_loyer/{id}', [LocationController::class, 'getAttestationLoyer'])->name('location.attestationLoyer');
    Route::get('/document_CAF/{id}',[LocationController::class,'documentCAF'])->name('location.documentCAF');
    Route::get('/telechargement_document_caf/{id}',[LocationController::class,'telechargement_caf'])->name('document_caf.telechargement');
    Route::post('/enregister',[LocationController::class,'enregistrement_documentCAF'])->name('documenCAF.enregister');

});




/*  corbeille */
Route::middleware('InscriptionControle', 'IsOwner')->group(function () {
    Route::get('/corbeille', [CorbeilleController::class, 'index'])->name('corbeille.index');
    Route::get('/corbeilleVider', [CorbeilleController::class, 'vider'])->name('corbeille.vider');
    Route::get('/corbeille/restaurer/{id}', [CorbeilleController::class, 'restaurer'])->name('corbeille.restaurer');
    Route::get('/corbeille/permanent-delete', [CorbeilleController::class, 'deletePermanent'])->name('permanent-delete');
    Route::get('/corbeille/permanent-restore', [CorbeilleController::class, 'restorePermanent'])->name('permanent-restore');
    Route::get('/corbeille/supprimer/{id}', [CorbeilleController::class, 'supprimer'])->name('corbeille.supprimer');
    Route::get('Ajoutlocataire', [Proprietaire::class, 'addLocataire'])->name('locataire.ajouterColocataire');

    Route::get('ajouterLocation', function () {
        return view('location.ajouterLocation');
    })->name('location.ajouterLocation');
    Route::post('/location/reviser-loyer', [LocationController::class, 'saveRevisionLoyer'])->name('location.save_revision_loyer');
    Route::delete('/location/annuler-revision-loyer', [LocationController::class, 'annulerRevision'])->name('location.delete_revision_loyer');

    Route::any('ajout-revenu', [revenuController::class, 'index'])->name('ajout-revenu');
    Route::post('upload-documents_revenu', [revenuController::class, 'documents'])->name('upload-documents_revenu');
    Route::post('delete-documents_revenu', [revenuController::class, 'deleteEtatFiles'])->name('delete-documents_revenu');
    Route::post('documents_revenu/delete', [revenuController::class, 'deleterevenuFiles'])->name('delete-revenu');
    Route::any('enregistrer-revenu', [revenuController::class, 'enregistrer_revenu'])->name('finance.enregistrer-revenu');
    Route::any('modifier-revenu/{id}', [revenuController::class, 'modifier_revenu'])->name('modifier-revenu');
    Route::post('sauver_revenu', [revenuController::class, 'sauver_revenu'])->name('finance.sauver_revenu');
    Route::any('delete_revenu/{id}', [revenuController::class, 'delete_revenu'])->name('finance.delete');
    Route::any('ajout-depense', [revenuController::class, 'ajout_depense'])->name('ajout-depense');
    Route::any('enregistrer-depense', [revenuController::class, 'enregistrer_depense'])->name('finance.enregistrer-depense');
    Route::any('modifier-depense/{id}', [revenuController::class, 'modifier_depense'])->name('modifier-depense');
    Route::post('sauver_depense', [revenuController::class, 'sauver_depense'])->name('finance.sauver_depense');
    Route::any('delete_depense/{id}', [revenuController::class, 'delete_depense'])->name('depense.delete');
    Route::any('modifier-loyer/{id}', [revenuController::class, 'modifier_loyer'])->name('modifier-loyer');
    Route::post('sauver_Loyer', [revenuController::class, 'sauver_Loyer'])->name('finance.sauver_Loyer');
});


Route::middleware('InscriptionControle', 'IsOwner')->group(function () {
    Route::resource('/location', LocationController::class);
    Route::post('/enregistrementL',[LocationController::class,'enregistre']);
    Route::get('/save-tempGar',[LocationController::class,'saveTempGarant']);
    Route::get('/save-tempLoc',[LocationController::class,'saveTempLoc']);
    Route::get('/save-tempGarTable',[LocationController::class,'saveTempTable']);
    Route::get('/search/autocomplete',[LocationController::class,'cherchelocataire']);
    Route::get('/getLocataire',[LocationController::class,'getLocataire']);
    Route::get('/deleteMultiple',[LocationController::class,'deleteMultiple']);
    Route::get('/ArchiveMultiple',[LocationController::class,'archiveMultiple']);
    Route::get('/ExportExcel',[LocationController::class,'export'])->name('export');
    Route::get('/ExportOpenOffice',[LocationController::class,'exportODS'])->name('exportODS');
    Route::get('/edition/{encoded_id}',[LocationController::class,'edition'])->name('edition');
    Route::get('/modifier/{id}',[LocationController::class,'modification'])->name('modificationlocation');
    Route::get('/delete/{id}',[LocationController::class,'delete'])->name('location.delete');
    Route::get('/modificationComplementaire/{id}',[LocationController::class,'modification_complementaire'])->name('modification.complementaire');
    Route::post('/modificationDocument',[LocationController::class,'modification_document'])->name('modification.doc');
    Route::get('etats-des-lieux', [EtatDesLieuController::class, 'index'])->name('proprietaire.etat-des-lieux');
    Route::get('etats-des-lieux/{is_active}/actives', [EtatDesLieuController::class, 'activity'])->name('proprietaire.etat-des-lieux.activity');
    Route::get('etats-des-lieux/etat/{id?}/{nom?}', [EtatDesLieuController::class, 'create'])->name('proprietaire.ajout-etat');
    Route::get('etats-des-lieux/location/{type_id}/{location_id}', [EtatDesLieuController::class, 'createWith'])->name('proprietaire.ajout-etat-location');
    Route::get('inventaireLocation/{location_id}', [EtatDesLieuController::class, 'inventaireLocation'])->name('proprietaire.inventaireLocation');

    Route::get('etats-des-lieux/inventaire/{id}', [EtatDesLieuController::class, 'createWithInventaire'])->name('proprietaire.ajout-etat-inventaire');
    Route::post('etats-des-lieux/upload-etat-files', [EtatDesLieuController::class, 'uploadEtatFiles'])->name('upload-etat-lieux-files');
    Route::post('etats-des-lieux/delete-etat-files', [EtatDesLieuController::class, 'deleteEtatFiles'])->name('delete-etat-lieux-files');
    Route::get('etats-des-lieux/delete-etat', [EtatDesLieuController::class, 'deleteEtat'])->name('delete-etat');
    Route::get('etats-des-lieux/archiver', [EtatDesLieuController::class, 'archiverEtat'])->name('archiver-etat');
    Route::post('etats-des-lieux/enregistrer-etat/{id?}', [EtatDesLieuController::class, 'storeOrUpdate'])->name('proprietaire.enregistrer-etat');
    Route::post('etats-des-lieux/enregistrer-etat-invenataire/{id?}', [EtatDesLieuController::class, 'storeOrUpdateInv'])->name('proprietaire.enregistrer-etat-inventaire');
    Route::get('etats-des-lieux/{id}/exporter', [EtatDesLieuController::class, 'exporter'])->name('proprietaire.exporter-etat');
    Route::get('etats-des-lieux/{id}/exporter-word', [EtatDesLieuController::class, 'exportToWord'])->name('proprietaire.exporter-etat-word');
    Route::get('/archiver',[LocationController::class,'archiver']);
    Route::get('/desarchive',[LocationController::class,'desarchive'])->name('location.desarchive');
    Route::get('/ficheLocation/{encoded_id}',[LocationController::class,'ficheLocation'])->name('ficheLocation');
    Route::post('/site/file-delete',[LocationController::class,'deleteFile'])->name('delete-etat-files');
    Route::post('/modificationGarant',[LocationController::class,'modificationGarant'])->name('modification.garant');
    Route::post('/ajouterGarant',[LocationController::class,'ajoutGarant'])->name('garant');
    Route::get('/importLocation',[LocationController::class,'import'])->name('location.import');
    Route::get('/importLocataire',[LocataireController::class,'import'])->name('locataire.import');
    Route::get('/importModel',[LocataireController::class,'importModel'])->name('locataire.download');
    Route::post('/importDonne',[LocationController::class,'importData'])->name('import.data');
    Route::post('/import',[LocationController::class,'importD'])->name('location.impordonne');
    Route::post('/importLocataire',[LocataireController::class,'importDonne'])->name('locataire.impordonne');
    Route::get('/download',[LocationController::class,'download'])->name('import.download');
    Route::get('/downloadODS',[LocationController::class,'downloadODS'])->name('import.downloadODS');
    Route::get('/downloadCSV',[LocationController::class,'downloadCSV'])->name('import.downloadCSV');
    Route::post('AjoutNote',[LocationController::class,'note'])->name('location.note');
    Route::get('/deleteNote',[LocationController::class,'deleteNote'])->name('note.delete');
    Route::get('TerminerLocation/{encoded_id}',[LocationController::class,'terminer'])->name('location.terminer');
    Route::post('/enregistrement_depart',[LocationController::class,'depart'])->name('location.depart');
    Route::get('/revisionLoyer/{encoded_id}',[LocationController::class,'revision'])->name('location.revision');
    Route::post('/regenerationLoyer',[LocationController::class,'regeneration'])->name('location.regeneration');
    Route::get('Annuler_depart/{encoded_id}',[LocationController::class,'annuler_depart'])->name('location.annuler_depart');
    Route::get('/regularisationCharge/{encoded_id}',[LocationController::class,'regularisation'])->name('location.regularisation');
    Route::post('/enregistrementReguarisation',[LocationController::class,'enregistrement_regularisation'])->name('enregistrement.regularisantion');
    Route::get('/formatPDF/{id}',[LocationController::class,'formatPDF'])->name('regularisantion.formatPDF');
    Route::get('/formatWord/{id}',[LocationController::class,'formatWord'])->name('regularisation.formatWord');
    Route::get('/aprecu/{id}',[LocationController::class,'apercu_regularisation'])->name('apercu_regularisation');
    Route::get('confirmation/{id}',[LocationController::class,'confirmation'])->name('confirmation.regularisation');
    Route::get('/annulerRegularisation/{id}',[LocationController::class,'anulationRegularisation'])->name('annuler.regularisation');
    Route::get('/Ajouter_commentaire/{encoded_id}',[LocationController::class,'ajout_comentaire'])->name('location.ajoutCommentaire');
    Route::post('/EnregistrementCommentaire',[LocationController::class,'enregistrementComment'])->name('enregistrement.commentaire');
    Route::post('/desativation',[LocationController::class,'desactivation_location'])->name('location.desactivation');
    Route::post('/reactivaion',[LocationController::class,'reactivation_location'])->name('location.reactivation');
    Route::post('/rappeleAssurance',[LocationController::class,'rapelle_assurance'])->name('location.rapelle');
    Route::get('/bilan_location/{id}',[LocationController::class,'voir_bilan'])->name('voir_bilan');
    Route::get('/finance_location/{id}',[LocationController::class,'voir_finance'])->name('voir_finance');
    Route::any('/getlocation/{id}', [LocationController::class, 'getlocation']);
    Route::get('/getcautionpdf/{id}', [LocationController::class, 'getcaution'])->name('location.cautionPdf');
    Route::get('/getcautiondoc/{id}', [LocationController::class, 'getcautiondoc'])->name('location.cautiondoc');
    Route::get('/justificatif-assurance/{id}', [LocationController::class, 'getJustificatifAssurance'])->name('location.justificatifPdf');

    Route::get('/justificatif-assurance-doc/{id}', [LocationController::class, 'getJustificatifAssuranceDoc'])->name('location.justificatifdoc');
    Route::get('/newMessage/{id}',[LocationController::class,'message'])->name('location.message');
    Route::post('/envoyer',[LocationController::class,'envoie_message'])->name('location.envoyerMessage');
    Route::post('location/upload-files', [LocationController::class, 'uploadLocFiles'])->name('upload-location-files');
    Route::post('location/delete-files', [LocationController::class, 'deleteLocFiles'])->name('delete-location-files');
    Route::get('location/create/{logement_id?}', [LocationController::class, 'create'])->name(('logement.create.location'));
    Route::get('inventaire/delete/{id}', [inventaireController::class, 'delete'])->name('delete-inventaire');
    Route::get('inventaire/deleteMultiple', [inventaireController::class, 'deleteMultiple'])->name('deletedeleteMultiple-inventaire');
    Route::get('/telechargement/{id}',[LocationController::class,'telechargementContrat'])->name('location.telechargerContrat');
    Route::get('/telechargementWord/{id}',[LocationController::class,'telechargementWord'])->name('location.telechargerContratWord');
    Route::get('/down/{id}',[LocationController::class,'downDocuments'])->name('location.downDocuments');
    Route::get('inventaire/archive', [inventaireController::class, 'archive'])->name('archive-inventaire');
    Route::get('/contact/{contact_id?}', [CarnetController::class, 'show'])->name('carnet.show');
    Route::get('/locataire/{id}/type-modele/{type}/pdf', [LocataireController::class, 'downloadFilePdf'])->name('locataire.download-pdf');
    Route::get('/locataire/{id}/type-modele/{type}/docx', [LocataireController::class, 'downloadFileDocx'])->name('locataire.download-docx');
    Route::get('/locataire/{id}/file-view', [LocataireController::class, 'renderFileView'])->name('locataire.file-view');
    Route::post('/locataire/note-avis', [LocataireController::class, 'note_avis'])->name('location.note_avis');
    Route::get('/locataire/note-avis', [RatingController::class, 'index_note'])->name('note.index');
    Route::put('/locataire/update/note-avis', [RatingController::class, 'ratingUpdate'])->name('note.update');

});


Route::any('/cache_clear', function () {
    Artisan::call('cache:clear');
});


// route location


// fin route location


Route::post('/paypal', [App\Http\Controllers\PayPalController::class, 'postPaymentWithpaypal'])->name('paypal');
Route::get('/paypal', [App\Http\Controllers\PayPalController::class, 'getPaymentStatus'])->name('status');


Route::get('/geoapify_key/{page}', [GeopifyController::class, 'geoapify_key'])->name('geoapify.key');
Route::any('/update_gestion_geoapify/{page}', [GeopifyController::class, 'update_gestion_geoapify'])->name('geoapify.gestion');




Route::any('/run_toctoc', [App\Http\Controllers\TotocController::class, 'initToctoc'])->name('run_toctoc');
Route::any('/run_cron_task_toctoc', [App\Http\Controllers\TotocController::class, 'testcron'])->name('testcron');






Route::get('envoi_facture', 'HomeController@envoi_facture');
Route::get('facture', 'HomeController@facturevue');

Route::group([
    'middleware' => 'cors',
], function () {
    Route::post('/hogehoge', 'TransferController@assurance');
});

Route::any('/hogehoge', 'TransferController@affiche_transfer')->middleware('cors')->name('api.meslocs');
Route::post('/email-error-autocomplete-geoapify', 'HomeController@error_geoapify');

Route::get('/useremail', 'HomeController@useremail')->name('useremail');
Route::post('/save_mail', 'HomeController@save_mail')->name('save_mail');

Route::get('/connexion', 'HomeController@loginPopup')->name('login');
Route::any('/connexion', 'Auth\LoginController@showLoginForm')->name('login_popup');
/*Route::get('connexion', 'Auth\LoginController@showLoginForm')->name('login');*/
Route::get('connexion/inscritnormal', 'Auth\LoginController@showLoginForm');
Route::post('signal_no_phone', 'AdDetailsController@signalNoPhone');
Route::get('/showPhoneCount/{ads_id}', 'AdDetailsController@countShowPhone')->name('showPhoneCount');
Route::get('/showFbContactCount/{ads_id}', 'AdDetailsController@showFbContactCount')->name('showFbContactCount');
Route::post('signal_no_fb', 'AdDetailsController@signalNoFb');
Route::post('signal_ad_loue', 'AdDetailsController@signalAdLoue');
Route::get('qui-sommes-nous', 'HomeController@quiSommesNous');
Route::get('recrutement', 'HomeController@recrutement');
Route::post('connexion', 'Auth\LoginController@login');
Route::post('connexionPopup', 'Auth\LoginController@loginPopup')->name('loginPopup');
Route::post('login_facebook', 'Auth\LoginController@loginFacebook');
Route::get('report_error', 'ErrorController@reportError');
Route::post('connexion/inscritnormal', 'Auth\LoginController@login');
Route::post('deconnecter', 'Auth\LoginController@logout')->name('logout');
Route::get('creer-compte/{ad_id?}', 'Auth\LoginController@createAccount')->name('register');
Route::get('sitemap.xml', 'PublicFolderController@sitemap')->name('sitemap');
Route::get('creer-profil-recherche', 'UsersController@creerProfilRecherche')->name('searchProfile');
Route::get('creer-profil-recherche/inscritnormal3', 'UsersController@creerProfilRecherche');
Route::get('creer-profil-recherche/inscritfb3', 'UsersController@creerProfilRecherche');
Route::get('creer-profil-recherche/inscritgoogle', 'UsersController@creerProfilRecherche');
Route::get('creer-profil-recherche/inscritlinkedin', 'UsersController@creerProfilRecherche');
Route::post('saveProfilRecherche', 'UsersController@saveProfilRecherche')->name('saveProfilRecherche');


Route::post('save-alert', 'SearchListingController@saveAlert')->name('save-alert');
Route::get('choisir-design/{type_design}', 'UsersController@chooseDesign')->name('chooseDesign');
Route::get('linkController', 'LinkController@healthCheck');

// Registration Routes...
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('/reinitialisation-mot-de-passe', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.resetForm');


Route::get('recaptcha', 'antiBotController@captcha')->name("recaptcha");
Route::get('img/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('images/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('uploads/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('js/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('css/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('scss/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('slick/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('icons/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('fonts/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('fontawesome/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('font/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('bootstrap-fileinput/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('pdf/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');
Route::get('xml/{file}', 'PublicFolderController@showPublicFile')->where('file', '.+');


Route::post('/check_coordonne_profil', 'UsersController@checkCoordoneAds');

Route::get('/publiez-annonce/{type?}', 'HomeController@postAds')->name("postAds")->middleware('InscriptionControle');

Route::get('/creer-compte/etape/1', 'HomeController@creerCompteStep1')->name('register');
Route::get('/creer-compte/etape/2', 'HomeController@creerCompteStep2')->name('registerStep2');
Route::get('/register_phone', 'HomeController@registerPhone')->name('registerPhone');
Route::post('/add_phone', 'HomeController@addPhone')->name('add_phone');
Route::get('/contact_admin', 'HomeController@contactAdmin')->name('contactAdmin');
Route::get('/signaler', 'HomeController@signaler')->name('signalerPhone');

Route::get('/load_register_form', 'HomeController@loadRegister');
Route::post('/save-temp-user-infos', 'HomeController@saveTempInfos');
Route::post('/save-raison-delete', 'UsersController@saveRaisonDelete');
Route::post('/payment/paypal', 'PayPalController@postPaymentWithpaypal')->name('paypal-payment');
Route::post('/payment/paypal/return', 'PayPalController@getPaypalPaymentStatus')->name('paypal-return');
Route::post('validate_recaptcha', 'antiBotController@verifyCaptcha')->name('validate_recaptcha');
Route::post('/getFbProfile', 'AdDetailsController@get_fb_profile')->name('getFbProfile');
Route::any('/home', 'HomeController@index');
Route::any('/home1', 'HomeController@index1');

Route::any('/', 'HomeController@index')->name('home');

Route::any('/reinitialisation-mdp-popup', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('reset_password_popup');
Route::any('/index.html', 'HomeController@indexHtml');
Route::get('/voir-profil-utilisateur/{id}/{ad_id?}', 'HomeController@viewUserProfil')->name('user-profil');
Route::get('/annonce-logement/{ville}/{lat}/{long}', 'HomeController@annonceLogement')->name("annonce.ville");
Route::get('/', 'HomeController@index');
Route::post('/data_ville', 'SearchListingController@adsVilles');
Route::get('/featured_data', 'HomeController@featuredData');
Route::get('/solution-pour-les-bailleurs', 'HomeController@bailleurSolution')->name('solution-bailleurs');
Route::get('/solution-pour-les-locataires', 'HomeController@locataireSolution')->name('solution-locataire');
Route::get('/change_lang/{locale}', 'HomeController@changeLang');
Route::post('/getVilleHome', 'HomeController@getVilleHome');
Route::post('/donner_avis/', 'UsersController@rate');
/*Route::get('/tous-les-avis', 'ReviewsController@index');*/
Route::get('/tableau-de-bord/{type?}', 'DashboardController@dashboard')->name('user.dashboard')->middleware('InscriptionControle');
Route::get('/region_slug', 'HomeController@regionSlug');
Route::get('/tableau-de-bord/inscritok', 'DashboardController@dashboardRegistration')->name('user.dashboard_registration')->middleware('InscriptionControle');
Route::post('/log_payment', 'SubscriptionController@logPayment');
Route::get('/parrainage', 'ParrainageController@index')->name('parrainage');
Route::post('/save_parainnage', 'UsersController@saveParainnage');
Route::post('/generate_ad_url', 'PropertyController@generateAdUrl');

//login and register facebook
Route::get('login/{driver}', 'UsersController@redirectToProvider'); //redirection
Route::get('login/{driver}/callback', 'UsersController@handleProviderCallback'); //callback
Route::any('/facebook-inscription/etape2', 'HomeController@fbRegister')->name("fb_register"); //recuperation num
Route::any('/save_fb_loyer', 'HomeController@saveFbLoyer')->name("save_fb_loyer"); //confirmation numero


Route::get('/rechercher-annonce/{id}', 'SearchListingController@searchAds')->name('searchad');
Route::any('/rechercher-annonce/', 'SearchListingController@searchAnnonce')->name('searchadScenId');
Route::get('/delete-ad/{id}', 'PropertyController@deleteAd')->name("delete-ad-comunity");
Route::get('/deactive-ad/{id}', 'PropertyController@deactiveAd')->name("deactive-ad-comunity");

Route::any('/searchads/map/{id}', 'SearchListingController@searchAdsMap')->name('searchad.map');
Route::get('/searchads-location/{location_id}/{scenario_id}', 'SearchListingController@searchAdsLocation')->name('searchadlocation');
Route::get('/searchads-location/map/{location_id}/{scenario_id}', 'SearchListingController@searchAdsLocationMap')->name('searchadlocation.map');
Route::get('/add_remove_favorites', 'AdDetailsController@addRemoveFavorites');
Route::get('/load_request_to_visit/{type}/{id}/{sender_ad_id}', 'AdDetailsController@loadRequestToVisit');
Route::post('/pagination_sponsorise', 'HomeController@PaginateSponsorisedAds');
Route::post('/save_request_to_visit', 'AdDetailsController@saveRequestToVisit');
Route::post('/message/mark_message_as_read', 'AdDetailsController@markMessageAsRead');
Route::post('/message/get_read_messages', 'AdDetailsController@getReadMessages');
Route::post('/message/get_new_messages', 'AdDetailsController@newMessagesSideBar');
Route::post('/notif/get_new_coup_de_foudre', 'HomeController@getNewCoupFoudre');
Route::post('/notif/count_notif', 'HomeController@getNbNotification');
Route::post('/notif/get_new_visit_request', 'HomeController@getNewVisitRequest');
Route::post('/notif/get_new_application', 'HomeController@getNewApplication');
Route::post('/notif/get_new_comment', 'HomeController@getNewComment');
Route::post('/flash/get_all_message_flash', 'AdDetailsController@getAllMessagesFlash');
Route::post('/message/mark_message_as_checked', 'HomeController@markMessagesAsCheckedNotif');
Route::get('/search/infos-annonce', 'SearchListingController@infosAnnoncePopup');
Route::post('/visit/is_allowed_user', 'AdDetailsController@isAllowedVisitRequest');
Route::post('/message/get_new_received_messages', 'HomeController@getNewReceivedMessage');
Route::post('/notif/get_new_registration', 'HomeController@getNewRegistration');
Route::post('/notif/get_new_subscription', 'HomeController@getNewSubscription');
Route::post('/save_message', 'AdDetailsController@saveMessage');
Route::post('/send_message_flash', 'AdDetailsController@sendMessageFlash');
Route::any('/messages-boite-reception/corbeille', 'AdDetailsController@viewMessageRoomsTrash')->name('viewmessagerooms.trash');
Route::any('/messages-boite-reception/{id?}', 'AdDetailsController@viewMessageRooms')->name('viewmessagerooms.inbox');
Route::post('/get_message_rooms_updates/', 'AdDetailsController@getMessageRoomUpdates');
Route::post('/get_active_thread_updates/', 'AdDetailsController@getActiveThreadUpdates');
Route::get('/get_message_updates/', 'AdDetailsController@getMessageUpdates');
Route::post('/archive_threads', 'AdDetailsController@archiveMessages')->name('archive.threads');
Route::post('/delete_threads', 'AdDetailsController@deleteMessages')->name('delete.threads');
Route::post('/signal/add', 'SignalController@add');
Route::post('/subscribeNewsletter', 'HomeController@subscribeNewsletter');
Route::post('/subscribeContact', 'HomeController@subscribeContact');
Route::post('/submit_code_promo', 'SubscriptionController@codePromo');
Route::get('/handle-paypal', 'SubscriptionController@HandlePaypal')->name("handlePaypal");
Route::get('/page404', 'HomeController@page404')->name("page404");

Route::post('/comment/add', 'CommentsController@add');
Route::get('/comment/get/{ad_id}', 'CommentsController@index');
Route::post('/comment/edit', 'CommentsController@edit');
Route::post('/message/is_allowed_message', 'AdDetailsController@isAllowedMessage');
Route::post('/message/get_all_message_notif', 'HomeController@getAllMessageNotif');
Route::post('/message/get_all_message_notif_controle', 'HomeController@getAllMessageNotifControle');
Route::post('/notif/get_all_toctoc', 'DashboardController@getAllCoupDeFoudreNotif');
Route::post('/get_user_ads', 'AdDetailsController@getUserAds');
Route::post('/message/get_all_coup_de_foudre', 'HomeController@getAllCoupDeFoudreNotif');
Route::post('/message/get_all_application', 'HomeController@getAllApplicationNotif');
Route::post('/message/get_all_comments', 'HomeController@getAllCommentsNotif');
Route::post('/message/get_all_visit_request', 'HomeController@getAllVisitRequestNotif');
Route::post('/comment/delete/{comment_id}', 'CommentsController@remove');
Route::post('/comment/voteup/{comment_id}', 'CommentsController@vote_up');
Route::post('/comment/votedown/{comment_id}', 'CommentsController@vote_down');
Route::get('/mes-commentaires', 'MyCommentsController@received');
Route::get('/mes-documents', 'MyDocumentsController@myDocuments')->name('user.documents');
Route::get('/mes-alertes', 'DashboardController@mesAlertes')->name('user.alerts');
Route::get('/desactiver-email/{id}', 'DashboardController@desactiverAlert');
Route::get('/delete-alert/{id}', 'DashboardController@deleteAlert');
Route::get('/activer-email/{id}', 'DashboardController@activerAlert');
Route::get('/view-alertes/{id}', 'DashboardController@viewAlertes')->name('view.alerts');
Route::get('/remove_documents', 'MyDocumentsController@removeDocuments');
Route::get('/remove_alerts', 'DashboardController@removeAlerts');
Route::get('/mes-commentaires/poste', 'MyCommentsController@posted');
Route::post('/my-comment/response/add', 'MyCommentsController@addResponse');
Route::post('/my-comment/response/edit', 'MyCommentsController@editResponse');
Route::post('/my-comment/response/delete/{response_id}', 'MyCommentsController@deleteResponse');
Route::post('/add_document', 'MyDocumentsController@addDocument');
Route::post('/edit_document', 'MyDocumentsController@editDocument');
Route::post('/delete_edit_file', 'MyDocumentsController@deleteEditDocument');

Route::get('/demandes/{type}/{ad_id}', 'RequestsController@requests');
Route::get('/accept_request_to_visit/{request_id}/{ad_id}', 'RequestsController@acceptRequest');
Route::get('/decline_request_to_visit/{request_id}/{ad_id}', 'RequestsController@declineRequest');
Route::get('/cancel_request_to_visit/{request_id}/{ad_id}', 'RequestsController@cancelRequest');

Route::post('/application/add', 'ApplicationController@add');
Route::post('/application/edit', 'ApplicationController@edit');
Route::post('/applicationfiles/{id}', 'ApplicationController@uploadFiles');
Route::get('/candidatures-annonce/{type}/{id}', 'ApplicationController@applications');
Route::post('/applications/submit', 'ApplicationController@submitApplication');
Route::get('/applications/get_updates', 'ApplicationController@getApplicationUpdate');
Route::get('/my-comment/get_updates', 'MyCommentsController@getCommentsUpdate');
Route::get('/mes-candidatures/envoyes/{type}', 'MyApplicationController@index')->middleware('InscriptionControle');
Route::any('/transfert_document', 'MyApplicationController@tranfert_document')->name('document.transfert')->middleware('InscriptionControle');
Route::get('/mes-candidatures/recu', 'MyApplicationController@received')->name('candidature.recu');
Route::get('/mes-candidatures/modifier-candidature/{id}', 'MyApplicationController@edit');
Route::post('/app_common_friend', 'MyApplicationController@common_friend_fb');

Route::any('/louer-une-propriete/{registerType?}', 'PropertyController@postAnAd')->name('rent.property');
Route::any('/partager-un-logement/{registerType?}', 'PropertyController@postAnAd')->name('rent.accommodation');
Route::any('/chercher-a-louer-une-propriete', 'PropertyController@postAnAd')->name('looking.property');
Route::any('/chercher-a-partager-un-logement', 'PropertyController@postAnAd')->name('looking.accommodation');
Route::any('/chercher-ensemble-une-propriete', 'PropertyController@postAnAd')->name('looking.partner');
Route::any('/publier-une-annonce/{id?}', 'PropertyController@postAnAd')->name('post.ad');

Route::get('/modifier-annonce/{id}', 'PropertyController@editAnAd')->name("modifier.ad");
Route::get('/desactiver-annonce/{id}', 'DashboardController@deactivateAd')->name("desactiver.ad");
Route::get('/activer-annonce/{id}', 'DashboardController@activateAd')->name("activer.ad");
Route::get('/reactiver-annonce/{id}.{email}', 'DashboardController@reactivateAdGet')->name("reactiver.ad.get");
Route::get('/reactiver-annonce/update/{id}.{email}', 'DashboardController@reactivateAdUpdate')->name("reactiver.ad.update");
Route::get('/supprimer-annonce/{id}', 'DashboardController@deleteAd')->name('delete.ad');


Route::post('ad/uploadfiles', 'PropertyController@uploadFiles');
Route::post('ad/deletefile', 'PropertyController@deleteFile');
Route::post('ad/deletefileUploaded', 'PropertyController@deleteFileUploaded');

Route::post('ad/uploadGuaranteeFiles', 'PropertyController@uploadGuaranteeFiles');
Route::get('ad/deleteGuaranteeFile', 'PropertyController@deleteGuaranteeFile');
Route::get('ad/deleteGuaranteeFileUploaded', 'PropertyController@deleteGuaranteeFileUploaded');

Route::post('/save_step_1', 'PropertyController@saveStep1')->name('save.step1');
Route::post('/save_step_m1', 'PropertyController@saveStepm1')->name('save.stepm1');

Route::post('/save_step_2', 'PropertyController@saveStep2')->name('save.step2');
Route::post('/save_step_3', 'PropertyController@saveStep3')->name('save.step3');

Route::post('/save_step_1_sc2', 'PropertyController@saveStep1Sc2')->name('save.step1.sc2');
Route::post('/save_step_2_sc2', 'PropertyController@saveStep2Sc2')->name('save.step2.sc2');
Route::post('/save_step_3_sc2', 'PropertyController@saveStep3Sc2')->name('save.step3.sc2');
Route::post('/save_step_4_sc2', 'PropertyController@saveStep4Sc2')->name('save.step4.sc2');

Route::post('/save-amount', 'SubscriptionController@saveAmount')->name('admin.save-amount');

Route::post('/save_step_1_sc3', 'PropertyController@saveStep1Sc3')->name('save.step1.sc3');
Route::post('/save_step_2_sc3', 'PropertyController@saveStep2Sc3')->name('save.step2.sc3');
Route::post('/save_step_3_sc3', 'PropertyController@saveStep3Sc3')->name('save.step3.sc3');

Route::post('/save_step_1_sc4', 'PropertyController@saveStep1Sc4')->name('save.step1.sc4');
Route::post('/save_step_2_sc4', 'PropertyController@saveStep2Sc4')->name('save.step2.sc4');
Route::post('/save_step_3_sc4', 'PropertyController@saveStep3Sc4')->name('save.step3.sc4');
Route::post('/save_step_4_sc4', 'PropertyController@saveStep4Sc4')->name('save.step4.sc4');

Route::post('/save_step_1_sc5', 'PropertyController@saveStep1Sc5')->name('save.step1.sc5');
Route::post('/save_step_2_sc5', 'PropertyController@saveStep2Sc5')->name('save.step2.sc5');
//  Call to undefined method Illuminate\Routing\RouteFileRegistrar::get();

Route::post('/ajax_login', 'UsersController@postLogin')->name('login.ajax');
Route::get('/save_all', 'PropertyController@saveAll')->name('save.all');


Route::post('/ajax_reg_validate_st1', 'UsersController@postRegisterSt1');
Route::post('/ajax_reg_validate_st2', 'UsersController@postRegisterSt2');
Route::post('/save-register-society', 'UsersController@registerStep2Society');
Route::post('/ajax_reg_validate_error', 'UsersController@postRegisterError');
Route::post('/postRegisterPhone', 'UsersController@postRegisterPhone');
Route::post('/bot_check_phon', 'UsersController@bot_check_phon');
Route::post('/phone_verify_ajax', 'UsersController@verify_phone_ajax');

Route::post('/ajax_reg_validate_st3', 'UsersController@postRegisterSt3');
Route::post('/ajax_reg_validate_st4', 'UsersController@postRegisterSt4');
Route::post('/rotate_image', 'PropertyController@rotateImage');
Route::post('/rotate_image_modif', 'PropertyController@rotateImageModif');
Route::post('/ajax_resend_verification', 'Auth\LoginController@resendMailVerif')->name('ajax_resend_verification');
Route::get('/users/verify/email/{token}', 'UsersController@verifyEmail');
Route::get('/creer-compte/etape/2/{token}', 'UsersController@verifyEtape2');
Route::post('/order_photos', 'PropertyController@orderPhotos');

Route::post('/verificationUser', 'Auth\LoginController@verificationUser')->name('verificationUser');
Route::get('/verificationUser', 'Auth\LoginController@verificationUser')->name('verificationUser_get');



Route::post('/ajax_get_cities', 'UsersController@getCities');
Route::any('/modifier-profile', 'UsersController@editProfile')->name('edit.profile')->middleware('InscriptionControle');
Route::any('/modifier-profile/inscritfb', 'UsersController@editProfile');
Route::any('/modifier-profile/inscritnormal', 'UsersController@editProfile');
Route::any('/modifier-profile/inscritgoogle', 'UsersController@editProfile');
Route::any('/modifier-profile/inscritlinkedin', 'UsersController@editProfile');
Route::any('/modifier-profile/inscritok', 'UsersController@editProfileInscription')->name('edit.profile-newUser');
Route::post('/get_return_page', 'AdDetailsController@getReturnPage');
Route::post('/ajax_post_edit_profile_1', 'UsersController@postEditProfileStep1');
Route::post('/ajax_post_edit_profile_2', 'UsersController@postEditProfileStep2');
Route::post('/ajax_post_edit_profile_3', 'UsersController@postEditProfileStep3');

#####
Route::post('/ajax_retirer_photo_profile', 'UsersController@changePhotoProfile');



Route::post('/ajax_post_edit_profile', 'UsersController@postEditProfile');
//Route::any('/change_email', 'UsersController@changeEmail')->name('edit.changeemail');
Route::any('/modifier-mot-de-passe', 'UsersController@changePassword')->name('edit.changepwd');
Route::get('/supprimer-profile', 'UsersController@deleteProfile')->name('delete.profile');

Route::get('/politique-de-confidentialite', 'HomeController@cdc');
Route::get('/condition-generale-utilisation', 'HomeController@cgu');
Route::any('/pub-click/{type?}', 'HomeController@insertPubClick');
Route::post('/process-payment', 'SubscriptionController@processP');
Route::get('/maintenance', 'HomeController@maintenance')->name('maintenance');
Route::get('/bloqued', 'Bloqued@bloqued')->name('bloquena');
Route::get('/bloquedUser', 'Bloqued@bloquedUser')->name('bloquedUser');
Route::post('/get-personal-data', 'UsersController@getPersonalData');
Route::post('/insert_user_badi', 'PropertyController@insertUserBadi');
Route::post('/contact-annonceur', 'HomeController@sendMessageAnnonceur');
Route::post('/save_adresse_annonce', 'PropertyController@saveAdressAnnonce')->name("save.adresse_annonce");

Route::get('/changement-langue', 'HomeController@basculer')->name("changement-langue");

Route::group(['prefix' => getConfig('admin_prefix'), 'namespace' => 'Admin'], function () {
    Route::middleware(['checkadminlogin'])->group(function () {
        Route::get('/', 'AdminLoginController@login');
        Route::get('login', 'AdminLoginController@login')->name('admin.login');
        Route::post('login', 'AdminLoginController@postLogin');
    });
    Route::get('/error-acces', 'CommunityController@ErrorAccesUrl')->name('admin.error-acces');
    Route::any('logout', 'AdminLoginController@postLogout')->name('admin.logout');
    Route::get('courbes', 'DashboardController@dashboardCourbes')->name('admin.courbes');
    Route::any('checkAd/{adId}/{status}', 'ManageAdsController@checkAd');
    Route::middleware(['adminauth'])->group(function () {
        Route::get('parainage', 'ManageUsersController@parainage')->name('admin.user.parainage');
        Route::get('boostAd', 'ManageAdsController@boostAd')->name('admin.boostAd');
        Route::get('active_data', 'ManageUsersController@activeData')->name('admin.active_data');
        Route::get('edit_static_page', 'StaticController@editStaticPage')->name('admin.edit_static_page');
        Route::post('saveStatic', 'StaticController@saveStaticPage')->name('admin.saveStatic');
        Route::get('/manage-debug/{value}', 'DashboardController@manageDebug')->name('admin.manage-debug');
        Route::get('/manage-verification-mail/{value}', 'DashboardController@manageVerifMail')->name('admin.manage-verification-mail');
        Route::get('/manage-payment/{value}', 'DashboardController@managePayment')->name('admin.stripe_checkout');
        Route::get('/toggle-payment-paypal/{value}', 'DashboardController@togglePayPal')->name('admin.toggle_paypal');
        //activer ou desactiver le Toctoc auto
        Route::get('/toctoc-auto/{status}', 'DashboardController@manageToctoc')->name('admin.toctoc_auto');
        Route::get('/notification-nbr-mail/{status}', 'DashboardController@manageNotificationMail')->name('admin.notification.mail');
        Route::get('/guest-search-listing-ads/{status}', 'DashboardController@manageGuestSearch')->name('admin.search.guest.ads');

        //suivi de mail
        Route::get('/mail_suivi', 'MailTracking@index')->name('admin.mail.suivi');

        Route::get('/telegram_pub/{type}/{value}', 'DashboardController@manageTelegramPub')->name('admin.telegram_pub');
        Route::get('/parameters', 'DashboardController@Config')->name('admin.parameters');
        Route::post('/save-config', 'DashboardController@saveConfig')->name('admin.save-config');
        Route::post('/ajax_edit_photo_couverture', 'DashboardController@editPhotoCouverture');

        Route::post('modify-profession', 'ManageUsersController@modifyProfession')->name('modify-profession');
        Route::post('save-notif-subscription', 'ManageNotifController@saveNotifSubscription')->name('admin.save-notif-subscription');
        Route::any('subscription_notif', 'ManageNotifController@subscriptionNotif')->name('admin.subscription_notif');
        Route::post('modify-school', 'ManageUsersController@modifySchool')->name('modify-school');
        Route::get('manage_avis', 'ManageUsersController@activeDeactiveAvis')->name('admin.activeAvis');
        Route::any('login/{user_id}', 'AdminLoginController@superAdminLogin')->name('admin.superAdmin');

        Route::get('dashboard', 'DashboardController@dashboard')->name('admin.dashboard');
        Route::get('dashboardStat', 'DashboardController@dashboardStat')->name('admin.dashboardStat');


        //page admin

        Route::get('ajout_variable', [DashboardController::class, 'ajout_variable'])->name('ajout_variable');
        Route::post('sauver_variable', [DashboardController::class, 'sauver_variable'])->name('sauver_variable');
        Route::get('ajout_valeur', [DashboardController::class, 'ajout_valeur'])->name('ajout_valeur');
        Route::post('sauver_valeur', [DashboardController::class, 'sauver_valeur'])->name('sauver_valeur');
        Route::get('supprimer_propriete', [DashboardController::class, 'supprimer_propriete'])->name('supprimer_propriete');
        Route::post('sauver_propriete', [DashboardController::class, 'sauver_propriete'])->name('sauver_propriete');
        Route::get('affiche_domaine', [DashboardController::class, 'affiche_domaine'])->name('affiche_domaine');
        Route::get('/editer_propriete/{id}', [DashboardController::class, 'editer_propriete'])->name('editer_propriete');
        Route::get('/editer_domaine/{id}', [DashboardController::class, 'editer_domaine'])->name('editer_domaine');
        Route::post('modifier_domaine', [DashboardController::class, 'modifier_domaine'])->name('modifier_domaine');
        Route::get('delete_property/{id}', [DashboardController::class, 'delete_property'])->name('delete_property');
        Route::get('supprimer_domaine/{id}', [DashboardController::class, 'supprimer_domaine'])->name('supprimer_domaine');
        Route::get('ajout_propriete', [DashboardController::class, 'ajout_propriete'])->name('ajout_propriete');
        Route::post('sauver_value', [DashboardController::class, 'sauver_value'])->name('sauver_value');
        Route::get('supprimer_valeur/{id}', [DashboardController::class, 'supprimer_valeur'])->name('supprimer_valeur');
        Route::get('/editer_valeur/{id}', [DashboardController::class, 'editer_valeur'])->name('editer_valeur');
        Route::post('sauver_modif', [DashboardController::class, 'sauver_modif'])->name('sauver_modif');
        Route::get('archivage', [DashboardController::class, 'archivage'])->name('admin.archivage');
        Route::get('archivageAds', [DashboardController::class, 'archivageAds'])->name('admin.archivageAds');
        Route::get('archivageProfile', [DashboardController::class, 'archivageProfile'])->name('admin.archivageProfile');



        //mail maintenance modification
        Route::get('mailmaintenance', 'DashboardController@mailmaintenance')->name('admin.mailmaintenance');
        Route::post('updatemailmaintenance', 'DashboardController@updatemailmaintenance')->name('admin.updatemailmaintenance');

        Route::get('code-promo', 'PromoCodeController@index')->name('admin.code-promo');
        Route::get('add-code-promo', 'PromoCodeController@newPromoCode')->name('admin.add-code-promo');
        Route::get('user_alert', 'ManageUsersController@userAlert')->name('admin.user_alert');
        Route::post('/saveCodePromo', 'PromoCodeController@savePromoCode')->name('admin.save-code-promo');
        Route::post('/check_ad_fb_link', 'CommunityController@checkAdByFbLink');
        Route::post('/check_ad_profil_info', 'CommunityController@checkAnnonceByProfilInfo');
        Route::get('siteusers', 'ManageUsersController@index')->name('admin.users');
        Route::get('professions', 'ManageUsersController@professions')->name('admin.professions');
        Route::get('message_delete', 'ManageUsersController@messageDelete')->name('admin.message_delete');
        Route::get('user_avis', 'ManageUsersController@userAvis')->name('admin.user_avis');
        Route::get('comunity-list', 'ManageUsersController@comunityList')->name('admin.user.comunity_list');
        Route::get('daily_interaction', 'ManageUsersController@dailyInteraction')->name('admin.daily_interaction');
        Route::get('signal', 'ManageSignalController@index')->name('admin.signal');
        Route::get('signal/do/{id}', 'ManageSignalController@updateSignalActivated')->name('admin.signaldo');
        Route::any('siteusers/activeDeactiveUser/{userId}/{status}', 'ManageUsersController@activeDeactiveUser')->name('admin.activeDeactiveUser');
        Route::any('siteusers/activeDeactiveUserP/{userId}/{status}', 'ManageUsersController@activeDeactiveUserP')->name('admin.activeDeactiveUserP');
        Route::any('siteusers/activeDeactiveUser2/{userId}/{status}', 'ManageUsersController@activeDeactiveUser2')->name('admin.activeDeactiveUser2');
        Route::any('siteusers/bloqued_ip_user/{user_id}', 'ManageUsersController@bloqued_user')->name('admin.bloqued_user');


        Route::any('activeDeactiveAds', 'CommunityController@activeDeactiveAds')->name('admin.activeDeactiveAds');

        Route::any('siteusers/Etape2activeDeactive/{userId}/{status}', 'ManageUsersController@Etape2activeDeactive')->name('admin.Etape2activeDeactive');
        Route::any('siteusers/deleteUser/{userId}', 'ManageUsersController@deleteUser');
        Route::any('siteusers/user_profile/{userId}', 'ManageUsersController@userProfile')->name('admin.user.user_profile');
        Route::any('siteusers/edit_profile/{userId}', 'ManageUsersController@editProfile')->name('admin.user.edit_profile');
        Route::any('add_user', 'ManageUsersController@addUser')->name('admin.user.add_user');
        Route::any('add_comunity', 'CommunityController@add_comunity')->name('admin.user.add_comunity');
        Route::any('test_cron', 'CommunityController@test');
        Route::post('/save_comunity', 'CommunityController@saveComunity')->name("save_comunity");
        Route::post('/save_user_profile', 'ManageUsersController@saveUserProfile');
        Route::get('signal/delete/{id}', 'ManageSignalController@delete')->name('admin.signal.delete');
        Route::get('signal/remove/{id}', 'ManageSignalController@delete_signal')->name('admin.signal.delete.signal');
        Route::any('manage_ads', 'ManageAdsController@index')->name('admin.ads');
        Route::any('add_featured_city', 'FeaturedCityController@index')->name('admin.featuredcity.addFeaturedCity');
        Route::post('/ajax_get_cities_from_location', 'FeaturedCityController@getCityNameFromLocation');
        Route::get('featured_city_list', 'FeaturedCityController@featuredCityList')->name('admin.featuredCityList');
        Route::any('activeDeactiveFeaturedCity/{featuredCityId}/{status}', 'FeaturedCityController@activeDeactiveFeaturedCity');
        Route::any('deleteFeaturedCity/{featuredCityId}', 'FeaturedCityController@deleteFeaturedCity');
        //url point of proximity
        Route::resource('proximity', 'PointProximityController');

        Route::any('adList', 'ManageAdsController@index')->name('admin.adList');
        Route::any('contactAlert', 'ManageAdsController@indexAlert')->name('admin.contactAlert');

        Route::any('trakingAd/{type}', 'ManageAdsController@trakingAd')->name('admin.trakingAd');
        Route::any('popularAd/{type}', 'ManageAdsController@popularAd')->name('admin.popularAd');
        Route::any('signalAd/{type}', 'ManageAdsController@signalAd')->name('admin.signalAd');
        Route::any('botList', 'antiBotController@index')->name('admin.botList');
        Route::any('admin-create-ad', 'CommunityController@index')->name('admin.create-ad');
        Route::any('list-ad-community', 'CommunityController@listAd')->name('admin.list-ad-community');
        Route::post('/admin-create-ad', 'CommunityController@saveAd')->name('admin.post-create-ad');
        Route::post('comunity-uploadfiles', 'CommunityController@uploadFilesComunity');
        Route::post('/ajax_mark_ad_as_featured', 'ManageAdsController@markAdAsFeatured');
        Route::any('siteusers/activeDeactiveAd/{adId}/{status}', 'ManageAdsController@activeDeactiveAd');
        Route::post('DeactiveAdSignalAd', 'ManageSignalController@DeactiveAd')->name("DeactiveAdSignalAd");
        Route::any('treatSignalAd/{signalId}/{status}', 'ManageSignalController@treatAd');

        Route::any('package_list', 'PackagesController@index')->name('admin.packageList');
        Route::any('new_upselling', 'PackagesController@newUpselling')->name('admin.new_upselling');
        Route::post('save-upselling', 'PackagesController@saveUpselling')->name("admin.save_upselling");
        Route::any('upselling_list', 'PackagesController@listUpselling')->name('admin.upselling_list');
        Route::any('boosted_ads', 'PackagesController@listBoostedAds')->name('admin.boosted_ads');
        Route::any('payment_by_city', 'PackagesController@paymentByCity')->name('admin.payment_by_city');
        Route::any('fb_pixel', 'FbController@index')->name('admin.fb_pixel');
        Route::any('edit_fb_pixel', 'FbController@editPixelId')->name('admin.edit_fb_pixel');
        Route::any('edit_bitly_token', 'CommunityController@editBitlyToken')->name('admin.edit_bitly_token');
        Route::any('code-groupe-tictachouse', 'groupController@index')->name('admin.code_group');
        Route::any('modifier-code-groupe', 'groupController@editCodeGroup')->name('admin.edit_code_group');
        //        Route::any('add_package', 'PackagesController@addPackage')->name('admin.addPackage');
        Route::any('edit_package/{id}', 'PackagesController@editPackage')->name('admin.editPackage');
        Route::get('edit_package_status/{id}/{status}', 'PackagesController@activateDeactivatePackage')->name('admin.packagestatus');
        Route::any('add_new_blog', 'BlogController@addNewBlog')->name('admin.addnewblog');
        Route::get('blog_list', 'BlogController@blogListing')->name('admin.bloglisting');
        Route::post('/mark_blog_as_featured', 'BlogController@markBlogAsFeatured');
        Route::post('/edit_page_text', 'StaticController@editPageText');

        Route::get('/get-traduction/{id}', 'StaticController@getTraduction');
        Route::get('/cancel-traduction/{id}', 'StaticController@cancelPending')->name('admin.traduction.cancel.traduction');
        Route::get('/list-proposition-traduction', 'ValidationController@index')->name('admin.list.traduction.validation');
        Route::post('/proposition-traduction', 'ValidationController@valide')->name('admin.traduction.validation');
        Route::post('/invalide-traduction', 'ValidationController@invalide')->name('admin.traduction.invalide');


        Route::get('mot_cles', 'StaticController@MotCles')->name('admin.mot_cles');
        Route::any('add_new_mot_cles', 'StaticController@addMotCles')->name('admin.add_new_mot_cles');
        Route::any('edit_blog/{id}', 'BlogController@editBlog')->name('admin.editblog');
        Route::get('edit_blog_status/{id}/{status}', 'BlogController@activateDeactivateBlog')->name('admin.blogstatus');
        Route::get('delete_blog/{id}', 'BlogController@deleteBlog')->name('admin.deleteblog');
        Route::any('add_new_page', 'StaticController@addNewPage')->name('admin.addnewpage');
        Route::get('page_list', 'StaticController@pageListing')->name('admin.pagelisting');
        Route::get('page_text_list', 'StaticController@pageTextListing')->name('admin.pagetextlisting');
        Route::any('edit_page/{id}', 'StaticController@editPage')->name('admin.editpage');
        Route::get('edit_page_status/{id}/{status}', 'StaticController@activateDeactivatePage')->name('admin.pagestatus');
        Route::get('user_package_list', 'ManageUsersController@userPackages')->name('admin.user_package_list');
        Route::get('delete_page/{id}', 'StaticController@deletePage')->name('admin.deletepage');
        Route::any('all_daily_report', 'CommunityController@AllDailyReport')->name('admin.all_report');
        Route::any('daily_report_list', 'CommunityController@dailyReportList')->name('admin.daily_report_list');
        Route::any('new_daily_report', 'CommunityController@add_daily_report')->name('admin.new_daily_report');
        Route::any('manage_report_field', 'CommunityController@manageReportField')->name('admin.manage_report_field');
        Route::any('manage_task_category', 'CommunityController@manageTaskCategory')->name('admin.manage_task_category');
        Route::any('manage_profile', 'CommunityController@manageProfile')->name('admin.manage_profile');
        Route::any('premium_phrase', 'PackagesController@premiumPhrase')->name('admin.premium_phrase');
        Route::any('link_phrase', 'CommunityController@linkPhrase')->name('admin.link_phrase');
        Route::any('regex', 'CommunityController@regex')->name('admin.regexEdit');
        Route::any('active_deactivated_element', 'ElementsController@listElements')->name('admin.active_deactivated_element');
        Route::any('user_badi', 'ManageUsersController@userBadi')->name('admin.user-badi');
        Route::post('/save_daily_report', 'CommunityController@saveDailyReport')->name('admin.save-report');
        Route::post('/save-report-data', 'CommunityController@saveReportData')->name('admin.save-report-data');
        Route::post('/save_daily_report_field', 'CommunityController@saveDailyReportField')->name('admin.save-report-field');
        Route::post('/save-category', 'CommunityController@saveCategory')->name('admin.save-category');
        Route::post('/save-profile', 'CommunityController@saveProfile')->name('admin.save-profile');

        Route::post('/dashboard-chart-data', 'DashboardController@dashChartData')->name('admin.chart-data');
        Route::post('/dashboard-chart-registration', 'DashboardController@dashChartData2')->name('admin.chart-registration');
        Route::post('/dashboard-chart-registrationh', 'DashboardController@dashChartData2h')->name('admin.chart-registrationh');

        Route::post('/dashboard-chart-data-ville', 'DashboardController@dashChartDataVille')->name('admin.chart-data-ville');

        Route::post('/dashboard-chart-comunity-data', 'DashboardController@dashChartDataComunity')->name('admin.chart-comunity-data');
        Route::post('/dashboard-chart-pub-click', 'DashboardController@dashChartPubClick')->name('admin.chart-pub-click');
        Route::post('/dashboard-chart-register', 'DashboardController@registerChartData')->name('admin.chart-register');
        Route::post('/manage-link-phrase', 'CommunityController@managePhrase')->name('admin.manage-link-phrase');
        Route::post('/manage-regex', 'CommunityController@manageRegex')->name('admin.manage-regex');
        Route::post('/manage-phrase', 'PackagesController@managePhrase')->name('admin.manage-phrase');
        Route::post('/manage-elements', 'ElementsController@manageElements')->name('admin.manage-elements');
        Route::post('/save-raison-deactive', 'ManageAdsController@saveRaisonDeactive');
        Route::get('indicators', 'DashboardController@indicators')->name('admin.indicators');
        Route::get('link_source', 'DashboardController@linkSource')->name('admin.link_source');



        Route::get('click_toctoc_mail', 'DashboardController@TocTocClickMail')->name('admin.click_toctoc_mail');
        Route::get('siteusers/set_to_premium', 'ManageUsersController@setToPremium')->name('admin.set_to_premium');
        Route::get('contact_show', 'DashboardController@contactShow')->name('admin.contact_show');
        Route::get('block_ip', 'ManageUsersController@blockIp')->name('admin.block_ip');
        Route::post('/save-ip', 'ManageUsersController@saveIp')->name('admin.save-ip');
        Route::get('/delete-ip/{id}', 'ManageUsersController@deleteIp')->name('admin.delete-ip');
        Route::post('/save-texte', 'StaticController@saveNewTexte')->name('admin.save-texte');
        Route::get('/community_publication', 'CommunityController@pubCommunity')->name('admin.pub_community');

        Route::get('/monthly_check', 'CommunityController@monthlyCheck')->name('admin.monthly_check');
        Route::get('/dateWork', 'CommunityController@dateWork')->name('admin.date_work');
        Route::get('/list_by_community', 'CommunityController@pubByCommunity')->name('admin.list_by_community');
        Route::post('/save_publication_comunity', 'CommunityController@SavePublication');
        Route::post('/save_all_publication_comunity', 'CommunityController@SaveAllPublication')->name('admin.save_all_publication_comunity');
        Route::get('/list_community_publication', 'CommunityController@pubCommunityList')->name('admin.pub_community_list');
        Route::get('/list_community_check', 'CommunityController@pubCommunityListCheck')->name('admin.pub_community_list_check');
        Route::get('/list_community_check_Rouge_status', 'CommunityController@communitylistRougeStatus')->name('admin.listStatRouge');
        Route::get('/list_community_check_active_status', 'CommunityController@communitylistActiveStatus')->name('admin.listStatActive');
        Route::get('/courbe_community_active_status', 'CommunityController@graphcommunitylistActiveStatus')->name('admin.courbeStatActive');;
        Route::post('/community-chart-data-status', 'CommunityController@listStatVerifieData')->name('admin.chart-data-status');
        //Route pour l'emploi du temps du comunity
        Route::get('/emploi_temps_comunity', 'CommunityController@emploi_temp_comunity')->name('admin.emploi_temp_comunity');
        Route::post('/save_emploi_temps', 'CommunityController@saveEmploiTempComunity')->name('admin.saveEmploiTemps');
        Route::get('/delete_emploi_temps/{id_heure}', 'CommunityController@deleteEmploiTemps')->name('admin.delete_emploi_temps');
        Route::post('/update_emploi_temps/{id_heure}', 'CommunityController@updataEmploiTemps')->name('admin.updateEmploiTemps');
        //Route pour l'email de recepion de l'erreur sur le site
        Route::get('/Mail_Error_Admin', 'CommunityController@test_mail_admin')->name('admin.mail_error_admin');
        Route::post('/saveEmailError', 'CommunityController@saveEmailError')->name('admin.saveEmailError');
        Route::post('/updateEmailError/{id}', 'CommunityController@updateEmailError')->name('admin.updateEmailError');
        Route::get('/deletEmailError/{id}', 'CommunityController@deleteEmailError')->name('admin.deletEmailError');
        //Route pour la facturation
        Route::get('/Gerer_Facture', 'CommunityController@gerer_facture')->name('admin.gerer_facture');
        Route::post('/facture', 'CommunityController@facturation')->name('admin.facture');
        Route::post('/searchUserSansmail', 'CommunityController@searchUserSansmail')->name('admin.searchUserSansmail');
        Route::post('/factureSansMail', 'CommunityController@factureSansMail')->name('admin.factureSansMail');
        //Liste et envoi des toctoc
        Route::get('/liste_toctoc/{order_by?}', 'CommunityController@liste_toctoc')->name('liste_toctoc');
        Route::any('/toctocListHome', [App\Http\Controllers\Admin\CommunityController::class, 'toctocListHome'])->name('toctocListHome');
        Route::any('/toctocListColocation', [App\Http\Controllers\Admin\CommunityController::class, 'toctocListColocation'])->name('toctocListColocation');


        Route::get('/send_toctoc', 'CommunityController@send_toctoc')->name('admin.send_toctoc');

        Route::get('/stat_community_publication', 'CommunityController@statCommunityList')->name('admin.stat_community_list');
        Route::get('/convertir', 'CommunityController@convertir');
        Route::get('/convertir2', 'CommunityController@convertir2');
        Route::get('/convertir3', 'CommunityController@convertir3');

        Route::get('/verification', 'CommunityController@verification');
        Route::get('/statFacebook', 'CommunityController@statFacebook')->name('admin.stat_community_statFacebook');

        Route::get('/statCommunityFb', 'CommunityController@statCommunityFb')->name('admin.stat_community_statCommunityFb');
        Route::get('/statParCommunity', 'CommunityController@statParCommunity')->name('admin.stat_community_statParCommunity');
        Route::get('/statParAdsCommunity', 'CommunityController@statParAdsCommunity')->name('admin.stat_community_statParAdsCommunity');

        Route::get('/doublons', 'CommunityController@doublons')->name('admin.stat_community_doublons');
        Route::get('/indicateur_vente', 'CommunityController@indicateurVente')->name('admin.indicateur_vente');

        Route::get('/lienGroup', 'CommunityController@lienGroup');
        Route::get('/page/{page}', 'addPageController@index');

        Route::post('/save-edit-pub', 'CommunityController@saveEditPub')->name('admin.save-edit-pub');
        Route::get('/delete_pub/{id}', 'CommunityController@deletePub')->name('admin.deletePub');
        Route::get('/add_module/{id?}', 'AdminModuleController@addModule')->name('admin.add_module');
        Route::post('/save_module', 'AdminModuleController@saveModule')->name('admin.save_module');
        Route::get('/add_page_module/{id?}', 'AdminModuleController@addModulePage')->name('admin.add_module_page');
        Route::post('/save_module_page', 'AdminModuleController@saveModulePage')->name('admin.save_module_page');
        Route::get('/list_module', 'AdminModuleController@listModule')->name('admin.list_module');
        Route::get('/list_module_page', 'AdminModuleController@listPageModule')->name('admin.list_modul_page');
        Route::get('/delete_module/{id}', 'AdminModuleController@deleteModule')->name('admin.delete_module');
        Route::get('/delete_page_module/{id}', 'AdminModuleController@deletePageModule')->name('admin.delete_page_module');
        Route::get('/list_role', 'AdminModuleController@listRoles')->name('admin.list_role');
        Route::get('/add_role/{id?}', 'AdminModuleController@addRoles')->name('admin.add_role');
        Route::post('/save_role', 'AdminModuleController@saveRoles')->name('admin.save_role');
        Route::get('/delete_role/{id}', 'AdminModuleController@deleteRoles')->name('admin.delete_role');
        Route::get('/manage-sposored-ads/{value}', 'DashboardController@manageSposorisedAds')->name('admin.manage-sponsorised-ads');
        Route::get('/details-approode-ads', 'ManageAdsController@approoveAdsDetails')->name('admin.approove_ads_details');
        Route::get('/maintenance/{value}', 'DashboardController@manageMaintenance')->name('admin.maintenance');
        Route::get('/tva/{value}', 'DashboardController@tva')->name('admin.tva');


        Route::get('/icone_fb/{value}', 'DashboardController@manageIconeFB')->name('admin.icone_fb');

        Route::get('/multilangue/{value}', 'DashboardController@manageMultilangue')->name('admin.multilangue');

        # Google AdSense
        Route::get('/google_adsense/{value}', 'DashboardController@manageGoogleAdsense')->name('admin.google_adsense');

        # Routes Annonces expires
        Route::get('/delay-annonces-list', 'AdDelayController@list')->name('delay.annonce.list');
        Route::get('/delay-annonces-mail/all', 'AdDelayController@mailAll')->name('delay.annonce.mail.all');

        // page_admin
    });
});


Route::get('/contacter-annonce/{id}', 'PropertyController@contactAnnonce');
Route::get('/postuler-annonce/{id}', 'AdDetailsController@applyToAds');
Route::get('/demander-visite/{id}', 'AdDetailsController@SendVisitRequest');
Route::any('/admin/{page_not_found}', 'HomeController@adminPage404');
Route::any('/mes-favoris', 'FavoritesController@favoritesList')->name('favorites_list');
Route::any('/confirmation-boost-stripe/{adId}/{ups}/{amount}', 'SubscriptionController@confirmBoostStripe')->name('confirmation-boost-stripe');
Route::any('/confirmation-du-paiement', 'SubscriptionController@confirm')->name('paiement-confirmation');
Route::any('/confirmation-boost-paiement/{paymentId}', 'SubscriptionController@confirmBoost')->name('boost-paiement-confirmation');
Route::any('/confirmation-creation-annonce', 'PropertyController@confirmAnnonce')->name('confirm-ad-creation');
Route::any('/subscription_plan/{id?}', 'SubscriptionController@subscriptionPlan')->name('subscription_plan');
Route::post('/get_user_phone', 'SearchListingController@getUserPhone');
Route::any('/payment/{packageId?}', 'SubscriptionController@showPaymentForm')->name('payment');
Route::any('/process_payment', 'SubscriptionController@verifierPayment')->name('process_payment');
Route::any('/booster-annonce/{id}', 'DashboardController@boostAd')->name('boost_ad');
Route::any('/boost_ad_payment', 'SubscriptionController@boostAdPayment')->name('boostAdPayment');
Route::any('/offre/{scenario}/{ville}/{region}', 'SearchListingController@viewSearchResults')->name('searchoffre.ad');
Route::any('/demande/{scenario}/{ville}/{region}', 'SearchListingController@viewSearchDemandeResults')->name('searchdemande.ad');
Route::any('/check_ad_and_user_subscription', 'AdDetailsController@isUserAdUserSubscription');
Route::any('/check_ad_and_user_subscription_phone', 'AdDetailsController@isUserAdUserSubscriptionPhone');

//Route::get('create_url_slug_ad', 'AdDetailsController@createURLSlugAd');
Route::get('/blog', 'BlogController@blogListing')->name('bloglisting');
//Route::get('/{url_slug}', 'HomeController@staticPage')->name('staticpage');
Route::get('/faq', 'HomeController@faq')->name('bloglisting');


Route::get('/mailable', function () {
    $subject = 'Registered with BlaBlaRent';

    $UserName = "Rishi Sharma";
    $VerificationLink = "www.gmail.com";

    //return new App\Mail\UserRegistration($subject, $UserName, $VerificationLink);
});

Route::get('/site/shutdown', function () {
    return Artisan::call('down');
});
Route::get('site/live', function () {
    return Artisan::call('up');
});


Route::post('/list-school', 'HomeController@listSchool');
Route::post('/list-metro', 'HomeController@listMetro');
Route::post('/notif_admin', 'HomeController@NotifBailti');
Route::post('/list-metier', 'HomeController@listMetier');
Route::get('/view-profile/{user}/{adId?}', 'UsersController@viewProfile')->name('view.profile');
Route::get('/adresse-annonce/{type?}', 'PropertyController@postAnAd')->name('step-address-annonce');
Route::get('/adresse-annonce/inscritnormal4', 'PropertyController@postAnAd');
Route::get('/adresse-annonce/inscritfb4', 'PropertyController@postAnAd');
Route::get('/adresse-annonce/inscritgoogle', 'PropertyController@postAnAd');
Route::get('/adresse-annonce/inscritlinkedin', 'PropertyController@postAnAd');
Route::get('/adresse-annonce/profilnormalok', 'PropertyController@postAnAd');
Route::get('/adresse-annonce/profilfbok', 'PropertyController@postAnAd');
Route::get('/adresse-annonce/profilgoogleok', 'PropertyController@postAnAd');
Route::get('/adresse-annonce/profillinkedinok', 'PropertyController@postAnAd');
Route::get('/register_with_ad', 'UsersController@registerWithAd')->name('registerWithAd');
Route::get('/duplicate/{id}', 'AdDetailsController@duplicateAd')->name('duplicate.ad');
Route::post('/save_data_return', 'AdDetailsController@saveReturnInfos');
Route::get('/contactez-nous', 'HomeController@contactUs')->name('contact.us');
Route::get('/desindexe/{id}', 'AdDetailsController@desindexeAd')->name('desindexe.ad');
Route::get('/indexe/{id}', 'AdDetailsController@indexeAd')->name('indexe.ad');
Route::get('/{scenario}/{ville}', 'AdDetailsController@viewAd')->name('view.ad'); //ici
Route::post('/create-checkout-session', 'SubscriptionController@createCheckoutSession')->name('package.checkout_session');
Route::post('/create-checkout-session-stockage', 'SubscriptionController@createCheckoutSessionStockage')->name('package.checkout_session.stockage')->middleware('InscriptionControle');
Route::post('/create-checkout-session-boost', 'SubscriptionController@createCheckoutSessionBoost')->name('package.checkout_session_boost');

/*Route::get('/colocation/{ville}', 'HomeController@index')->name('villeAcceuil');*/

Route::any('/{scenario}/{ville}/{region}/profilnormalok', 'SearchListingController@viewSearchResults')->name('search.ad')->middleware('InscriptionControle');
Route::any('/{scenario}/{ville}/{region}/profilgoogleok', 'SearchListingController@viewSearchResults')->name('search.ad')->middleware('InscriptionControle');
Route::any('/{scenario}/{ville}/{region}/profilfbok', 'SearchListingController@viewSearchResults')->name('search.ad')->middleware('InscriptionControle');
//Route::any('/{scenario}/{ville}/{region}/inscritnormal3', 'SearchListingController@viewSearchResults')->name('search.ad')->middleware('InscriptionControle');
Route::any('/{scenario}/{ville}/{region}/inscritnormal3', 'SearchListingController@viewSearchResults')->name('search.ad');

Route::any('/{scenario}/{ville}/{region}/inscritfb3', 'SearchListingController@viewSearchResults')->name('search.ad')->middleware('InscriptionControle');
Route::any('/{scenario}/{ville}/{region}', 'SearchListingController@viewSearchResults')->name('search.ad')->middleware('InscriptionControle');
Route::any('/demande/{scenario}/{ville}/{region}/{registerType?}', 'SearchListingController@viewSearchDemandeResults')->name('searchdemande.ad')->middleware('InscriptionControle');
/*Route::any('/{scenario}/{ville}/{region}/inscritnormal', 'SearchListingController@viewSearchResults')->name('search.inscritnormal');
Route::any('/{scenario}/{ville}/{region}/inscritgoogle', 'SearchListingController@viewSearchResults')->name('search.inscritgoogle');
Route::any('/{scenario}/{ville}/{region}/inscritfb', 'SearchListingController@viewSearchResults')->name('search.inscritfb');
Route::any('/{scenario}/{ville}/{region}/inscritlinkedin', 'SearchListingController@viewSearchResults')->name('search.inscritlinkedin');*/
Route::get('{year}/{month}/{url_slug}/{id}', 'BlogController@blogDetail')->name('blogdetail');
Route::get('/{scenario}', 'AdDetailsController@viewAd');

/*Route::get('/{villePage}', 'HomeController@villePage');*/
