import $ from 'jquery'
import trans from './../script/functions'

export default class chmForm {

  static submit (event, target = null) {
    event.preventDefault()

    if (target === null) target = $(event.target)

    if (!this.validate(target)) {
      return false
    }

    // Get form data
    var form = $(target)[0]
    var data = new window.FormData(form)

    // Check if google captcha checked
    if (
        $(target).find('#g-recaptcha-response').length === 1 &&
        $(target).find('#g-recaptcha-response').val() === ''
    ) {
      var errorMsg = trans('Veuillez cocher la case "Je ne suis pas un rebot".')
      var errorBlock = $('<div class="chm-error-block" id="chm-error-captcha">' + errorMsg + '</div>')
      if ($('#chm-error-captcha').length === 0) {
        $(target).find('#g-recaptcha-response').closest('.g-recaptcha').append(errorBlock)
        return
      }
    }

    // Disable submit button
    var btn = $(document.activeElement)
    var btnHtml = btn.html()
    var loadingLabel = trans('En traitement ...')
    var loadingAttr = $(target).attr('chm-loading-label')
    if (loadingAttr !== undefined) {
      loadingLabel = loadingAttr
    }
    btn.html('<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;' + loadingLabel)
    btn.prop('disabled', true)

    // Check if submit buttin has a name
    if ($(btn).attr('name') !== undefined) {
      data.append($(btn).attr('name'), '')
    }

    $('.chm-response-messages').empty()

    var action = $(target).attr('action')
    if (action === '') action = window.location.href

    // Prepare ajax arguments
    var ajaxArgs = {
      type: $(target).attr('method'),
      url: action,
      data: data,
      processData: false,
      contentType: false,
      cache: false,
      timeout: 600000
    }
    if ($(target).find('[type="file"]')) ajaxArgs.enctype = 'multipart/form-data'

    // Fire ajax request
    $.ajax(ajaxArgs).done(function (response, textStatus, jqXHR) {
      try {
        if (typeof response === 'string') response = $.parseJSON(response)

        // Trigger callback
        $(target).trigger('chmFormSuccess', response)

        if (response.message !== '' && ['alert', 'success', 'info', 'warning', 'danger', 'error'].indexOf(response.status) !== -1) {
          let alertTitle = 'title' in response ? response.title : ''
          let alertParams = 'params' in response ? response.params : {}
          if (typeof response.message === 'object') {
            if (response.status === 'alert') {
              var errors = '<ul class="mb-0 ml-15">'
              $.each(response.message, function (k, m) {
                errors += '<li style="list-style: square;">' + m + '</li>'
              })
              errors += '</ul>'
              alertParams['width'] = 400
              window.chmModal.alert(alertTitle, errors, alertParams)
            } else {
              if (response.status !== 'success') {
                window.chmAlert.danger(trans('Veuillez vérifier les messages d\'erreurs'))
              }
              // var dismissible = (response.data.dismissible && response.data.dismissible === true)
              window.chmForm.showMessagesBlock(response.status, response.message, target)
            }
          } else if (response.status === 'alert') {
            alertParams['width'] = 350
            window.chmModal.alert(alertTitle, response.message, alertParams)
          } else {
            window['chmAlert'][response.status](response.message)
          }
        }

        // Brute Force Attack - Enable recaptcha
        if (response.status === 'recaptcha') {
          $(target).find('#BFARecaptcha').html(response.data.recaptchaBlock)
          window.chmAlert.error(response.message)
        }

        // Show remaining timer
        if ('data' in response && 'remaining' in response.data) {
          $(btn).data('remaining', response.data.remaining)
          chmForm.showRemainingTime($(btn))
        }

        if (response.status === 'reload') {
          window.location.reload()
        }

        if (response.status === 'success') {
          window.chmModal.destroy()
          if ($(target).attr('target-table') !== undefined) {
            window.chmTable.refresh($(target).attr('target-table'))
          }
        }
      } catch (e) {
        window.chmAlert.error(trans('Une erreur est survenue:') + ' ' + e.message)
      }
    }).fail(function (jqXHR, textStatus, errorThrown) {
      var message = jqXHR.status + ' - ' + jqXHR.statusText
      window.chmAlert.danger('<i class="fa fa-times-circle"></i>&nbsp;' + message)
    }).always(function () {
      btn.html(btnHtml)
      btn.prop('disabled', false)
      if (window.grecaptcha !== undefined) {
        window.grecaptcha.reset()
      }
      // Trigger callback
      $(target).trigger('chmFormFinished')
    })
  }

