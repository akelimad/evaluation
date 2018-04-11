@extends('layouts.app')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3> {{ $inProgress }} </h3>
                    <p>Nombre d'entretiens en cours</p>
                </div>
                <div class="icon"><i class="fa fa-comments"></i></div>
                <a href="" class="small-box-footer"> </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3> {{ $finished }} </h3>
                    <p>Entretiens terminés</p>
                </div>
                <div class="icon"><i class="fa fa-comments"></i></div>
                <a href="" class="small-box-footer"> </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $nbColls }}</h3>
                    <p>Nombre de Collaborateurs</p>
                </div>
                <div class="icon"><i class="fa fa-users"></i></div>
                <a href="" class="small-box-footer"> </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $nbMentors }}</h3>
                    <p>Nombre de Mentors</p>
                </div>
                <div class="icon"><i class="fa fa-users"></i></div>
                <a href="" class="small-box-footer"> </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-navy">
                <div class="inner">
                    <h3>{{ $taux }} %</h3>
                    <p> Taux de réalisations des entretiens </p>
                </div>
                <div class="icon"><i class="fa fa-users"></i></div>
                <a href="" class="small-box-footer"> </a>
            </div>
        </div>
        <!-- <div class="col-md-6">
            <div class="cartBox card">
                <canvas id="pie-chart" height="450" width="600"></canvas>
            </div>
        </div> -->
        <div class="clearfix"></div>
    </div>
</section>
@endsection

@section('javascript')
    <script>
        // new Chart(document.getElementById("bar-chart"), {
        //     type: 'bar',
        //     data: {
        //       labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        //       datasets: [
        //         {
        //           label: "Population (millions)",
        //           backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
        //           data: [2478,5267,734,784,433]
        //         }
        //       ]
        //     },
        //     options: {
        //       legend: { display: false },
        //       title: {
        //         display: true,
        //         text: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos, ea.'
        //       }
        //     }
        // });

        // new Chart(document.getElementById("pie-chart"), {
        //     type: 'pie',
        //     data: {
        //       labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        //       datasets: [{
        //         label: "Population (millions)",
        //         backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
        //         data: [2478,5267,734,784,433]
        //       }]
        //     },
        //     options: {
        //       title: {
        //         display: true,
        //         text: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.'
        //       }
        //     }
        // });

    </script>
@endsection
