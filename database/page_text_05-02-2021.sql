INSERT INTO `page_text` (`id`, `page`, `index`, `text_fr`, `text_en`, `url`, `ordre`) VALUES
(NULL, 'inputfile', 'browse', 'browse', 'browse', '/fileinput', NULL),
(NULL, 'inputfile', 'cancel', 'cancel', 'cancel', '/fileinput', NULL),
(NULL, 'inputfile', 'remove', 'remove', 'remove', '/fileinput', NULL),
(NULL, 'inputfile', 'upload', 'upload', 'upload', '/fileinput', NULL);

INSERT INTO `page_text` (`id`, `page`, `index`, `text_fr`, `text_en`, `url`, `ordre`, `text_fr_proposition`, `text_en_proposition`, `valide`, `commentaire`, `id_traducteur`) VALUES
(NULL, 'verification de numero', 'envoie_code', 'Nous avons envoyé un code au numéro que vous avez fournie', 'Nous avons envoyé un code au numéro que vous avez fournie', '/verification-telephone', 1000, 'Nous avons envoyé un code au numéro que vous avez fournie', 'Nous avons envoyé un code au numéro que vous avez fournie', 1, NULL, NULL),
(NULL, 'verification de numero', 'numero_phone', 'Numéro Téléphone', 'Numéro Téléphone', '/verification-telephone', 1000, 'Numéro Téléphone', 'Numéro Téléphone', 1, NULL, NULL),
(NULL, 'verification de numero', 'renvoyer_code', 'Renvoyer le Code', 'Renvoyer le Code', '/verification-telephone', 1000, 'Renvoyer le Code', 'Renvoyer le Code', 1, NULL, NULL),
(NULL, 'verification de numero', 'verifier_code', 'Vérifier le code Phone', 'Vérifier le code Phone', '/verification-telephone', 1000, 'Vérifier le code Phone', 'Vérifier le code Phone', 1, NULL, NULL),
(NULL, 'verification de numero', 'ivalid', 'Invalid Code', 'Invalid Code', '/verification-telephone', 1000, 'Invalid Code', 'Invalid Code', 1, NULL, NULL),
(NULL, 'verification de numero', 'verifie_phone', 'Verify Phone No', 'Verify Phone No', '/verification-telephone', 1000, 'Verify Phone No', 'Verify Phone No', 1, NULL, NULL);


('email', 'thank_subscribing', 'Merci de vous être abonné à notre service', 'Thank you for subscribing to our service', '/email', 147),
('email', 'account_created', 'Félicitation et bienvenu votre compte a été crée .Il vous reste qu\'une dernière étape.', 'Congratulations and welcome, your account has been created. You just have one last step.', '/email', 148),
('email', 'reset_message', 'Votre demande de réinitialisation de mot de passe a bien été reçu. Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe.', 'Your request to reset your password has been received. Click on the link below to reset your password.', '/email', 149),
('email', 'cependant_click', 'Afin de valider votre adresse mail et que vos annonces soient visibles cliquez sur le lien ci-dessous :', 'In order to validate your email address and that your ads are visible click on the link below:', '/email', 150),
('email', 'faire_appel', 'Si vous voulez faire appel répondez sur ce mail en indiquant votre point de vu.', 'If you want to appeal, answer this email stating your point of view.', '/email', 151);
