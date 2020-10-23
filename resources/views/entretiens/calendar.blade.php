@extends('layouts.app')
@section('title', 'Calendrier')
@section('breadcrumb')
  <li>Calendrier</li>
@endsection
@section('style')
  @parent
  <link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css')}}">
@endsection

@section('content')
  <section class="content entretiens-list">
    <div class="row">
      <div class="col-md-12">
        <div class="title-section mb-20">
          <h3 class="mt-0"><i class="fa fa-calendar"></i> {{ __("Calendrier des campagnes") }}</h3>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box p-0">
          <div class="box-body p-0">
            @if( count($entretiens)>0 )
              <div id="fullCalendar"></div>
            @else
              @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
            @endif
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </section>
  @endsection

  @section('javascript')
  @parent
  @if(isset($entretiens))
      <!-- datepicker -->
  <script src="{{asset('js/moment.min.js')}}"></script>
  <script src="{{asset('js/bootstrap-datepicker.fr.min.js')}}"></script>
  <script src="{{asset('js/fullcalendar.min.js')}}"></script>
  <script>
    $(function () {
      var $calendar = $('#fullCalendar');
      var today = new Date();
      var y = today.getFullYear();
      var m = today.getMonth();
      var d = today.getDate();
      $calendar.fullCalendar({
        buttonText: {
          today: "Aujourd'hui"
        },
        height: 580,
        locale: 'fr',
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: today,
        selectLongPressDelay: 10,
        selectable: true,
        selectHelper: true,
        views: {
          month: { // name of view
            titleFormat: 'MMMM YYYY'
            // other view-specific options here
          },
          week: {
            titleFormat: " MMM D YYYY"
          },
          day: {
            titleFormat: 'D MMM, YYYY'
          }
        },
        eventLimit: true, // allow "more" link when too many events
        // color classes: [ event-blue | event-azure | event-green | event-orange | event-red ]
        events: [
            @foreach($entretiens as $entretien)
            {
            title: "{!! $entretien->titre !!}",
            start: '{{ $entretien->date }}',
            end: '{{ Carbon\Carbon::parse($entretien->date_limit)->addDays(1) }}',
            allDay: true,
            className: 'btn-success',
          },
          @endforeach
      ]
      });
    })
  </script>
  @endif
@endsection