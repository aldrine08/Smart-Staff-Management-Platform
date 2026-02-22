<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Chat Room: {{ $room->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div id="chat-messages" class="h-96 overflow-y-auto border rounded p-4 mb-4">
                @foreach($room->messages as $message)
                    <p><strong>{{ $message->user->name }}:</strong> {{ $message->content }}</p>
                @endforeach
            </div>

            <form id="chat-form" >
                @csrf
                <input type="text" name="content" placeholder="Type your message..." class="w-full border rounded px-3 py-2">
                <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">Send</button>
            </form>
        </div>
    </div>
</x-app-layout>


<script>
document.getElementById('chat-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const input = this.querySelector('input[name="content"]');
    const content = input.value.trim();
    if (!content) return;

    fetch("{{ route('chat.store', $room->id) }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ content })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const box = document.getElementById('chat-messages');
            box.innerHTML += `<p><strong>${data.message.user.name}:</strong> ${data.message.content}</p>`;
            input.value = '';
            box.scrollTop = box.scrollHeight;
        }
    });
});
</script>
