import chmSite from './chmSite'

export default class chmCookie {

  static create (name, value, days = 365, cPath = '') {
    if (cPath === '') {
      //cPath = chmSite.url().replace(window.location.origin, '')
      cPath = "/"
    }
    var cExpires = ''
    if (days) {
      var date = new Date()
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000))
      cExpires = '; expires=' + date.toGMTString()
    }
    document.cookie = name + '=' + value + cExpires + '; path=' + cPath
  }

  static read (name, _default = null) {
    var cookies = document.cookie
    var patern = new RegExp(name + '=([^;]+)')
    if (patern.test(cookies)) {
      return cookies.match(patern)[1]
    }
    return _default
  }

  static erase (name) {
    this.create(name, '', -1)
  }

}
