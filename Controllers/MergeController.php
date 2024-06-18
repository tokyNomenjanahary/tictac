<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MergeController extends Controller
{
    public function getGitBranches(Request $request)
    {
        $searchTerm = $request->input('search');

        $command = "git ls-remote --heads origin | awk '{print $2}' | sed 's/refs\/heads\///'";
        exec($command, $output);

        $branches = [];
        foreach ($output as $branch) {
            if (str_contains($branch, $searchTerm)) {
                $branches[] = $branch;
            }
        }
        return response()->json($branches);
    }


    public function chmodWecoco()
    {
        $domain = $_SERVER['HTTP_HOST'];
        if ($domain == "127.0.0.1:8000") {
            exec("cd ..; cd ..; chmod 777 -R bailti_repo2022; git reset --hard", $output);
        } else {
            exec("cd ..; cd ..; chmod 777 -R recette_wecoco; git reset --hard", $output);
        }
        exec("cd ..; php artisan storage:link", $output);
        return response()->json(['message' => $output]);
    }

    public function cacheClear()
    {
        exec("cd ..; php artisan cache:clear; php artisan config:clear; php artisan view:clear; php artisan route:clear", $output);
        return response()->json(['message' => $output]);
    }

    public function merge(Request $request)
    {
        $branch = $request->input('branch');
        $destination = $request->input('destination');
        $domain = $_SERVER['HTTP_HOST'];
        if ($domain == "127.0.0.1:8000") {
            // Vérifier si les branches existent
            exec("git branch -r | grep  $branch", $outputBranch);
            if (empty($outputBranch)) {
                return response()->json(['success' => 'false', 'message' => 'La branche à fusionner n\'existe pas.'], 500);
            }
            exec("git branch -r | grep  $destination", $outputDestination);
            if (empty($outputDestination)) {
                return response()->json(['success' => 'false', 'message' => 'La branche destinataire n\'existe pas.'], 500);
            }
            // Fetch
            exec("git fetch", $outputFetch);
            // Checkout
            exec("git checkout $destination", $outputCheckout);
            // Pull
            exec("git pull", $outputPull);
            // Merge
            exec("git merge $branch", $outputMerge);
            // Push
            exec("git push", $outputPush);
            return response()->json(['outputFetch' => $outputFetch, 'outputCheckout' => $outputCheckout, 'outputPull' => $outputPull, 'outputMerge' => $outputMerge, 'outputPush' => $outputPush]);
        } else {
            exec($branch, $output);
            return response()->json(['output' => $output]);
        }
    }

    public function index()
    {
        return view("merge.merge");
    }
}
