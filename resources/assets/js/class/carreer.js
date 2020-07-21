import $ from 'jquery'

export default class chmCarreer {

  static create (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('entretiens/' + params.eid + '/u/' + params.uid + '/carrieres/create')}, {
      form: {
        class: 'allInputsFormValidation form-horizontal',
        callback: 'chmCarreer.store'
      },
      footer: {
        label: 'Sauvegarder'
      }
    })
  }

  static edit (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('entretiens/' + params.eid + '/u/' + params.uid + '/carrieres/' + params.cid + '/edit')}, {
      form: {
        class: 'allInputsFormValidation form-horizontal',
        callback: 'chmCarreer.store'
      },
      footer: {
        label: 'Mettre à jour'
      }
    })
  }

  static show (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('user/' + params.id)})
  }

  static store (event) {
    event.preventDefault()
    var form = $(event.target)[0]
    var data = new window.FormData(form)
    var btn = $(event.target).find('[type="submit"]')
    var btnHtml = btn.html()
    btn.html('<i class="fa fa-circle-o-notch"></i>&nbsp;Traitement en cours...')
    btn.prop('disabled', true)
    var id = $('[name="id"]').val()
    var eid = $('[name="eid"]').val()
    var uid = $('[name="uid"]').val()
    var ajaxParams = {
      id: id,
      type: 'POST',
      url: window.chmSite.url('entretiens/' + eid + '/u/' + uid + '/carrieres/store'),
      data: data,
      processData: false,
      contentType: false,
      cache: false,
      timeout: 600000
    }
    if ($(event.target).find('[type="file"]')) ajaxParams.enctype = 'multipart/form-data'
    $.ajax(ajaxParams).done(function (response, textStatus, jqXHR) {
      if (response.status !== 'success') {
        window.chmModal.showAlertMessage(response.status, response.message)
      } else {
        window.chmModal.alert('<i class="fa fa-check-circle"></i>&nbsp;Opération effectuée', response.message, {width: 415, callback: 'location.reload()'})
      }
    }).fail(function (jqXHR, textStatus, errorThrown) {
      var message = jqXHR.status + ' - ' + jqXHR.statusText
      window.chmModal.showAlertMessage('danger', message)
    }).always(function () {
      btn.html(btnHtml)
      btn.prop('disabled', false)
    })
  }

  static delete (params) {
    var token = $('input[name="_token"]').val()
    var object = window.chmModal.show({type: 'DELETE', url: window.chmSite.url('user/' + params.id + '/delete'), data: {'_token': token}}, {
      message: '<i class="fa fa-trash"></i>&nbsp;Suppression en cours...'
    })
    object.modal.attr('chm-modal-action', 'reload')
  }

}
