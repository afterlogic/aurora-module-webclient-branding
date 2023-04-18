<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\BrandingWebclient;

use Aurora\System\SettingsProperty;

/**
 * @property bool $Disabled
 * @property string $LoginLogo
 * @property string $TabsbarLogo
 * @property string $TopIframeUrl
 * @property int $TopIframeHeightPx
 */

class Settings extends \Aurora\System\Module\Settings
{
    protected function initDefaults()
    {
        $this->aContainer = [
            "Disabled" => new SettingsProperty(
                false,
                "bool",
                null,
                "Setting to true disables the module",
            ),
            "LoginLogo" => new SettingsProperty(
                "",
                "string",
                null,
                "URL of logo image used on login page",
            ),
            "TabsbarLogo" => new SettingsProperty(
                "",
                "string",
                null,
                "URL of logo image used in top left corner of main interface",
            ),
            "TopIframeUrl" => new SettingsProperty(
                "",
                "string",
                null,
                "URL of iframe displaying custom content (e.g. advertisement) on top of main interface",
            ),
            "TopIframeHeightPx" => new SettingsProperty(
                0,
                "int",
                null,
                "Height of iframe with custom content, in pixels",
            ),
        ];
    }
}
