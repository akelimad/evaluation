<div class="content pt-0 pb-0">
  <input type="hidden" name="id" value="{{ $email->id }}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-4">
      <label for="date" class="control-label required">Réf</label>
      <input type="text" name="ref" class="form-control" value="{{$email->ref}}" {{ $email->ref != '' ? 'readonly':'' }}>
    </div>
    <div class="col-md-4">
      <label for="date" class="control-label required">Email de l'émetteur</label>
      <input type="text" class="form-control" name="sender" placeholder="contact@exemple.com" required="" value="{{ App\User::getOwner()->email ? App\User::getOwner()->email : '' }}">
    </div>
    <div class="col-md-4">
      <label for="name" class="control-label required">Nom de l'émetteur</label>
      <input type="text" class="form-control" name="name" placeholder="Name" required="" value="{{ App\User::getOwner()->name ? App\User::getOwner()->name : '' }}">
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="subject" class="control-label required">Object</label>
        <input type="text" class="form-control" name="subject" placeholder="Object" required="" value="{{ $email->subject  }}">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="message" class="control-label required">Message</label>
        <textarea id="compose-textarea" name="message" class="form-control" rows="10" required="" placeholder="Votre message">{{ $email->message }}</textarea>
      </div>
    </div>
  </div>
</div>

<script>
  $(function () {
    //Add text editor
    setTimeout(function () {
      $("#compose-textarea").wysihtml5({
        toolbar: {
          "image": false,
          "html": true,
          "link": false
        },
      });
    }, 100)
  });
</script>
