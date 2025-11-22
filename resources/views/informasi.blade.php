@extends('layouts.app')

@section('title', 'Informasi - Portal Karang Taruna')

@section('content')
    <section class="section section--gray" id="informasi">
        <div class="container">
            <h2 class="section__title">Informasi & Berita</h2>

            <form action="{{ url('/informasi') }}" method="GET" class="info-controls">
                <div class="search-box">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="9" r="6"></circle><path d="M14 14L18 18"></path></svg>
                    <input type="text" name="q" class="search-box__input" placeholder="Cari artikel..." value="{{ request('q') }}">
                </div>
                <div class="filter-group">
                    <label for="categoryFilter" class="filter-group__label">Kategori:</label>
                    <select name="category" id="categoryFilter" class="filter-group__select" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @foreach(['Pengumuman', 'Kegiatan', 'Berita', 'Artikel'] as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="articles-grid" id="articlesGrid">
                @forelse($articles as $article)
                    <article class="article-card clickable" onclick='openArticleModal(@json($article))'>
                        <div class="article-card__image" style="position: relative; overflow: hidden;">
                            @if($article->image)
                                <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width:100%; height:100%; background: linear-gradient(135deg, #A50104, #FCBA04); display:flex; align-items:center; justify-content:center; color:white; font-weight:bold;">
                                    {{ $article->category }}
                                </div>
                            @endif
                        </div>

                        <div class="article-card__content">
                            <span class="article-card__category">{{ $article->category }}</span>
                            <h3 class="article-card__title">{{ $article->title }}</h3>
                            <p class="article-card__excerpt">{{ Str::limit($article->excerpt, 100) }}</p>
                            <time class="article-card__date">{{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}</time>
                        </div>
                    </article>
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                        <p class="admin-empty">Belum ada artikel yang dipublish.</p>
                    </div>
                @endforelse
            </div>

            <div class="pagination" id="pagination">
                {{ $articles->withQueryString()->links() }}
            </div>
        </div>
    </section>

    <div class="modal-overlay" id="articleModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 10000; justify-content: center; align-items: center; padding: 20px;">
        <div class="modal-box" style="background: white; width: 100%; max-width: 800px; border-radius: 12px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative;">

            <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <h3 style="margin: 0; font-size: 18px; color: #333;">Detail Informasi</h3>
                <button onclick="closeArticleModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
            </div>

            <div style="padding: 20px; overflow-y: auto; flex-grow: 1;">

                <div style="width: 100%; margin-bottom: 20px; border-radius: 8px; overflow: hidden; background: #f3f4f6; display: flex; justify-content: center; align-items: center;">
                    <img id="modalImg" src="" style="width: 100%; height: auto; max-height: 600px; object-fit: contain; display: none;">
                    <div id="modalPlaceholder" style="width: 100%; height: 200px; background: linear-gradient(135deg, #A50104, #FCBA04); display: none; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold;">
                        </div>
                </div>

                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <span id="modalCategory" style="background: #3b82f6; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase;">ARTIKEL</span>
                    <span id="modalDate" style="color: #666; font-size: 13px;">-</span>
                </div>

                <h2 id="modalTitle" style="margin: 0 0 15px 0; font-size: 24px; color: #111; line-height: 1.3;"></h2>
                <div id="modalContent" style="color: #4b5563; line-height: 1.7; font-size: 16px; white-space: pre-line;"></div>

                <div id="modalLinkContainer" style="margin-top: 30px; display: none;">
                    <a id="modalLink" href="#" target="_blank" class="btn btn--primary" style="display: inline-block; text-decoration: none;">Baca Selengkapnya / Daftar →</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openArticleModal(data) {
            const modal = document.getElementById('articleModal');
            const baseUrl = "{{ asset('') }}";

            document.getElementById('modalTitle').innerText = data.title;
            document.getElementById('modalContent').innerText = data.content;

            const dateObj = new Date(data.published_at);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            document.getElementById('modalDate').innerText = dateObj.toLocaleDateString('id-ID', options);

            const catEl = document.getElementById('modalCategory');
            catEl.innerText = data.category;

            // Warna Badge
            if(data.category === 'Pengumuman') catEl.style.background = '#ef4444';
            else if(data.category === 'Kegiatan') catEl.style.background = '#f59e0b';
            else catEl.style.background = '#3b82f6';

            // Gambar Logic
            const imgEl = document.getElementById('modalImg');
            const phEl = document.getElementById('modalPlaceholder');

            if(data.image) {
                imgEl.src = baseUrl + data.image;
                imgEl.style.display = 'block';
                phEl.style.display = 'none';
            } else {
                imgEl.style.display = 'none';
                phEl.style.display = 'flex';
                phEl.innerText = data.category;
            }

            // Link
            const linkContainer = document.getElementById('modalLinkContainer');
            const linkBtn = document.getElementById('modalLink');
            if(data.link) {
                linkBtn.href = data.link;
                linkContainer.style.display = 'block';
            } else {
                linkContainer.style.display = 'none';
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeArticleModal() {
            document.getElementById('articleModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        document.getElementById('articleModal').addEventListener('click', function(e) {
            if(e.target === this) closeArticleModal();
        });
    </script>
@endsection
