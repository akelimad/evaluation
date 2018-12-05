export default class chmUrl {

  /**
   * Get url parameter
   *
   * @param string name
   * @return string params
   */
  static getParam (name) {
    var results = new RegExp('[?&]' + name + '=([^&#]*)').exec(window.location.href)
    if (results == null) {
      return null
    } else {
      return results[1] || 0
    }
  }

  /**
   * Change url parameter
   *
   * @param param string
   * @param value string
   * @return void
   */
  static setParam (param, value) {
    var url = window.location.href
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
    window.history.pushState(null, document.title, url)
  }

  static eraseParam (param) {
    var url = window.location.href
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
    window.history.pushState(null, document.title, url)
  }

}
