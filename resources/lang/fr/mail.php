<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Language Lines
    |--------------------------------------------------------------------------
    |
    */
	"support_premium_subject"=> i18n("mail.support_premium_subject"),
    "cpu_usage_alert" =>i18n('mail.cpu_usage_alert'),
	"hi" => i18n("hi"),
	'reseau_social' => i18n("reseau_social"),
	"title_registration" => i18n("title_registration"),
	"thanks" => i18n("thanks"),
	"perso_data_text" => i18n("perso_data_text"),
	"code_groupe_message" => i18n("code_groupe_message"),
	"activated_ad_message" => i18n('activated_ad_message'),
	"deactivated_ad_message" => i18n('deactivated_ad_message'),
	"welcome_create_annonce" => i18n("welcome_create_annonce"),
	"complete_ad_text" => i18n("complete_ad_text"),
	"newsletter_message" => i18n("newsletter_message"),
	"message_acceuil" => i18n("message_acceuil"),
	"thank_subscribing" => i18n("thank_subscribing"),
	"account_created" => i18n("account_created"),
	"reset_message" => i18n("reset_message"),
	"cependant_click" => i18n("cependant_click"),
	"faire_appel" => i18n("faire_appel"),
	'mail_caption' => i18n("mail.mail_caption"),
	"first_name" => i18n("mail.first_name"),
	'last_name' => i18n("mail.last_name"),
	'postal_code' => i18n("mail.postal_code"),
	'mobile_number' => i18n("mail.mobile_number"),
	'new_ad_title' => i18n("mail.new_ad_title"),
	'title' => i18n("mail.title"),
	'description' => i18n("mail.description"),
	'address' => i18n("mail.address"),
	"user_registration" => i18n("user_registration"),
	"ad_creation" => i18n("ad_creation"),
	"new_application" => i18n("new_application"),
	"view_application" => i18n("view_application"),
	"view_message" => i18n("view_message"),
	"send_message" => i18n("send_message"),
	"new_message_subject" => i18n("new_message_subject"),
	"new_application_subject" => i18n("new_application_subject"),
	"perso_data_subject" => i18n("perso_data_subject"),
	"code_groupe" => i18n("code_groupe"),
	"ad_activation" => i18n("ad_activation"),
	"ad_desactivation" => i18n("ad_desactivation"),
	"view_ad" => i18n("view_ad"),
	"create_annonce" => i18n("create_annonce"),
	"complete_ad" => i18n("complete_ad"),
	"post_your_ad" => i18n("post_your_ad"),
	"post_your_ad_link" => i18n("post_your_ad_link"),
	"improve_ad" => i18n("improve_ad"),
	"mail_error_subject" => i18n("mail_error_subject"),
	"error_message" => i18n("error_message"),
	"complete_profil_text" => i18n("complete_profil_text"),
	"verify_account" => i18n("verify_account"),
	"team" => i18n("mail.team").' '.config('app.name', 'TicTacHouse'),
	"reset" => i18n("mail.reset"),
	"message_membre" => i18n("mail.message_membre").config('app.name', 'TicTacHouse') . i18n("mail.message_membre1"),
	"bien_a_vous" => i18n("bien_a_vous"),
	"flash_message" => i18n("flash_message"),
	"flash_message_toc" => i18n("mail.flash_message_toc"),
	"voir_profil" => i18n("voir_profil"),
	"message_flash" => i18n("mail.message_flash"),
	"voir_profil_message" => i18n("voir_profil_message"),
	"dear" => i18n("dear"),
	"subs_detail" => i18n("subs_detail"),
	"plan_name" => i18n("mail.plan_name"),
	"plan_duration" => i18n("mail.plan_duration"),
	"plan_amount" => i18n("plan_amount"),
	"start_valid" => i18n("start_valid"),
	"amount_paid" => i18n("amount_paid"),
	"to" => i18n("mail.to"),
	"payment_detail" => i18n("payment_detail"),
	"payment_success" => i18n("payment_success"),
	"complete_profile" => i18n("complete_profile"),
	"visit_message" => i18n("visit_message"),
	"visit_message2" => i18n("visit_message2"),
	"visit_object" => i18n("visit_object"),
	"consult_visit" => i18n("consult_visit"),
	"text_message_administrateur" => i18n('text_message_administrateur'),
	"raison" => i18n("mail.raison"),
	"message_administrateur" => i18n("mail.message_administrateur"),
	"contact_annonceur" => i18n("mail.contact_annonceur"),
	"forgot_subject" => i18n("mail.forgot_subject"),
	"serveur_d" => i18n("mail.serveur_d"),
	"serveur_u" => i18n("mail.serveur_u"),
	"email_max" => i18n("mail.email_max"),
	"parainnage_text" => i18n("mail.parainnage_text").config('app.name', 'TicTacHouse'),
	"register" => i18n("mail.register"),
	"parainnage_subject" => i18n("mail.parainnage_subject"),
	"parain_text" => i18n("mail.parain_text1").config('app.name', 'TicTacHouse') . i18n("mail.parain_text2") . config('app.name', 'TicTacHouse') . i18n("mail.parain_text3"),
	"telegram" => (isTelegramMail()) ? i18n('mail.telegram_true').'<a href="'. url('/pub-click/mail') .'?id=6">'. i18n('mail.telegram_clik').'</a>' : '',
	"voir_ads_toctoc" => i18n("voir_ads_toctoc"),
	"annonce_search" => i18n("annonce_search"),
	"voir_ad_toctoc" => i18n("voir_ad_toctoc"),
	"first_message_expired1"       => i18n("first_message_expired1"),
    "first_message_expired2"       => i18n("first_message_expired2"),
    "second_message_expired"      => i18n("second_message_expired"),
    "third_message_expired"       => i18n("third_message_expired"),
    "message_connecter"           => i18n("message_connecter"),
    "message_reactiver"           => i18n("message_reactiver"),
	"first_message_desactive1"     => i18n("first_message_desactive1"),
    "first_message_desactive2"     => i18n("first_message_desactive2"),
    "second_message_desactive"    => i18n("second_message_desactive"),
    "politesse_message_desactive" => i18n("politesse_message_desactive"),
	"fac_facture"	=> i18n("fac_facture"), //facture //Invoice
	"fac_date"		=> i18n("fac_date"), //date
	"fac_duree"		=> i18n("fac_duree"), //durée //Time
	"fac_prix"		=> i18n("fac_prix"), //prix //Price
	"fac_jours"		=> i18n("fac_jours"), //jours //days
	"fac_prenom"	=> i18n("fac_prenom"), //Prénom //First name
	"fac_adress"	=> i18n("fac_adress"), //adresse //Address
	"fac_intro"		=> i18n("fac_intro"), //Voici votre facture //Here is your invoice
	"fac_pack"		=> i18n("fac_pack"), //Pack
	"dashboard"     => i18n("mail.dashboard"),
	"log_in"        => i18n("mail.log_in"),
	"indiquant"     => i18n("mail.indiquant"),
	"chambre_dispo" => i18n("mail.chambre_dispo"),
	"clients"       => i18n("mail.clients"),
	"new_select"    => i18n("mail.new_select"),
	"paris"         => i18n("mail.paris"),
	"alert_title"   => i18n("mail.alert_title"),
	"per_month"     => i18n("mail.per_month"),
	"voir_annonce"  => i18n("mail.voir_annonce"),
	"new_ad"        => i18n("mail.new_ad"),
	"server_down"   => i18n("mail.server_down"),
	"server_up"     => i18n("mail.server_up"),
	"thanks_verify" => i18n("mail.thanks_verify"),
	"error_code"    => i18n("mail.error_code"),
	"max_send"      => i18n("mail.max_send"),
	"max_verify"    => i18n("mail.max_verify"),
	"votre_annonce" => i18n("mail.votre_annonce"),
	"ad_du_success" => i18n("mail.ad_du_success"),
	"paris_naples"    => i18n("mail.paris_naples"),
	"debug_active"    => i18n("mail.debug_active"),
	"debug_desactive"    => i18n("mail.debug_desactive"),
	"verification_active"    => i18n("mail.verification_active"),
	"verification_desactive"    => i18n("mail.verification_desactive"),
	"community_Hsup"    => i18n("mail.community_Hsup"),
	"worked_today"    => i18n("mail.worked_today"),
	"n_indicator"    => i18n("mail.n_indicator"),
	"ad_post_community"    => i18n("mail.ad_post_community"),
	"comment_community"    => i18n("mail.comment_community"),
	"last_community_comment"    => i18n("mail.last_community_comment"),
	"coup_foudre"    => i18n("mail.coup_foudre"),
	"erro_etap_2"    => i18n("mail.erro_etap_2"),
	"please_check"    => i18n("mail.please_check"),
	"user_contact_urgent"    => i18n("mail.user_contact_urgent"),
	"address_mail"    => i18n("mail.address_mail"),
	"article_blog"    => i18n("mail.article_blog"),
	"article_blog_modified"    => i18n("mail.article_blog_modified"),
	"login_seo"    => i18n("mail.login_seo"),
	"is_translator"    => i18n("mail.is_translator"),
	"propose_traduction"    => i18n("mail.propose_traduction"),
	"the_translator"    => i18n("mail.the_translator"),
	"canceled_proposal"    => i18n("mail.canceled_proposal"),
	"traduction_invalide"    => i18n("mail.traduction_invalide"),
	"site_use"    => i18n("mail.site_use"),
	"profile_save"    => i18n("mail.profile_save"),
	"identifiants_connex"    => i18n("mail.identifiants_connex"),
    "je_suis_lea"              => i18n("mail.je_suis_lea"),
	"welcome_bailti_community" => i18n("mail.welcome_bailti_community"),
	"accompany_search"         => i18n("mail.accompany_search"),
	"suggest_ads"              => i18n("mail.suggest_ads"),
	"can_help_you"             => i18n("mail.can_help_you"),
	"you_disposal"             => i18n("mail.you_disposal"),
	"good_for_you"             => i18n("mail.good_for_you"),
	"bailti_bailti"            => i18n("mail.bailti_bailti"),
	"ad_is_valid"              => i18n('mail.ad_is_valid'),
	"ad_deactivated"           => i18n('mail.ad_deactivated'),
    "restart_mysql"          => i18n("mail.restart_mysql"),
	"cpu_used_mysqld"        => i18n("mail.cpu_used_mysqld"),
	"site_email"              => i18n("mail.sites"),
	"your_selection"     =>i18n("mail.your_selection"),
	"Additional_hour"  => i18n("mail.Additional_hour"),
	'information_indication'  => i18n('mail.information_indication'),
	'geopify_error'      => i18n('mail.geopify_error'),
	'need_help'     => i18n('mail.need_help'),
	'Parainnage'     => i18n('mail.Parainnage'),
	'Alerte_traduction'  => i18n('mail.Alerte_traduction'),
	'subject_default'    => i18n('mail.subject_default')


];

