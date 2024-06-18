<?php

namespace App\Http\Controllers;

use App\quittance;
use App\SignatureUsers;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use PDF;
use DateTime;
use App\Finance;
use App\Location;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\UserProfiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Property\PropertyTypes;

class quittanceController extends Controller
{
  public function index($id)
  {
    $id = Crypt::decrypt($id);
    $user_id = Auth::user()->id;
    $location = Finance::where('user_id', $user_id)->with(['Locataire', 'Logement'])->where('id', $id)->first();
    $numbre = UserProfiles::where('user_id', $user_id)->first();
      $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();
      $signature = [];
      if (isset($signatureProprietaire->name_file)) {
          $path = storage_path('/uploads/signature/' . $signatureProprietaire->name_file);
          if (File::exists($path)) {
              $desiredWidth = 300;
              $desiredHeight = 150;
              $image = Image::make($path);
              $image->resize($desiredWidth, $desiredHeight);
              $image->save();
              $imageSize = getimagesize($path);
              $imageWidth = $imageSize[0];
              $imageHeight = $imageSize[1];
              // Calculer les nouvelles dimensions
              $desiredWidth = 130; // Largeur souhaitée
              $desiredHeight = ($imageHeight / $imageWidth) * $desiredWidth; // Calculer la hauteur proportionnelle
              // Insérer l'image en spécifiant les nouvelles dimensions
              $signature ['path'] = '/uploads/signature/' . $signatureProprietaire->name_file;
              $signature ['desiredWidth'] = $desiredWidth;
              $signature ['desiredHeight'] = $desiredHeight;
          }
      }

      return view('locataire.quittance', compact( 'location', 'numbre','signature'));
  }


    public function send($id)
    {
        $id = Crypt::decrypt($id);
        $finance = Finance::where('id', $id)->with(['Logement', 'Locataire', 'Location'])->first();
        $bien = $finance->Logement->identifiant;
        $locataire_email = $finance->Locataire->TenantEmail;
        $locataire_destinataire_id = $finance->Locataire->user_account_id;
        $location_id = $finance->location_id;
        $finance_id = $finance->id;

        $user_id = Auth::user()->id;
        $location = Finance::where('user_id', $user_id)->with(['Locataire', 'Logement'])->where('id', $id)->first();
        $numbre = UserProfiles::where('user_id', $user_id)->first();
        $proprietaire = DB::table('users')->where('id', $location->user_id)->first();
        $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();
        $signature = [];
        if (isset($signatureProprietaire->name_file)) {
            $path = storage_path('/uploads/signature/' . $signatureProprietaire->name_file);
            if (File::exists($path)) {
                $desiredWidth = 300;
                $desiredHeight = 150;
                $image = Image::make($path);
                $image->resize($desiredWidth, $desiredHeight);
                $image->save();
                $imageSize = getimagesize($path);
                $imageWidth = $imageSize[0];
                $imageHeight = $imageSize[1];
                // Calculer les nouvelles dimensions
                $desiredWidth = 130; // Largeur souhaitée
                $desiredHeight = ($imageHeight / $imageWidth) * $desiredWidth; // Calculer la hauteur proportionnelle
                // Insérer l'image en spécifiant les nouvelles dimensions
                $signature ['path'] = $path;
                $signature ['desiredWidth'] = $desiredWidth;
                $signature ['desiredHeight'] = $desiredHeight;
            }
        }
        $fichier = PDF::loadView('locataire.quittanceFichier', compact('location', 'numbre', 'proprietaire', 'signature'));

        //nom de quittance obtenu
        $nom_fichier = 'Quittance_' . $finance->Location->identifiant;

        //  sauvegarder de quittqnce dans dossier
        $path_to_file = public_path('uploads/Finance/') . $nom_fichier . '.pdf';
        $fichier->save($path_to_file);

        //sauvegarde dans bdd
        $quittance = new quittance();
        $quittance->bien = $bien;
        $quittance->location_id = $location_id;
        $quittance->finance_id = $finance_id;
        $quittance->user_id_destinataire = $locataire_destinataire_id;
        $quittance->user_id_sender = Auth::user()->id;
        $quittance->montant = $finance->montant;
        $quittance->description = 'Loyer';
        $quittance->quittance = $nom_fichier . '.pdf';
        $quittance->save();

        //notification de locataire
        Mail::raw('Bonjour, Votre quittance de loyer est disponible en téléchargement, veuillez consulter votre espace locataire.', function (Message $message) use ($locataire_email) {
            $message->to($locataire_email);
            $message->subject('Message reçu');
        });

        toastr()->success(__("finance.receipt_sent"));

        return back();
    }

