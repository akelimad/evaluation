export default class chmSite {

  static url (path = '') {
    var url = ''
    var base = document.querySelector('base')
    if (base !== null) {
      url = base.getAttribute('href')
    }
    if (url.substr(-1) !== '/') {
      url = url + '/'
    }
    return url + path
  }

}
