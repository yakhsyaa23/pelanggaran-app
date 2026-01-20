<a href="{{ route('violations.show', $query->id) }}" class="btn btn-info btn-sm">Detail</a>
<a href="{{ route('violations.edit', $query->id) }}" class="btn btn-warning btn-sm">Edit</a>
<form action="{{ route('violations.destroy', $query->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>
</form>