import $ from 'jquery'
import trans from './../script/functions'

export default class chmModal {

  static show (params, options = {}) {
    // Set form action as modal route
    if ('form' in options && options.form.action === undefined) {
      options.form.action = params.url
    }

    // Prepare modal options
    options = this.getOptions(options)

    // Hide any popover window before opening modal
    if (!options.disableLoader) {
      $('[data-toggle="popover"]').popover('hide')
    }

    // Define a modal object variable
    var modalObject = {}

    // Store chmModal class instance to use it inside Ajax response
    var classInstance = this

    // Get modal template
    if (options.disableLoader) {
      var modalTemplate = $(this.template(options.id))
    } else {
      modalTemplate = this.loading(options.message, options.id)
    }
    modalTemplate.find('.modal-dialog').css('max-width', this.getModalWidth(options.width))
    let width = (window.outerWidth > options.width) ? options.width : '100%'
    modalTemplate.find('.modal-dialog').css('width', width)

    // Fetch modal content with Ajax
    $.ajax(this.getParams(params)).done(function (response, textStatus, jqXHR) {
      try {
        // Parse response
        if (typeof response === 'string') {
          response = $.parseJSON(response)
        }

        // Update options list from response
        if ('data' in response && 'options' in response.data) {
          options = $.extend(true, options, response.data.options)
        }

        // Calculate and set modal width
        modalTemplate.find('.modal-dialog').css('max-width', classInstance.getModalWidth(options.width))
        modalTemplate.attr('chm-modal-id', null)

        // Store response into modal object
        modalObject.response = response

        // Add modal title
        let hasTitle = 'title' in response && response.title.length > 0
        if (hasTitle) {
          modalTemplate.find('.modal-title').html(response.title)
        } else {
          modalTemplate.find('.modal-header').hide()
          modalTemplate.find('.modal-body').css('border', 'none')
        }

        if (options.scrollbarHeight !== null) {
          modalTemplate.find('.modal-body').addClass('custom-scrollbar')
          modalTemplate.find('.modal-body').css('max-height', options.scrollbarHeight)
        }

        // Add modal content
        let hasContent = 'content' in response && response.content.length > 0
        if (hasContent) {
          modalTemplate.find('.modal-body')
              .html('<div class="chm-modal-content">' + response.content + '</div>')
              .show()
        } else {
          modalTemplate.find('.modal-body').hide()
        }

        modalTemplate.find('button.close').show()
        modalTemplate.find('.modal-footer').remove()
        modalTemplate.removeClass('chm-loading-modal')
        modalTemplate.removeClass('chm-confirm-modal')

        // Add modal footer
        if (response.status !== 'hide_form' && options.form.action !== null && hasContent) {
          // prepare form attributes
          var attributes = options.form.attributes
          if (!classInstance.inArray(attributes, 'method') && options.form.method !== null) {
            attributes.method = options.form.method
          }
          if (!classInstance.inArray(attributes, 'action') && options.form.action !== null) {
            attributes.action = options.form.action
          }

          let callback = options.form.callback
          if (classInstance.inArray(attributes, 'callback')) {
            callback = attributes.callback
          }
          if (callback !== null) {
            if (callback == 'chmForm.submit') {
              attributes['chm-form'] = ''
            } else {
              attributes.onsubmit = "return " + callback + "(event)"
            }
          }

          delete attributes['callback']

          if (!classInstance.inArray(attributes, 'class') && options.form.class !== null) {
            attributes.class = options.form.class
          }
          if (!classInstance.inArray(attributes, 'id') && options.form.id !== null) {
            attributes.id = options.form.id
          }
          if (!classInstance.inArray(attributes, 'enctype') && options.form.enctype) {
            attributes.enctype = "multipart/form-data"
          }

          var attrs = ''
          $.each(attributes, function (k, v) {
            attrs += ' ' + k + '="' + v + '"'
          })

          var footer = '<div class="modal-footer"><button type="button" class="btn btn-danger button-xs" data-dismiss="modal" aria-hidden="true">' + trans("Fermer") + '</button><button type="submit" id="' + attributes.id + 'Submit" class="btn btn-primary pull-right button-xs">' + trans(options.footer.label) + '</button></div>'

          if (modalTemplate.find('.modal-footer').length === 0) {
            modalTemplate.find('.modal-content').append(footer)
          } else {
            modalTemplate.find('.modal-footer').replaceWith(footer)
          }

          modalTemplate.find('.modal-content').wrap('<form role="form"' + attrs + '></form>')
        }

        // Render an empty modal
        if (options.empty_modal) {
          modalTemplate.find('.modal-header').remove()
          modalTemplate.find('.modal-body').remove()
          modalTemplate.find('.modal-footer').remove()
          modalTemplate.find('.modal-content').html(response.content).show()
        }

        // Destroy modal if no title or content available
        if (!hasTitle && !hasContent) {
          classInstance.destroy(modalTemplate)
          if (options.showAlert && 'status' in response && 'message' in response && response.message !== '') {
            window['chmAlert'][response.status](response.message)
          }
        }

        // Trigger onSuccess callback
        classInstance.onSuccess(options, response)

        // Make modal dragablle
        if (options.draggable) {
          $(modalTemplate).find('.modal-dialog').draggable({
            cursor: 'move',
            handle: '.modal-header'
          })
          $(modalTemplate).find('.modal-header').css('cursor', 'move')
        }

        // Wrap every 3 dynamic fields in a row
        window.chmForm.wrapDynamicFields()

        $(modalTemplate).trigger('chmModalLoaded', response)
      } catch (e) {
        console.error(e)
        classInstance.onError(options, e.message)
        modalTemplate.find('.modal-content').html(response).show()
      }
    }).fail(function (jqXHR, textStatus, errorThrown) {
      classInstance.onError(options, jqXHR)
      modalTemplate.find('.modal-content').html('<div class="modal-header"><h4 class="modal-title">' + trans("Erreur survenue !") + '</h4><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div><div class="modal-body custom-scrollbar">' + jqXHR.responseText + '</div>').show()
    }).always(function (jqXHR, textStatus) {
      classInstance.onComplete(options, jqXHR)
    })

    // Store modal template in the modal object
    modalObject.modal = modalTemplate

    return modalObject
  }

