<?php
/**
 * Admin Controller for Dynamic Banners - VERSION CORRIGÉE AVEC BOUTONS
 */

class AdminDynamicBannersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'dynamic_banners';
        $this->className = 'DynamicBanner';
        $this->identifier = 'id_banner';
        $this->lang = false;
        
        // ACTIONS - Très important pour les boutons!
        $this->actions = array('edit', 'delete');
        
        parent::__construct();

        $this->fields_list = array(
            'id_banner' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 25,
                'class' => 'fixed-width-xs'
            ),
            'title' => array(
                'title' => $this->l('Titre'),
                'width' => 'auto',
                'filter_key' => 'a!title'
            ),
            'image' => array(
                'title' => $this->l('Image'),
                'width' => 100,
                'align' => 'center',
                'callback' => 'displayImage',
                'orderby' => false,
                'search' => false
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'width' => 120,
                'callback' => 'formatPosition'
            ),
            'active' => array(
                'title' => $this->l('Statut'),
                'active' => 'status',
                'type' => 'bool',
                'width' => 60,
                'align' => 'center',
                'class' => 'fixed-width-sm'
            ),
            'date_add' => array(
                'title' => $this->l('Date ajout'),
                'width' => 130,
                'type' => 'datetime',
                'align' => 'center'
            )
        );

        // Actions groupées
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Supprimer'),
                'confirm' => $this->l('Supprimer les éléments sélectionnés?')
            )
        );
    }

    /**
     * Affichage de l'image dans la liste
     */
    public static function displayImage($image, $row)
    {
        if (!empty($image)) {
            $image_path = _MODULE_DIR_ . 'dynamicbanners/views/img/' . $image;
            return '<img src="' . $image_path . '" width="50" height="auto" style="border: 1px solid #ddd; border-radius: 4px;">';
        }
        return $this->l('Aucune image');
    }

    /**
     * Formatage de la position pour l'affichage
     */
    public static function formatPosition($position, $row)
    {
        $positions = array(
            'home' => '🏠 Accueil',
            'footer' => '⬇️ Footer',
            'cart' => '🛒 Panier',
            'header' => '⬆️ Header'
        );
        
        return isset($positions[$position]) ? $positions[$position] : $position;
    }

    /**
     * Initialisation de la page
     */
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();
        
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_banner'] = array(
                'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
                'desc' => $this->l('Ajouter une bannière'),
                'icon' => 'process-icon-new'
            );
        }
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Bannière'),
                'icon' => 'icon-picture-o'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Titre'),
                    'name' => 'title',
                    'required' => true,
                    'lang' => false,
                    'col' => 6
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Image'),
                    'name' => 'image',
                    'display_image' => true,
                    'required' => true,
                    'col' => 6,
                    'hint' => $this->l('Formats autorisés: JPG, PNG, GIF')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Lien'),
                    'name' => 'link',
                    'required' => true,
                    'lang' => false,
                    'col' => 6,
                    'hint' => $this->l('Exemple: https://www.example.com')
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Position'),
                    'name' => 'position',
                    'required' => true,
                    'options' => array(
                        'query' => array(
                            array('id' => 'home', 'name' => '🏠 Accueil'),
                            array('id' => 'footer', 'name' => '⬇️ Footer'),
                            array('id' => 'cart', 'name' => '🛒 Panier'),
                            array('id' => 'header', 'name' => '⬆️ Header')
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                    'col' => 4
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Date de début'),
                    'name' => 'date_start',
                    'col' => 3
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Date de fin'),
                    'name' => 'date_end',
                    'col' => 3
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Actif'),
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Oui')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Non')
                        )
                    ),
                    'col' => 2
                )
            ),
            'submit' => array(
                'title' => $this->l('Enregistrer'),
                'class' => 'btn btn-default pull-right'
            ),
            'buttons' => array(
                'save-and-stay' => array(
                    'title' => $this->l('Enregistrer et rester'),
                    'name' => 'submitAdd' . $this->table . 'AndStay',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save'
                )
            )
        );

        return parent::renderForm();
    }

    /**
     * Traitement de l'ajout
     */
    public function processAdd()
    {
        // Gestion de l'upload d'image
        if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
            $result = $this->processImageUpload();
            if (!$result) {
                return false;
            }
        } else {
            $this->errors[] = $this->l('Veuillez sélectionner une image');
            return false;
        }
        
        // Dates par défaut
        $_POST['date_add'] = date('Y-m-d H:i:s');
        $_POST['date_upd'] = date('Y-m-d H:i:s');
        
        return parent::processAdd();
    }

    /**
     * Traitement de la modification
     */
    public function processUpdate()
    {
        // Gestion de l'upload d'image seulement si nouvelle image fournie
        if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
            $result = $this->processImageUpload();
            if (!$result) {
                return false;
            }
        }
        
        $_POST['date_upd'] = date('Y-m-d H:i:s');
        
        return parent::processUpdate();
    }

    /**
     * Traitement de l'upload d'image
     */
    private function processImageUpload()
    {
        if (!isset($_FILES['image']) || empty($_FILES['image']['name'])) {
            $this->errors[] = $this->l('Aucun fichier sélectionné');
            return false;
        }
        
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->l('Erreur lors de l\'upload de l\'image');
            return false;
        }
        
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $allowed_types)) {
            $this->errors[] = $this->l('Type de fichier non autorisé. Utilisez JPG, PNG ou GIF.');
            return false;
        }
        
        $filename = md5($_FILES['image']['name'] . time()) . '.' . $extension;
        $path = _PS_MODULE_DIR_ . 'dynamicbanners/views/img/' . $filename;
        
        $img_dir = _PS_MODULE_DIR_ . 'dynamicbanners/views/img/';
        if (!file_exists($img_dir)) {
            mkdir($img_dir, 0755, true);
        }
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
            $_POST['image'] = $filename;
            return true;
        } else {
            $this->errors[] = $this->l('Erreur lors du déplacement du fichier');
            return false;
        }
    }
}