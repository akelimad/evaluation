export default class chmCookie {

  static create (name, value, days = 365, cPath = '') {
    if (cPath === '') {
      cPath = this.url().replace(window.location.origin, '')
    }
    var cExpires = ''
    if (days) {
      var date = new Date()
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000))
      cExpires = '; expires=' + date.toGMTString()
    }
    document.cookie = name + '=' + value + cExpires + '; path=' + cPath
  }

  static read (name) {
    var match = document.cookie.match(new RegExp(name + '=([^;]+)'))
    return (match[1] !== undefined) ? match[1] : null
  }

  static erase (name) {
    this.create(name, '', -1)
  }

  static url (path = '') {
    var url = document.querySelector('link[rel="website"]').getAttribute('href')
    if (url.substr(-1) !== '/') {
      url = url + '/'
    }
    return url + path
  }

}