  static confirm (target = '', title = '', message = '', callable = '', args = {}, params = {}) {
    // prepare action
    var action = ''
    params.width = 355
    if (callable !== '') {
      args = $.extend(args, window.chmForm.getTargetParams(target, 'target-params'))
      action = callable + '(' + this.htmlEntities(JSON.stringify(args)) + ')'
    } else if ($(target).is('a')) {
      action = "window.redirect('" + $(target).attr('href') + "')"
    } else if ($(target).is('input[type="submit"]')) {
      action = '$("' + $(target).closest('form').attr('id') + '").submit()'
    }

    if (title === '') title = '<i class="fa fa-warning"></i>&nbsp;' + trans("Confirmer")
    if (message === '') message = 'Êtes-vous sûr ?'
    var modal = ($('.chm-modal').length > 0) ? $('.chm-modal') : $(this.template())
    modal.find('button.close').hide()
    modal.find('.modal-title').html(title)
    modal.find('.modal-body').html('<div class="chm-modal-content">' + message + '</div>')

    // add footer actions
    var dismiss = ''
    if (params.closeAfterConfirm === true) dismiss = ' data-dismiss="modal" aria-hidden="true" '

    var footer = '<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-hidden="true">' + trans("Fermer") + '</button><button onclick="return ' + action + '" ' + dismiss + ' class="btn btn-primary btn-sm pull-right">' + trans("Appliquer") + '</button>'

    if (modal.find('.modal-footer').length === 0) {
      modal.find('.modal-content').append('<div class="modal-footer">' + footer + '</div>')
    } else {
      modal.find('.modal-footer').empty().append(footer)
    }

    // applay css
    if (params.width !== '' && window.outerWidth > params.width) {
      modal.find('.modal-dialog').css('width', params.width)
    }
    modal.attr('chm-modal-id', 'confirm')
    modal.modal({ backdrop: 'static', keyboard: false })
  }

