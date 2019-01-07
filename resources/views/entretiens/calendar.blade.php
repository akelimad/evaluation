@extends('layouts.app')
@section('title', 'Calendrier')
@section('breadcrumb')
    <li>Calendrier</li>
@endsection
@section('content')
    <section class="content entretiens-list">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Aperçu sur les entretiens en fonction des dates  </h3>
                    </div>
                    <div class="box-body table-responsive no-padding mb40">
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
    @if(isset($entretiens))
    <script src="{{ asset('js/fullCalendar.fr.js')}}"></script>
    <script>
        $(function(){
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
                header    : {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'month,agendaWeek,agendaDay'
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
                        end  : '{{ Carbon\Carbon::parse($entretien->date_limit)->addDays(1) }}',
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