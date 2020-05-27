<?php

require_once 'koama_event_select_groups.civix.php';
use CRM_KoamaEventSelectGroups_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/ 
 */
function koama_event_select_groups_civicrm_config(&$config) {
  _koama_event_select_groups_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function koama_event_select_groups_civicrm_xmlMenu(&$files) {
  _koama_event_select_groups_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function koama_event_select_groups_civicrm_install() {
  _koama_event_select_groups_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function koama_event_select_groups_civicrm_postInstall() {
  _koama_event_select_groups_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function koama_event_select_groups_civicrm_uninstall() {
  _koama_event_select_groups_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function koama_event_select_groups_civicrm_enable() {
  _koama_event_select_groups_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function koama_event_select_groups_civicrm_disable() {
  _koama_event_select_groups_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function koama_event_select_groups_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _koama_event_select_groups_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function koama_event_select_groups_civicrm_managed(&$entities) {
  _koama_event_select_groups_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function koama_event_select_groups_civicrm_caseTypes(&$caseTypes) {
  _koama_event_select_groups_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function koama_event_select_groups_civicrm_angularModules(&$angularModules) {
  _koama_event_select_groups_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function koama_event_select_groups_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _koama_event_select_groups_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function koama_event_select_groups_civicrm_entityTypes(&$entityTypes) {
  _koama_event_select_groups_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function koama_event_select_groups_civicrm_themes(&$themes) {
  _koama_event_select_groups_civix_civicrm_themes($themes);
}


/* 
    Afficher et sélectionner (multi valeurs) les groupes qui sont des commissions dans un custom field de l'event
    Function ajoutée par Makoa le 6/10/19 par Usha & Antoine 
*/
function koama_event_select_groups_civicrm_fieldOptions($entity, $field, &$options, $params) {
	// print_r ( 'field***' + $field);
    if ($entity == 'Event' && $field == 'custom_68' ) {
        //recupere l'id du groupe commissions
        $parents = civicrm_api3('Group','get',array('title'=>'Commissions'));
        $idParents = $parents['id'];
        try {
    
            $result = civicrm_api3('Group', 'get',array('options' => array('limit' => 0,),));
            foreach($result['values'] as $value) {
                if ($value['parents'] == $idParents) {
                    $id = $value['id'];
                    $title = $value['title'];
                    $options[$id] = $title;
                }
            }
        } catch (CiviCRM_API3_Exception $e) {
          $error = $e->getMessage();
        }
  }
}
function  koama_event_select_groups_civicrm_buildForm ( $formName,  &$form )  { 
  // notez que le formulaire a été transmis par référence 
  $form->assign('intro_text',ts( 'hello world' )); 
 }

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 *
function koama_event_select_groups_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 *
function koama_event_select_groups_civicrm_navigationMenu(&$menu) {
  _koama_event_select_groups_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _koama_event_select_groups_civix_navigationMenu($menu);
} // */
