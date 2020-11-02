@extends('layouts.app')
@section('title', $objectif->id > 0 ? "Modifier l'objectif" : "Ajouter un objectif")
@section('breadcrumb')
  <li><a href="{{ route('objectifs') }}" class="text-blue">{{ __("Objectifs") }}</a></li>
  <li>{{ $objectif->id > 0 ? $objectif->title : 'Ajouter' }}</li>
@endsection

@section('content')
  <div class="content p-sm-10" id="content">
    <form @submit.prevent="handleSubmit()" action="" method="post" novalidate>
      <div class="row mb-30">
        <div class="col-md-8 col-md-offset-2">
          <div class="card border-info mb-20" v-for="(objectif, oIndex) in objectifs">
            <div v-if="mode == 'add'" class="card-header">
              <span class="badge">@{{ oIndex + 1 }}</span>
              <button v-if="mode == 'add'" type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeObjectif(oIndex)"><i class="fa fa-trash"></i></button>
            </div>
            <div class="card-body">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group" :class="{'has-error': errors.has('type')}">
                    <label for="description" class="control-label required">{{ __("Choisissez le type d'objectif") }}</label>
                    <select name="type" id="" class="form-control" v-model="objectif.type" v-validate="'required'">
                      <option value=""></option>
                      <option value="Personnel">{{ __("Individuel") }}</option>
                      <option value="Equipe">{{ __("Collectif") }}</option>
                    </select>
                    <span v-show="errors.has('type')" class="help-block">@{{ errors.first('type') }}</span>
                  </div>
                </div>
                <div class="col-md-6" v-show="objectif.type == 'Equipe'">
                  <div class="form-group" :class="{'has-error': errors.has('team')}">
                    <label for="description" class="control-label">{{ __("Choisissez l'équipe") }}</label>
                    <select name="team" id="team" class="form-control" v-model="objectif.team" v-validate="objectif.type == 'Equipe' ? 'required' : ''">
                      <option value=""></option>
                      @foreach($teams as $team)
                      <option value="{{ $team->id }}">{{ $team->name }}</option>
                      @endforeach
                    </select>
                    <span v-show="errors.has('team')" class="help-block">@{{ errors.first('team') }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group" :class="{'has-error': errors.has('titre')}">
                    <label for="title" class="control-label required">{{ __("Titre") }}</label>
                    <input type="text" name="titre" id="title" class="form-control" value="{{ isset($o->title) ? $o->title :''}}" v-model="objectif.title" v-validate="'required'" data-vv-as="">
                    <span v-show="errors.has('titre')" class="help-block">@{{ errors.first('titre') }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="description" class="control-label">{{ __("Description") }}</label>
                    <textarea name="description" id="description" class="form-control" v-model="objectif.description">{{ isset($o->description) ? $o->description :''}}</textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="description" class="control-label">{{ __("Echéance") }}</label>
                    <date-picker name="deadline" v-model="objectif.deadline" :config="{format: 'DD-MM-YYYY', locale: 'fr', minDate: new Date(), ignoreReadonly: true}" readonly></date-picker>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mb-20">
                  <label class="control-label font-16"><i class="fa fa-list"></i> {{ __("Indicateurs") }} <i class="fa fa-info-circle text-primary" data-toggle="tooltip" title="Total de la pondération des indicateurs de cet objectif doit être égale à 100"></i></label>
                </div>
                <div class="col-md-12">
                  <div class="indicators-container">
                    <div class="indicator-item" v-for="(indicator, indexIndicator) in objectif.indicators">
                      <div class="row mb-20">
                        <div class="col-md-5 mb-sm-30">
                          <label>
                            <input type="text" class="form-control" placeholder=" " v-model="indicator.title" maxlength="64">
                            <span>{{ __("Titre de l'indicateur") }}</span>
                          </label>
                        </div>
                        <div class="col-md-3 mb-sm-30">
                          <label>
                            <input type="number" min="1" max="100" class="form-control" placeholder=" " v-model="indicator.fixed" maxlength="3">
                            <span>{{ __("Objectif fixé") }}</span>
                          </label>
                        </div>
                        <div class="col-md-3 mb-sm-30">
                          <label>
                            <input type="number" min="1" max="100" class="form-control" title="Pendération en % ex: 10%" placeholder=" " v-model="indicator.ponderation" maxlength="3">
                            <span>{{ __("Pondération (%)") }}</span>
                          </label>
                        </div>
                        <div class="col-md-1 mb-sm-30">
                          <button v-if="indexIndicator + 1 == objectif.indicators.length" type="button" class="btn btn-success pull-right text-success" @click="addIndicator(oIndex)"><i class="fa fa-plus"></i></button>

                          <button v-if="indexIndicator + 1 < objectif.indicators.length" type="button" class="btn btn-danger pull-right text-danger" @click="removeIndicator(oIndex, indexIndicator)"><i class="fa fa-minus"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-if="mode == 'add'" class="addObjContainer mb-20">
            <button type="button" class="btn btn-primary" @click="addObjectf()"><i class="fa fa-plus"></i> {{ __("Ajouter un nouvel objectif") }}</button>
          </div>
          <div v-if="objectifs.length > 0" class="card" >
            <div class="card-body">
              <button class="btn btn-success pull-right submit-btn" :disabled="submitted"><i class="fa fa-save"></i> {{ __("Enregistrer") }}</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('javascript')
  <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
  <script src="{{asset('js/moment.min.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
  <script src="https://unpkg.com/vue-i18n/dist/vue-i18n.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/locale/fr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/pc-bootstrap4-datetimepicker@4.17/build/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vee-validate@<3.0.0/dist/vee-validate.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue-bootstrap-datetimepicker@5"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
  <script src="https://cdn.rawgit.com/rikmms/progress-bar-4-axios/0a3acf92/dist/index.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/rikmms/progress-bar-4-axios/0a3acf92/dist/nprogress.css" />

  <script>
    $(document).ready(function () {
      loadProgressBar()
      Vue.use(VeeValidate);
      Vue.component('date-picker', VueBootstrapDatetimePicker);
      new Vue({
        el: '#content',
        data: {
          mode: "{{ $objectif->id > 0 ? 'edit' : 'add' }}",
          objectifs: [
            {
              id: "{{ $objectif->id > 0 ? $objectif->id : 0 }}",
              type: "{{ $objectif->type }}",
              team: "{{ $objectif->team }}",
              title: "{!! $objectif->title !!}",
              description: "{{ $objectif->description }}",
              deadline: "{{ date('d-m-Y', strtotime($objectif->deadline)) }}",
              indicators: [
                @foreach($objectif->getIndicators() as $indicator)
                {
                  id: "{{ isset($indicator['id']) ? $indicator['id'] : 0 }}",
                  title: "{!! isset($indicator['title']) ? $indicator['title'] : '' !!}",
                  fixed: "{{ isset($indicator['fixed']) ? $indicator['fixed'] : '' }}",
                  ponderation: "{{ isset($indicator['ponderation']) ? $indicator['ponderation'] : '' }}",
                },
                @endforeach
              ]
            },
          ],
          submitted: false,
        },
        methods: {
          addObjectf: function () {
            this.objectifs.push({
              id: 0,
              type: '',
              team: '',
              title: '',
              description: '',
              deadline: '',
              indicators: [
                {
                  id: 0,
                  title: '',
                  fixed: '',
                  realized: '',
                  ponderation: '',
                }
              ]
            })
            setTimeout(function () {
              $('[data-toggle="tooltip"]').tooltip()
            }, 500)
          },
          removeObjectif: function (oIndex) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              this.objectifs.splice(oIndex, 1);
            }
          },
          addIndicator: function (oIndex) {
            var sumPonderation = 0
            this.objectifs[oIndex].indicators.forEach(function(obj) {
              sumPonderation += parseInt(obj.ponderation)
            })
            if (sumPonderation >= 100) {
              swal({
                type: 'warning',
                text: "Vous ne pouvez pas ajouter un autre indicateur, la somme de la pondération des indicateurs ne doit pas dépasser 100 !"
              })
              return
            }
            this.objectifs[oIndex].indicators.push({
              id: 0,
              title: '',
              fixed: '',
              realized: '',
              ponderation: '',
            })
          },
          removeIndicator: function (oIndex, indexIndicator) {
            this.objectifs[oIndex].indicators.splice(indexIndicator, 1);
          },
          handleSubmit: function () {
            this.$validator.validateAll().then((result) => {
              if (result) {
                var ponderationIsValid = true
                this.objectifs.forEach(function(obj, index) {
                  var sumPonderation = 0
                  obj.indicators.forEach(function(indicator) {
                    sumPonderation += parseInt(indicator.ponderation)
                  })
                  if (sumPonderation != 100) {
                    ponderationIsValid = false;
                    return false;
                  }
                })
                if (!ponderationIsValid) {
                  swal({
                    title: "Erreur",
                    text: "La somme de la pondération des indicateurs doit être égale à 100 pour chaque objectif !",
                    type: "error",
                    allowOutsideClick: false
                  })
                  return
                }
                this.submitted = true;
                axios.post("{{ route('objectif.store') }}", {
                  objectifs: this.objectifs
                }).then(function (response) {
                  this.submitted = false
                  var success = response.data.status == 'success'
                  swal({
                    title: response.data.status == 'success' ? "Enregistré" : "Erreur",
                    text: response.data.message,
                    type: response.data.status,
                    allowOutsideClick: false
                  }).then(function () {
                    if (success) {
                      window.location.href = "{{ route('objectifs') }}"
                    } else {
                      $('.submit-btn').prop('disabled', false)
                    }
                  });
                }).catch(function (error) {
                  swal({title: "Erreur", text: error, type: "danger"})
                  this.submitted = false
                });
              }
            })
          }
        },
        directives: {
          focus: {
            inserted (el) {
              el.focus()
            }
          }
        }
      })
    })
  </script>
@endsection