<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width" />
    <!-- NOTE: external links are for testing only -->
</head>

<body>
    <table border="0" cellpadding="0" cellspacing="0" style="width:100%">
        <tbody>
            <tr>
                <td style="background-color:#fff">&nbsp;
                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                        style="border:1px solid; width:600px">
                        <tbody>
                            <tr>
                                <td style="background-color:#f5f5f9">
                                    <table align="center" border="0" cellpadding="15" cellspacing="0"
                                        style="width:100%">
                                        <tbody>
                                            <tr>
                                                <td><span style="color:black;">Invitation via .....</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" cellpadding="10" cellspacing="0" style="width:100%">
                                        <tbody>
                                            <tr>
                                                <td style="background-color:#fff">
                                                    <table border="0" cellpadding="10" cellspacing="0"
                                                        style="border:1px solid; width:100%">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <p style="margin-top: 10px;">
                                                                        {{ Auth::user()->first_name }} <span
                                                                            style="text-transform: uppercase;">{{ Auth::user()->last_name }}</span>
                                                                        voudrait vous inviter sur
                                                                        Bailti. C'est un service qui aide les
                                                                        propriétaires et les locataires dans leurs
                                                                        interactions au quotidien. Communiquer,
                                                                        télécharger les quittances électroniques,
                                                                        partager et stocker des documents en toute
                                                                        sécurité. </p>
                                                                    <p>
                                                                    <h4>Après l'inscription gratuite, vous obtiendrez
                                                                        les fonctionnalités suivantes :</h4>
                                                                    </p>
                                                                    <ul>
                                                                        <li>Compte sécurisé</li>
                                                                        <li>Dossier locataire</li>
                                                                        <li>Système de messagerie</li>
                                                                        <li>Quittances électroniques</li>
                                                                        <li>Stockage de fichiers sécurisé</li>
                                                                        <li>Gestion des travaux et interventions</li>
                                                                    </ul>
                                                                    <p>Veuillez cliquer le lien ci-dessous pour accepter
                                                                        l'invitation et confirmer votre inscription</p>
                                                                    <p style="text-align: center;"> <a
                                                                            href="{{ route('proprietaire.bureau') }}">Souscrire</a>
                                                                    </p>
                                                                    <pre> <small>
                                                                         Cordialement,<br />
                                                                         {{ Auth::user()->first_name }} <span style="text-transform: uppercase;">{{ Auth::user()->last_name }}
                                                                      </small>
                                                                    </pre>
                                                                    <p
                                                                        style="text-align:center; font-size:10px; line-height: 10px;">
                                                                        © Bailti
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color:#f5f5f9">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
