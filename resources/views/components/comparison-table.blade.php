@props(['comparison'])

<div class="comparison-table-wrap">
    <table class="comparison-table">
        <thead>
            <tr>
                <th scope="col">Feature</th>
                <th scope="col">{{ $comparison['left']['name'] }}</th>
                <th scope="col">{{ $comparison['right']['name'] }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comparison['rows'] as $row)
                <tr>
                    <th scope="row">{{ $row['label'] }}</th>
                    <td data-label="{{ $comparison['left']['name'] }}">{{ $row['left']['value'] }}</td>
                    <td data-label="{{ $comparison['right']['name'] }}">{{ $row['right']['value'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
