<div class="row">
    <div class="col-md-1" style="width: 106px">
        <form> 
            <select name="per_page" id="per_page_select" class="form-control">
                <option value="5" {{ (isset($selected) && $selected == 5 ? 'selected':'') }}>5</option>
                <option value="10" {{ (isset($selected) && $selected == 10 ? 'selected':'') }}>10</option>
                <option value="15" {{ (isset($selected) && $selected == 15 ? 'selected':'') }}>15</option>
                <option value="100" {{ (isset($selected) && $selected == 100 ? 'selected':'') }}>100</option>
                <option value="all" {{ (isset($selected) && $selected == "all" ? 'selected':'') }}>Tout</option>
            </select>
        </form>
    </div>
    <div class="col-md-11 pagination-container">
        {{$results->links()}}
    </div>
    <div class="clearfix"></div>
</div>

@section('javascript')

<script>
    $(function() {
        $("#per_page_select").change(function() {
            $("form").submit();
        });
    });
</script>
@endsection