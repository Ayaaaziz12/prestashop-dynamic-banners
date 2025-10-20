<?php
/**
 * Class DynamicBanner - Représente une bannière dynamique
 */

class DynamicBanner extends ObjectModel
{
    /** @var int ID de la bannière */
    public $id_banner;
    
    /** @var string Titre de la bannière */
    public $title;
    
    /** @var string Nom du fichier image */
    public $image;
    
    /** @var string Lien de redirection */
    public $link;
    
    /** @var string Position d'affichage */
    public $position;
    
    /** @var bool Statut actif/inactif */
    public $active;
    
    /** @var string Date de début d'affichage */
    public $date_start;
    
    /** @var string Date de fin d'affichage */
    public $date_end;
    
    /** @var string Date d'ajout */
    public $date_add;
    
    /** @var string Date de mise à jour */
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'dynamic_banners',
        'primary' => 'id_banner',
        'fields' => array(
            'title' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255),
            'image' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255),
            'link' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255),
            'position' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 50),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'date_start' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_end' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );
}