/**
 * chmTable
 *
 * How to use this class
 *
 | <div
 |   chm-table="route/table"
 |   chm-table-options='{"with_ajax": false}'
 |   chm-table-params='{"id": 10, "name": "John Doe"}'
 |   id="testTableContainer"
 | ></div>
 *
 */
import $ from 'jquery'
import trans from './../script/functions'

export default class chmTable {

  static render (target, params = {}) {
    // Store self class object to use it inside Ajax
    var self = this

    // Get Table route
    var route = $(target).attr('chm-table')
    if (route === '') return

    // Prepare Table params
    params = $.extend({}, {
      page: 1,
      scrollTo: false,
      lodingIcon: 'fa fa-refresh fa-spin fast-spin'
    }, params)

    // Decrease Table opacity while loading
    if ($(target).find('table').length === 0) {
      self.fill(target, '<div class="pt-10"><i class="' + params.lodingIcon + '"></i>&nbsp;' + trans("Chargement de la table ...") + '</div>', params.scrollTo)
    } else {
      // $(target).css('opacity', '0.3')
      $(target).find('#table-overlay').show()
    }

    // Fire Ajax action
    let ajaxParams = params
    delete ajaxParams['scrollTo']
    delete ajaxParams['lodingIcon']
    $.get(route, params).done(function (response) {
      try {
        // Transform response to JSON if type is string
        if (typeof response === 'string') {
          response = $.parseJSON(response)
        }
        // Fill the Table container with new rendred HTML
        if (response.status === 'success') {
          self.fill(target, response.content, params.scrollTo)
        } else {
          if ('message' in response) {
            var message = '<strong>' + response.message + '</strong>'
          } else {
            message = '<strong>' + trans("An error occurred while loading the table.") + '</strong>'
          }
          self.fill(target, message, params.scrollTo)
        }
        // Update total results element
        let trId = '#' + $(target).find('table').attr('id') + '_total_results'
        if (response.total_results !== undefined && $(trId).length > 0) {
          $(trId).text(response.total_results)
        }

        $(target).trigger('chmTableSuccess', response)

        window.chmForm.popover('.chm-modal [data-toggle="popover"]')
      } catch (e) {
        if (/<table/.test(response)) {
          self.fill(target, response, params.scrollTo)
          $(target).trigger('chmTableSuccess', response)
        } else {
          // Show error message
          self.fill(target, response, params.scrollTo)
          $(target).trigger('chmTableError', e.message)
        }
      }
    }).fail(function (jqXHR, textStatus, errorThrown) {
      let message = `${jqXHR.statusText} (${jqXHR.status})`
      self.fill(target, jqXHR.responseText, params.scrollTo)
      $(target).trigger('chmTableError', message)
    })
  }

  static refresh (target, params = {}) {
    if ($(target).length === 0) {
      return
    }

    params = $.extend({}, this.getTableParams(target), params)

    // Prepare params array
    if (!('page' in params)) {
      params.page = window.chmUrl.getParam('page', 1)
    }
    if (!('scrollTo' in params)) params.scrollTo = false

    this.render(target, params)

    this.enableAjaxPagination(target, params)
  }

  static fill (target, content, scrollTo = false) {
    // $(target).empty().html(content).css('opacity', '1')
    $(target).empty().html(content)
    $(target).find('#table-overlay').hide()
    if (scrollTo) {
      $('html, body').animate({
        scrollTop: $(target).offset().top
      }, 2000)
    }
  }

  static getTableParams (target) {
    var params = {}
    if ($(target).attr('chm-table-params') !== undefined) {
      try {
        params = $.parseJSON($(target).attr('chm-table-params'))
        if ($.isArray(params)) {
          params = {}
        }
      } catch (e) {
        window.chmAlert.warning(trans("The given JSON format is not correct."))
      }
    }
    return params
  }

  static setTableParams (target, params) {
    let newParams = $.extend(true, chmTable.getTableParams(target), params)
    $(target).attr('chm-table-params', JSON.stringify(newParams))
  }

