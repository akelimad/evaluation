<div class="content p-xxs-10" id="contentaa">
  <input type="hidden" name="id" value="{{ $skill->id > 0 ? $skill->id : null }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="" class="control-label required">{{ __("Titre") }}</label>
        <input type="text" name="title" id="title" class="form-control" chm-validate="required" value="{{ $skill->title }}">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <label for="entretien" class="control-label">{{ __("Fonction du supérieur hiérarchique") }}</label>
      <select name="hierarchy_function_id" id="hierarchy_function_id" class="form-control">
        <option value=""></option>
        @foreach(App\Fonction::getAll()->get() as $function)
          <option value="{{ $function->id }}" {{ $skill->hierarchy_function_id == $function->id ? 'selected':'' }}>{{ $function->title }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <label for="entretien" class="control-label">{{ __("Fonction du supérieur fonctionnel") }}</label>
      <select name="functional_function_id" id="functional_function_id" class="form-control">
        <option value=""></option>
        @foreach(App\Fonction::getAll()->get() as $function)
          <option value="{{ $function->id }}" {{ $skill->functional_function_id == $function->id ? 'selected':'' }}>{{ $function->title }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <label for="entretien" class="control-label required">{{ __("Fonction") }}</label>
      <select name="function_id" id="function_id" class="form-control" chm-validate="required">
        <option value=""></option>
        @foreach(App\Fonction::getAll()->get() as $function)
          <option value="{{ $function->id }}" {{ $skill->function_id == $function->id ? 'selected':'' }}>{{ $function->title }}</option>
        @endforeach
      </select>
      <span class="d-block mt-5"><i><a href="{{ route('functions') }}" target="_blank">{{ __("Cliquer ici pour ajouter d'autres fonctions") }}</a></i></span>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="" class="control-label">{{ __("Niveau de formation") }}</label>
        <select name="formationlevel_id" id="formationlevel_id" class="form-control">
          <option value=""></option>
          @foreach(App\FormationLevel::orderBy('sort_order', 'ASC')->get() as $level)
            <option value="{{ $level->id }}" {{ $skill->formationlevel_id == $level->id ? 'selected':'' }}>{{ $level->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="" class="control-label">{{ __("Niveau de d'expérience") }}</label>
        <select name="experiencelevel_id" id="experiencelevel_id" class="form-control">
          <option value=""></option>
          @foreach(App\ExperienceLevel::orderBy('sort_order', 'ASC')->get() as $level)
            <option value="{{ $level->id }}" {{ $skill->experiencelevel_id == $level->id ? 'selected':'' }}>{{ $level->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="" class="control-label">{{ __("Description") }}</label>
        <textarea name="description" id="description" class="form-control">{{ $skill->description }}</textarea>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <p class="control-label mb-5">{{ __("Relation fonctionnelle") }}</p>
      <div class="row" v-for="(item, iIndex) in functionnelRelations">
        <div class="col-md-4">
          <textarea :name="'functionnel_relation['+iIndex+'][title]'" id="titre" placeholder="{{ __("Titre") }}" class="form-control" :value="item.title" style="min-height: 37px; height: 37px;"></textarea>
        </div>
        <div class="col-md-7 pl-0">
          <textarea :name="'functionnel_relation['+iIndex+'][description]'" id="description" placeholder="{{ __("Description") }}" class="form-control" :value="item.description" style="min-height: 37px; height: 37px;"></textarea>
        </div>
        <div class="col-md-1 pl-0">
          <button type="button" :class="iIndex == functionnelRelations.length - 1 ? 'btn btn-success':'btn btn-danger'" @click="iIndex == functionnelRelations.length - 1 ? addFunctionnelRelation() : removeFunctionnelRelation(iIndex)"><i :class="iIndex == functionnelRelations.length - 1 ? 'fa fa-plus':'fa fa-minus'"></i></button>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default mb-20" v-for="(type, tIndex) in types">
        <div class="panel-heading" v-if="mode == 'add'" >
          <span class="badge">@{{ tIndex + 1 }}</span>
          <button v-if="mode == 'add'" type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeType(tIndex)"><i class="fa fa-trash"></i></button>
        </div>
        <div class="panel-body pb-0">
          {{ csrf_field() }}
          <div class="row">
            <input type="hidden" :name="'types['+tIndex+'][id]'" :id="'t-'+tIndex+'-id'" :value="type.id">
            <div class="col-md-12">
              <div class="form-group">
                <label for="title" class="control-label required">{{ __("Titre") }}</label>
                <input type="text" :name="'types['+tIndex+'][title]'" :id="'t-'+tIndex+'-title'" class="form-control" value="" v-model="type.title" chm-validate="required">
              </div>
            </div>
          </div>
          <div class="row mb-0">
            <div class="col-md-12 mb-20">
              <label class="control-label font-16"><i class="fa fa-list"></i> {{ __("Compétences") }} <i class="fa fa-info-circle text-blue" title="Total des pondérations doit être égal à 100" data-toggle="tooltip"></i></label>
            </div>
            <div class="col-md-12">
              <div class="indicators-container">
                <div class="indicator-item" v-for="(skill, sIndex) in type.skills">
                  <div class="row">
                    <div class="col-md-8">
                      <label>
                        <input type="text" :name="'types['+tIndex+'][skills]['+sIndex+'][title]'" :id="'t-'+tIndex+'-skill-'+sIndex+'-title'" class="form-control" placeholder=" " v-model="skill.title" chm-validate="required">
                        <span>{{ __("Titre de la compétence") }}</span>
                      </label>
                    </div>
                    <div class="col-md-3 pl-0">
                      <label>
                        <input type="number" :name="'types['+tIndex+'][skills]['+sIndex+'][ponderation]'" :id="'t-'+tIndex+'-skill-'+sIndex+'-ponderation'" min="1" max="100" class="form-control" title="Pendération en % ex: 10%" placeholder=" " v-model="skill.ponderation" chm-validate="required|min_len,1|max_len,3">
                        <span>{{ __("Pondération (%)") }}</span>
                      </label>
                    </div>
                    <div class="col-md-1 pl-0">
                      <button v-if="sIndex + 1 == type.skills.length" type="button" class="btn btn-success pull-right text-success" @click="addSkill(tIndex)"><i class="fa fa-plus"></i></button>

                      <button v-if="sIndex + 1 < type.skills.length" type="button" class="btn btn-danger pull-right text-danger" @click="removeSkill(tIndex, sIndex)"><i class="fa fa-minus"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="addTypeContainer">
        <button type="button" class="btn btn-default" @click="addType()"><i class="fa fa-plus"></i> {{ __("Ajouter un nouveau type de compétence") }}</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
<script src="https://cdn.rawgit.com/rikmms/progress-bar-4-axios/0a3acf92/dist/index.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/rikmms/progress-bar-4-axios/0a3acf92/dist/nprogress.css" />

<script>

  $(document).ready(function () {
    setTimeout(function () {
      new Vue({
        el: '#contentaa',
        data: {
          mode: "add",
          functionnelRelations: [
            @foreach($skill->getFunctionnelRelations() as $key => $item)
            {
              title: "{!! isset($item['title']) ? $item['title'] : '' !!}",
              description: "{!! isset($item['description']) ? $item['description'] : '' !!}",
            },
            @endforeach
          ],
          types: [
              @foreach($skill->getSkillsTypes() as $key => $type)
              {
              id: {{ $key }},
              title: "{!! $type['title'] !!}",
              skills: [
                  @foreach($type['skills'] as $skill)
                  {
                    title: "{!! $skill['title'] !!}",
                    ponderation: "{{ $skill['ponderation'] }}"
                  },
                @endforeach
              ]
            },
            @endforeach
          ],
        },
        methods: {
          addType: function () {
            this.types.push({
              title: "",
              skills: [
                {
                  title: "",
                  ponderation: ""
                }
              ]
            })
            setTimeout(function () {
              $('[data-toggle="tooltip"]').tooltip()
            }, 500)
          },
          removeType: function (tIndex) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              this.types.splice(tIndex, 1)
            }
          },
          addSkill: function (tIndex) {
            this.types[tIndex].skills.push({
              title: "",
              ponderation: "",
            })
          },
          removeSkill: function (tIndex, sIndex) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              this.types[tIndex].skills.splice(sIndex, 1)
            }
          },
          addFunctionnelRelation: function () {
            this.functionnelRelations.push({
              title: '',
              description: ''
            })
          },
          removeFunctionnelRelation: function (iIndex) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              this.functionnelRelations.splice(iIndex, 1)
            }
          }
        },
        mounted () {
          $('[data-toggle="tooltip"]').tooltip()
        }
      })
      $('#description').wysihtml5({
        toolbar: {
          "image": false,
          "html": true,
          "link": false
        },
      });
    }, 300)
  })
</script>