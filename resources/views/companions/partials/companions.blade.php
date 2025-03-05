@foreach($companions as $companion)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $companion->companion_name }}</td>
        <td>{{ $companion->created_at }}</td>
        <td>{{ $companion->updated_at }}</td>
        <td>
            <button class="btn btn-primary btn-sm edit-btn" data-id="{{ $companion->id }}">Edit</button>
            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $companion->id }}">Hapus</button>
        </td>
    </tr>
@endforeach
