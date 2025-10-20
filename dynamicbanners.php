<?php
/**
 * Module Dynamic Banners - Bannières Dynamiques 
 * 
 * @author Aya Aziz
 * @version 1.0.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class DynamicBanners extends Module
{
    public function __construct()
    {
        $this->name = 'dynamicbanners';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'AYA AZIZ';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Bannières Dynamiques');
        $this->description = $this->l('Gestion des bannières dynamiques pour PrestaShop');
        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller?');
        
        // Charger la classe DynamicBanner
        $this->autoload();
        
        // Vérifier le dossier images
        $this->checkImageDirectory();
    }

    /**
     * Autoload des classes personnalisées
     */
    private function autoload()
    {
        $classPath = $this->getLocalPath() . 'classes/';
        if (file_exists($classPath . 'DynamicBanner.php')) {
            require_once $classPath . 'DynamicBanner.php';
        }
    }

    /**
     * Vérification du dossier images
     */
    private function checkImageDirectory()
    {
        $img_dir = $this->getLocalPath() . 'views/img/';
        if (!file_exists($img_dir)) {
            mkdir($img_dir, 0755, true);
        }
    }

    /**
     * Installation du module
     */
    public function install()
    {
        return parent::install()
            && $this->installDb()
            && $this->registerHooks();
    }

    /**
     * Création de la table dans la base de données
     */
    private function installDb()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "dynamic_banners` (
            `id_banner` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `image` varchar(255) NOT NULL,
            `link` varchar(255) NOT NULL,
            `position` varchar(50) NOT NULL,
            `active` tinyint(1) NOT NULL DEFAULT '1',
            `date_start` datetime NULL,
            `date_end` datetime NULL,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_banner`)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

        return Db::getInstance()->execute($sql);  
    }

    /**
     * Enregistrement des hooks
     */
    private function registerHooks()
    {
        $hooks = [
            'displayHome', 
            'displayFooter',
            'displayHeader',
            'displayShoppingCart',
            'displayNav1',
            'displayNav2', 
            'displayBanner',
            'displayLeftColumn',
            'displayRightColumn',
            'actionFrontControllerSetMedia'
        ];

        foreach ($hooks as $hook) {
            if (!$this->registerHook($hook)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Désinstallation du module
     */
    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDb();
    }

    /**
     * Suppression de la table de la base de données
     */
    private function uninstallDb()
    {
        return Db::getInstance()->execute(
            "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "dynamic_banners`"
        );
    }

    /**
     * Configuration du module (page admin)
     */
    public function getContent()
    {
        $output = '';

        // Message de bienvenue
        $output .= $this->displayConfirmation(
            $this->l('Module installé avec succès!')
        );

        // Lien vers la gestion des bannières
        $adminLink = $this->context->link->getAdminLink('AdminDynamicBanners');
        $output .= '
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-cogs"></i> ' . $this->l('Gestion des bannières') . '
            </div>
            <div class="panel-body">
                <p>' . $this->l('Pour gérer vos bannières, cliquez sur le bouton ci-dessous:') . '</p>
                <a href="' . $adminLink . '" class="btn btn-primary">
                    <i class="icon-image"></i> ' . $this->l('Gérer les bannières') . '
                </a>
            </div>
        </div>';

        return $output;
    }

    // =================================================================
    // HOOKS D'AFFICHAGE
    // =================================================================

    /**
     * HOOK: Affichage sur la page d'accueil
     */
    public function hookDisplayHome($params)
    {
        return $this->displayBanners('home');
    }

    /**
     * HOOK: Affichage dans le footer
     */
    public function hookDisplayFooter($params)
    {
        return $this->displayBanners('footer');
    }

    /**
     * HOOK: Affichage dans le header
     */
    public function hookDisplayHeader($params)
    {
        // Charge le CSS
        $this->context->controller->registerStylesheet(
            'dynamicbanners-css',
            $this->_path . 'views/css/dynamicbanners.css',
            ['media' => 'all', 'priority' => 150]
        );
        
        // Retourne les bannières header
        return $this->displayBanners('header');
    }

    /**
     * HOOK: Affichage sur la page panier
     */
    public function hookDisplayShoppingCart($params)
    {
        return $this->displayBanners('cart');
    }

    /**
     * HOOK: Affichage dans la navigation 1
     */
    public function hookDisplayNav1($params)
    {
        return $this->displayBanners('nav1');
    }

    /**
     * HOOK: Affichage dans la navigation 2  
     */
    public function hookDisplayNav2($params)
    {
        return $this->displayBanners('nav2');
    }

    /**
     * HOOK: Affichage bannière générale
     */
    public function hookDisplayBanner($params)
    {
        return $this->displayBanners('banner');
    }

    /**
     * HOOK: Affichage colonne gauche
     */
    public function hookDisplayLeftColumn($params)
    {
        return $this->displayBanners('left');
    }

    /**
     * HOOK: Affichage colonne droite
     */
    public function hookDisplayRightColumn($params)
    {
        return $this->displayBanners('right');
    }

    /**
     * HOOK: Chargement des CSS et JS
     */
    public function hookActionFrontControllerSetMedia($params)
    {
        // Charge le CSS seulement si le module est actif
        $this->context->controller->registerStylesheet(
            'dynamicbanners-css',
            $this->_path . 'views/css/dynamicbanners.css',
            ['media' => 'all', 'priority' => 150]
        );
    }

    // =================================================================
    // MÉTHODES D'AFFICHAGE
    // =================================================================

    /**
     * Méthode pour afficher les bannières selon la position
     */
    private function displayBanners($position)
    {
        // Requête SQL pour récupérer les bannières actives
        $sql = "SELECT * FROM `" . _DB_PREFIX_ . "dynamic_banners` 
                WHERE position = '" . pSQL($position) . "' 
                AND active = 1 
                AND (date_start IS NULL OR date_start <= NOW()) 
                AND (date_end IS NULL OR date_end >= NOW())
                ORDER BY date_add DESC";
        
        $banners = Db::getInstance()->executeS($sql);
        
        // Si pas de bannières, on n'affiche rien
        if (!$banners || empty($banners)) {
            return '';
        }

        // Vérifier si le template existe
        $templateFile = 'views/templates/front/' . $position . '.tpl';
        if (!file_exists($this->getLocalPath() . $templateFile)) {
            // Si le template n'existe pas, on utilise un template par défaut
            $templateFile = 'views/templates/front/default.tpl';
            if (!file_exists($this->getLocalPath() . $templateFile)) {
                return ''; // Aucun template trouvé
            }
        }

        // Préparer les données pour le template
        $this->context->smarty->assign([
            'banners' => $banners,
            'image_path' => $this->_path . 'views/img/',
            'module_path' => $this->_path,
            'physical_image_path' => $this->getLocalPath() . 'views/img/',
            'position' => $position
        ]);

        // Charge le template correspondant
        return $this->display(__FILE__, $templateFile);
    }

    /**
     * Méthode pour compter les bannières actives
     */
    public function getActiveBannersCount($position = null)
    {
        $sql = "SELECT COUNT(*) as total FROM `" . _DB_PREFIX_ . "dynamic_banners` 
                WHERE active = 1 
                AND (date_start IS NULL OR date_start <= NOW()) 
                AND (date_end IS NULL OR date_end >= NOW())";
        
        if ($position) {
            $sql .= " AND position = '" . pSQL($position) . "'";
        }
        
        $result = Db::getInstance()->getValue($sql);
        return (int)$result;
    }
}