  static showMessagesBlock (type, messages, target, dismissible = true) {
    var alert = window.chmAlert.getAlertBlock(type, messages, dismissible)
    var modal = $(target).closest('.chm-modal')
    var container = (modal.length > 0) ? modal : target
    if (modal.length > 0) {
      if ($('.chm-response-messages').length < 1) {
        $(container).find('.modal-body').prepend('<div class="chm-response-messages"></div>')
      }
      $('.chm-response-messages').empty().html(alert)
      $('body .chm-modal').animate({scrollTop: 0}, 1000)
    } else {
      if ($('.chm-response-messages').length === 0) {
        $(container).prepend('<div class="chm-response-messages"></div>')
      }
      $('.chm-response-messages').empty().html(alert)
      $('body, html').animate({scrollTop: $('.chm-response-messages').offset().top - 5}, 1000)
    }
  }

  static getTargetParams (target, attrName) {
    var params = {}
    var value = $(target).attr(attrName)
    if (value !== undefined && value !== '') {
      try {
        params = $.parseJSON(value)
      } catch (e) {
        window.chmAlert.warning(trans('Le format JSON donné est incorrect.'))
      }
    }
    return params
  }

  static setTargetParams (target, attrName, params) {
    $(target).attr(attrName, JSON.stringify(params))
  }

  static validate (form) {
    var isValid = true

    // Check if there is any field with errors
    $(form).find('[chm-validate]').each(function () {
      if (!chmForm.isValid(this)) {
        isValid = false
      }
    })

    // Scroll to first field with error
    if (!isValid) {
      if ($(form).closest('.chm-modal').length === 0) {
        $('body, html').animate({scrollTop: $('.chm-error-block').first().offset().top - 120}, 1000)
      } else {
        window.chmAlert.error(trans('Merci de remplir tous les champs obligatoires.'))
      }
    }

    return isValid
  }

