<p>Staff: {{ $user->name }}</p>
<p>Action: Clock {{ $type }}</p>
<p>Date & Time: {{ now() }}</p>
@if($type === 'in')
    <p>You have successfully clocked in. Have a productive day!</p>
@else
    <p>You have successfully clocked out. See you tomorrow!</p>
@endif