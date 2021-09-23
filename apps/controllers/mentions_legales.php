<?php
if ($iniObj->debugViews) echo __FILE__;
$carouselActive = false;

include _VIEW_PATH . "/common_header.phtml"; ?>

<!-- Header -->
<header id="js-header" class="u-header">
    <div class="u-header__section">
        <?php //include _VIEW_PATH . "global_top_bar.phtml"; 
        ?>
        <?php include _VIEW_PATH . $lib->lang . "/top_menu.phtml"; ?>
    </div>
</header>
<!-- End Header -->

<!-- Breadcrumbs -->
<section class="g-bg-gray-light-v5 g-py-50">
    <div class="container">
        <div class="d-sm-flex text-center">
            <div class="align-self-center">
                <h2 class="h3 g-font-weight-300 w-100 g-mb-10 g-mb-0--md">
                    CGU
                </h2>
            </div>

            <div class="align-self-center ml-auto">
                <ul class="u-list-inline">
                    <li class="list-inline-item g-mr-5">
                        <a class="u-link-v5 g-color-main g-color-primary--hover" href="/<?php echo $lib->lang; ?>">
                            <?php echo $lang->trl('Home'); ?>
                        </a>
                        <i class="g-color-gray-light-v2 g-ml-5">/</i>
                    </li>
                    <li class="list-inline-item g-color-primary">
                        <span>
                            CGU
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="container g-py-40">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Blog Minimal Blocks -->
            <article class="g-mb-">
                <div class="g-mb-30">
                    <h2 id="article" class="h4 g-color-black g-font-weight-600 mb-3"><a class="u-link-v5 g-color-black g-color-primary--hover" href="#" style="font-size :25px;">Conditions Générales d'Utilisation</a></h2>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;">Le présent document régit, avec la demande d’expédition en ligne, la demande d’enlèvement, le bon de commande, et les documents mentionnés, VOTRE UTILISATION DU SERVICE MYTRANSPORTEO.
                        <br><B>EN COCHANT EN LIGNE SUR LE SITE INTERNET LA CASE INDIQUANT VOTRE ACCEPTATION ET/OU EN SIGNANT UN DEMANDE D’ENLEVEMENT, UN DEVIS, OU UN BON DE COMMANDE PAPIER FAISANT RÉFÉRENCE AU PRÉSENT DOCUMENT, VOUS EN ACCEPTEZ INTEGRALEMENT ET SANS RESERVE LES DISPOSITIONS.</B></br>
                    </p>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;"><B>Article 1 DEFINITIONS</B>
                        <br>Dans le cadre du présent contrat, on entend par :
                        « Client » : désigne l’organisme (entreprise, collectivité, association, etc.) qui a procédé à une demande d’ouverture de compte auprès de la société Transporteo International en vue de l’utilisation du service Mytransporteo ; cet organisme est généralement votre employeur ou un client de votre employeur.
                        Le Client agit en tant que responsable du traitement de GED en ce qui concerne les Documents et les données qu’ils contiennent lorsque ce sont des données à caractère personnel. Par extension, le Client désigne, quand c ’est pertinent, tout Utilisateur Final dont l’accès a été créé par ledit Client ou sous l’autorité dudit Client.

                        <br><B>« Conditions Générales d’Utilisation » :</B> désigne les présentes conditions générales d'utilisation du Service (y compris tout document qui est expressément inclus par référence dans les présentes conditions générales), toutes éventuelles conditions particulières indiquées par Transporteo et tous éventuels avenants aux conditions générales/particulières.

                        <br><B>« Contenu Hébergé » :</B> décrit l’ensemble de l’information, sous quelle que forme que ce soit, qui est rendue accessible en ligne, c’est à dire par le biais d’un accès internet à un serveur distant.

                        <br><B>« Document » :</B> désigne l’ensemble des informations numériques, rassemblées, enregistrées et gérées sous un nom unique sur un espace de stockage et dont le stockage implique la définition d’un format spécifique.

                        <br><B>« Données » :</B> désigne l'ensemble des éléments que vous pouvez déposer sur Votre Espace, c'est-à-dire les documents dématérialisés dans un format également conforme aux modalités requises par le Service.
                        Les Données ainsi reçues pourront être conservées dans Votre Espace pendant une durée de conservation spécifique déterminée contractuellement avec vous.

                        <br><B>« Données à Caractère Personnel» :</B> désigne toute information se rapportant à une personne physique identifiée ou identifiable (dénommée «personne concernée»); est réputée être une «personne physique identifiable» une personne physique qui peut être identifiée, directement ou indirectement, notamment par référence à un identifiant, tel qu'un nom, un numéro d'identification,
                        des données de localisation, un identifiant en ligne, ou à un ou plusieurs éléments spécifiques propres à son identité physique, physiologique, génétique, psychique, économique, culturelle ou sociale. Exemples : Nom, prénom, numéro de téléphone, adresse de courriel ainsi que toutes les données qui sont rattachées à la personne concernée : historique des commandes, parcours digital,
                        adresse IP de connexion etc. Les Données à Caractère Personnel peuvent aussi ci-après être nommées DCP ou encore Données Personnelles.

                        <br><B>« Espace de Stockage » :</B> espace mis à votre disposition par Transporteo à l’adresse suivante : <a href="https://www.transporteo.com/fr/MyTransporteo"><B>https://www.transporteo.com/fr/MyTransporteo</B></a> et permettant le stockage à distance de vos Données conformément aux Conditions Générales d’Utilisation.

                        <br><B>« Gestion Electronique de Documents (GED) » :</B> décrit un service informatique par lequel des Documents sont téléchargés, traités et gérés sur un espace disque.

                        <br><B>« Invité » :</B> personne tierce que vous autorisez à consulter vos Données sur votre Espace de Stockage.

                        <br><B>« Mise à Jour » :</B> désigne les améliorations apportées aux fonctionnalités existantes et l’ajout de nouvelles fonctionnalités, fournies par Transporteo sous forme de lancements périodiques réguliers.

                        <br><B>« Partie » :</B> personne physique ou morale engagée dans une relation contractuelle de sous-traitance du Service pour le compte du souscripteur d’un abonnement au Service,
                        notamment Transporteo en tant que sous-traitant et son Client en tant que responsable de traitement.

                        <br><B>« JIS COMPUTING » :</B> la société JIS COMPUTING, société immatriculée au Registre du Commerce de Bobigny sous le n° 484 875 513, qui agit en tant que sous-traitant au sens donné par le règlement général
                        sur la protection des données en ce qui concerne les DCP contenues dans les documents, et en tant que responsable de traitement relativement aux DCP qui vous concernent,
                        traitées pour rendre le Service (notamment l’adresse e- mail que vous utilisez pour vous connecter à votre Espace de Stockage).

                        <br><B>« Serveur » :</B> désigne l’infrastructure digitale, sécurisée, gérée et administrées par JIS COMPUTING ou ses prestataires sur laquelle les clients des revendeurs transfèrent leur contenu hébergé par le biais de leur accès MyTransporteo.

                        <br><B>« Service » :</B> désigne les services vous étant fournis par Transporteo sous la marque MyTransporteo. Les Services comprennent,
                        <br>&ensp;&ensp;&ensp;• des services de base tels que le droit de classer des Données sur l’Espace de Stockage selon les modalités fixées par l’offre commerciale retenue,
                        <br>&ensp;&ensp;&ensp;• des services optionnels tels qu’éventuellement convenus.
                        <br>La souscription au Service se fait via la signature du présent contrat, ce qui implique l'acceptation sans réserve des présentes conditions générales et la communication par vos soins des informations requises.

                        <br><B>« Software as a Service (SaaS) » :</B> désigne un logiciel distribué auprès de l’Utilisateur Final sous forme de service.

                        <br><B>« Traçabilité » :</B> le Service assure la traçabilité des différentes opérations sur l’Espace de Stockage. Les éléments de traçabilité sont consignés dans un journal d'opérations en ce qui concerne les identifications et les envois,
                        transmissions et réceptions de Données effectués. Dans le cadre des opérations de traçabilité,
                        JIS COMPUTING respecte les prescriptions légales applicables en matière de collecte des données de connexion, notamment les dispositions du règlement général sur la protection des données.

                        <br><B>« Utilisateur Final » :</B> désigne une personne, notamment employée du Client, pour laquelle un accès à MyTransporteo a été créé sous la forme d’un mot de passe unique associé à une adresse courriel fournie par le Client.

                        <br><B>« Vous » :</B> toute personne ayant accepté les présentes conditions générales d’utilisation du Service MYTRANSPORTEO.
                    </p>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;"><B>Article 2 DESCRIPTION DU SERVICE</B>
                        <br>JIS COMPUTING est éditeur de la solution MyTransporteo, solution de Gestion logistique et <B>Electronique de Documents</B> qu’elle distribue sous la forme d’une offre en mode SaaS (Software as a Service / Logiciel en tant que Service).
                        Ce logiciel permet à l’Utilisateur Final de gérer un Contenu Hébergé sous la forme de Documents à travers des fonctionnalités telles que la recherche, la consultation, l’échange et la distribution de ces Documents.
                        Les documents déposés par le Client au sein de MyTransporteo peuvent contenir tout type d’information, notamment des Données à Caractère Personnel (DCP) au sens du Règlement Général sur la Protection des Données (RGPD).
                        <br>JIS COMPUTING n’a aucun moyen de savoir ni de prédire quels types de données sont ou seront présentes dans les documents que ses clients déposent dans MyTransporteo.
                        La gamme des fonctionnalités de MyTransporteo peut être modifiée lorsqu’une Mise à Jour est publiée par JIS COMPUTING dans le cadre de son service.

                        <br><B>2.1 Finalité du Service</B>
                        <br>La finalité principale du service est la gestion informatisée des Documents déposés au sein de MyTransporteo en offrant en particulier :
                        <br>&ensp;&ensp;&ensp;• des fonctions d’indexation et de recherche permettant d’utiliser des critères définis par les Utilisateurs Finaux pour retrouver les documents qui satisfont à ces critères
                        <br>&ensp;&ensp;&ensp;• des fonctions de lecture des données contenues dans les documents
                        <br>&ensp;&ensp;&ensp;• des fonctions d’exportation ou de transfert de données lues dans les documents, notamment sous forme structurée aux fins d’être transférées vers des logiciels ou des services tiers.

                        <br><B>2.2 Eléments du Service</B>
                        <br>Bien qu’il soit impossible de lister la totalité des éléments composant le Service, JIS COMPUTING précise que le Service inclut notamment les éléments suivants :
                        <br>1. Accès à un stockage de données hébergé :
                        <br>&ensp;&ensp;&ensp;a. La capacité de stockage est définie par le type de document accepté par MyTransporteo
                        <br>&ensp;&ensp;&ensp;b. Pour un nombre illimité d’utilisateurs
                        <br>2. La réplication des données stockées sur trois serveurs différents, la sécurisation de ces données au moyen d’outils tels qu’un pare-feu, le cryptage des données (lors du transfert des documents de l’espace du client à son espace MyTransporteo et sur l’espace MyTransporteo lui-même) et le contrôle de l’empreinte numérique du document
                        <br>3. L’accès en ligne aux Documents à travers un navigateur internet, sur support ordinateur, smartphone ou tablette, notamment via une interface web.
                        <br>4. La consultation des Documents au moyen d’un outil de visualisation permettant des approches de visualisation (zoom, défilement de pages, changement d’angle, etc.)
                        <br>5. L’accès à de multiples fonctionnalités de distribution et d’échange des documents hébergés dont :
                        <br>&ensp;&ensp;&ensp;a. L’envoi par courriel d’un ou plusieurs Documents hébergé au sein de MyTransporteo
                        <br>&ensp;&ensp;&ensp;b. Le partage d’un ou plusieurs Documents via un lien d’accès
                        <br>6. L’extraction des données incluses dans les index des documents, notamment sous format Excel ou CSV
                        <br>7. Des fonctionnalités d’administration du service, notamment :
                        <br>&ensp;&ensp;&ensp;a. Filtrage de l’accès aux documents suivant leurs index
                        <br>&ensp;&ensp;&ensp;b. Filtrage d’accès à l’ensemble des fonctionnalités disponibles dans MyTransporteo
                        <br>&ensp;&ensp;&ensp;c. Création de nouveaux Utilisateurs Finaux
                        <br>8. Mise à Jour automatique des versions pendant toute la durée d’utilisation
                        <br>9. Maintenance du service sur la durée d’utilisation.
                    </p>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;"><B>Article 3 OBJET</B>
                        <br>Les présentes Conditions Générales d’Utilisation ont pour objet de déterminer les conditions dans lesquelles Vous bénéficiez du Service MyTransporteo tel que décrit dans l’article 2, auquel vous vous connectez, notamment au moyen de l’interface https://www.transporteo.com/fr/MyTransporteo
                        <br>Vous êtes informé que ce service est réservé au stockage et à la mise à disposition de Données libres de droit ou pour lesquelles Vous détenez les droits de reproduction, de communication et de mise à disposition du public sous réserve des exceptions propres au droit d’auteur.
                    </p>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;"><B>Article 4 VOS OBLIGATIONS</B>
                        <br>Vous devez avoir 18 ans ou plus pour commander ou utiliser le Service et le fait d’accepter les présentes conditions confirme que vous avez au moins 18 ans. Il vous appartient préalablement à la validation des présentes Conditions Générales d’Utilisation de vérifier l’adéquation de votre matériel informatique avec le Service proposé.
                        <br>Vous devez à tout le moins être équipé du matériel informatique et de télécommunication suivant sans lequel Vous ne pourrez accéder au Service, à savoir au moins :
                        <br>&ensp;&ensp;&ensp;- un ordinateur ou un terminal (smartphone, tablette, etc.) disposant d’une connexion internet Haut débit.
                        <br>&ensp;&ensp;&ensp;- un navigateur de génération récente, la dernière génération disponible étant fortement recommandée ou l’application mobile MyTransporteo, exécutable sur un smartphone ou une tablette.
                        <br>L’équipement, le coût des communications téléphoniques et de l’accès au réseau Internet, ainsi que toute autorisation nécessaire y afférente, sont à votre charge ou à celle du Client qui a créé Votre accès à MyTransporteo. Il Vous appartient d’être équipé de manière appropriée, notamment en matière informatique et de communications électroniques,
                        pour accéder au Site et au Service et de prendre toutes les mesures appropriées de façon à protéger contre toute atteinte ou dommage vos Données stockés sur, en provenance ou à destination de votre équipement informatique. Vous Vous engagez à ne pas entraver le bon fonctionnement du Site et/ou du Service de quelque manière que ce soit, notamment
                        en transmettant tout élément susceptible de contenir un virus ou de nature à autrement endommager ou affecter le Site et/ou le Service et, plus largement, le système d'information de Transporteo et/ou de ses cocontractants. Vous reconnaissez connaître et comprendre Internet et ses limites et, notamment, ses caractéristiques fonctionnelles et performances techniques,
                        les risques d'interruption, les temps de réponse pour consulter, interroger ou transférer des informations, les risques, quels qu'ils soient, inhérents à tout transfert de Données notamment sur réseau ouvert. Afin d’assurer la confidentialité des Données stockées, Transporteo Vous demandera lors de la mise en place du Service de Vous identifier à l’aide d’un code.
                        Ce code est strictement personnel. Vous vous engagez à le conserver et à ne le communiquer à personne.
                        <br>Vous êtes seul responsable des conséquences résultant de la communication de votre code personnel à tout tiers, sans préjudice des dommages et intérêts que Transporteo pourrait Vous réclamer en raison du préjudice subi de ce fait.
                        <br>Vous vous portez fort du respect par Vos Invités des dispositions visées dans le présent article. Vos Invités devront s’identifier à l’aide d’un nom d’utilisateur et d’un mot de passe. Ce mot de passe est confidentiel. Vous Vous engagez à en informer Vos Invités et dégagez JIS COMPUTING et Transporteo de toute responsabilité relativement à l’usage fait
                        de cet identifiant par Votre Invité ou toute autre personne autorisée ou non à accéder au Service.
                        <br>Vous reconnaissez que le Service est destiné à un usage dans des conditions d’utilisation normales et raisonnables. Vous Vous engagez à utiliser le Service dans le strict respect de la loi. Vous Vous engagez à ne pas sciemment transmettre, télécharger, partager, envoyer à des destinataires externes ou vers votre Espace de Stockage des Données qui
                        contiendraient des virus ou tout autre code ou programme similaire capables d'interrompre, de détruire ou d'altérer tout programme, ordinateur ou moyen de communications électroniques ou d'en limiter la fonctionnalité. Vous déclarez et garantissez que toute Donnée est conforme à toute loi, réglementation et/ou usage applicables, ainsi qu'aux droits de tiers.
                        Notamment, Vous Vous engagez à ne pas transmettre de Données :
                        <br>&ensp;&ensp;&ensp;- pouvant constituer une apologie de crimes contre l'humanité ou de crimes de guerre ;
                        <br>&ensp;&ensp;&ensp;- susceptible de porter atteinte au respect et à la dignité de la personne humaine, à l'égalité entre les hommes et les femmes, à la protection des enfants et des adolescents, notamment par la fabrication, le transport et la diffusion de messages à caractère violent, pornographique ou pédophile ;
                        <br>&ensp;&ensp;&ensp;- contraire à l'ordre public ou aux bonnes m œurs ;
                        <br>&ensp;&ensp;&ensp;- à caractère menaçant, abusif, constitutif de harcèlement, diffamatoire, injurieux ;
                        <br>&ensp;&ensp;&ensp;- constituant un acte de contrefaçon, de concurrence déloyale ou de parasitisme ;
                        <br>&ensp;&ensp;&ensp;- provoquant ou permettant la provocation à la discrimination, la haine ou la violence en raison des origines, du sexe, de l'état de santé, de l'appartenance politique ou syndicale ;
                        <br>&ensp;&ensp;&ensp;- portant atteinte à la vie privée ;
                        <br>&ensp;&ensp;&ensp;- comprenant, sans que cette liste ne soit limitative, des virus informatiques ou tout autre code ou programme, conçus pour interrompre, détruire ou limiter la fonctionnalité de tout logiciel, ordinateur ou outil de télécommunication ;
                        <br>&ensp;&ensp;&ensp;- encourageant à la commission de crimes, délits ou actes de terrorisme ;
                        <br>&ensp;&ensp;&ensp;- incitant à la consommation de substances interdites ;
                        <br>&ensp;&ensp;&ensp;- violant le secret des correspondances ;
                        <br>&ensp;&ensp;&ensp;- permettant à un tiers de se procurer directement ou indirectement des logiciels piratés, des logiciels permettant des actes de piratage et d'intrusion dans des systèmes informatiques et de télécommunication et, d'une manière générale, tout outil logiciel ou autre permettant de porter atteinte aux droits d'autrui et à la sécurité des personnes et des biens ;
                        <br>&ensp;&ensp;&ensp;- Vous Vous engagez par ailleurs à respecter strictement les dispositions du Code de la propriété intellectuelle ;
                        <br>&ensp;&ensp;&ensp;- Vous Vous engagez à ne pas modifier, essayer de modifier ou porter atteinte au Serveur ni au Service sous quelque manière que ce soit et à ne pas utiliser de logiciel ou toute forme de programme informatique ayant pour but d'atteindre ou de rendre disponible un contenu protégé ou non disponible librement. Il est également interdit de créer une œuvre ou
                        un site dérivant de tout ou partie du présent Service. Il est à cet égard rappelé que :
                        <br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<B>•TOUTE REPRODUCTION ET/OU COMMUNICATION ET/OU MISE A DISPOSITION DE TIERS D’UNE ŒUVRE SANS DETENTION DES DROITS CORRESPONDANTS CONSTITUE LE DELIT DE CONTREFACON SANCTIONNE PAR LES ARTICLES L.335-2 ET SUIVANTS DU CODE DE LA PROPRIETE INTELLECTUELLE.
                            <br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;•AU TERME DE L’ARTICLE L.335-4 DU MEME CODE, EST PUNI DE TROIS ANS D’EMPRISONNEMENT ET DE 300 000 EUROS D’AMENDE TOUTE FIXATION, REPRODUCTION, COMMUNICATION OU MISE A DISPOSITION DU PUBLIC, A TITRE ONEREUX OU GRATUIT, OU TOUTE TELEDIFFUSION D’UNE PRESTATION, D’UN PHONOGRAMME, D’UN VIDEOGRAMME OU D’UN PROGRAMME, REALISEE SANS L’AUTORISATION, LORSQU’ELLE EST EXIGEE,
                            DE L’ARTISTE INTEPRETE, DU PRODUCTEUR DE PHONOGRAMMES OU DE VIDEOGRAMMES OU DE L’ENTREPRISE DE COMMUNICATION AUDIOVISUELLE.</B>
                    </p>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;"><B>Article 5 NOS ENGAGEMENTS</B>
                        <br><B>5.1 Hébergement</B>
                        <br>JIS COMPUTING met en œuvre tous les moyens techniques raisonnablement envisageables afin d’assurer un hébergement sécurisé des serveurs de stockage et des Données qu’ils contiennent contre toute intrusion malveillante (piratage, vol de données…). Tous les sites d’hébergement des serveurs de stockage et des Données sont localisés sur le territoire français.
                        Vous êtes informé que le Service n’intègre ni ne fournit aucune prestation d’identification ni de protection contre les virus. JIS COMPUTING Vous conseille fortement d’installer sur votre terminal informatique un anti-virus susceptible d’identifier et de détruire les fichiers infectés. En aucun cas, JIS COMPUTING ne pourra être tenu pour responsable des conséquences du téléchargement d’un fichier infecté de virus.
                        <br><B>5.2 Restitution et réversibilité des données</B>
                        <br>JIS COMPUTING met en œuvre tous les moyens raisonnablement envisageables afin d’assurer la restitution sur votre équipement informatique ou sur tout autre équipement informatique similaire ou compatible, des Données que Vous aurez stockées sur Votre Espace de stockage dans les meilleurs délais, sous réserve d’un fonctionnement normal du réseau Internet et en l’absence de toute interruption de service rendue nécessaire par des opérations de maintenance, réparation ou évolution.
                        À tout moment de la durée du service, Vous avez la possibilité de télécharger et récupérer l’ensemble de Vos Données archivées dans MyTransporteo.
                        <br>JIS COMPUTING aura rempli son obligation de restitution dès lors que l’ensemble des Données visées à l’alinéa précédent aura été mis à votre disposition sur le réseau Internet. C’est le principe de réversibilité du service.
                        En option et à la demande du client, les Données pourront lui être restituées dans leur intégralité, à tout moment, selon les modalités techniques et financières suivantes :
                        <br>&ensp;&ensp;&ensp;- les Données seront restituées sous le format informatique original sous lequel elles ont été déposées
                        <br>&ensp;&ensp;&ensp;- les Données seront restituées sur CD-Rom, clé USB, selon votre choix et faisabilité technique
                        <br>&ensp;&ensp;&ensp;- JIS COMPUTING se réserve le droit de facturer le temps passé par son personnel à la mise en place de cette restitution, le coût du support informatique sur lequel les Données seront transférées ainsi que le coût d’envoi de ce support par Lettre Recommandée avec Accusé Réception
                        <br><B>5.3 Destruction des données</B>
                        <br>Vous êtes informé que Transporteo détruira toutes Vos données stockées sur ses serveurs 30 jours après la date à laquelle Vous aurez résilié le présent contrat.
                        <br><B>5.4 Transfert de données</B>
                        <br>JIS COMPUTING met en œuvre tous les moyens raisonnablement envisageables afin d’assurer le transfert des Données que Vous aurez sélectionnées sur le poste informatique de l’Invité que Vous aurez invité et/ou la possibilité pour l’Invité de visionner les fichiers auxquels Vous lui aurez accordé l’accès sous réserve d’un fonctionnement normal du réseau Internet et en l’absence de toute interruption de Service rendue nécessaire par des opérations de maintenance, réparation ou évolution.
                        <br><B>5.5 Confidentialité des données</B>
                        <br>Sous réserve des dispositions légales applicables, JIS COMPUTING met en œuvre tous les moyens techniques raisonnablement envisageables dans le cadre d’un Service comportant à la fois une prestation de stockage et de mise à disposition de Données, pour assurer et respecter, faire assurer et faire respecter la plus stricte confidentialité sur l’existence et le contenu des fichiers et Données stockés par l’intermédiaire du Service, dans les limites de l’article 6.1 ci-après. En particulier,
                        les Documents sont cryptés avec une clé de cryptage propre au Client de telle façon que seules les personnes ayant accès à l’espace de stockage du client (son Armoire virtuelle) puissent y accéder. Ainsi, ni TRANSPORTEO, ni les autres clients de TRANSPORTEO ne sont en mesure d’accéder au contenu des documents déposés dans l’Armoire d’un Client donné.
                        Le Client ou un Utilisateur Final peut néanmoins autoriser un accès temporaire à son Armoire, en particulier aux équipes de JIS COMPUTING à des fins de support.
                        Les détails de la politique de confidentialité de TRANSPORTEO sont disponibles sur ses sites web.
                    </p>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;"><B>Article 6 RESPONSABILITE </B>
                        <br>En tant qu’utilisateur du Service, Vous Vous engagez à l’utiliser pour le compte du Client qui a effectué la souscription au dit Service et sous la responsabilité duquel votre accès a été créé. Vous Vous engagez à utiliser le Service uniquement selon ses instructions ou dans le cadre des missions qui Vous ont été confiées par lui.
                        Votre responsabilité emporte celle du Client vis-à-vis de Transporteo. Le Client pourra, le cas échéant, se retourner contre Vous suite à un manquement ou à une violation des présentes Conditions Générales de Votre part.
                        <br><B>6.1 Obligation de moyens sur les Données</B>
                        <br>JIS COMPUTING, en sa qualité de sous-traitant dépositaire des Données, s'oblige à restituer, dans leur Intégrité, les Données hébergées, dans le cadre d'une obligation de moyens. Elle précise être assurée au titre de sa responsabilité civile auprès d'une compagnie d'assurance notoirement connue sur la place et s'engage à fournir à première demande du Client une attestation en justifiant.
                        <br><B>6.2 Réponses aux besoins particuliers</B>
                        <br>JIS COMPUTING ne consent aucune garantie sur l'aptitude du Site et/ou du Service à répondre à Vos attentes ou besoins particuliers. De la même manière, JIS COMPUTING n'est pas en mesure de garantir qu'aucune erreur ou autre trouble de fonctionnement ou d'utilisation n'apparaîtra au cours de l'utilisation du Site et/ou du Service.
                        <br>JIS COMPUTING ne garantit pas que les résultats et les informations obtenus soient exempts de toute erreur ou de tout autre défaut. JIS COMPUTING est tenue, s'agissant de la fourniture du Service, par une obligation de moyens.
                        <br>En aucun cas, JIS COMPUTING n'est responsable des préjudices tels que notamment : préjudice financier, commercial, perte de clientèle, trouble commercial quelconque, perte de bénéfice, perte d'image de marque, perte de programmes informatiques subis par Vous qui pourraient résulter de l'inexécution des présentes conditions générales,
                        lesquels préjudices sont, de convention expresse, réputés avoir le caractère de préjudice indirect. JIS COMPUTING ne sera en aucun cas responsable des dommages consécutifs, même partiellement, à une inexécution totale ou partielle de Vos obligations, ainsi que de tous dommages indirects même si elle a eu connaissance de la possibilité de survenance de tels dommages.
                        <br>JIS COMPUTING n'assume aucun engagement ni aucune responsabilité quant aux retards, à l'altération ou autres erreurs pouvant se produire dans la transmission des envois à partir du Site lorsque ces événements résultent de l'utilisation des réseaux ou d'une défaillance de Votre part. JIS COMPUTING n'est pas responsable de l'indisponibilité des réseaux (logiciel ou matériel)
                        qui ne sont pas entièrement sous son contrôle direct, ni de toute modification, suspension ou interruption de diffusion du Service, ainsi que de la continuité, pérennité, conformité, compatibilité ou performance de ceux-ci ou à l'absence de bugs.
                        <br>Vous reconnaissez et acceptez que le réseau Internet ou tout autre réseau utilisé aux fins de transmission des Données stockées puisse être saturé et/ou que les Données échangées au moyen du réseau puissent être détournées et conséquemment, Vous dégagez JIS COMPUTING et Transporteo de toute responsabilité à cet égard.
                        <br>De même, la responsabilité de JIS COMPUTING et Transporteo ne pourra être engagée du fait des interférences de tiers dans les systèmes de communication et de transmission que Vous utilisez ou des dysfonctionnement ou arrêt du Service dus à des négligences, à des fautes ou à un acte quelconque de votre part ou d’un tiers.
                        <br>JIS COMPUTING et Transporteo ne pourront notamment être tenus pour responsable de toute défaillance lors de la réception des Données et/ou des messages d’invitations transmis, dès lors que ces défaillances résulteraient des techniques de filtrage et/ou de blocage mis en place par des intermédiaires techniques, tels les fournisseurs d’accès. Dans le cas où Vous constateriez de telles défaillances,
                        Vous êtes invité à Vous rapprocher de votre fournisseur d’accès à Internet.
                        <br><B>6.3 Intégrité des Données</B>
                        <br>Lors du rapatriement des fichiers, il Vous appartient de tester et de vérifier que ceux-ci sont en tous points conformes aux fichiers prétendument déposés et qu’ils s ’intègrent correctement sur les applications informatiques d’origine ou sur des applications informatiques en tout point similaires.
                        Vous Vous engagez à reporter sur le formulaire de contact à l’adresse https://www.transporteo.com/fr/contact toute anomalie que vous aurez constatée dans les 24 heures suivant la réception des données en communiquant votre nom et votre e-mail.
                        <br>Dans le cas où Vous n’indiqueriez pas à l’adresse mentionnée ci-dessus l’anomalie constatée dans les délais et selon les procédures visées à l’alinéa précédent, JIS COMPUTING ne pourra être tenue pour responsable de tout préjudice résultant de la perte de ces Données dès lors que l’absence d’information la prive de la possibilité de procéder à une nouvelle
                        restitution des données épurées des anomalies initialement constatées dans un délai permettant d’éviter ou de limiter votre préjudice.
                        <br><B>6.4 Propriété des Données</B>
                        <br>Les Données stockées sur les serveurs de JIS COMPUTING restent votre pleine et exclusive propriété.
                        <br>JIS COMPUTING et Transporteo ne sont techniquement pas en mesure et s’interdisent de contrôler les Données stockées et/ou circulant depuis/vers votre serveur. En conséquence, JIS COMPUTING et Transporteo ne peut être tenue pour responsable de la présence sur votre serveur ou de la restitution sur votre terminal informatique, de données obtenues frauduleusement et/ou interdites par la loi ou le règlement.
                        Vous détenez seul la possibilité de choisir ou non d'enregistrer ces fichiers, de les conserver, de les partager, de les envoyer ou de les détruire, et assumez seul la responsabilité de ces choix.
                        <br>JIS COMPUTING vous informe néanmoins qu’elle est tenue de collaborer avec toute autorité judiciaire dûment mandatée pour conserver, contrôler voire éliminer les Données stockées sur ses serveurs.
                        <br>JIS COMPUTING ne saurait être tenue pour responsable de défauts et/ou de l’interruption du Service dans les cas de force majeure tels que définis à l’article 10 des présentes.
                        <br><B>6.5 Téléchargements et Cookies</B>
                        <br>Vous pourrez Vous voir proposer dans le cadre de la fourniture du Service, le téléchargement de logiciel(s) susceptible notamment de faciliter l’utilisation du Service. Vous Vous engagez lors de l’utilisation de ces logiciels dans le cadre du Service à respecter les présentes conditions d’utilisation. En tout état de cause, Vous reconnaissez que tout programme informatique que
                        <br>JIS COMPUTING vous proposerait de télécharger dans le cadre de l’utilisation du présent Service reste sa propriété, JIS COMPUTING ne vous concédant à ce titre qu’une simple licence d’utilisation. JIS COMPUTING se réserve par ailleurs le droit exclusif de procéder à toute intervention sur ces logiciels tant au titre de la maintenance que de l’interopérabilité avec tout autre programme ou matériel.
                        <br>Afin de Vous permettre de Vous connecter au Serveur et, le cas échéant, au Service, les serveurs de page web du Site doivent peuvent devoir placer des cookies sur Votre terminal. Les cookies sont stockés dans l’espace de stockage local au terminal et conservés sur ce dernier jusqu'au retrait effectué par Vous. Vous pouvez refuser les cookies en modifiant les paramètres de Votre navigateur. Néanmoins,
                        le refus des cookies pourra Vous empêcher de bénéficier de toutes les fonctionnalités du Service MyTransporteo.
                        <br><B>6.6 Limitations et modifications</B>
                        <br>Vous reconnaissez avoir reçu en ligne une information complète sur le Service et y souscrire en toute connaissance de cause.
                        <br>JIS COMPUTING ne pourra être tenu pour responsable de toute prétendue inadéquation du Service avec Vos besoins.
                        <br>JIS COMPUTING se réserve par ailleurs le droit de :
                        <br>&ensp;&ensp;&ensp;- mettre en place des barrières techniques limitant le nombre, le type et/ou la taille des données échangées et/ou le nombre d’invités afin de limiter les usages abusifs ou détournés du Service. Vous serez informé des limitations techniques apportées à l’utilisation du Service en ligne par courrier électronique lors de votre inscription ;
                        <br>&ensp;&ensp;&ensp;- modifier les présentes conditions générales d’utilisation du Service.
                        <br>Vous pourrez néanmoins résilier le présent Contrat dans les 30 jours suivant la mise en place de ces modifications. A défaut de résiliation dans ce délai, Vous serez réputé les avoir définitivement acceptées.
                        <br><B>6.7 Responsabilité concernant les Données</B>
                        <br>Vous êtes seul responsable des Données que Vous souhaitez déposer sur l’Espace de Stockage. En conséquence de quoi, Vous êtes responsable de tous les dommages qui peuvent découler de la communication de Données erronées ou incomplètes.
                        <br>Vous êtes responsable de tous les dommages causés par Vous-même à JIS COMPUTING. Vous vous engagez à indemniser JIS COMPUTING, en cas de demande, réclamation ou condamnation à des dommages et intérêts, dont JIS COMPUTING ferait l'objet à la suite du non-respect de Vos obligations ou aux dommages causés à autrui ou à elle-même par les Données que Vous auriez diffusés en utilisant le Service.
                        <br>Vous garantissez JIS COMPUTING contre toute réclamation, prétention ou exigence de tiers qui invoqueraient une violation de leurs droits, à la suite de l'utilisation du Service faite par Vous.
                        <br>Vous reconnaissez que toute utilisation du Service avec Vos éléments d'Identification est présumée faite par Vous et Vous sera imputée, à Vous charge d'apporter la preuve contraire.
                    </p>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;"><B>Article 7 INTERRUPTION DU SERVICE</B>
                        <br>Vous êtes informé et acceptez que tout ou partie du Service puisse, pour des raisons de réparation, évolution ou maintenance, être momentanément interrompu. JIS COMPUTING ne pourra être tenu pour responsable des conséquences résultant de toute interruption liée aux opérations de maintenance ou d’évolution. JIS COMPUTING fera tous ses efforts le cas échéant pour rétablir le Service dans les meilleurs délais.
                        Dans la mesure du possible, JIS COMPUTING Vous informera de toute interruption prévisible du Service supérieure à 24 (vingt-quatre) heures.
                        <br>Interruption de plein droit de la part de Transporteo :
                        <br>En cas de violation des termes des présentes Conditions Générales d’Utilisation, de tout détournement des fonctionnalités du Service, Transporteo sera en droit de procéder à l’interruption de plein droit du Service par simple email, sans que Vous puissiez solliciter une quelconque indemnité à ce titre. Vos Données seront alors définitivement supprimées des serveurs de JIS COMPUTING sous un délai de 30 jours sans que Vous puissiez solliciter un quelconque préjudice à ce titre.
                        <br>Dans le cas où Transporteo serait informé de l’utilisation du Service aux fins de stockage de Données contraires aux lois et règlements français ou de l’Etat à partir duquel Vous accédez au Service, Transporteo sera en droit de procéder immédiatement à l’interruption du Service, sans préavis. Vous en serez alors informé par email. Vos Données seront alors définitivement supprimées des serveurs sous un délai de 30 jours sans que Vous puissiez solliciter un quelconque préjudice à ce titre.
                    </p>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;"><B>Article 8 PROPRIETE INTELLECTUELLE</B>
                        <br>JIS COMPUTING est seule propriétaire du ou des logiciels par elle créés nécessaires au fonctionnement du Service et de toute documentation y relative.
                        Des droits de propriété intellectuelle, notamment d’utilisation et d’exploitation, relatifs à d’autres éléments du Service peuvent avoir été acquis par JIS Computing ou conférés à JIS COMPUTING, notamment des droits d’utilisation et d’exploitation d’éléments logiciels « open source ».
                        <br>JIS COMPUTING Vous concède un droit d’utilisation du ou des logiciels dont l’usage est strictement limité au bon fonctionnement du Service. Ce droit d’utilisation ne peut être détaché du présent Service. JIS COMPUTING se réserve le droit exclusif de procéder à toute intervention sur ces logiciels tant au titre de la maintenance que de l’interopérabilité avec tout autre programme ou matériel.
                    </p>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;"><B>Article 9 FORCE MAJEURE</B>
                        <br>TRANSPORTEO ne pourra pas être tenu pour responsable de l’inexécution partielle ou totale de ses obligations ou de tout retard dans l’exécution de celles-ci, si cette inexécution ou ce retard ont été provoqués par la survenance d’événements imprévisibles, raisonnablement irrésistibles et extérieurs. De façon expresse, sont considérés comme cas de force majeure ou cas fortuit, outre ceux habituellement retenus par la jurisprudence des cours et tribunaux français,
                        les cas suivants : grève totale ou partielle, lock-out, émeute, trouble civil, insurrection, guerre civile ou étrangère, risque nucléaire, embargo, confiscation, capture ou destruction par toute autorité publique, intempérie, épidémie, blocage des moyens de transport ou d'approvisionnement pour quelque raison que ce soit, tremblement de terre, incendie, tempête, inondation, dégâts des eaux, restrictions gouvernementales ou légales, modifications légales ou réglementaires des formes de commercialisation,
                        blocage des communications électroniques, y compris des réseaux de communications électroniques, un arrêt ou un incident de machines, un ou plusieurs virus informatiques, une attaque d’un ou plusieurs pirates, et tous cas non prévisible par JIS COMPUTING, remettant en cause les normes et standards de sa profession et tout autre cas indépendant de la volonté des parties empêchant l'exécution normale des obligations découlant du présent Contrat.
                        <br>JIS COMPUTING informera le client de tout retard résultant d’un cas de force majeure et prendra toutes les mesures pour tenter d’y remédier. Si le retard causé par la force majeure dépasse quatre-vingt-dix jours (90), chaque partie pourra résilier le présent Contrat.
                        Le paiement restera dû pour les obligations déjà accomplies et les parties solderont leurs comptes en conséquence, sans pouvoir prétendre à une quelconque indemnité de quelque nature que ce soit.
                    </p>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;"><B>Article 10 PROTECTION DES VOS DONNEES PERSONNELLES</B>
                        <br>Les informations recueillies à Votre sujet, notamment à partir des formulaires et des documents permettant de créer Votre accès au service sont nécessaires pour rendre le service tel que défini ci-dessus. Ces informations font l’objet d’un traitement informatique destiné à TRANSPORTEO pour assurer le bon fonctionnement du service MyTransporteo et ont fait l’objet de déclarations à la CNIL sous les n°1857056. TRANSPORTEO est le seul destinataire des données collectées. Elles sont stockées sur des serveurs situés en France.
                        <br>TRANSPORTEO s’interdit de communiquer les données que Vous lui communiquez, notamment via les formulaires, à quelques tiers que ce soit, sauf à ses sous-traitants, aux personnes qui, en raison de leur fonction, sont chargées de traiter Vos données et en cas de demande des autorités légalement habilitées. Conformément à la règlementation en vigueur, Vous disposez de droits concernant vos données :
                        <br><B>• Le droit à l’information</B>
                        <br>Lorsque des données à caractère personnel qui Vous concernent sont collectées directement auprès de vous-même, TRANSPORTEO vous fournit, au moment où les données en question sont obtenues ou avant leur obtention, toute une série d’informations. Ces informations sont notamment contenues dans les présentes Conditions Générales, et notamment :
                        <br>&ensp;&ensp;&ensp;- Les finalités du traitement sont de vous fournir un service de Gestion Informatisée de Documents en mode SaaS tel que défini à l’Article 2 ci- dessus.
                        <br>&ensp;&ensp;&ensp;- Les catégories de Données Personnelles concernées sont : l’adresse email utilisée pour vous connecter au Service, et des données relatives à votre connexion (adresse IP de votre terminal, détails techniques de voter navigateur, en particulier à des fins d’adaptation des interfaces visuelles)
                        <br>&ensp;&ensp;&ensp;- Vos Données Personnelles sont conservées durant la période d’abonnement qui lie le Client qui a créé votre accès, augmentée de 30 jours
                        <br>&ensp;&ensp;&ensp;- L’existence des droits ci-dessous :
                        <br><B>• Le droit d’accès (article 15 du RGPD)</B>
                        <br>Vous avez le droit d’obtenir de Transporteo la confirmation que Vos données personnelles sont ou ne sont pas traitées et, lorsqu’elles le sont, Vous avez le droit d’obtenir l’accès auxdites données ainsi qu’à un certain nombre d’informations complémentaire. Ce droit comprend également celui d’obtenir une copie des données qui font l’objet d’un traitement.
                        <br><B>• Le droit de rectification (article 16 du RGPD)</B>
                        <br>Vous avez le droit de demander que Vos données soient rectifiées ou complétées, et ce dans les meilleurs délais.
                        <br><B>• Le droit d’effacement ou « droit à l’oubli » (article 17 du RGPD)</B>
                        <br>Vous avez le droit de demander l’effacement de Vos données, dans les meilleurs délais. Vous êtes informé qu’en cas d’exercice de ce droit, Vous ne pourrez plus vous connecter au Service et, subséquemment, que Vous ne pourrez plus l’utiliser.
                        <br><B>• Le droit à la limitation du traitement (article 18 du RGPD)</B>
                        <br>Vous avez le droit, dans certains cas prévus par la loi, d’obtenir de Transporteo la limitation de Vos données. Lorsqu’une telle limitation est demandée, JIS COMPUTING ne pourra plus que stocker les données. Aucune autre opération ne pourra, en principe, avoir lieu sur Vos données personnelles. Vous ne pourrez donc plus utiliser le Service si Vous demandez l’exercice de ce droit.
                        <br><B>• Le droit à la portabilité des données (article 20 du RGPD)</B>
                        <br>Vous avez le droit de récupérer les données que Vous avez fournies au responsable de traitement, dans un format structuré, couramment utilisé et lisible par machine, et Vous avez le droit de transmettre ces données à un autre responsable du traitement, par exemple pour pouvoir changer de fournisseur de service.
                        <br><B>• Le droit à la communication d’une violation de données à caractère personnel (article 34 du RGPD).</B>
                        <br>Le responsable de traitement est obligé Vous notifier les violations de données susceptibles de Vous exposer à un risque élevé à ses droits et libertés.
                        <br><B>• Le droit d'introduire une réclamation auprès d'une autorité de contrôle (article 15 du RGPD).</B>
                        <br>Vous pouvez introduire une réclamation auprès de la CNIL en France, si Vous jugez que Vos droits n’ont pas été respectés ou qu’un traitement effectué dans le cadre du Service est susceptible de Vous porter préjudice.
                        <br>Vous pouvez exercer ces droits en adressant un courriel sur le formulaire de contact à l’adresse https://www.transporteo.com/fr/contact ou en contactant TRANSPORTEO par courrier postal à Transporteo International, 2 Rue Maryse Bastié, 93600 Aulnay-Sous-Bois - France
                    </p>
                    <p class="g-color-black g-line-height-1_8" style="text-align : justify;"><B>Article 11 CONFIDENTIALITE DES DONNEES PERSONNELLES CONTENUES DANS LES DOCUMENTS DEPOSES DANS MYTRANSPORTEO</B>
                        <br><B>11.1 Objet</B>
                        <br>Le présent article a pour objet de définir les conditions dans lesquelles le JIS COMPUTING, le Sous-Traitant, s’engage à effectuer pour le compte du Client, le Responsable de Traitement, qui a créé Votre accès au Service, les opérations de traitement de données à caractère personnel définies ci-après.
                        Dans le cadre de leurs relations contractuelles, les parties s ’engagent à respecter la réglementation en vigueur applicable au traitement de données à caractère personnel et, en particulier, le règlement (UE) 2016/679 du Parlement européen et du Conseil du 27 avril 2016 applicable à compter du 25 mai 2018 (ci-après, « le règlement européen sur la protection des données » ou le RGPD).
                        <br>Pour l’exécution du Service, JIS COMPUTING (ci-après « le Sous-Traitant ») met à Votre disposition, en tant qu’Utilisateur Final agissant sous l’autorité du Responsable de Traitement (le Client qui a créé votre accès au Service), les informations nécessaires suivantes :
                        <br><B>11.1.1 Service offert par Transporteo à ses clients</B>
                        <br>Ce service est décrit à l ’Article 2 ci-dessus.
                        <br><B>11.1.2 Finalité du traitement opéré par le Sous-Traitant</B>
                        <br>JIS COMPUTING, le Sous-Traitant est autorisé à traiter pour le compte du Client, le Responsable de Traitement, les données à caractère personnel potentiellement comprises dans les documents que le Responsable de Traitement dépose au sein de MyTransporteo.
                        La nature des opérations réalisées sur les données est définie à l’article – DESCRIPTION DU SERVICE ci-dessus.
                        <br>La finalité du traitement opéré par JIS COMPUTING est la gestion informatisée des documents du Client Responsable du Traitement, en ligne (dans une infrastructure d’informatique en nuage, Cloud Computing).
                        <br>Les potentielles finalités ultérieures, pour lesquelles le Client Responsable du Traitement traite les données à caractère personnel potentiellement contenues dans les documents qu’il dépose au sein de MyTransporteo sont déterminées par ledit Client Responsable de Traitement. Ces finalités peuvent être, par exemple et sans que cela soit limitant, la gestion de commandes, l’administration des services d’une collectivité locale, la gestion de dossier de contentieux, la gestion de personnel ou de ressources humaines, etc.
                        <br><B>11.1.3 Données à Caractère Personnel traitées dans le cadre du contrat de sous-traitance</B>
                        <br>Les catégories de données à caractère personnel traitées dans le cadre du service ne sont pas déterminées par JIS COMPUTING mais par le Client Responsable du Traitement. En effet, il s’agit de données présentes dans les documents déposés dans MyTransporteo par le Client, et JIS COMPUTING n’a aucun contrôle sur la décision que le Client prend de déposer des documents dans MyTransporteo ni aucun moyen de déterminer ou de prédire quelles données ces documents contiennent ou contiendront.
                        <br>Le Responsable de Traitement est la seule Partie en capacité de déterminer et de documenter lesdites catégories de données à caractère personnel.
                        <br><B>11.1.4 Catégories de personnes concernées par les DCP dans le cadre de la sous-traitance</B>
                        <br>De la même façon, les catégories de personnes concernées par les DCP présentes dans les documents déposés dans MyTransporteo par le Client Responsable de Traitement ne sont pas déterminées par JIS COMPUTING mais par le Client, Responsable de Traitement.
                        <br>Le Client est la seule Partie en capacité de déterminer et de documenter lesdites catégories de personnes concernées.
                        <br><B>11.1.5 Durée de conservation des DCP dans le cadre du contrat de sous-traitance</B>
                        <br>De la même façon, la durée de conservation des DCP potentiellement contenues dans les documents déposés dans MyTransporteo et/ou indexées par MyTransporteo n’est pas du ressort de JIS COMPUTING mais de celui du Client, Responsable de Traitement.
                        <br>Le Client est la seule Partie en capacité de déterminer et de documenter ladite durée de conservation.
                        <br><B>11.1.6 Obligations de JIS COMPUTING, le Sous-Traitant, vis -à-vis du Client, le Responsable de Traitement</B>
                        <br>Le Sous-Traitant s'engage à :
                        <br><B>11.1.6.1 Finalité</B>
                        <br>Traiter les données à caractère personnel éventuellement contenues dans les documents déposés par le Client dans MyTransporteo uniquement pour la ou les seule(s) finalité(s) qui fait/font l’objet de la sous-traitance (Finalité du Service, voir ci-dessus).
                        <br><B>11.1.6.2 Conformité</B>
                        <br>Traiter les données conformément aux dispositions des présent es conditions générales et/ ou de tout contrat liant les Parties, ou, le cas échéant, aux instructions documentées du Responsable de Traitement acceptées par les deux Parties.
                        Si le Sous -Traitant considère qu’une instruction à lui donnée par le Responsable de Traitement constitue une violation du RGPD ou de toute autre disposition du droit de l’Union ou du droit des Etats membres relative à la protection des données, il en informe le Responsable de Traitement de façon diligente et spontanée. En outre, si le Sous-Traitant est tenu de procéder à un transfert de données vers un pays tiers ou à une organisation internationale, en vertu du droit de l’Union ou du droit de l’Etat membre auquel il est soumis, il doit informer le Responsable du Traitement de cette obligation juridique avant le traitement, sauf si le droit concerné interdit une telle information pour des motifs importants d'intérêt public
                        <br><B>11.1.6.3 Confidentialité</B>
                        <br>Garantir la confidentialité des données à caractère personnel traitées dans le cadre du présent contrat et en particulier veiller à ce que les personnes autorisées à traiter les données à caractère personnel en vertu du présent contrat :
                        <br>&ensp;&ensp;&ensp;• s ’engagent à respecter la confidentialité ou soient soumises à une obligation légale appropriée de confidentialité
                        <br>&ensp;&ensp;&ensp;• reçoivent la formation nécessaire en matière de protection des données à caractère personnel.
                        <br><B>11.1.6.4 Privacy by Design</B>
                        <br>Prendre en compte, s’agissant de ses outils, produits, applications ou services, les principes de protection des données dès la conception et de protection des données par défaut.
                        <br><B>11.1.6.5 Fournisseurs </B>
                        <br>Le Sous-Traitant est autorisé à faire appel à tout fournisseur permettant de rendre opérationnels le Service (ci-après, les « Fournisseurs »), notamment pour mener les activités de traitement suivantes : Hébergement de serveurs, opérateurs de centre de données, fournisseurs de solution d’informatique en nuage (Cloud Computing), fournisseurs de solution de sauvegardes, accès à un réseau de données Internet ou similaire, service de personnalisation de document etc.
                        <br>Tout Fournisseur est tenu de respecter les obligations du présent contrat pour le compte et selon les instructions du Responsable de Traitement. Il appartient au Sous-Traitant initial de s ’assurer que le Fournisseur présente les mêmes garanties suffisantes quant à la mise en œuvre de mesures techniques et organisationnelles appropriées de manière à ce que le traitement réponde aux exigences du règlement européen sur la protection des données. Si le Fournisseur ne remplit pas ses obligations en matière de protection des données, le Sous-Traitant demeure responsable devant le Responsable de Traitement de l’exécution par le Fournisseur de ses obligations.
                        <br><B>11.1.6.6 Droit d’information des personnes concernées</B>
                        <br>Il appartient au Responsable de Traitement de fournir l’information aux personnes concernées par les opérations de traitement au moment de la collecte des données.
                        Le Sous-Traitant pourra mettre à disposition du Client des mentions et dispositions type afin de l’aider à véhiculer l’information aux personnes concernées que les données à caractère personnel les concernant peuvent faire l’objet d’un traitement par le Sous-Traitant.

                        <br><B>11.1.6.7 Exercice des droits des personnes</B>
                        <br>Dans la mesure du possible, le Sous-Traitant doit aider le Responsable de Traitement à s’acquitter de son obligation de donner suite aux demandes d’exercice des droits des personnes concernées : droit d’accès, de rectification, d’effacement et d’opposition, droit à la limitation du traitement, droit à la portabilité des données, droit de ne pas faire l’objet d’une décision individuelle automatisée (y compris le profilage). Le Responsable de Traitement a l’entier contrôle des données à caractère personnel en relation avec les personnes concernées et le Sous-Traitant fait ses meilleurs efforts afin de mettre en œuvre les mesures techniques et organisationnelles permettant au Responsable
                        de Traitement de s ’acquitter de ses obligations de donner suite aux demandes d’exercice des droits des personnes concernées.
                        <br><B>11.1.6.8 Notification des violations de données à caractère personnel</B>
                        <br>Le Sous-Traitant notifie au Responsable de Traitement toute violation de données à caractère personnel dans un délai maximum de 72 heures après en avoir pris connaissance et par tout moyen approprié, notamment un des moyens suivants : courriel, appel téléphonique, courrier écrit, fax, SMS etc. Cette notification est accompagnée de toute documentation utile afin de permettre au Responsable de Traitement, si nécessaire, de notifier cette violation à l’autorité de contrôle compétente ainsi qu’aux personnes concernées.
                        <br>La notification contient, dans la mesure du possible :
                        <br>&ensp;&ensp;&ensp;• la description de la nature de la violation de données à caractère personnel y compris, si possible, les catégories et le nombre approximatif de personnes concernées par la violation et les catégories et le nombre approximatif d'enregistrements de données à caractère personnel concernés ;
                        <br>&ensp;&ensp;&ensp;• le nom et les coordonnées du délégué à la protection des données ou d'un autre point de contact auprès duquel des informations supplémentaires peuvent être obtenues ;
                        <br>&ensp;&ensp;&ensp;• la description des conséquences probables de la violation de données à caractère personnel ;
                        <br>&ensp;&ensp;&ensp;• la description des mesures prises ou que le Sous-Traitant propose de prendre pour remédier à la violation de données à caractère personnel, y compris, le cas échéant, les mesures pour en atténuer les éventuelles conséquences négatives. Si, et dans la mesure où il n’est pas possible de fournir toutes ces informations en même temps, les informations peuvent être communiquées de manière échelonnée sans retard indu.
                        <br><B>11.1.6.9 Aide du Sous-Traitant dans le cadre du respect par le Responsable de Traitement de ses obligations</B>
                        <br>Le Sous-Traitant aide et conseille le Responsable de Traitement pour la réalisation d’analyses d’impact relative à la protection des données. Le Sous-Traitant peut aussi aider et conseiller le Responsable de Traitement pour la réalisation de la consultation préalable de l’autorité de contrôle.
                        <br><B>11.1.6.10 Mesures de sécurité</B>
                        <br>En ce qui concerne les documents déposés dans MyTransporteo, le Sous-Traitant s’engage à mettre en œuvre les mesures de sécurité suivantes :
                        <br>&ensp;&ensp;&ensp;- le chiffrement des documents ;
                        <br>&ensp;&ensp;&ensp;- les moyens permettant de garantir la confidentialité, l'intégrité, la disponibilité et la résilience constantes des systèmes et des services de traitement ;
                        <br>&ensp;&ensp;&ensp;- les moyens permettant de rétablir la disponibilité des données à caractère personnel et l'accès à celles-ci dans des délais appropriés en cas d'incident physique ou technique ;
                        <br>&ensp;&ensp;&ensp;- une procédure visant à tester, à analyser et à évaluer régulièrement l'efficacité des mesures techniques et organisationnelles pour assurer la sécurité du traitement
                        <br><B>11.1.6.11 Sort des données</B>
                        <br>Au terme de la prestation de services relatifs au traitement de ces données, le Sous-Traitant s ’engage à :
                        <br>Au choix du Responsable du Traitement :
                        <br>&ensp;&ensp;&ensp;- détruire toutes les données du Client, dont les données à caractère personnel ou
                        <br>&ensp;&ensp;&ensp;- à renvoyer toutes les données, dont les données à caractère personnel au Responsable de Traitement ou
                        <br>&ensp;&ensp;&ensp;- à renvoyer les données à caractère personnel au Sous-Traitant désigné par le Responsable de Traitement
                        <br>Le renvoi doit s ’accompagner de la destruction de toutes les copies existantes dans les systèmes d’information du Sous-Traitant. Une fois détruites, le Sous- Traitant doit justifier par écrit de la destruction.
                        <br><B>11.1.6.12 Délégué à l a protection des données</B>
                        <br>Le Sous-Traitant communique au Responsable de Traitement le nom et les coordonnées de son délégué à la protection des données, s ’il en a désigné un conformément à l’article 37 du règlement européen sur la protection des données, ou de toute autre personne on service assumant des fonctions similaires pour le compte du Sous-Traitant. Cette personne ou ce service sera joignable par courriel à dpo@MyTransporteo.com
                        <br><B>11.1.6.13 Registre des catégories d’activités de traitement</B>
                        <br>Le Sous-Traitant déclare être e en capacité de produire un registre de toutes les catégories d’activités de traitement effectuées pour le compte du Responsable de Traitement comprenant :
                        <br>&ensp;&ensp;&ensp;- le nom et les coordonnées du Responsable de Traitement pour le compte duquel il agit, des éventuels Sous-Traitants ou fournisseurs et, le cas échéant, du délégué à la protection des données ;
                        <br>&ensp;&ensp;&ensp;- les catégories de traitements effectués pour le compte du Responsable de Traitement ;
                        <br>&ensp;&ensp;&ensp;- les destinataires des données à caractère personnel, y compris prestataires, fournisseurs et Sous-Traitants ultérieurs ;
                        <br>&ensp;&ensp;&ensp;- le cas échéant, les transferts de données à caractère personnel vers un pays tiers ou à une organisation internationale, y compris l'identification de ce pays tiers ou de cette organisation internationale et, dans le cas des transferts visés à l'article 49, paragraphe 1, deuxième alinéa du règlement européen sur la protection des données, les documents attestant de l'existence de garanties appropriées ;
                        <br>&ensp;&ensp;&ensp;- dans la mesure du possible, une description générale des mesures de sécurité techniques et organisationnelles, y compris entre autres, selon les besoins :
                        <br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;o le chiffrement des documents déposés dans MyTransporteo ;
                        <br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;o des moyens permettant de garantir la confidentialité, l'intégrité, la disponibilité et la résilience constantes des systèmes et des services de traitement ;
                        <br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;o des moyens permettant de rétablir la disponibilité des données, dont données à caractère personnel et l'accès à celles-ci dans des délais appropriés en cas d'incident physique ou technique;
                        <br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;o une procédure visant à tester, à analyser et à évaluer régulièrement l'efficacité des mesures techniques et organisationnelles pour assurer la sécurité du traitement.
                        <br><B>11.1.6.14 Documentation et information</B>
                        <br>Le Sous-Traitant met à la disposition du Responsable de Traitement les informations ou la documentation nécessaire pour démontrer le respect de toutes ses obligations et pour permettre la réalisation d'audits, y compris des inspections, par le responsable du traitement ou un autre auditeur qu'il a mandaté, et contribuer à ces audits.
                        <br><B>11.1.7 Obligations du Responsable de Traitement vis-à-vis du Sous-Traitant</B>
                        <br><B>11.1.7.1 Information des personnes concernées</B>
                        <br>Le Responsable de Traitement s’engage à informer les personnes concernées du fait que les données personnelles les concernant qui sont contenues dans des documents peuvent faire l’objet d’un stockage dans une solution de Gestion Electronique de Documents MyTransporteo et que des traitements de type indexation et lecture optiques puis stockage de ces données peuvent être effectués au sein de la solution de Gestion Electronique de Documents MyTransporteo.
                        <br><B>11.1.7.2 Licéité</B>
                        <br>Le Responsable de Traitement s’engage, lorsqu’il dépose un document dans MyTransporteo qui contient des données à caractère personnel, à le faire uniquement de façon licite, c ’est-à-dire lorsque :
                        <br>&ensp;&ensp;&ensp;1. le document est nécessaire à l'exécution d'un contrat auquel la personne concernée est partie ou à l'exécution de mesures précontractuelles prises à la demande de celle-ci (vente, prestation, commande, devis …)
                        <br>&ensp;&ensp;&ensp;2. la personne concernée a consenti au traitement de ses données à caractère personnel pour une ou plusieurs finalités spécifiques, et ce consentement a été recueilli dans les termes et selon les obligations du RGPD, en particulier après avoir reçu une information claire sur les finalités et de façon positive (pas de case de consentement pré-cochée par exemple)
                        <br>&ensp;&ensp;&ensp;3. le traitement ou le document est nécessaire au respect d'une obligation légale à laquelle le responsable du traitement est soumis, notamment établissement et conservation de factures ou de bulletins de salaire ;
                        <br>&ensp;&ensp;&ensp;4. le traitement ou le document est nécessaire à la sauvegarde des intérêts vitaux de la personne concernée ou d'une autre personne physique ;
                        <br>&ensp;&ensp;&ensp;5. le traitement ou le document est nécessaire à l'exécution d'une mission d'intérêt public ou relevant de l'exercice de l'autorité publique dont est investi le Responsable du Traitement ;
                        <br>&ensp;&ensp;&ensp;6. le traitement ou le document est nécessaire aux fins des intérêts légitimes poursuivis par le responsable du traitement ou par un tiers, à moins que ne prévalent les intérêts ou les libertés et droits fondamentaux de la personne concernée qui exigent une protection des données à caractère personnel, notamment lorsque la personne concernée est un enfant.
                        <br><B>11.1.7.3 Données Sensibles</B>
                        <br>Lorsque les documents qu’il dépose dans MyTransporteo contiennent potentiellement des données sensibles au sens du RGPD, le Responsable de Traitement s ’engage à le faire de façon licite, c ’est-à-dire dans un des cas prévus par le RGPD et qui sont rappelés ci-dessous. Dans ce cas, le Responsable de Traitement s’engage ou bien à déposer ces documents dans un coffre-fort numérique proposé optionnellement au sein de MyTransporteo ou bien à effectuer s ’il le juge nécessaire, une éventuelle analyse d’impact et à prendre en charge les éventuelles mesures correctives qui devront être mise en place à la suite de ladite analyse d’impact.
                        <br>Plus généralement, le Responsable du Traitement s’engage à assumer l’entière responsabilité relative au fait de déposer dans MyTransporteo des documents contenant des données sensibles au sens du RGPD.
                        <br>Le RGPD considère comme des données sensibles les données à caractère personnel qui révèlent l'origine raciale ou ethnique, les opinions politiques, les convictions religieuses ou philosophiques ou l'appartenance syndicale, ainsi les données génétiques, les données biométriques aux fins d'identifier une personne physique de manière unique, les données concernant la santé ou les données concernant la vie sexuelle ou l'orientation sexuelle d'une personne physique. Les numéros d’identification nationaux (numéros NIR ou INSEE en France) sont aussi considérés comme des données sensibles, ainsi que les données relatives aux condamnations pénales et aux infractions.
                        <br>Le traitement de telles données n’est possible que dans les cas suivants :
                        <br>&ensp;&ensp;&ensp;1. la personne concernée a donné son consentement explicite au traitement de ces données à caractère personnel pour une ou plusieurs finalités spécifiques, sauf lorsque le droit de l'Union ou le droit de l'État membre prévoit que ce consentement ne peut pas être donné par la personne concernée ;
                        <br>&ensp;&ensp;&ensp;2. le traitement est nécessaire aux fins de l'exécution des obligations et de l'exercice des droits propres au responsable du traitement ou à la personne concernée en matière de droit du travail, de la sécurité sociale et de la protection sociale, dans la mesure où ce traitement est autorisé par le droit de l'Union, par le droit d'un État membre ou par une convention collective conclue en vertu du droit d'un État membre qui prévoit des garanties appropriées pour les droits fondamentaux et les intérêts de la personne concernée ;
                        <br>&ensp;&ensp;&ensp;3. le traitement est nécessaire à la sauvegarde des intérêts vitaux de la personne concernée ou d'une autre personne physique, dans le cas où la personne concernée se trouve dans l'incapacité physique ou juridique de donner son consentement ;
                        <br>&ensp;&ensp;&ensp;4. le traitement est effectué, dans le cadre de leurs activités légitimes et moyennant les garanties appropriées, par une fondation, une association ou tout autre organisme à but non lucratif et poursuivant une finalité politique, philosophique, religieuse ou syndicale, à condition que ledit traitement se rapporte exclusivement aux membres ou aux anciens membres dudit organisme ou aux personnes entretenant avec celui-ci des contacts réguliers en liaison avec ses finalités et que les données à caractère personnel ne soient pas communiquées en dehors de cet organisme sans le consentement des personnes concernées ;
                        <br>&ensp;&ensp;&ensp;5. le traitement porte sur des données à caractère personnel qui sont manifestement rendues publiques par la personne concernée;
                        <br>&ensp;&ensp;&ensp;6. le traitement est nécessaire à la constatation, à l'exercice ou à la défense d'un droit en justice ou chaque fois que des juridictions agissent dans le cadre de leur fonction juridictionnelle ;
                        <br>&ensp;&ensp;&ensp;7. le traitement est nécessaire pour des motifs d'intérêt public important, sur la base du droit de l'Union ou du droit d'un État membre qui doit être proportionné à l'objectif poursuivi, respecter l'essence du droit à la protection des données et prévoir des mesures appropriées et spécifiques pour la sauvegarde des droits fondamentaux et des intérêts de la personne concernée ;
                        <br>&ensp;&ensp;&ensp;8. le traitement est nécessaire aux fins de la médecine préventive ou de la médecine du travail, de l'appréciation de la capacité de travail du travailleur, de diagnostics médicaux, de la prise en charge sanitaire ou sociale, ou de la gestion des systèmes et des services de soins de santé ou de protection sociale sur la base du droit de l'Union, du droit d'un État membre ou en vertu d'un contrat conclu avec un professionnel de la santé et soumis aux conditions et garanties visées ci-dessous ;
                        <br>&ensp;&ensp;&ensp;9. le traitement est nécessaire pour des motifs d'intérêt public dans le domaine de la santé publique, tels que la protection contre les menaces transfrontalières graves pesant sur la santé, ou aux fins de garantir des normes élevées de qualité et de sécurité des soins de santé et des médicaments ou des dispositifs médicaux, sur la base du droit de l'Union ou du droit de l'État membre qui prévoit des mesures appropriées et spécifiques pour la sauvegarde des droits et libertés de la personne concernée, notamment le secret professionnel;
                        <br>&ensp;&ensp;&ensp;10. le traitement est nécessaire à des fins archivistiques dans l'intérêt public, à des fins de recherche scientifique ou historique ou à des fins statistiques, sur la base du droit de l'Union ou du droit d'un État membre qui doit être proportionné à l'objectif poursuivi, respecter l'essence du droit à la protection des données et prévoir des mesures appropriées et spécifiques pour la sauvegarde des droits fondamentaux et des intérêts de la personne concernée.
                        <br>Qui plus est, les données sensibles peuvent faire l'objet d'un traitement, si ces données sont traitées par un professionnel de la santé soumis à une obligation de secret professionnel conformément au droit de l'Union, au droit d'un État membre ou aux règles arrêtées par les organismes nationaux compétents, ou sous sa responsabilité, ou par une autre personne également soumise à une obligation de secret conformément au droit de l'Union ou au droit d'un État membre ou aux règles arrêtées par les organismes nationaux compétents.
                        <br>Le traitement des données à caractère personnel relatives aux condamnations pénales et aux infractions ou aux mesures de sûreté connexes, ne peut être effectué que sous le contrôle de l'autorité publique, ou si le traitement est autorisé par le droit de l'Union ou par le droit d'un État membre qui prévoit des garanties appropriées pour les droits et libertés des personnes concernées. Tout registre complet des condamnations pénales ne peut être tenu que sous le contrôle de l'autorité publique.
                        <br><B>11.1.7.4 Respects des obligations du RGPD</B>
                        <br>Le Responsable de Traitement s ’engage à respecter les obligations du RGPD et à veiller, au préalable et pendant toute la durée du traitement, au respect des obligations prévues par le règlement européen sur la protection des données de la part du Sous-Traitant.
                        En particulier, le Responsable de Traitement s’engage, s ’il y est obligé ou si cela est fortement recommandé, à tenir un registre des traitements mentionnant notamment, en ce qui concerne les traitements qu’il effectue ou qui sont effectués pour son compte sur des données à caractère personnel : les finalités des traitements, les catégories de données à caractère personnel, les catégories de personne concernée et la durée de conservation des données à caractère personnel.
                        <br><B>11.1.7.5 Supervision</B>
                        <br>Le Responsable de Traitement s’engage à superviser le traitement, y compris, si cela s ’avère approprié, réaliser des audits et des inspections auprès du Sous-Traitant. Dans le cas ou de tels audits et inspections seraient réalisés, le Responsable de Traitement s’engage à mettre à disposition du Sous-Traitant les résultats desdits audits et inspection et à permettre au Sous-Traitant de les transmettre à ses clients et prospects ou de les rendre publics.
                        <br><B>11.1.7.6 Instructions données au Sous-Traitant</B>
                        <br>Le Responsable de Traitement s’engage à documenter par écrit toute instruction concernant le traitement des données par le Sous-Traitant.
                        <br><B>11.2 Obligations communes aux Parties</B>
                        <br>Les Parties s ’engagent individuellement et mutuellement à se conformer au texte du RGPD disponible sur le site de la commission européenne, et à toute réglementation nationale applicable qui porterait sur les traitements de données à caractère personnel. Ces réglementations feront foi dans le présent Contrat et complèteront ou prévaudront le cas échéant sur toutes les dispositions du présent Contrat.
                    </p>
                </div>
            </article>
        </div>
    </div>
</div>
<?php
if ($storeActive == 1) {
    include _VIEW_PATH . $lib->lang . "/common_footer_magasin.phtml";
} else {
    include _VIEW_PATH . $lib->lang . "/common_footer.phtml";
}

include _VIEW_PATH . "/common_include_files.phtml"; ?>