  static isValid (target) {
    if ($(target).closest('.note-editable').length > 0) {
      return false
    }
    this.resetValidation(target)

    if ($(target).attr('chm-validate') === undefined) {
      return true
    }

    let rules = $(target).attr('chm-validate')
    if (rules.length === 0 || $(target).attr('chm-validated') !== undefined) {
      return true
    }

    // Check if there is any errors for this field
    var errorMsg = null
    $.each(rules.split('|'), function (k, v) {
      let rule = v.split(',')
      if (rule[0] === 'required') {
        if ($(target).is('[type="file"]') && $(target).get(0).files.length === 0) {
          let $fileInput = $(target)
          let colname = $($fileInput).data('colname')
          let $colInput = $($fileInput).closest('#' + colname + 'Input')
          let $colActions = $($colInput).next('#' + colname + 'Actions')
          let fileName = $($colActions).find('input.file_name').val()
          if (fileName === undefined || fileName === '') {
            errorMsg = trans('Veuillez choisir un fichier.')
            return false
          }
        } else if ($(target).is('[type="checkbox"], [type="radio"]')) {
          if ($(':' + $(target).attr('type') + '[name="' + $(target).attr('name') + '"]:checked').length < 1) {
            errorMsg = trans('Veuillez cocher un élément.')
            return false
          }
        } else if ($(target).next().is('.note-editor')) {
          let content = $(target).summernote('code')
          if (content === '<p><br></p>' || content === '') {
            errorMsg = trans('Veuillez remplir ce champ.')
            return false
          }
        } else if ($.trim($(target).val()).length === 0) {
          errorMsg = trans('Veuillez remplir ce champ.')
          return false
        }
      } else if ($(target).val() !== null && $(target).val().length > 0) {
        var trimedValue = $.trim($(target).val())
        switch (rule[0]) {
          case 'regex':
            patern = v.replace('regex,', '')
            patern = patern.replace(/^\/+/g, '')
            patern = patern.replace(/\/+$/g, '')
            patern = new RegExp(patern)
            if (!patern.test($(target).val())) {
              errorMsg = trans('Ce champ doit contenir une valeur valide.')
              return false
            }
            break
          case 'valid_email':
            if (!chmForm.isValidEmail($(target).val())) {
              errorMsg = trans('Format de l\'email est incorrect')
              return false
            }
            break
          case 'password_strength':
            if (!chmForm.passwordStrength($(target).val())) {
              errorMsg = trans('Le mot de passe est faible, il doit être composé de caractères (a-z) et de chiffres (0-9)')
              return false
            }
            break
          case 'valid_url':
            if (!chmForm.isValidUrl($(target).val())) {
              errorMsg = trans('Format de l\'url est incorrect.')
              return false
            }
            break
          case 'valid_name': // \u0600-\u06FF
            var patern = /^[أ-يa-zÀ-ú\s-_'’]*$/i
            if (!patern.test(trimedValue)) {
              errorMsg = trans('haractères autorisés : a-z - _ \' ’')
              return false
            }
            break
          case 'french_date':
            patern = /^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/
            if (!patern.test(trimedValue)) {
              errorMsg = trans('Format autorisé: <b>jj/mm/aaaa</b>')
              return false
            }
            break
          case 'string':
            patern = /^[أ-يa-zÀ-ú\s\-_"°^'’.,:+*&#()%@€£$!?{}]*$/i
            if (!patern.test(trimedValue)) {
              errorMsg = trans('Charactères autorisés : a-z - _ " ° ^ \' ’ . , : + * & # () % @ € £ $ ! ?')
              return false
            }
            break
          case 'alpha_numeric':
            patern = /^[أ-يa-z0-9À-ú\s\-_"°^'’.,:+*&#()%@€£$!?{}]*$/i
            if (!patern.test(trimedValue)) {
              errorMsg = trans('Charactères autorisés : 0-9 a-z - _ " ° ^ \' ’ . , :')
              return false
            }
            break
          case 'html':
            patern = /^[0-9أ-يa-zÀ-ú\s-_"°^'’.,:+;*&#{}()%@€£$!?\\</>=]*$/i
            if (!patern.test(trimedValue)) {
              errorMsg = trans('Charactères autorisés : 0-9 a-z - _ " ° ^ \' ’ . , : + ; * & # {} () % @ € £ $ ! ? / \ <> =')
              return false
            }
            break
          case 'numeric':
            if (!$.isNumeric($(target).val())) {
              errorMsg = trans('Ce champ doit être un nombre valide.')
              return false
            }
            break
          case 'integer':
            if (!/^\d+$/.test($(target).val())) {
              errorMsg = trans('Ce champ doit être un entier.')
              return false
            }
            break
          case 'phone':
            patern = /^[0-9+]+$/i
            if (!patern.test($(target).val())) {
              errorMsg = trans('Ce champ n\'est pas un numéro de téléphone valide.')
              return false
            }
            break
          case 'dial_code':
            patern = /^\+[0-9+]{1,3}$/i
            if (!patern.test($(target).val())) {
              errorMsg = trans('Ce champ doit commencer par <b>+</b> suivi par le code, ex: +212')
              return false
            }
            break
          case 'min_len':
            if (trimedValue.length < rule[1]) {
              var msg = trans('Ce champ doit contenir au moins {param} caractères.')
              errorMsg = msg.replace('{param}', rule[1])
              return false
            }
            break
          case 'max_len':
            if (trimedValue.length > rule[1]) {
              msg = trans('Ce champ doit comporter au plus {param} caractères.')
              errorMsg = msg.replace('{param}', rule[1])
              return false
            }
            break
          case 'file_max_size':
            let size = $(target).get(0).files[0].size
            if (size > (rule[1] * 1024)) {
              msg = trans('La taille maximale du fichier est de {param} KB. Veuillez compresser votre fichier en utilisant le site <a target="_blank" href="{site}" style="color:#fff;">{site}</a>')
              msg = msg.replace('{param}', rule[1])
              errorMsg = msg.replace(/{site}/g, 'http://www.smallpdf.com/fr')
              $(target).trigger('chmFileSelected', [0, null])
              $(target).val('').clone(true)
              return false
            }
            break
          case 'extension':
            let ext = $(target).get(0).files[0].name.split('.').pop().toLowerCase()
            let extensions = rule[1].split(';')
            if (extensions.indexOf(ext) === -1) {
              msg = trans('Extensions autorisées: ({param})')
              errorMsg = msg.replace('{param}', '.' + rule[1].replace(/;/g, ', .'))
              $(target).trigger('chmFileSelected', [0, null])
              $(target).val('').clone(true)
              return false
            }
            break
        }
      } else if ($(target).attr('required') !== undefined && $(target).val() !== null && $(target).val().length === 0) {
        errorMsg = trans('Veuillez remplir ce champ.')
      }
    })

    // Append error message
    if (errorMsg !== null) {
      this.showErrorBlock(target, errorMsg)
      return false
    } else {
      $(target).addClass('chm-has-success')
      $(target).trigger('chmValidateSuccess')
      return true
    }
  }

  static resetValidation (target) {
    let targetId = $(target).attr('id')
    if ($(target).is('[type="checkbox"], [type="radio"]')) {
      targetId = $(target).attr('name')
      targetId = targetId.replace('[', '_')
      targetId = targetId.replace(']', '_')
      targetId = targetId.replace('[]', '') // for multiple name of field e.g user[sector][]
    }
    $(target).removeClass('chm-has-success')
    $(target).removeClass('chm-has-error')
    $('#chm-error_' + targetId).remove()
  }

  static isValidEmail (email) {
    let patern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@(([[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,4}))$/

    return patern.test(email)
  }

  static passwordStrength (password) {
    let patern = /^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9أ-يÀ-ú\s-_"°^'’.,:+;*&#{}()%@€£$!?]+)$/

    return patern.test(password)
  }

  static isValidUrl (url) {
    let patern = /[-a-zA-Z0-9@:%_+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_+.~#?&//=]*)?/gi

    return patern.test(url)
  }

  static showErrorBlock (target, errorMsg) {
    var targetId = $(target).attr('id')
    if ($(target).is('[type="checkbox"], [type="radio"]')) {
      targetId = $(target).attr('name')
      targetId = targetId.replace('[', '_')
      targetId = targetId.replace(']', '_')
      targetId = targetId.replace('[]', '') // for multiple name of field e.g user[sector][]
    }
    var errorBlock = $('<div class="chm-error-block" id="chm-error_' + targetId + '">' + errorMsg + '</div>')
    $(target).addClass('chm-has-error')
    if ($(target).next().is('.note-editor')) {
      var elAfter = $(target).next('.note-editor')
      $(errorBlock).css('top', '6px')
    } else if ($(target).next().is('.cke')) {
      elAfter = $(target).next('.cke')
      $(errorBlock).css('top', '6px')
    } else if ($(target).next().is('.bootstrap-tagsinput')) {
      elAfter = $(target).next('.bootstrap-tagsinput')
      $(errorBlock).css('top', '6px')
    } else if ($(target).next().is('.select2-container')) {
      elAfter = $(target).next('.select2-container')
      $(errorBlock).css('top', '6px')
    } else if ($(target).closest('.file-upload').length > 0) {
      elAfter = $(target).closest('.file-upload')
      $(errorBlock).css('top', '6px')
    } else if ($(target).is('[type="checkbox"], [type="radio"]')) {
      elAfter = $(target).closest('label').parent().find('label').last()
      if ($(target).closest('.form-check')) {
        elAfter = $(target).closest('.form-check-container')
      }
      $(errorBlock).css({'top': '6px', 'left': '-4px'})
    } else {
      elAfter = $(target)
    }
    if (/^form_fields/.test($(target).attr('name'))) {
      $(errorBlock).css('display', 'block')
    }

    $(elAfter).next('.chm-error-block').remove()
    $(errorBlock).insertAfter(elAfter)
  }

  static setRule (target, rule, value = null) {
    let rules = this.getRules(target)

    if (value === false) {
      delete rules[rule]
    } else {
      rules[rule] = value
    }

    if (rule === 'required') {
      $(target).prop('required', (value !== false))
    }

    if (Object.keys(rules).length === 0) {
      $(target).removeAttr('chm-validate')
    } else {
      let rulesStr = ''
      for (let index in rules) {
        if (rules[index] === null) {
          rulesStr += '|' + index
        } else {
          rulesStr += '|' + index + ',' + rules[index]
        }
      }

      // Add class required to target wrapper to show asterix
      if ('required' in rules) {
        $(target).closest('.form-group').addClass('required')
      } else {
        $(target).closest('.form-group').removeClass('required')
      }

      $(target).attr('chm-validate', rulesStr.substring(1))
    }
  }

  static getRules (target) {
    let rules = $(target).attr('chm-validate')
    if (rules === undefined) {
      return []
    }

    var rulesArr = {}
    $.each(rules.split('|'), function (k, v) {
      let rule = v.split(',')
      if (rule[0] !== '') {
        let key = rule[0]
        let value = rule[1] !== undefined ? rule[1] : null
        rulesArr[key] = value
      }
    })

    return rulesArr
  }

  static popover (target) {
    if ($(target).length < 1) {
      return false
    }

    $('[data-toggle="popover"]').popover('hide')
    $(target).popover({
      container: 'body',
      html: true,
      content: function () {
        return $($(this).data('popover-content')).clone(true).show()
      },
      placement: function () {
        return $(window).width() < 975 ? 'top' : 'right'
      }
    }).click(function (event) {
      event.preventDefault()
      $('[data-toggle="popover"]').not(this).popover('hide')
      if ($('.popover-header i.fa-times').length === 0) {
        $('.popover-header').append('<i class="fa fa-times pull-right" style="cursor:pointer;"></i>')
      }
    })
  }

  static addControlFeedback (target, status = 'success') {
    let $cf = $(target).closest('div').find('.form-control-feedback')
    if ($cf.length > 0) $cf.remove()

    let iconClass = 'fa-check-circle'
    if (status === 'error') {
      iconClass = 'fa-times-circle'
    } else if (status === 'loading') {
      iconClass = 'fa-circle-o-notch fa-spin'
    }

    $('<span class="fa ' + iconClass + ' form-control-feedback"></span>').insertAfter($(target))
  }

  // Wrap every 3 dynamic fields in a row
  static wrapDynamicFields () {
    let $fields = $('.dynamic_fields>.col-sm-4')
    if ($fields.length > 0) {
      for (let i = 0; i < $fields.length; i += 3) {
        $fields.slice(i, i + 3).wrapAll("<div class='row'></div>")
      }
    }
  }

  static showRemainingTime (target) {
    let remaining = $(target).data('remaining')
    if (remaining === '') {
      return true
    }

    $(target).hide()
    let $rBtn = $('<button title="' + trans('Vous pouvez soumettre ce formulaire à la fin de la date limite') + '" type="button" disabled class="' + $(target).attr('class') + '" data-remaining-value>' + remaining + '</button>')

    $($rBtn).insertAfter($(target))

    var interval = setInterval(function () {
      var timer = remaining.split(':')
      // by parsing integer, I avoid all extra string processing
      var minutes = parseInt(timer[0], 10)
      var seconds = parseInt(timer[1], 10)
      --seconds
      minutes = (seconds < 0) ? --minutes : minutes
      if (minutes < 0) {
        clearInterval(interval)
        $($rBtn).remove()
        $(target).show()
      }

      seconds = (seconds < 0) ? 59 : seconds
      seconds = (seconds < 10) ? '0' + seconds : seconds

      remaining = minutes + ':' + seconds

      $($rBtn).text(remaining)
    }, 1000)
  }

}

// Initialise forms
$(document).ready(function () {
  // Wrap every 3 dynamic fields in a row
  chmForm.wrapDynamicFields()

  // disable form submitting with enter key
  $(document).on('keyup, keypress', '[chm-form]', function (e) {
    var keyCode = e.keyCode || e.which
    if (keyCode === 13 && e.target.type !== 'textarea') {
      e.preventDefault()
      return false
    }
  })

  // Select all Forms occurences
  $('body').on('submit', '[chm-form]', function (event) {
    event.preventDefault()
    chmForm.submit(event, this)
  })

  // Show hide other field
  $('body').on('change', 'select:has([chm-form-other])', function (event) {
    var otherOption = $(this).find('[chm-form-other]')
    var $otherInput = $('input#' + otherOption.attr('chm-form-other'))
    $($otherInput).val('')
    if ($(this).val() === '_other') {
      window.chmForm.setRule($otherInput, 'required')
      $($otherInput).show()
    } else {
      window.chmForm.setRule($otherInput, 'required', false)
      $($otherInput).hide()
    }
  })

  // Validate form fields
  $('body').on('keyup', '[chm-validate]', function (event) {
    if ($(this).is('.chm-has-error')) {
      chmForm.isValid(this)
    }
  })

  $('body').on('change', '[chm-validate]', function (event) {
    chmForm.isValid(this)
  })

  $.fn.capitalize = function () {
    $(this[0]).keyup(function (event) {
      var box = event.target
      var txt = $(this).val()
      var stringStart = box.selectionStart
      var stringEnd = box.selectionEnd
      $(this).val(txt.replace(/^(.)|(\s|\\-)(.)/g, function ($word) {
        return $word.toUpperCase()
      }))
      box.setSelectionRange(stringStart, stringEnd)
    })
    return this
  }

  // Hide popover
  $('body').on('click', '.popover-header>i', function () {
    let id = $(this).closest('.popover').attr('id')
    let target = $('[aria-describedby="' + id + '"]')
    $(target).attr('title', $(target).attr('data-original-title'))
    $('[data-toggle="popover"]').popover('hide')
  })

  // show the selected file in custom file input bootstrap4
  $(document).on('change', '.custom-file-input', function () {
    let fileName = $(this).val().split('\\').pop()
    $(this).siblings('.custom-file-label').addClass('selected').html(fileName)
  })
})
