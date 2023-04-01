@props([
    'post'
])
<form action="{{ route('post.destroy', [$post]) }}" method="POST" style="display: inline">
    <a href="#" onclick="this.parentNode.submit();return false">delete</a>
    @csrf
    @method('DELETE')
</form>
