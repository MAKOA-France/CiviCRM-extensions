<?php

require_once 'evenement.civix.php';
use CRM_Evenement_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/ 
 */
function evenement_civicrm_config(&$config) {
  _evenement_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function evenement_civicrm_xmlMenu(&$files) {
  _evenement_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function evenement_civicrm_install() {
  _evenement_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function evenement_civicrm_postInstall() {
  _evenement_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function evenement_civicrm_uninstall() {
  _evenement_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function evenement_civicrm_enable() {
  _evenement_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function evenement_civicrm_disable() {
  _evenement_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function evenement_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _evenement_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function evenement_civicrm_managed(&$entities) {
  _evenement_civix_civicrm_managed($entities);
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
function evenement_civicrm_caseTypes(&$caseTypes) {
  _evenement_civix_civicrm_caseTypes($caseTypes);
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
function evenement_civicrm_angularModules(&$angularModules) {
  _evenement_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function evenement_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _evenement_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function evenement_civicrm_entityTypes(&$entityTypes) {
  _evenement_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function evenement_civicrm_themes(&$themes) {
  _evenement_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 *
function evenement_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 *
function evenement_civicrm_navigationMenu(&$menu) {
  _evenement_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _evenement_civix_navigationMenu($menu);
} // */


function evenement_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions)
{
  // skip permission checks for contact/create calls
  // (but keep the ones for email, address, etc./create calls)
  // note: unsetting the below would require the default ‘access CiviCRM’ permission
  $permissions['participant']['create'] = array();
  
  $permissions['participant']['update'] = array();

  // enforce ‘view all contacts’ check for contact/get, but do not test ‘access CiviCRM’
  $permissions['participant']['get'] = array('view all particpants');

  // add a new permission requirement for your own custom API call
  // (if all you want to enforce is ‘access CiviCRM’ you can skip the below altogether)
  $permissions['foo']['get'] = array('access CiviCRM', 'get all foos');

  // allow everyone to get info for a given event; also – another way to skip permissions
  if ($entity == 'participant' and $action == 'get' and $params['title'] == 'CiviCon 2038') {
      $params['check_permissions'] = false;
  }

  // skip permission checks for contact/create calls
  // (but keep the ones for email, address, etc./create calls)
  // note: unsetting the below would require the default ‘access CiviCRM’ permission
  $permissions['event']['create'] = array();
  
  $permissions['event']['update'] = array();

  // enforce ‘view all contacts’ check for contact/get, but do not test ‘access CiviCRM’
  $permissions['event']['get'] = array('view all events');

  // add a new permission requirement for your own custom API call
  // (if all you want to enforce is ‘access CisviCRM’ you can skip the below altogether)
  $permissions['foo']['get'] = array('access CiviCRM', 'get all foos');

  // allow everyone to get info for a given event; also – another way to skip permissions
  if ($entity == 'event' and $action == 'get' and $params['title'] == 'CiviCon 2038') {
      $params['check_permissions'] = false;
  }
}