  static alert (title = '', message = '', params = {}) {
    if ($('.chm-modal').length > 0) {
      var modal = $('.chm-modal')
    } else {
      modal = $(this.template())
    }

    if ('id' in params) {
      $(modal).attr('id', params.id)
    }

    modal.find('button.close').hide()
    if (title === '') title = trans("Alert !")
    modal.find('.modal-title').html(title)

    if (typeof message === 'object') {
      let msgList = '<ul style="margin-top: 0px;margin-bottom: 0px;padding-left: 15px;">'
      $.each(message, function (k, msg) {
        msgList += '<li style="list-style: square;">' + msg + '</li>'
      })
      msgList += '</ul>'
      message = msgList
    }

    if (message !== '') {
      modal.find('.modal-body').html('<div class="chm-modal-content">' + message + '</div>')
    } else {
      modal.find('.modal-body').hide()
    }

    // add footer actions
    var alertCallback = ('callback' in params) ? 'onclick="return ' + params.callback + '(event)"' : 'data-dismiss="modal" aria-hidden="true" '

    var close = ''
    if (params.close) {
      close = '<button type="button" class="btn btn-danger bg-danger btn-sm" ' + alertCallback + '>' + trans("Fermer") + '</button>'
    }
    var footer = close + '<button type="button" class="btn btn-primary btn-sm pull-right" ' + alertCallback + '>' + trans("OK") + '</button></div>'

    if (modal.find('.modal-footer').length === 0) {
      modal.find('.modal-content').append('<div class="modal-footer">' + footer + '</div>')
    } else {
      modal.find('.modal-footer').empty().append(footer)
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
    var content = (message !== '') ? message : this.getLoadingMessage()
    this.destroy()
    // var tpl = ($('.chm-modal').length > 0) ? $('.chm-modal') : $(this.template())
    var tpl = $(this.template(id))
    tpl.find('.modal-footer').remove()
    tpl.removeClass('chm-confirm-modal')
    tpl.addClass('chm-loading-modal')
    tpl.find('.modal-title').html(content)
    tpl.find('.modal-body').hide()
    tpl.modal({ backdrop: 'static', keyboard: false })
    return tpl
  }

  static getLoadingMessage () {
    return '<i class="fa fa-circle-o-notch fa-spin fast-spin"></i>&nbsp;' + trans("Chargement ...")
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
    if (id !== '') id = ' id="' + id + '"'
    return '<div class="modal chm-modal fade"' + id + 'role="dialog" data-keyboard="false"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="border-bottom: none;"><h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div><div class="modal-body" style="border-top: 1px solid #e5e5e5;"><div class="chm-response-messages"></div></div></div></div></div>'
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

  static getModal (target, type = 'params') {
    var params = {}
    if ($(target).attr('chm-modal-' + type) !== undefined) {
      try {
        params = $.parseJSON($(target).attr('chm-modal-' + type))
      } catch (e) {
        window.chmAlert.warning(trans("Le format JSON donné est invalide."))
      }
    }
    return params
  }

  static getParams (params = {}) {
    return $.extend({}, {
      type: 'POST',
      url: '',
      dataType: "json"
    }, params)
  }

  static getOptions (options = {}) {
    return $.extend(true, {
      message: this.getLoadingMessage(),
      id: null,
      showAlert: false,
      disableLoader: false,
      empty_modal: false,
      draggable: false,
      scrollbarHeight: null,
      width: 600,
      form: {
        method: 'POST',
        action: null,
        attributes: {
          class: 'allInputsFormValidation form-vertical',
          id: null,
          callback: 'chmForm.submit',
          novalidate: '',
          enctype: false
        }
      },
      footer: {
        label: trans("Enregistrer")
      },
      onSuccess: false,
      onError: false,
      onComplete: false
    }, options)
  }

  static getModalWidth (width) {
    if (window.outerWidth < width || window.outerWidth <= 600) {
      width = (window.outerWidth - 20)
    }
    return width
  }

  static inArray (array, value) {
    if (Array.isArray(array)) {
      return array.indexOf(value) > -1
    }
    return (value in array)
  }

  static onSuccess (options, response) {
    switch (typeof options.onSuccess) {
      case 'function':
        options.onSuccess(response)
        break
      case 'string':
        window[options.onSuccess](response)
        break
    }
  }

  static onError (options, response) {
    switch (typeof options.onError) {
      case 'function':
        options.onError(response)
        break
      case 'string':
        window[options.onError](response)
        break
    }
  }

  static onComplete (options, response) {
    switch (typeof options.onComplete) {
      case 'function':
        options.onComplete(response)
        break
      case 'string':
        window[options.onComplete](response)
        break
    }
  }
}

$(document).ready(function () {
  // Select all Modal occurences
  $('body').on('click', '[chm-modal]', function (event) {
    event.preventDefault()
    var target = $(this)
    var params = chmModal.getModal(target, 'params') || {}
    var options = chmModal.getModal(target, 'options') || {}

    if ($(target).attr('chm-modal').length > 0) {
      params.url = $(target).attr('chm-modal')
    } else if ($(target).attr('href').length > 0) {
      params.url = $(target).attr('href')
    }

    params.type = 'GET'
    chmModal.show(params, options)

    return false
  })

  // Modal close event
  $('body').on('hidden.bs.modal', '.chm-modal', function () {
    if ($(this).attr('chm-modal-action') === 'reload') {
      window.location.reload()
    } else {
      $(this).modal('hide')
      $(this).remove()
      $(this).data('bs.modal', null)
      if ($('.chm-modal').length < 1) {
        $('.modal-backdrop').remove()
      } else {
        $(this).prev('.modal-backdrop').remove()
      }
    }
  })

})