  static enableAjaxPagination (target, params = {}) {
    // TODO - fix multiple submit bug
    let $pLink = '#' + $(target).attr('id') + ' .pagination > li > a'

    $('body').on('click', $pLink, function (event) {
      event.preventDefault()
      $('[data-toggle="popover"]').popover('hide')
      if ($(this).attr('href') !== undefined && !$(this).closest('li').hasClass('active')) {
        target = $(this).closest('[chm-table]')

        // Update params page number
        params = chmTable.getTableParams(target)

        if (!('change_url' in params)) {
          params.change_url = 1
        }

        // Get clicked url page number
        let url = $(this).attr('href')
        let pageNumber = window.chmUrl.getParam('page', 1, url)
        params.page = pageNumber
        // return false
        // console.log(pageNumber)
        if (!$('.chm-modal').is(':visible') && params.change_url === 1) {
          // Change page value on the current url
          window.chmUrl.setParam('page', pageNumber)
        }

        // Refresh Table content
        chmTable.render(target, params)
      }
    })
  }

  static tableResponsive() {
    
  }

}


$(document).ready(function () {
  // update total rows count
  // print table results count
  $('body').on('chmTableSuccess', function () {
    $('.badge-count').text($('table').data('count'))
  })

  // Get current page
  const page = window.chmUrl.getParam('page', 1)

  // update perpage
  $(document).on('change', '.chmTable_perpage', function () {
    let perpage = parseInt($(this).val())
    let $container = $(this).closest('[chm-table]')
    let params = chmTable.getTableParams($container)

    if (!('change_url' in params) || params.change_url == 1) {
      window.chmUrl.setParam('page', 1)
      window.chmUrl.setParam('perpage', perpage)
    }

    chmTable.setTableParams($container, {'page': 1, 'perpage': perpage})
    chmTable.refresh($container)
  })

  $(document).on('click', '[chm-table-sort]', function () {
    let orderby = $(this).attr('chm-table-sort')
    let order = window.chmUrl.getParam('order', 'asc') === 'asc' ? 'desc' : 'asc'
    let $container = $(this).closest('[chm-table]')
    let params = chmTable.getTableParams($container)

    if (!('change_url' in params) || params.change_url == 1) {
      window.chmUrl.setParam('order', order)
      window.chmUrl.setParam('orderby', orderby)
    }

    chmTable.setTableParams($container, {'orderby': orderby, 'order': order})
    chmTable.refresh($container)
  })

  $('[chm-table]').each(function () {
    var options = {with_ajax: true, autoLoad: true}
    var tableOptions = $(this).attr('chm-table-options')
    if (tableOptions !== undefined) {
      options = $.extend({}, options, $.parseJSON(tableOptions))
    }

    if (options.autoLoad) {
      var params = chmTable.getTableParams(this)
      params.page = page

      chmTable.render(this, params)

      if (options.with_ajax) {
        chmTable.enableAjaxPagination(this, params)
      }
    }
  })

  $('[chm-table]').on('chmTableSuccess', function () {
    $('[data-toggle="tooltip"]').tooltip()
    var actionsHeight = $('.dropdown-menu').innerHeight()
    $('.table-responsive').css('min-height', actionsHeight)

    tableResponsive()

    $(this).find('a.preview').each(function () {
      $(this).magnificPopup({type: 'iframe'})
    })

    var existsBulkActions = $('#table-bulk-action-select').length > 0
    if (!existsBulkActions) {
      $('.checkAll').remove()
      $('.hunter_cb_td').remove()
    }
  })

  tableResponsive()

  $(window).on('resize',function () {
    if ($('[chm-table]').length > 0) tableResponsive()
  })

})

function tableResponsive() {
  $.fn.hasScrollBar = function(direction) {
    if (this.get(0) != undefined) {
      if (direction == 'vertical') {
        return this.get(0).scrollHeight > this.innerHeight()
      } else if (direction == 'horizontal') {
        return this.get(0).scrollWidth > this.innerWidth()
      }
    }
    return false
  }
  var hasHorizScrollBar = $('.table-responsive').hasScrollBar('horizontal');
  if (hasHorizScrollBar) {
    $('.table-responsive').css("overflow", "auto")
  } else {
    $('.table-responsive').css("overflow", "inherit")
  }
}
