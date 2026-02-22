<h2>Processed Payroll Report</h2>

<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th>Staff</th>
            <th>Unit</th>
            <th>Department</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Net Salary</th>
            <th>Processed At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payrolls as $pay)
        <tr>
            <td>{{ $pay->user->name }}</td>
            <td>{{ $pay->unit->name }}</td>
            <td>{{ $pay->department->name ?? '-' }}</td>
            <td>{{ $pay->start_date }}</td>
            <td>{{ $pay->end_date }}</td>
            <td>{{ number_format($pay->net_salary, 2) }}</td>
            <td>{{ $pay->processed_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
