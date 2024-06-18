<?php

namespace App\Console\Commands;

use App\SignatureUsers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Property\PropertyTypes;
use DateTime;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use PDF;
use Illuminate\Support\Facades\DB;
use App\UserProfiles;
use App\Finance;
use PhpParser\Node\Stmt\Foreach_;
use Illuminate\Console\Command;

class EcheanceMensuelle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'echeance:envoi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoi echeance mensuelle';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $AnneeEnCours = Carbon::now()->year;
        $MoisEnCours = Carbon::now()->month;

        $_locations = Finance::whereYear('debut', $AnneeEnCours)
        ->where('type','loyer')
        ->where('Etat','0')
        ->whereMonth('debut','=',$MoisEnCours)
        ->with(['Locataire', 'Logement'])->get();
        try {
            foreach($_locations as $finance)
            {
                $infoLocataire = [];
                $infoLocataire['TenantAddress'] = $finance->Locataire->TenantAddress;
                $infoLocataire['bien'] = 1;
                $type = PropertyTypes::where('id',$finance->Logement->property_type_id)->first();
                $infoLocataire['type'] = $type->property_type;
                $infoLocataire['debut'] = $finance->debut;
                $dateFinDuMois = new DateTime();
                $dateFinDuMois->modify('last day of this month');
                $dateF = $dateFinDuMois->format('Y-m-d');
                $infoLocataire['fin'] = $dateF;

                //donnees au fichier blade
                $location = Finance::where('location_id',$finance->location_id)
                    ->where('Etat','0')
                    ->where('type','loyer')
                    ->whereYear('debut', $AnneeEnCours)
                    ->whereMonth('debut','=',$MoisEnCours)
                    ->first();

                $numbre=UserProfiles::where('user_id',$finance->user_id)->first();
                $proprietaire = DB::table('users')->where('id',$finance->user_id)->first();

                // fin donnees au fichier blade

                $infoLocataire['first_name'] = $proprietaire->first_name;
                $infoLocataire['last_name'] = $proprietaire->last_name;

                $signatureProprietaire = SignatureUsers::where('user_id', $finance->user_id)->first();
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

                $fichier = PDF::loadView('locataire.echeanceFichier',compact('location','numbre','proprietaire','signature'));
                $subject = __('quittance.subjete_cheance');
                $nom_fichier = __('finance.Avis_échéance');

                sendMail($finance ->Locataire->TenantEmail, 'emails.echeance', [
                    "infoLocataire" => $infoLocataire,
                    "subject" => $subject,
                ], [
                    "file" => $fichier,
                    "title" => $nom_fichier.'.pdf'
                ]);
            }
        }
        catch (\Exception $e){
            echo ($e->getMessage());
        }
    }
}
