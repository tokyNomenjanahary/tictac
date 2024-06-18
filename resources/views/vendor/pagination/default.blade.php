@if ($paginator->hasPages())
<div class="pagination-bx">
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <li><a style="cursor: not-allowed;" href="javascript:void(0)"><img class="icon-prev" src="/img/navigation-arrow-left.png" ></a></li>
        @else
        <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><img class="icon-prev" src="/img/navigation-arrow-left.png" ></a></li>
        @endif

        {{-- Pagination Elements --}}
     

      
      
       




      @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
              
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                    <input id="input" classe="" name="mon_texte" type="text" value='{{ $page }}'>                 
                        
                    @else
                        
                    @endif
                @endforeach
            @endif
        @endforeach 

        <li class="disabled so-on-dot"><a style="cursor: not-allowed;" href="javascript:void(0)">/ {{ $paginator->lastPage()}}</a></li>
        


        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <li><a href="{{ $paginator->nextPageUrl() }}" rel="next"><img class="icon-next" src="/img/navigation-arrow-right.png" ></a></li>
        @else
        <li><a style="cursor: not-allowed;" href="javascript:void(0)"><img class="icon-next" src="/img/navigation-arrow-right.png" ></a></li>
        @endif       


        
    </ul>
</div>

<style>
    input#input {
   
     position: relative !important;
    top: -17px;
    left: -6px;
    height: 45px;
    width: 57px;
    border: none;
    border-radius: 4px;
    padding: 0px;
    font-size: 1.4rem;
    padding: 16px;
    min-width: 0;
    font-size: 15px;
    font-family: sans-serif;
}

a#ok{
    display:none;
}
@media only screen and (max-width: 820px) { /*1023px*/
    input#input {
   
   position: relative !important;
   top: -16px;

height: 38px;

}



}
</style>
<script>
 


 var input = document.getElementById("input");

input.addEventListener("keyup", function(event) {
  if (event.keyCode === 13) {
 let ok= $(this).val();

var url = window.location.href;

const searchTerm = '#';
var indexOfFirst = url.indexOf(searchTerm);
if(indexOfFirst > 0 ){

    let nouv = url.substr(0, indexOfFirst);
    let uli = nouv+'#'+ok;
       
    window.location.href = uli;

     
} else{ 
    
 let uli = url+'#'+ok;
  window.location.href = uli;

}

  }

});
</script>
@endif
