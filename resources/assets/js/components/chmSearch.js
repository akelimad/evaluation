import $ from 'jquery'
import trans from './../script/functions'

export default class chmSearch {

  constructor () {
    this.type = 'GET'
    this.url = null
    this.data = {}
    this.heading = trans("Filtering options")
    this.targetTable = null
    this.onSuccess = null
    this.onError = null
  }

  init () {
    let forms = document.querySelectorAll('[chm-search]')
    for (let i = 0; i < forms.length; i++) {
      this.render(forms[i])
    }
  }

  render (target) {
    // Check if there an attribute chm-search-url
    this.url = target.getAttribute('chm-search-url')
    if (!this.url) {
      return
    }
    if (this.url.length === 0) {
      this.errorLog(target, trans("The chm-search-url attribute is not defined for this form"))
      return
    }
    // Set form attributes
    this.type = target.getAttribute('chm-search-type') || 'GET'
    this.data = this.parseJSON(target.getAttribute('chm-search-data')) || {}
    this.heading = target.getAttribute('chm-search-heading') || this.heading
    this.targetTable = target.getAttribute('chm-search-table') || null
    this.onSuccess = target.getAttribute('chm-search-onSuccess') || null
    this.onError = target.getAttribute('chm-search-onError') || null

    // Get form HTML with Ajax
    this.loading(target)
    this.getForm(target)
  }

  static refresh (target) {
    let search = new chmSearch()
    search.render(target)
  }

  loading (target) {
    target.innerHTML = '<i class="fa fa-circle-o-notch fa-spin fast-spin"></i>&nbsp;' + trans("Loading filtering options...")
  }

  collapse (target) {
    if (target.classList.contains('collapsed')) {
      target.classList.remove('collapsed')
      window.chmCookie.create('chms', 0)
      if (target.querySelector('form') === null) {
        this.render(target)
      }
    } else {
      target.classList.add('collapsed')
      window.chmCookie.create('chms', 1)
    }
    $(target).find('.chm-title span i').toggleClass('fa-chevron-down fa-chevron-up')
  }

  getForm (target) {
    let formHtml = ''
    let hideForm = parseInt(window.chmCookie.read('chms', 1))
    if (this.heading.length > 0) {
      let icon = (hideForm === 1) ? 'fa fa-chevron-down' : 'fa fa-chevron-up'
      formHtml += '<h2 class="chm-title" title="' + trans("Click to show or hide the form") + '">' + this.heading + '<span class="btn btn-outline-secondary btn-xs pull-right" style="margin-top: -4px;padding: .3rem .4rem .3rem .4rem;"><i class="' + icon + '"></i></span></h2>'
    }

    if (hideForm === 1 || target.querySelector('form') !== null) {
      target.innerHTML = formHtml
      target.classList.add('collapsed')
      target.querySelector('.chm-title').addEventListener('click', () => {
        this.collapse(target)
      })
    } else {
      $.ajax({
        type: this.type,
        url: this.url,
        data: this.data,
        dataType: "json"
      }).done((response, textStatus, jqXHR) => {
        let style = (this.heading.length === 0) ? 'margin-top:0px;' : ''
        formHtml += '<div class="chm-search-form" style="' + style + '">' + response.form + '</div>'
        target.innerHTML = formHtml
        target.classList.remove('collapsed')
        target.querySelector('.chm-title').addEventListener('click', () => {
          this.collapse(target)
        })
        $(target).closest('[chm-search]').trigger('chmSearchSuccess', response)
      }).fail(function (jqXHR, textStatus, errorThrown) {
        target.innerHTML = '<b>' + trans("An error occurred while loading filtering options") + '<a href="javascript:void(0)" onclick="chmSearch.refresh(this.closest(\'[chm-search]\'))" class="btn btn-default btn-xs pull-right"><i class="fa fa-refresh"></i></a></b>'
      })
    }
  }

  parseJSON (json) {
    try {
      return JSON.parse(json)
    } catch (e) {
      return false
    }
  }

  errorLog (target, message) {
    target.style.borderColor = '#ff0505'
    target.innerHTML += '<p style="font-size: 12px;color:#ff0505;margin: 10px auto 0;">' + message + '</p>'
  }

}

$(document).ready(function () {
  let search = new chmSearch()
  search.init()
})

$(document).on('submit', '[chm-search] form', function (event) {
  let $serach = $(this).closest('[chm-search]')
  let targetTableId = $serach.attr('chm-search-table')

  if (targetTableId !== undefined && $(targetTableId).length > 0) {
    event.preventDefault()

    var params = window.chmTable.getTableParams(targetTableId)

    $(this).find('input, select').each(function () {
      let name = $(this).attr('name')
      let value = $(this).val() || ''
      if (name === '_token' || name === 'token') {
        return true
      }
      name = name.replace('[]', '')
      if (value.length > 0) {
        params[name] = value
      } else {
        delete params[name]
      }
    })

    params = Object.assign({}, params)

    let url = window.location.href.split("?")[0] + '?' + $.param(params)
    window.history.pushState(null, document.title, url)
    window.chmTable.setTableParams(targetTableId, params)
    window.chmTable.refresh(targetTableId, params)
  }
})