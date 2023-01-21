<div class="row">
    <div class="col-lg-12 align-self-center">
        <div class="row">
            @foreach ($books as $key => $book)
                <div class="col-sm-12 col-md-3">
                    <div class="team-1">
                        <div class="team-img animate__animated">
                            <img src="{{ $book->photo_url }}" alt="{{ $book->name }}">
                        </div>
                        <div class="team-content" style="">
                            <h2 class="animate__animated">Title: {{ $book->name }}</h2>
                            <h3 class="animate__animated">Author: {{ $book->author }}</h3>
                            <h4 class="animate__animated">Price: ${{ $book->price }}</h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
