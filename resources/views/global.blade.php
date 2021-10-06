<script>
    function fixDigit(val){
        return val.toString().length === 1 ? "0" + val : val;
    }

    function getDate(val)
    {
        var content = '-';
        if(val!=null)
        {
            var date_time = val.split(' ');
            var date = date_time[0].split('-');
            content = date[2] + '-' + date[1] + '-' + date[0];
        }
        return content;
    }
    
    var APP_URL = {!! json_encode(url('/')) !!}
</script>