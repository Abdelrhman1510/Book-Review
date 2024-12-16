@extends('layouts.app')

@section('content')
  <!-- Success Message -->
  @if(session('success'))
    <div class="mb-4 p-4 text-green-700 bg-green-100 border border-green-200 rounded">
      {{ session('success') }}
    </div>
  @endif

  <!-- Back Button -->
  <div class="mb-4">
    <a href="{{ route('books.index') }}" class="btn btn-secondary">Back to Book List</a>
  </div>

  <div class="mb-4">
    <h1 class="mb-2 text-2xl">{{ $book->title }}</h1>

    <div class="book-info">
      <div class="book-author mb-4 text-lg font-semibold">by {{ $book->author }}</div>
      <div class="book-rating flex items-center">
        <div class="mr-2 text-sm font-medium text-slate-700">
          {{ number_format($book->reviews_avg_rating, 1) }}
          <x-star-rating :rating="$book->reviews_avg_rating" />
        </div>
        <span class="book-review-count text-sm text-gray-500">
          {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
        </span>
      </div>
    </div>
  </div>

  <div class="mb-4">
    <a href="{{ route('books.reviews.create', $book) }}" class="reset-link">Add a Review!</a>
  </div>

  <div>
    <h2 class="mb-4 text-xl font-semibold">Reviews</h2>
    <ul>
      @forelse ($reviews as $review)
        <li class="book-item mb-4">
          <div>
            <div class="mb-2 flex items-center justify-between">
              <div class="font-semibold">{{ $review->rating }} <br> <x-star-rating :rating="$review->rating" /></div>

              <div class="book-review-count">
                {{ $review->created_at->format('M j, Y') }}
              </div>
            </div>
            <p class="text-gray-700">{{ $review->review }}</p>
          </div>
        </li>
      @empty
        <li class="mb-4">
          <div class="empty-book-item">
            <p class="empty-text text-lg font-semibold">No reviews yet</p>
          </div>
        </li>
      @endforelse
    </ul>

    <!-- Pagination for reviews -->
    @if ($reviews->count())
      <nav class="mt-4">
        {{ $reviews->links() }}
      </nav>
    @endif
  </div>
@endsection
