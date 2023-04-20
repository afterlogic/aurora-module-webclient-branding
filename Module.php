<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\BrandingWebclient;

/**
 * Adds ability to specify logos URL for login screen and main interface of application.
 *
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing Afterlogic Software License
 * @copyright Copyright (c) 2023, Afterlogic Corp.
 *
 * @property Settings $oModuleSettings
 *
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

    /**
     * @return Module
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return Module
     */
    public static function Decorator()
    {
        return parent::Decorator();
    }

    /**
     * @return Settings
     */
    public function getModuleSettings()
    {
        return $this->oModuleSettings;
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

        if (!empty($TenantId)) {
            \Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::TenantAdmin);
            $oTenant = \Aurora\System\Api::getTenantById($TenantId);

            if ($oTenant) {
                $sLoginLogo = $this->oModuleSettings->GetTenantValue($oTenant->Name, 'LoginLogo', $sLoginLogo);
                $sTabsbarLogo = $this->oModuleSettings->GetTenantValue($oTenant->Name, 'TabsbarLogo', $sTabsbarLogo);
            }
        } else {
            $sLoginLogo = $this->oModuleSettings->GetValue('LoginLogo', $sLoginLogo);
            $sTabsbarLogo = $this->oModuleSettings->GetValue('TabsbarLogo', $sTabsbarLogo);
        }

        return array(
            'LoginLogo' => $sLoginLogo,
            'TabsbarLogo' => $sTabsbarLogo,
            'TopIframeUrl' => $this->_getUrlWithSeed($this->oModuleSettings->GetValue('TopIframeUrl', '')),
            'TopIframeHeightPx' => $this->oModuleSettings->GetValue('TopIframeHeightPx', ''),
        );
    }

    private function _getUrlFromParts($aParts)
    {
        $sUrl = '';
        if (!empty($aParts['scheme'])) {
            $sUrl .= $aParts['scheme'] . ':';
        }
        if (!empty($aParts['user']) || !empty($aParts['host'])) {
            $sUrl .= '//';
        }
        if (!empty($aParts['user'])) {
            $sUrl .= $aParts['user'];
        }
        if (!empty($aParts['pass'])) {
            $sUrl .= ':' . $aParts['pass'];
        }
        if (!empty($aParts['user'])) {
            $sUrl .= '@';
        }
        if (!empty($aParts['host'])) {
            $sUrl .= $aParts['host'];
        }
        if (!empty($aParts['port'])) {
            $sUrl .= ':' . $aParts['port'];
        }
        if (!empty($aParts['path'])) {
            $sUrl .= $aParts['path'];
        }
        if (!empty($aParts['query'])) {
            if (is_array($aParts['query'])) {
                $sUrl .= '?' . http_build_query($aParts['query']);
            } else {
                $sUrl .= '?' . $aParts['query'];
            }
        }
        if (!empty($aParts['fragment'])) {
            $sUrl .= '#' . $aParts['fragment'];
        }

        return $sUrl;
    }

    private function _getUrlWithSeed($sUrl)
    {
        $aParts = parse_url($sUrl);

        if (!empty($aParts['host']) || !empty($aParts['path'])) {
            $aQuery = [];
            if (isset($aParts['query']) && is_string($aParts['query']) && !empty($aParts['query'])) {
                parse_str($aParts['query'], $aQuery);
            }
            $aQuery['datetimeseed'] = date('Y-m-d-H-i-s');
            $aParts['query'] = http_build_query($aQuery);
        }

        return $this->_getUrlFromParts($aParts);
    }
    /**
     * Updates module's settings - saves them to config.json file or to user settings in db.
     * @param string $LoginLogo
     * @param string $TabsbarLogo
     * @param int $TenantId
     * @return boolean
     */
    public function UpdateSettings($LoginLogo, $TabsbarLogo, $TenantId = null)
    {
        $result = false;
        $oSettings = $this->getModuleSettings();
        if (!empty($TenantId)) {
            \Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::TenantAdmin);
            $oTenant = \Aurora\System\Api::getTenantById($TenantId);

            if ($oTenant) {
                $oSettings->SetTenantValue($oTenant->Name, 'LoginLogo', $LoginLogo);
                $oSettings->SetTenantValue($oTenant->Name, 'TabsbarLogo', $TabsbarLogo);
                $result = $oSettings->SaveTenantSettings($oTenant->Name);
            }
        } else {
            \Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::SuperAdmin);

            $oSettings->SetValue('LoginLogo', $LoginLogo);
            $oSettings->SetValue('TabsbarLogo', $TabsbarLogo);
            $result = $oSettings->Save();
        }

        return $result;
    }

    /***** public functions might be called with web API *****/
}
