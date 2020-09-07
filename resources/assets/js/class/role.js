import $ from 'jquery'

export default class chmRole {

  static create () {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('role/create')}, {
      form: {
        class: 'allInputsFormValidation',
        id: 'roleForm',
      },
      footer: {
        label: 'Sauvegarder'
      }
    })
  }

  static edit (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('role/' + params.id + '/edit')}, {
      form: {
        class: 'allInputsFormValidation',
        id: 'roleForm',
      },
      footer: {
        label: 'Mettre à jour'
      }
    })
  }

}
