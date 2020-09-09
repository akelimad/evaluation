export default class chmUrl {

  /**
   * Get all parameters
   *
   * @return string params
   */
  static getAll () {
    var search = window.location.search.substring(1)
    if (search !== '') {
      return JSON.parse('{"' + decodeURI(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') + '"}')
    }
    return {}
  }

	/**
	 * Get url parameter
	 *
	 * @param string name
	 * @return string params
	 */
  static getParam (name, _default = null, url = null) {
    if (url === null) {
      url = window.location.href
    }
    var results = new RegExp('[?&]' + name + '=([^&#]*)').exec(url)
    if (results == null) {
      return _default
    } else {
      return results[1] || 0
    }
  }

	/**
	 * Change url parameter
	 *
	 * @param param string
   * @param value string
   * @param url string
	 * @param pushState bool
   *
	 * @return void
	 */
  static setParam (param, value, url = null, pushState = true) {
    if (url === null) {
      url = window.location.href
    }
    var reExp = new RegExp('[?|&]' + param + '=[0-9a-zA-Z_+-|.,;]*')
    if (reExp.test(url)) { // update
      reExp = new RegExp('[?&]' + param + '=([^&#]*)')
      var delimiter = reExp.exec(url)[0].charAt(0)
      url = url.replace(reExp, delimiter + param + '=' + value)
    } else { // add
      var newParam = param + '=' + value
      if (!url.indexOf('?')) {
        url += '?'
      }
      if (url.indexOf('#') > -1) {
        var urlparts = url.split('#')
        url = urlparts[0] + '&' + newParam + (urlparts[1] ? '#' + urlparts[1] : '')
      } else if (url.indexOf('?') === -1) {
        url += '?' + newParam
      } else {
        url += '&' + newParam
      }
    }
    if (pushState) {
      window.history.pushState(null, document.title, url)
    }
    return url
  }

  /**
	 * Delete parameter from url
	 *
	 * @param param string
	 * @return void
	 */
  static eraseParam (param, url = null, pushState = true) {
    if (url === null) {
      url = window.location.href
    }
    // prefer to use l.search if you have a location/link object
    var urlparts = url.split('?')
    if (urlparts.length >= 2) {
      var prefix = encodeURIComponent(param) + '='
      var pars = urlparts[1].split(/[&;]/g)
      // reverse iteration as may be destructive
      for (var i = pars.length; i-- > 0;) {
        // idiom for string.startsWith
        if (pars[i].lastIndexOf(prefix, 0) !== -1) {
          pars.splice(i, 1)
        }
      }
      url = urlparts[0] + '?' + pars.join('&')
    }
    if (pushState) {
      window.history.pushState(null, document.title, url)
    }
    return url
  }

  static eraseAllParams () {
    var url = window.location.href.split('?')[0]
    window.history.pushState(null, document.title, url)
  }

}
