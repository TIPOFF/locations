@foreach($page->market->announcements as $announcement)
    @if ($announcement->active)
        <h2>{{ $announcement->title }}</h2>
        <p>{{ $announcement->description }}}</p>
    @endif
@endforeach
