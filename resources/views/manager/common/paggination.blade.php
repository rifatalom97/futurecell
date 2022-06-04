@if ($paginator->lastPage() > 1)
<div class="row mt-3">
    <div class="col-md-6">
        <nav aria-label="Next page brand">
            <ul class="pagination">
                <li class="{{ ($paginator->currentPage() == 1) ? ' page-item disabled' : 'page-item' }}">
                    <a href="{{ $paginator->url(1) }}" class="page-link">Previous</a>
                </li>
                @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                    <li class="{{ ($paginator->currentPage() == $i) ? ' page-item active' : 'page-item' }}">
                        <a href="{{ $paginator->url($i) }}" class="page-link">{{ $i }}</a>
                    </li>
                @endfor
                <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' page-item disabled' : 'page-item' }}">
                    <a href="{{ $paginator->url($paginator->currentPage()+1) }}" class="page-link">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="col-md-6 text-right">
        <p class="mb-0 mt-2 small">
            <span>Showing {{ $paginator->currentPage() }} of {{ $paginator->total() }}</span>
        </p>
    </div>
</div>
@endif