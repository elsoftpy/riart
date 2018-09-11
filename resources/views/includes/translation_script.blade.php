    <script>
        $(document).ready(function(){
            $('.fixed-action-btn').floatingActionButton();
            //$('.tooltipped').tooltip('open');
            var elems = document.querySelectorAll('.tooltipped');
            var instances = M.Tooltip.init(elems);
        });    
    </script>    