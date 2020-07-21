import $ from 'jquery'

export default class chmComment {

  static create (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('entretiens/' + params.eid + '/u/' + params.uid + '/commentaires/create')}, {
      form: {
        class: 'allInputsFormValidation form-horizontal',
      },
      footer: {
        label: 'Sauvegarder'
      }
    })
  }

  static edit (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('entretiens/' + params.eid + '/u/' + params.uid + '/commentaires/' + params.cid + '/edit')}, {
      form: {
        class: 'allInputsFormValidation form-horizontal',
      },
      footer: {
        label: 'Mettre à jour'
      }
    })
  }

  static show (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('user/' + params.id)})
  }

  static delete (params) {
    var token = $('input[name="_token"]').val()
    var object = window.chmModal.show({type: 'DELETE', url: window.chmSite.url('user/' + params.id + '/delete'), data: {'_token': token}}, {
      message: '<i class="fa fa-trash"></i>&nbsp;Suppression en cours...'
    })
    object.modal.attr('chm-modal-action', 'reload')
  }

}
