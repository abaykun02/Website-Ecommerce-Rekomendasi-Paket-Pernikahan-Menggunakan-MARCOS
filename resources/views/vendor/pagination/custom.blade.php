@if ($paginator->hasPages())
    <nav class="pagination-wrapper" role="navigation" aria-label="Navigasi Halaman">
        <div class="pagination-info">
            Menampilkan <strong>{{ $paginator->firstItem() ?? 0 }}</strong> &ndash; <strong>{{ $paginator->lastItem() ?? 0 }}</strong> dari <strong>{{ $paginator->total() }}</strong> data
        </div>

        <ul class="pagination-buttons">
            {{-- Tombol Sebelumnya (Previous) --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="pagination-btn disabled" aria-disabled="true">Sebelumnya</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn">Sebelumnya</a>
                </li>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($elements as $element)
                {{-- Pemisah Tiga Titik (...) --}}
                @if (is_string($element))
                    <li>
                        <span class="pagination-btn pagination-btn-number disabled" aria-disabled="true">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="pagination-btn pagination-btn-number is-active" aria-current="page">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="pagination-btn pagination-btn-number">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Selanjutnya (Next) --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn">Selanjutnya</a>
                </li>
            @else
                <li>
                    <span class="pagination-btn disabled" aria-disabled="true">Selanjutnya</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
