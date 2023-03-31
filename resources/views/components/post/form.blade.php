<form action="{{ $route }}" method="post">
    @csrf
    <div>
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title') }}">
        @error('title')
            <div>{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label>Content</label>
        <textarea name="content" cols="30" rows="10">{{ old('content') }}</textarea>
        @error('content')
            <div>{{ $message }}</div>
        @enderror
    </div>
    <input type="submit" value="{{ $actionTitle }}">
</form>
