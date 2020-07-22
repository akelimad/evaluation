@extends('layouts.app')
@section('title', $objectif->id > 0 ? "Modifier l'objectif" : "Ajouter un objectif")
@section('breadcrumb')
  <li><a href="{{ route('config.objectifs') }}" class="text-blue">Objectifs</a></li>
  <li>{{ $objectif->id > 0 ? $objectif->title : 'Ajouter' }}</li>
@endsection
@section('content')
  <div class="content" id="content">
    <form @submit.prevent="handleSubmit()" action="" method="post" novalidate>
      <div class="row mb-30">
        <div class="col-md-8 col-md-offset-2">
          <div class="card mb-20" v-for="(objectif, oIndex) in objectifs">
            <div class="card-header">
              <span class="badge">@{{ oIndex + 1 }}</span>
              <button type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeObjectif(oIndex)"><i class="fa fa-trash"></i></button>
            </div>
            <div class="card-body">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group" :class="{'has-error': errors.has('type')}">
                    <label for="description" class="control-label required">Choisissez le type d'objectif</label>
                    <select name="type" id="" class="form-control" v-model="objectif.type" v-validate="'required'">
                      <option value=""></option>
                      <option value="Personnel">Personnel</option>
                      <option value="Equipe">Equipe</option>
                    </select>
                    <span v-show="errors.has('type')" class="help-block">@{{ errors.first('type') }}</span>
                  </div>
                </div>
                <div class="col-md-6" v-show="objectif.type == 'Equipe'">
                  <div class="form-group">
                    <label for="description" class="control-label">Choisissez l'équipe</label>
                    <select name="" id="" class="form-control" v-model="objectif.team">
                      <option value=""></option>
                      @foreach($teams as $team)
                      <option value="{{ $team->id }}">{{ $team->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group" :class="{'has-error': errors.has('titre')}">
                    <label for="title" class="control-label required">Titre</label>
                    <input type="text" name="titre" id="title" class="form-control" value="{{ isset($o->title) ? $o->title :''}}" v-model="objectif.title" v-validate="'required'" data-vv-as="">
                    <span v-show="errors.has('titre')" class="help-block">@{{ errors.first('titre') }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="description" class="control-label">Description</label>
                    <textarea name="description" id="description" class="form-control" v-model="objectif.description">{{ isset($o->description) ? $o->description :''}}</textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group" :class="{'has-error': errors.has('deadline')}">
                    <label for="description" class="required control-label">Echéance</label>
                    <input type="text" name="deadline" id="deadline" class="form-control" placeholder="Choisir une date" v-model="objectif.deadline">
                    <span v-show="errors.has('deadline')" class="help-block">@{{ errors.first('deadline') }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mb-10">
                  <label class="control-label font-16"><i class="fa fa-list"></i> Indicateurs</label>
                </div>
                <div class="col-md-12">
                  <div class="indicators-container">
                    <div class="indicator-item" v-for="(indicator, indexIndicator) in objectif.indicators">
                      <div class="row">
                        <div class="col-sm-1">
                          <span class="form-control">@{{ indexIndicator+1 }}</span>
                        </div>
                        <div class="col-md-4 pl-0">
                          <input type="text" class="form-control" placeholder="Titre" v-model="indicator.title">
                        </div>
                        <div class="col-md-2 pl-0">
                          <input type="number" min="1" max="100" class="form-control" placeholder="Objectif fixé" v-model="indicator.fixed">
                        </div>
                        <div class="col-md-2 pl-0">
                          <input type="number" min="1" max="200" class="form-control" placeholder="Réalisé" title="Sera rémpli par collaborateur" disabled v-model="indicator.realized">
                        </div>
                        <div class="col-md-2 pl-0">
                          <input type="number" min="1" max="100" class="form-control" title="Pendération en % ex: 10%" placeholder="Pondération" v-model="indicator.ponderation">
                        </div>
                        <div class="col-md-1 pl-0">
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
          <div class="addObjContainer mb-20">
            <button type="button" class="btn btn-primary" @click="addObjectf()"><i class="fa fa-plus"></i> Créer un nouvel objectif</button>
          </div>
          <div v-if="objectifs.length > 0" class="card" >
            <div class="card-body">
              <button class="btn btn-primary pull-right" :disabled="submitted"><i class="fa fa-save"></i> Enregistrer</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('javascript')
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script src="https://unpkg.com/vue-i18n/dist/vue-i18n.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vee-validate@<3.0.0/dist/vee-validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
<script>
  $(document).ready(function () {
    Vue.use(VeeValidate);
    new Vue({
      el: '#content',
      data: {
        isEditMode: {{ $objectif->id > 0  }},
        objectifs: [
          {
            id: null,
            type: '',
            team: '',
            title: '',
            description: '',
            deadline: '',
            indicators: [
              {
                id: null,
                title: '',
                fixed: '',
                realized: '',
                ponderation: '',
              }
            ]
          },
        ],
        submitted: false,
      },
      methods: {
        addObjectf: function () {
          this.objectifs.push({
            id: null,
            type: '',
            team: '',
            title: '',
            description: '',
            deadline: '',
            indicators: [
              {
                id: null,
                title: '',
                fixed: '',
                realized: '',
                ponderation: '',
              }
            ]
          })
        },
        removeObjectif: function (oIndex) {
          this.objectifs.splice(oIndex, 1);
        },
        addIndicator: function (oIndex) {
          var sumPonderation = 0
          this.objectifs[oIndex].indicators.forEach(function(obj) {
            console.log(obj.ponderation)
            sumPonderation += parseInt(obj.ponderation)
          })
          if (sumPonderation >= 100) {
            alert("Vous ne pouvez pas ajouter un autre indicateur, la somme de pondérations des indicateurs ne doit pas dépasser 100 !")
            return
          }
          this.objectifs[oIndex].indicators.push({
            id: null,
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
              this.submitted = true;
              axios.post("{{ route('config.objectifs.store') }}", {
                objectifs: this.objectifs
              }).then(function (response) {
                if (response.status == 200) {
                  swal({
                    title: "Succès",
                    text: "Les informations ont bien été enregistrées",
                    type: "success"
                  }).then(function () {
                    window.location.href = "{{ route('config.objectifs') }}"
                  });
                }
              }).catch(function (error) {
                console.log(error)
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

    $('.datepicker').datepicker({
      startDate: new Date(),
      autoclose: true,
      format: 'dd/mm/yyyy',
      language: 'fr',
      todayHighlight: true,
    })
  })
</script>
@endsection