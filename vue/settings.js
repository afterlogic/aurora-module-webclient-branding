import _ from 'lodash'

import typesUtils from 'src/utils/types'

class BrandingSettings {
  constructor (appData) {
    const brandingWebclientData = typesUtils.pObject(appData.BrandingWebclient)
    if (!_.isEmpty(brandingWebclientData)) {
      this.loginLogo = brandingWebclientData.LoginLogo
      this.tabsbarLogo = brandingWebclientData.TabsbarLogo
      this.mobileLoginLogo = brandingWebclientData.MobileLoginLogo
      this.productName = brandingWebclientData.ProductName
    }
  }

  saveBrandingsSettings ({ loginLogo, tabsbarLogo, mobileLoginLogo, productName }) {
    this.loginLogo = loginLogo
    this.tabsbarLogo = tabsbarLogo
    this.mobileLoginLogo = mobileLoginLogo
    this.productName = productName
  }
}

let settings = null

export default {
  init (appData) {
    settings = new BrandingSettings(appData)
  },
  saveBrandingsSettings (data) {
    settings.saveBrandingsSettings(data)
  },
  getBrandingsSettings () {
    return {
      loginLogo: settings?.loginLogo,
      tabsbarLogo: settings?.tabsbarLogo,
      mobileLoginLogo: settings?.mobileLoginLogo,
      productName: settings?.productName,
    }
  },

}