  public function voirPdf($id)
  {
    // $id = Crypt::decrypt($id);
    $user_id = Auth::user()->id;
    $proprietaire = DB::table('users')->where('id', $user_id)->first();
    $location = Finance::where('user_id', $user_id)->with(['Locataire', 'Logement'])->where('id', $id)->first();
    $numbre = UserProfiles::where('user_id', $user_id)->first();
      $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();
      $signature = [];
      if (isset($signatureProprietaire->name_file)) {
          $path = storage_path('/uploads/signature/' . $signatureProprietaire->name_file);
          if (File::exists($path)) {
              $desiredWidth = 300;
              $desiredHeight = 150;
              $image = Image::make($path);
              $image->resize($desiredWidth, $desiredHeight);
              $image->save();
              $imageSize = getimagesize($path);
              $imageWidth = $imageSize[0];
              $imageHeight = $imageSize[1];
              // Calculer les nouvelles dimensions
              $desiredWidth = 130; // Largeur souhaitée
              $desiredHeight = ($imageHeight / $imageWidth) * $desiredWidth; // Calculer la hauteur proportionnelle
              // Insérer l'image en spécifiant les nouvelles dimensions
              $signature ['path'] = $path;
              $signature ['desiredWidth'] = $desiredWidth;
              $signature ['desiredHeight'] = $desiredHeight;
          }
      }
      $pdf = PDF::loadView('locataire.quittanceFichier', compact('location', 'numbre', 'proprietaire', 'signature'));
      $nom_fichier = __('finance.Quittance_de_loyer');

    return $pdf->download($nom_fichier.'.pdf');
  }
  public function avisEcheance($id)
  {
    $id = Crypt::decrypt($id);
    $user_id = Auth::user()->id;
    $location = Finance::where('user_id', $user_id)->with(['Locataire', 'Logement'])->where('id', $id)->first();
    $numbre = UserProfiles::where('user_id', $user_id)->get();
      $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();
      $signature = [];
      if (isset($signatureProprietaire->name_file)) {
          $path = storage_path('/uploads/signature/' . $signatureProprietaire->name_file);
          if (File::exists($path)) {
              $desiredWidth = 300;
              $desiredHeight = 150;
              $image = Image::make($path);
              $image->resize($desiredWidth, $desiredHeight);
              $image->save();
              $imageSize = getimagesize($path);
              $imageWidth = $imageSize[0];
              $imageHeight = $imageSize[1];
              // Calculer les nouvelles dimensions
              $desiredWidth = 130; // Largeur souhaitée
              $desiredHeight = ($imageHeight / $imageWidth) * $desiredWidth; // Calculer la hauteur proportionnelle
              // Insérer l'image en spécifiant les nouvelles dimensions
              $signature ['path'] = '/uploads/signature/' . $signatureProprietaire->name_file;
              $signature ['desiredWidth'] = $desiredWidth;
              $signature ['desiredHeight'] = $desiredHeight;
          }
      }


    return view('locataire.echeance', compact( 'location', 'numbre', 'signature'));
  }

  public function showEcheance($id)
  {
    // $id = Crypt::decrypt($id);
    $user_id = Auth::user()->id;
    $location= Finance::where('user_id', $user_id)->with(['Locataire', 'Logement'])->where('id', $id)->first();
    // $donnes=Finance::where('id',$id)->get();
    $proprietaire = DB::table('users')->where('id', $user_id)->first();
    $numbre = UserProfiles::where('user_id', $user_id)->first();
    $nom_fichier = __('finance.Avis_échéance');

      $signatureProprietaire = SignatureUsers::where('user_id', Auth::user()->id)->first();
      $signature = [];
      if (isset($signatureProprietaire->name_file)) {
          $path = storage_path('/uploads/signature/' . $signatureProprietaire->name_file);
          if (File::exists($path)) {
              $desiredWidth = 300;
              $desiredHeight = 150;
              $image = Image::make($path);
              $image->resize($desiredWidth, $desiredHeight);
              $image->save();
              $imageSize = getimagesize($path);
              $imageWidth = $imageSize[0];
              $imageHeight = $imageSize[1];
              // Calculer les nouvelles dimensions
              $desiredWidth = 130; // Largeur souhaitée
              $desiredHeight = ($imageHeight / $imageWidth) * $desiredWidth; // Calculer la hauteur proportionnelle
              // Insérer l'image en spécifiant les nouvelles dimensions
              $signature ['path'] = $path;
              $signature ['desiredWidth'] = $desiredWidth;
              $signature ['desiredHeight'] = $desiredHeight;
          }
      }

    $pdf =  PDF::loadView('locataire.echeanceFichier', compact('location', 'numbre', 'proprietaire','signature'));
    return $pdf->download($nom_fichier.'.pdf');
  }

  public function inviteLocataire($email)
  {
    $subject = __('quittance.invitation_locataire');

    sendMail($email, 'emails.invite', [
      "subject" => $subject,
      "lang" => getLangUser(Auth::id())
    ]);
    toastr()->success(__("finance.Invitation_sent"));

    return back();
  }
}
