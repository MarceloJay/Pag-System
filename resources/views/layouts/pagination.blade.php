<ul class="pagination">
   {{-- Link para a página anterior --}}
   @if ($paginator->onFirstPage())
      <li class="page-item disabled">
         <span class="page-link">&laquo;</span>
      </li>
   @else
      <li class="page-item">
         <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
      </li>
   @endif

   {{-- Renderização dos links de página --}}
   @foreach ($elements as $element)
      {{-- Verifica o tipo do elemento --}}
      @if (is_string($element))
         <li class="page-item disabled">
            <span>{{ $element }}</span>
         </li>
      @endif

      {{-- Verifica se o elemento é um link de página ativa --}}
      @if (is_array($element))
         @foreach ($element as $page => $url)
         {{-- Verifica se o elemento é um link de página ativa --}}
            @if ($page == $paginator->currentPage())
            <li class="page-item custom-active">
                <span class="page-link">{{ $page }}</span>
            </li>
            @else
            <li class="page-item">
                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
            </li>
            @endif
         @endforeach
      @endif
   @endforeach

   {{-- Link para a próxima página --}}
   @if ($paginator->hasMorePages())
      <li class="page-item">
         <a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
      </li>
   @else
      <li class="page-item disabled">
         <span class="page-link">&raquo;</span>
      </li>
   @endif
</ul>
<style>
    .page-item {
        display: inline-flex;
        vertical-align: middle;
    }
    .page-link {
        vertical-align: middle;
    }
    /* .custom-active {
        background-color: #007bff;
        border-color: #007bff;
    } */
</style>