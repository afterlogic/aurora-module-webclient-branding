<?php
/**
 * This code is licensed under AGPLv3 license or AfterLogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 * 
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing AfterLogic Software License
 * @copyright Copyright (c) 2018, Afterlogic Corp.
 */

namespace Aurora\Modules\BrandingWebclient;

/**
 * @package Modules
 */
class Module extends \Aurora\System\Module\AbstractWebclientModule
{
	/***** private functions *****/
	/**
	 * Initializes module.
	 * 
	 * @ignore
	 */
	public function init()
	{
		$oUser = \Aurora\System\Api::getAuthenticatedUser();

		//if (!empty($oUser) && $oUser->Role === \Aurora\System\Enums\UserRole::SuperAdmin)
		// {
			//$this->includeTemplate('StandardAuthWebclient_StandardAccountsSettingsFormView', 'Edit-Standard-Account-After', 'templates/AccountPasswordHintView.html', $this->sName);
		// }
	}
	

	/***** private functions *****/
	
	/***** public functions might be called with web API *****/
	/**
	 * Obtains list of module settings for authenticated user.
	 * 
	 * @return array
	 */
	public function GetSettings($TenantId = null)
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::Anonymous);

		$sLoginLogo = '';
		$sTabsbarLogo = '';

		$oSettings = $this->GetModuleSettings();
		if (!empty($TenantId))
		{
			\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::TenantAdmin);
			$oTenant = \Aurora\System\Api::getTenantById($TenantId);

			if ($oTenant)
			{
				$sLoginLogo = $oSettings->GetTenantValue($oTenant->Name, 'LoginLogo', $LoginLogo);		
				$sTabsbarLogo = $oSettings->GetTenantValue($oTenant->Name, 'TabsbarLogo', $TabsbarLogo);		
			}
		}
		else
		{
			$sLoginLogo = $oSettings->GetValue('LoginLogo', $LoginLogo);		
			$sTabsbarLogo = $oSettings->GetValue('TabsbarLogo', $TabsbarLogo);		
		}

		
		return array(
			'LoginLogo' => $sLoginLogo,
			'TabsbarLogo' => $sTabsbarLogo
		);
	}

	/**
	 * Updates module's settings - saves them to config.json file or to user settings in db.
	 * @param int $ContactsPerPage Count of contacts per page.
	 * @return boolean
	 */
	public function UpdateSettings($LoginLogo, $TabsbarLogo, $TenantId = null)
	{
		$oSettings = $this->GetModuleSettings();
		if (!empty($TenantId))
		{
			\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::TenantAdmin);
			$oTenant = \Aurora\System\Api::getTenantById($TenantId);

			if ($oTenant)
			{
				$oSettings->SetTenantValue($oTenant->Name, 'LoginLogo', $LoginLogo);		
				$oSettings->SetTenantValue($oTenant->Name, 'TabsbarLogo', $TabsbarLogo);		
				return $oSettings->SaveTenantSettings($oTenant->Name);
			}
		}
		else
		{
			\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::SuperAdmin);

			$oSettings->SetValue('LoginLogo', $LoginLogo);
			$oSettings->SetValue('TabsbarLogo', $TabsbarLogo);
			return $oSettings->Save();
		}
	}

	/***** public functions might be called with web API *****/
}
