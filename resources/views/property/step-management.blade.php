@if(!is_null(getParameter("step")))
<style>
    .step-content
    {
        display: none;
    }

    .step-{{getParameter("step")}}-content
    {
        display: block;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        var step = '{{getParameter("step")}}';
        showCurrentStep(step);
    });
    function showCurrentStep(step)
    {
        $('.menu').removeClass("active");
        $('.step-'+ step +'-menu').addClass("active");
    }
</script>
@else
<style>
    .step-content
    {
        display: none;
    }
    .step-1-content
    {
        display: block;
    }
</style>
@endif
