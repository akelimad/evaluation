import $ from 'jquery'

export default class chmAlert {

  static createAlert (message, type, timeout) {
    $('.chm-float-alert').alert('close')
    var alert = $('<div class="alert alert-' + type + ' fade in chm-float-alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>' + message + '</strong></div>')

    $(alert).appendTo('body')

    $(alert).delay(timeout).fadeOut(1000, function () {
      $(this).alert('close')
    })
  }

  static success (message, timeout = 3000) {
    this.createAlert(message, 'success', timeout)
  }

  static info (message, timeout = 3000) {
    this.createAlert(message, 'info', timeout)
  }

  static warning (message, timeout = 3000) {
    this.createAlert(message, 'warning', timeout)
  }

  static danger (message, timeout = 3000) {
    this.createAlert(message, 'danger', timeout)
  }

  static error (message, timeout = 3000) {
    this.createAlert(message, 'danger', timeout)
  }

  static getAlertBlock (type, messages, dismissible = true) {
    if (type === 'error') type = 'danger'
    if (typeof messages !== 'object') messages = {messages}
    var alert = '<div class="chm-alerts alert alert-' + type + ' alert-white rounded mb-10">'
    if (dismissible === true) {
      alert += '<button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>'
    }
    alert += '<div class="icon"><i class="' + this.getIcon(type) + '"></i></div>'
    alert += '<ul>'
    $.each(messages, function (k, m) {
      alert += '<li>' + m + '</li>'
    })
    alert += '</ul>'
    alert += '</div>'
    return alert
  }

  static getIcon (type) {
    var icon = ''
    switch (type) {
      case 'danger':
        icon = 'fa fa-times-circle'
        break
      case 'info':
        icon = 'fa fa-info-circle'
        break
      case 'warning':
        icon = 'fa fa-warning'
        break
      default:
        icon = 'fa fa-check'
    }
    return icon
  }

}
