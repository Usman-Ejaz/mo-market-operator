<script>
    $(document).ready(function(){
        toastr.options = {
            "closeButton" : true,
            "progressBar" : true
        }
        @if(Session::has('success'))
            toastr.success("{{ session('success') }}");
        @elseif(Session::has('error'))
            toastr.error("{{ session('error') }}");
        @elseif(Session::has('info'))
            toastr.info("{{ session('info') }}");
        @elseif(Session::has('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    });
</script>
