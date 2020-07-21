import $ from 'jquery'

export default class chmQuestion {

  static create (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('surveys/' + params.sid + '/groupes/' + params.gid + '/questions/create'), data: {parent_id: params.parent_id}}, {
      form: {
        class: 'allInputsFormValidation form-horizontal',
        callback: 'chmQuestion.store'
      },
      footer: {
        label: 'Sauvegarder'
      }
    })
  }

  static edit (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('surveys/' + params.sid + '/groupes/' + params.gid + '/questions/' + params.qid + '/edit')}, {
      form: {
        class: 'allInputsFormValidation form-horizontal',
        callback: 'chmQuestion.store'
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
    var sid = $('[name="survey_id"]').val()
    var gid = $('[name="group_id"]').val()
    var ajaxParams = {
      id: id,
      type: 'POST',
      url: window.chmSite.url('surveys/' + sid + '/groupes/' + gid + '/questions/store'),
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
    var object = window.chmModal.show({
      type: 'POST',
      url: window.chmSite.url('surveys/' + params.sid + '/groupes/' + params.gid + '/questions/' + params.qid + '/delete'),
      data: {'_token': token, '_method': 'DELETE'}
    }, {
      message: '<i class="fa fa-trash"></i>&nbsp;Suppression en cours...'
    })
    object.modal.attr('chm-modal-action', 'reload')
  }

}
