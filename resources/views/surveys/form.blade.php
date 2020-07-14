@extends('layouts.app')

@section('content')
  <div class="content" id="content">
    <div class="row mb-20">
      <div class="col-md-12">
        <h3><a href="{{ route('surveys-list') }}"><i class="fa fa-angle-left"></i> Retourner aux questionnaires</a></h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="card">
          <div class="card-body">
            <div class="form-group mb-30">
              <label for="">Nom du modèle</label>
              <input type="text" name="" class="form-control" placeholder="">
            </div>
            <div class="form-group">
              <label for="">Section</label>
              <select name="" id="" class="form-control">
                <option value=""></option>
                @foreach($evaluations as $eval)
                  @if($eval->title =="Evaluations" || $eval->title =="Carrières")
                    <option value="{{$eval->id}}" {{ $eval->id == $survey->evaluation_id ? 'selected':''}}>{{$eval->title}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="card mb-40" v-for="(group, grpIndex) in groups">
          <div class="card-header">
            <input v-if="group.edit" v-model="group.title" class="form-control" @blur="group.edit = false; $emit('update')" @keyup.enter="group.edit=false; $emit('update')" v-focus placeholder="Entrez le titre">
            <h3 v-else class="mb-0 card-title w-100">
              <label @click="group.edit = true;" class="mb-0">@{{ group.title }}</label>
              <button type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeGroup(grpIndex)"><i class="fa fa-trash"></i></button>
              <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editGroup(group)"><i class="fa fa-pencil"></i></button>
            </h3>
          </div>
          <div class="box-body">
            <div class="panel panel-default" v-for="(question, qIndex) in group.questions">
              <div class="panel-heading">
                <input v-if="question.edit" v-model="question.title" class="form-control" @blur="question.edit = false; $emit('update')" @keyup.enter="question.edit=false; $emit('update')" v-focus>
                <p v-else class="m-0">
                  <label @click="question.edit = true;" class="mb-0">@{{ question.title }} - @{{ question.type }}</label>
                  <button type="button" class="btn btn-tool btn-xs pull-right text-danger" @click="removeQuestion(grpIndex, qIndex)"><i class="fa fa-trash"></i></button>
                  <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editQuestion(question)"><i class="fa fa-pencil"></i></button>
                </p>
              </div>
              <div class="panel-body">
                <ul class="list-unstyled">
                  <li v-for="(choice, cIndex) in group.questions[qIndex].choices" class="mb-10">
                    <input v-if="choice.edit" v-model="choice.title" class="form-control" @blur="choice.edit = false; $emit('update')" @keyup.enter="choice.edit = false; $emit('update')" v-focus placeholder="Ajouter une option">
                    <p v-else class="m-0">
                      <label @click="choice.edit = true;" class="mb-0">| @{{ choice.title }}</label>
                      <button type="button" class="btn btn-tool btn-xs pull-right text-danger" @click=""><i class="fa fa-trash"></i></button>
                      <button type="button" class="btn btn-tool btn-xs pull-right text-warning mr-5" @click="editChoice(choice)"><i class="fa fa-pencil"></i></button>
                    </p>
                  </li>
                  <a href="javascript:void(0)" @click="addNewChoice(grpIndex, qIndex)">Ajouter une option</a>
                </ul>
              </div>
            </div>
            <div class="add-new-question-btn">
              <div class="dropdown pull-right">
                <button class="btn btn-info dropdown-toggle" type="button" id="questionTypes" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-plus"></i> Ajouter une question <span class="caret"></span></button>
                <ul class="dropdown-menu" aria-labelledby="questionTypes">
                  <li><a href="javascript:void(0)" @click="addNewQuestion(grpIndex, 'text')">Court text</a></li>
                  <li><a href="javascript:void(0)" @click="addNewQuestion(grpIndex, 'textarea')">Long text</a></li>
                  <li><a href="javascript:void(0)" @click="addNewQuestion(grpIndex, 'radio')">Un seul choix (radio)</a></li>
                  <li><a href="javascript:void(0)" @click="addNewQuestion(grpIndex, 'checkbox')">Choix multiple (checkbox)</a></li>
                  <li><a href="javascript:void(0)" @click="addNewQuestion(grpIndex, 'select')">Liste déroulante (select)</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="add-new-section-btn">
          <button type="button" class="btn btn-success" @click="addNewGroup()"><i class="fa fa-plus"></i> Ajouter un groupe de questions</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('javascript')
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script>
    new Vue({
      el: '#content',
      data: {
        groups: [],
      },
      methods: {
        editGroup: function (group) {
          group.edit = true
        },
        addNewGroup: function () {
          this.groups.push({
            title: "Entrez le titre du groupe",
            edit: true,
            questions: []
          })
        },
        removeGroup: function (index) {
          if (this.groups.length > 1) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) this.groups.splice(index, 1)
          } else {
            alert("Le questionnaire doit avoir au moins un groupe, vous ne pouvez pas supprimer ce groupe !")
          }
        },
        editQuestion: function (question) {
          question.edit = true
        },
        addNewQuestion: function (groupIndex, qType) {
          var choicesArray = []
          if ($.inArray(qType, ['radio', 'checkbox', 'select']) !== -1) {
            //choicesArray.push({id: 1, title: '', edit: false})
          }
          this.groups[groupIndex].questions.push(
              {
                title: "Entrez le titre de la question",
                type: qType,
                edit: true,
                choices: []
              }
          )
        },
        removeQuestion: function (grpIndex, qIndex) {
          if (this.groups[grpIndex].questions.length > 1) {
            var confirmation = confirm("Etes-vous sûr de vouloir supprimer ?")
            if (confirmation) {
              this.groups[grpIndex].questions.splice(qIndex, 1)
            }
          } else {
            alert("La section doit avoir au moins une question !")
          }
        },
        addNewChoice: function(grpIndex, qIndex, choice) {
          this.groups[grpIndex].questions[qIndex].choices.push({title: "Ajouter une option", edit: true})
        },
        editChoice: function (choice) {
          choice.edit = true
        },
      },
      directives: {
        focus: {
          inserted (el) {
            el.focus()
          }
        }
      }
    })
  </script>
@endsection