import $ from 'jquery'

export default class chmModal {

  static show (params, options = {}) {
    var modalObject = {}
    params = $.extend({}, {
      type: 'POST'
    }, params)
    // Fire off the request
    var classInstance = this
    var loadingMessage = ('message' in options) ? options.message : ''
    var id = ('id' in options) ? options.id : ''
    var modalTemplate = this.loading(loadingMessage, id)

    var modalWidth = (window.outerWidth >= 600) ? 600 : (window.outerWidth - 20)

    if (options.width !== '' && window.outerWidth > (options.width + 20)) modalWidth = options.width
    modalTemplate.find('.modal-dialog').css('width', modalWidth)

    modalTemplate.attr('chm-modal-id', null)
    $.ajax(params).done(function (response, textStatus, jqXHR) {
      try {
        try {
          response = $.parseJSON(response)
        } catch (error) {
          response = undefined
        }

        modalObject.response = response

        if (response === undefined) {
          classInstance.destroy(modalTemplate)
        } else {
          // add title
          if (response.title !== undefined && response.title !== '') {
            modalTemplate.find('.modal-title').html(response.title)
          } else {
            modalTemplate.find('.modal-header').hide()
            modalTemplate.find('.modal-body').css('border', 'none')
          }
          // add content
          if (response.content !== undefined) {
            modalTemplate.find('.modal-body').html('<div class="chm-modal-content">' + response.content + '</div>').show()
          } else {
            modalTemplate.find('.modal-body').hide()
          }
          modalTemplate.find('button.close').show()
          modalTemplate.find('.panel-footer').remove()
          modalTemplate.removeClass('chm-loading-modal')
          modalTemplate.removeClass('chm-confirm-modal')
          // add footer actions
          if ('footer' in options && response.status !== 'hide_form') {
            var label = ('label' in options.footer) ? options.footer.label : 'Valider'
            var footerBloc = '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-hidden="true">Fermer</button><button type="submit" class="btn btn-primary btn-sm pull-right">' + label + '</button></div>'
            if (modalTemplate.find('.panel-footer').length === 0) {
              modalTemplate.find('.modal-content').append('<div class="panel-footer">' + footerBloc + '</div>')
            } else {
              modalTemplate.find('.panel-footer').empty().append(footerBloc)
            }
            var formMethod = 'POST'
            var formClass = 'form-horizontal'
            var formId = ''
            var formCallback = ''
            var formAction = ''
            if ('form' in options) {
              if ('action' in options.form) formAction = options.form.action
              if ('method' in options.form) formMethod = options.form.method
              if ('class' in options.form) formClass = options.form.class
              if ('id' in options.form) formId = 'id="' + options.form.id + '"'
              if ('callback' in options.form) formCallback = 'onsubmit="return ' + options.form.callback + '(event)"'
            }
            var enctype = ('enctype' in options.form && options.form.enctype === true) ? 'enctype="multipart/form-data"' : ''

            // make modal dragablle
            if ('draggable' in options && options.draggable === true) {
              try {
                $(modalTemplate).find('.modal-dialog').draggable({
                  cursor: 'move',
                  handle: '.modal-header'
                })
                $(modalTemplate).find('.modal-header').css('cursor', 'move')
              } catch (e) {
                console.error(e.message)
              }
            }

            $('.modal-content').wrap('<form method="' + formMethod + '" action="' + formAction + '" role="form" class="' + formClass + '" ' + formId + ' ' + formCallback + ' ' + enctype + '></div>')
          }

          if (!$(modalTemplate).find('.modal-title').is(':visible') && !$(modalTemplate).find('.modal-body').is(':visible')) {
            classInstance.destroy(modalTemplate)
          }
          // render an empty modal
          if ('empty_modal' in options && options.empty_modal === true) {
            modalTemplate.find('.modal-header').remove()
            modalTemplate.find('.modal-body').remove()
            modalTemplate.find('.panel-footer').remove()
            modalTemplate.find('.modal-content').html(response.content).show()
          }
        }

        // Trigger callback
        if ('onSuccess' in options) {
          switch (typeof options.onSuccess) {
            case 'function':
              options.onSuccess(response)
              break
            case 'string':
              window[options.onSuccess](response)
              break
          }
        }
      } catch (e) {
        classInstance.setError(modalTemplate, e.message)
      }
    }).fail(function (jqXHR, textStatus, errorThrown) {
      var message = jqXHR.status + ' - ' + jqXHR.statusText
      classInstance.setError(modalTemplate, message)
    })
    modalObject.modal = modalTemplate
    return modalObject
  }

