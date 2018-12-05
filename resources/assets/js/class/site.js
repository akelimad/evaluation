export default class chmSite {

  static url (path = '') {
    var url = document.querySelector('link[rel="website"]').getAttribute('href')
    if (url.substr(-1) !== '/') {
      url = url + '/'
    }
    return url + path
  }

}
