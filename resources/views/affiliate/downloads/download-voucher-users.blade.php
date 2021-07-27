<table>
    <thead>
        <tr>
            <th>User</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user }}</td>
            </tr>
        @endforeach
    </tbody>
</table>