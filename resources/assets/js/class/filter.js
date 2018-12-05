import $ from 'jquery'

export default class chmFilter {

  static init () {
    if (!$('.chm-filter-collapse').hasClass('collapsed')) {
      var filter = $('[chm-filter]:first')
      if (filter.length === 1 && filter.attr('chm-filter') !== '') {
        this.render(filter, filter.attr('chm-filter'), filter.attr('chm-filter-route'))
      }
    }
  }

  static render (target, fileName, route) {
    if ($(target).hasClass('refresh')) {
      target = $(target).closest('[chm-filter]')
    }
    $(target).empty().html('<i class="fa fa-circle-o-notch fa-spin fast-spin"></i>&nbsp;Chargement en cours...')
    var classInstance = this
    $.ajax({
      type: 'GET',
      url: window.chmSite.url('filter/render'),
      data: {
        file: fileName,
        route: route
      }
    }).done(function (response, textStatus, jqXHR) {
      try {
        if (response.status === 'error') {
          classInstance.errorMessage(target, route, fileName, response.content)
        } else {
          $(target).empty().html(response.content)
        }
      } catch (e) {
        classInstance.errorMessage(target, route, fileName)
      }
    }).fail(function (jqXHR, textStatus, errorThrown) {
      classInstance.errorMessage(target, route, fileName)
    })
  }

  static collapse (target) {
    var value = ($(target).hasClass('collapsed')) ? 1 : 0
    window.chmCookie.create('chm_filter', value)
    $(target).find('i').toggleClass('fa-eye fa-eye-slash')
    var filter = $('[chm-filter]:first')
    if (value === 1 && $(filter).is(':empty')) {
      this.render(filter, filter.attr('chm-filter'), filter.attr('chm-filter-route'))
    }
  }

  static errorMessage (target, fileName, route, message = null) {
    if (message === null) message = 'Une erreur est survenue lors de chargement de filter.'
    $(target).find('i').removeClass('fa-spin')
    $(target).empty().html('<div class="chm-alerts alert alert-warning alert-white rounded"><div class="icon"><i class="fa fa-warning"></i></div><ul><li>' + message + '</li></ul><a href="#" onclick="return chmFilter.render(this, &apos;' + fileName + '&apos;, &apos;' + route + '&apos;);" class="refresh"><i class="fa fa-refresh"></i></a></div>')
  }

}