  static confirm (target = '', title = '', message = '', callable = '', args = {}, params = {}) {
    // prepare action
    var action = ''
    if (callable !== '') {
      action = callable + '(' + this.htmlEntities(JSON.stringify(args)) + ')'
    } else if ($(target).is('a')) {
      action = 'window.location.href=&apos;' + $(target).attr('href') + '&apos;'
    } else if ($(target).is('input[type="submit"]')) {
      action = '$("' + $(target).closest('form').attr('id') + '").submit()'
    }

    if (title === '') title = '<i class="fa fa-warning"></i>&nbsp;Confirmation de l\'action'
    if (message === '') message = 'Êtes-vous sûr ?'
    var modal = ($('.chm-modal').length > 0) ? $('.chm-modal') : $(this.template())
    modal.find('button.close').hide()
    modal.find('.modal-title').html(title)
    modal.find('.modal-body').html('<div class="chm-modal-content">' + message + '</div>')

    // add footer actions
    var dismiss = ''
    if (params.closeAfterConfirm === true) dismiss = ' data-dismiss="modal" aria-hidden="true" '

    var footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" aria-hidden="true">Fermer</button><button onclick="return ' + action + '" ' + dismiss + ' class="btn btn-danger btn-sm pull-right">Appliquer</button>'

    if (modal.find('.panel-footer').length === 0) {
      modal.find('.modal-content').append('<div class="panel-footer">' + footer + '</div>')
    } else {
      modal.find('.panel-footer').empty().append(footer)
    }

    // applay css
    if (params.width !== '' && window.outerWidth > params.width) {
      modal.find('.modal-dialog').css('width', params.width)
    }
    modal.attr('chm-modal-id', 'confirm')
    modal.modal({ backdrop: 'static', keyboard: false })
  }

  static alert (title = '', message = '', params = {}) {
    var modal = ($('.chm-modal').length > 0) ? $('.chm-modal') : $(this.template())
    modal.find('button.close').hide()
    if (title === '') title = 'Alert !'
    modal.find('.modal-title').html(title)
    if (message !== '') {
      modal.find('.modal-body').html('<div class="chm-modal-content">' + message + '</div>')
    } else {
      modal.find('.modal-body').hide()
    }

    // add footer actions
    var alertCallback = ('callback' in params) ? 'onclick="return ' + params.callback + '(event)"' : 'data-dismiss="modal" aria-hidden="true" '

    var footer = '<button type="button" class="btn btn-danger btn-sm" ' + alertCallback + '>Fermer</button><button type="button" class="btn btn-default btn-sm pull-right" ' + alertCallback + '>OK</button></div>'

    if (modal.find('.panel-footer').length === 0) {
      modal.find('.modal-content').append('<div class="panel-footer">' + footer + '</div>')
    } else {
      modal.find('.panel-footer').empty().append(footer)
    }

    // applay css
    if (params.width !== '' && window.outerWidth > params.width) {
      modal.find('.modal-dialog').css('width', params.width)
    }
    modal.attr('chm-modal-id', 'alert')
    modal.modal({ backdrop: 'static', keyboard: false })

    return modal
  }

  static loading (message = '', id = '') {
    var content = (message !== '') ? message : '<i class="fa fa-circle-o-notch fa-spin fast-spin"></i>&nbsp;Chargement en cours...'
    this.destroy()
    // var tpl = ($('.chm-modal').length > 0) ? $('.chm-modal') : $(this.template())
    var tpl = $(this.template(id))
    tpl.find('.panel-footer').remove()
    tpl.removeClass('chm-confirm-modal')
    tpl.addClass('chm-loading-modal')
    tpl.find('.modal-title').html(content)
    tpl.find('.modal-body').hide()
    tpl.modal({ backdrop: 'static', keyboard: false })
    return tpl
  }

  static destroy (instance = null) {
    if (instance instanceof window.MouseEvent) {
      instance = $(instance.target).closest('.chm-modal')
    } else if (instance === null) {
      instance = $('.chm-modal')
    }
    $(instance).find('button.close').trigger('click')
  }

  static template (id = '') {
    if (id !== '') id = 'id="' + id + '"'
    return '<div class="modal chm-modal fade"' + id + 'role="dialog" data-keyboard="false"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="border-bottom: none;"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title"></h4></div><div class="modal-body" style="border-top: 1px solid #e5e5e5;"><div class="chm-response-messages"></div></div></div></div></div>'
  }

  static showAlertMessage (type, message, dismissible = true) {
    // var alert = window.chmAlert.getAlertBlock(type, message, dismissible)
    if (type === 'error') type = 'danger'
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
    var alert = '<div class="chm-alerts alert alert-' + type + ' alert-white rounded mb-10">'
    if (dismissible === true) {
      alert += '<button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>'
    }
    alert += '<div class="icon"><i class="' + icon + '"></i></div>'
    if (typeof message === 'object') {
      alert += '<ul>'
      $.each(message, function (k, message) {
        alert += '<li><strong>' + message + '</strong></li>'
      })
      alert += '</ul>'
    } else {
      alert += '<ul><li><strong>' + message + '</strong></li></ul>'
    }
    alert += '</div>'
    if ($('.chm-modal').find('.chm-response-messages').length === 0) {
      $('.chm-modal').find('.modal-body').prepend('<div class="chm-response-messages"></div>')
    }
    $('.chm-modal').find('.chm-response-messages').empty().html(alert)
    $('body, html').animate({scrollTop: $('.chm-response-messages').offset().top}, 1000)
  }

  static setError (modal, message) {
    modal.find('button.close').show()
    modal.find('.modal-title').html('<i class="fa fa-warning"></i>&nbsp;' + message)
    modal.find('.modal-body').hide()
  }

  static htmlEntities (str) {
    return String(str).replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
  }

}
