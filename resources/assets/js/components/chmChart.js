import $ from 'jquery'
import trans from './../script/functions'

export default class chmChart {

  constructor () {
    this.type = 'RadarChart'
    this.url = null
    this.data = {}
    this.options = {}
    this.loadingIcon = 'fa fa-circle-o-notch fa-spin fast-spin'
    this.loadingMessage = trans("Loading...")
  }

  chartLoop (charts, index) {
    let chart = new chmChart()
    return chart.render(charts[index]).then((response) => {
      index += 1
      if (typeof charts[index] !== 'undefined') {
        chart.chartLoop(charts, index)
      }
    })
  }

  render (target) {
    if (typeof target === 'string') {
      target = document.querySelector(target)
    }

    this.url = target.getAttribute('chm-chart') || null
    if (this.url === null) {
      return
    }

    this.type = target.getAttribute('chm-chart-type') || 'RadarChart'
    this.options = this.getOptions(target)
    this.data = this.parseJSON(target.getAttribute('chm-chart-data')) || {}
    this.loading(target)

    return $.post(this.url, this.data).done((response, textStatus, jqXHR) => {
      try {
        switch (this.type) {
          case 'radar-chart-two-value-axis':
            this.radarChartTwoValueAxis(response, target)
            break
        }        
      } catch (e) {
        target.innerHTML = '<strong style="color:red;" class="chart-error">' + e.message + '</strong>'
      }
    }).always(() => {
      this.stopLoading(target)
    })
  }

  radarChartTwoValueAxis (response, target) {
    let chart = am4core.create($(target).attr('id'), am4charts.RadarChart)

    chart.cursor = new am4charts.RadarCursor();

    chart.data = response.data || []

    let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis())

    categoryAxis.dataFields.category = 'label'

    let valueAxis = chart.yAxes.push(new am4charts.ValueAxis())

    if ('min' in this.options) {
      valueAxis.min = parseInt(this.options.min)
    }
    if ('max' in this.options) {
      valueAxis.max = parseInt(this.options.max)
    }

    valueAxis.renderer.axisFills.template.fill = chart.colors.getIndex(2)
    valueAxis.renderer.axisFills.template.fillOpacity = 0.05

    let series1 = chart.series.push(new am4charts.RadarSeries())
    series1.dataFields.valueY = this.options.firstValueY
    series1.dataFields.categoryX = 'label'
    series1.name = this.options.name
    series1.strokeWidth = 2

    let series2 = chart.series.push(new am4charts.RadarSeries())
    series2.dataFields.valueY = this.options.secondValueY
    series2.dataFields.categoryX = 'label'
    series2.name = this.options.name
    series2.strokeWidth = 2

    series1.tooltipText = this.options.firstValueY + ' ({valueY})'
    series1.tooltip.pointerOrientation = "vertical"

    series2.tooltipText = this.options.secondValueY + ' ({valueY})'
    series2.tooltip.pointerOrientation = "vertical"
  }

  static refresh (target) {
    let chart = new chmChart()
    chart.render(target)
  }

  loading (target) {
    if (target.querySelector('.loading') === null) {
      target.innerHTML = '<div class="loading" style="font-size: 14px;margin: 5px 8px;">\
        <i class="' + this.loadingIcon + '"></i>&nbsp;' + this.loadingMessage + '\
      </div>'
    }
  }

  stopLoading (target) {
    $(target).find('.loading').remove()
  }

  getOptions (target) {
    return this.parseJSON(target.getAttribute('chm-chart-options')) || {}
  }

  parseJSON (json) {
    try {
      return JSON.parse(json)
    } catch (e) {
      return false
    }
  }

}

$(document).ready(function () {
  if (typeof am4core !== 'undefined') {
    am4core.ready(function() {
      am4core.useTheme(am4themes_animated)
      let charts = document.querySelectorAll('[chm-chart]')
      if (charts.length > 0) {
        let c = new chmChart()
        for (let i = 0; i < charts.length; i++) {
          c.loading(charts[i])
        }
        c.chartLoop(charts, 0)
      }
    })
  }
})
